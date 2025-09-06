<?php
require_once 'db/db_connection.php';

// Handle the API request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (!validateInput($input)) {
        sendResponse(400, ['error' => 'Invalid input data']);
    }

    $apiToken = $input['api_token'];
    $imgData = $input['img_data'];

    // Validate API token
    $tokenData = validateApiToken($apiToken);
    if (!$tokenData) {
        sendResponse(401, ['error' => 'Invalid API token']);
    }

    // Send request to verification service
    $verificationResult = callVerificationService($apiToken, $imgData);

    if ($verificationResult === false) {
        // Log failed verification
        logUsage($tokenData['id'], 0, null);
        sendResponse(500, ['error' => 'Verification service error']);
    }

    // Determine document type
    $documentType = determineDocumentType($verificationResult);

    // Log successful verification
    logUsage($tokenData['id'], 1, $documentType);

    // Return success response
    sendResponse(200, $verificationResult);
} else {
    sendResponse(405, ['error' => 'Method not allowed']);
}

/**
 * Validate input data
 */
function validateInput($input) {
    if (!isset($input['api_token']) || empty($input['api_token'])) {
        return false;
    }

    if (!isset($input['img_data']) || empty($input['img_data'])) {
        return false;
    }

    return true;
}

/**
 * Validate API token against database
 */
function validateApiToken($apiToken) {
    $sql = "SELECT id, organization_id FROM access_token WHERE token = ? AND expire_date > NOW()";

    try {
        $result = execute_query($sql, [$apiToken]);

        if (empty($result)) {
            return false;
        }

        return $result[0];
    } catch (Exception $e) {
        error_log("API token validation error: " . $e->getMessage());
        return false;
    }
}

/**
 * Log usage to database
 */
function logUsage($accessTokenId, $status, $docType = null) {
    $sql = "INSERT INTO `usage` (access_token_id, status, doc_type, created_at) VALUES (?, ?, ?, NOW())";

    try {
        execute_query($sql, [$accessTokenId, $status, $docType]);
        return true;
    } catch (Exception $e) {
        error_log("Log usage error: " . $e->getMessage());
        return false;
    }
}

/**
 * Call the external verification service
 */
function callVerificationService($apiToken, $imgData) {
    $verificationUrl = 'http://127.0.0.1:5000/api/verify_image';

    $payload = json_encode([
        'api_token' => $apiToken,
        'img_data' => $imgData
    ]);

    $ch = curl_init($verificationUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload)
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$response) {
        error_log("Verification service error. HTTP Code: $httpCode, Response: " . $response);
        return false;
    }

    return json_decode($response, true);
}

/**
 * Determine document type from verification result
 */
function determineDocumentType($verificationData) {
    if ($verificationData['is_driving_licence']) {
        return 'driving_licence';
    } elseif ($verificationData['is_new_nic']) {
        return 'new_nic';
    } elseif ($verificationData['is_old_nic']) {
        return 'old_nic';
    } elseif ($verificationData['is_passport']) {
        return 'passport';
    }
    return null;
}

/**
 * Send JSON response
 */
function sendResponse($statusCode, $data) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>