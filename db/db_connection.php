<?php

// Database configuration
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'smart_doc_verification';
const POOL_SIZE = 5;

// Connection pool array
$connection_pool = [];
$pool_available = [];

/**
 * Initialize the connection pool
 */
function init_connection_pool(): void {
    global $connection_pool, $pool_available;

    for ($i = 0; $i < POOL_SIZE; $i++) {
        try {
            $connection_pool[$i] = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if ($connection_pool[$i]->connect_error) {
                throw new Exception("Connection failed: " . $connection_pool[$i]->connect_error);
            }

            $pool_available[$i] = true;
        } catch (Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            $connection_pool[$i] = null;
            $pool_available[$i] = false;
        }
    }
}

/**
 * Get an available connection from the pool
 */
function get_connection(): ?mysqli {
    global $connection_pool, $pool_available;

    foreach ($pool_available as $index => $available) {
        if ($available && $connection_pool[$index] !== null) {
            $pool_available[$index] = false;

            // Check if connection is still valid
            if ($connection_pool[$index]->ping()) {
                return $connection_pool[$index];
            } else {
                // Reconnect if connection is lost
                try {
                    $connection_pool[$index] = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                    return $connection_pool[$index];
                } catch (Exception $e) {
                    error_log("Reconnection failed: " . $e->getMessage());
                    return null;
                }
            }
        }
    }

    // If no connections available, create a new one (temporary)
    try {
        return new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    } catch (Exception $e) {
        error_log("Temporary connection failed: " . $e->getMessage());
        return null;
    }
}

/**
 * Release a connection back to the pool
 */
function release_connection(mysqli $conn): void {
    global $connection_pool, $pool_available;

    foreach ($connection_pool as $index => $pool_conn) {
        if ($pool_conn === $conn) {
            $pool_available[$index] = true;
            return;
        }
    }

    // If connection wasn't from pool, close it
    $conn->close();
}

/**
 * Execute a query using connection pool
 */
function execute_query(string $sql, array $params = []): array {
    $conn = get_connection();

    if ($conn === null) {
        throw new Exception("Failed to get database connection");
    }

    try {
        // Prepare statement if parameters are provided
        if (!empty($params)) {
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            // Bind parameters
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);

            // Execute
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            $result = $stmt->get_result();
            $stmt->close();
        } else {
            // Simple query execution
            $result = $conn->query($sql);
            if (!$result) {
                throw new Exception("Query failed: " . $conn->error);
            }
        }

        // Fetch results for SELECT queries
        $data = [];
        if ($result instanceof mysqli_result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $result->free();
        }

        return $data;

    } finally {
        // Always release connection back to pool
        if (isset($conn)) {
            release_connection($conn);
        }
    }
}

/**
 * Close all connections in the pool
 */
function close_connection_pool(): void {
    global $connection_pool, $pool_available;

    foreach ($connection_pool as $index => $conn) {
        if ($conn !== null) {
            $conn->close();
            $connection_pool[$index] = null;
            $pool_available[$index] = false;
        }
    }
}

// Initialize pool when script starts
init_connection_pool();

// Register shutdown function to close pool
register_shutdown_function('close_connection_pool');