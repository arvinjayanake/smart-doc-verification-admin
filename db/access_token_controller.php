<?php

require_once 'db_connection.php';

class AccessTokenController
{

    /**
     * Generate a secure random token
     */
    private static function generateToken(int $length = 16): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Create a new access token
     */
    public static function createAccessToken(int $organizationId, string $name, DateTime $expireDate, ?string $customToken = null): ?array
    {
        $token = $customToken ?? self::generateToken();
        $expireDateStr = $expireDate->format('Y-m-d H:i:s');
        $createdAt = date('Y-m-d H:i:s');

        $sql = "INSERT INTO access_token (organization_id, name, token, expire_date, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?)";

        try {
            execute_query($sql, [$organizationId, $name, $token, $expireDateStr, $createdAt, $createdAt]);

            // Return the created token with ID
            return [
                'id' => self::getLastInsertId(),
                'organization_id' => $organizationId,
                'name' => $name,
                'token' => $token,
                'expire_date' => $expireDateStr,
                'created_at' => $createdAt,
                'updated_at' => $createdAt
            ];
        } catch (Exception $e) {
            error_log("Create access token error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get access token by ID
     */
    public static function getAccessTokenById(int $id): ?array
    {
        $sql = "SELECT at.*, o.name as organization_name 
                FROM access_token at 
                LEFT JOIN organization o ON at.organization_id = o.id 
                WHERE at.id = ?";

        try {
            $result = execute_query($sql, [$id]);
            return $result[0] ?? null;
        } catch (Exception $e) {
            error_log("Get access token error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get access token by token value
     */
    public static function getAccessTokenByToken(string $token): ?array
    {
        $sql = "SELECT at.*, o.name as organization_name 
                FROM access_token at 
                LEFT JOIN organization o ON at.organization_id = o.id 
                WHERE at.token = ?";

        try {
            $result = execute_query($sql, [$token]);
            return $result[0] ?? null;
        } catch (Exception $e) {
            error_log("Get access token by token error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all access tokens
     */
    public static function getAllAccessTokens(): array
    {
        $sql = "SELECT at.*, o.name as organization_name 
                FROM access_token at 
                LEFT JOIN organization o ON at.organization_id = o.id 
                ORDER BY at.created_at DESC";

        try {
            return execute_query($sql);
        } catch (Exception $e) {
            error_log("Get all access tokens error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get access tokens by organization ID
     */
    public static function getAccessTokensByOrganization(int $organizationId): array
    {
        $sql = "SELECT at.*, o.name as organization_name 
                FROM access_token at 
                LEFT JOIN organization o ON at.organization_id = o.id 
                WHERE at.organization_id = ? 
                ORDER BY at.created_at DESC";

        try {
            return execute_query($sql, [$organizationId]);
        } catch (Exception $e) {
            error_log("Get access tokens by organization error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get active (non-expired) access tokens
     */
    public static function getActiveAccessTokens(): array
    {
        $currentDate = date('Y-m-d H:i:s');
        $sql = "SELECT at.*, o.name as organization_name 
                FROM access_token at 
                LEFT JOIN organization o ON at.organization_id = o.id 
                WHERE at.expire_date > ? 
                ORDER BY at.expire_date ASC";

        try {
            return execute_query($sql, [$currentDate]);
        } catch (Exception $e) {
            error_log("Get active access tokens error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get expired access tokens
     */
    public static function getExpiredAccessTokens(): array
    {
        $currentDate = date('Y-m-d H:i:s');
        $sql = "SELECT at.*, o.name as organization_name 
                FROM access_token at 
                LEFT JOIN organization o ON at.organization_id = o.id 
                WHERE at.expire_date <= ? 
                ORDER BY at.expire_date DESC";

        try {
            return execute_query($sql, [$currentDate]);
        } catch (Exception $e) {
            error_log("Get expired access tokens error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update access token details
     */
    public static function updateAccessToken(int $id, string $name, DateTime $expireDate): bool
    {
        $expireDateStr = $expireDate->format('Y-m-d H:i:s');
        $updatedAt = date('Y-m-d H:i:s');

        $sql = "UPDATE access_token SET name = ?, expire_date = ?, updated_at = ? WHERE id = ?";
        $params = [$name, $expireDateStr, $updatedAt, $id];

        try {
            execute_query($sql, $params);
            return true;
        } catch (Exception $e) {
            error_log("Update access token error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Regenerate token value
     */
    public static function regenerateToken(int $id): ?string
    {
        $newToken = self::generateToken();
        $updatedAt = date('Y-m-d H:i:s');

        $sql = "UPDATE access_token SET token = ?, updated_at = ? WHERE id = ?";

        try {
            execute_query($sql, [$newToken, $updatedAt, $id]);
            return $newToken;
        } catch (Exception $e) {
            error_log("Regenerate token error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete access token
     */
    public static function deleteAccessToken(int $id): bool
    {
        $sql = "DELETE FROM access_token WHERE id = ?";

        try {
            execute_query($sql, [$id]);
            return true;
        } catch (Exception $e) {
            error_log("Delete access token error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate access token (check if exists and not expired)
     */
    public static function validateAccessToken(string $token): bool
    {
        $currentDate = date('Y-m-d H:i:s');
        $sql = "SELECT id FROM access_token WHERE token = ? AND expire_date > ?";

        try {
            $result = execute_query($sql, [$token, $currentDate]);
            return !empty($result);
        } catch (Exception $e) {
            error_log("Validate access token error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get token details with validation
     */
    public static function getValidAccessToken(string $token): ?array
    {
        $currentDate = date('Y-m-d H:i:s');
        $sql = "SELECT at.*, o.name as organization_name 
                FROM access_token at 
                LEFT JOIN organization o ON at.organization_id = o.id 
                WHERE at.token = ? AND at.expire_date > ?";

        try {
            $result = execute_query($sql, [$token, $currentDate]);
            return $result[0] ?? null;
        } catch (Exception $e) {
            error_log("Get valid access token error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if token name already exists for organization
     */
    public static function tokenNameExists(string $name, int $organizationId, ?int $excludeId = null): bool
    {
        if ($excludeId) {
            $sql = "SELECT id FROM access_token WHERE name = ? AND organization_id = ? AND id != ?";
            $params = [$name, $organizationId, $excludeId];
        } else {
            $sql = "SELECT id FROM access_token WHERE name = ? AND organization_id = ?";
            $params = [$name, $organizationId];
        }

        try {
            $result = execute_query($sql, $params);
            return !empty($result);
        } catch (Exception $e) {
            error_log("Token name check error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get access tokens count
     */
    public static function getAccessTokensCount(): array
    {
        $currentDate = date('Y-m-d H:i:s');
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN expire_date > ? THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN expire_date <= ? THEN 1 ELSE 0 END) as expired
                FROM access_token";

        try {
            $result = execute_query($sql, [$currentDate, $currentDate]);
            return [
                'total' => (int)($result[0]['total'] ?? 0),
                'active' => (int)($result[0]['active'] ?? 0),
                'expired' => (int)($result[0]['expired'] ?? 0)
            ];
        } catch (Exception $e) {
            error_log("Get access tokens count error: " . $e->getMessage());
            return ['total' => 0, 'active' => 0, 'expired' => 0];
        }
    }

    /**
     * Get last insert ID
     */
    private static function getLastInsertId(): int
    {
        $sql = "SELECT LAST_INSERT_ID() as id";
        try {
            $result = execute_query($sql);
            return (int)($result[0]['id'] ?? 0);
        } catch (Exception $e) {
            error_log("Get last insert ID error: " . $e->getMessage());
            return 0;
        }
    }
}