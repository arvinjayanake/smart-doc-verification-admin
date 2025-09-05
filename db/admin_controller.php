<?php

require_once 'db_connection.php';

class AdminController {

    /**
     * Create a new admin user
     */
    public static function createAdmin(string $name, string $email, string $password): bool {
        $hashedPassword = hash('sha256', $password);
        $createdAt = date('Y-m-d H:i:s');

        $sql = "INSERT INTO admin (name, email, password, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?)";

        try {
            execute_query($sql, [$name, $email, $hashedPassword, $createdAt, $createdAt]);
            return true;
        } catch (Exception $e) {
            error_log("Create admin error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get admin by ID
     */
    public static function getAdminById(int $id): ?array {
        $sql = "SELECT id, name, email, created_at, updated_at FROM admin WHERE id = ?";

        try {
            $result = execute_query($sql, [$id]);
            return $result[0] ?? null;
        } catch (Exception $e) {
            error_log("Get admin error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all admins
     */
    public static function getAllAdmins(): array {
        $sql = "SELECT id, name, email, created_at, updated_at FROM admin ORDER BY created_at DESC";

        try {
            return execute_query($sql);
        } catch (Exception $e) {
            error_log("Get all admins error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update admin details
     */
    public static function updateAdmin(int $id, string $name, string $email, ?string $password = null): bool {
        $updatedAt = date('Y-m-d H:i:s');

        if ($password) {
            $hashedPassword = hash('sha256', $password);
            $sql = "UPDATE admin SET name = ?, email = ?, password = ?, updated_at = ? WHERE id = ?";
            $params = [$name, $email, $hashedPassword, $updatedAt, $id];
        } else {
            $sql = "UPDATE admin SET name = ?, email = ?, updated_at = ? WHERE id = ?";
            $params = [$name, $email, $updatedAt, $id];
        }

        try {
            execute_query($sql, $params);
            return true;
        } catch (Exception $e) {
            error_log("Update admin error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete admin
     */
    public static function deleteAdmin(int $id): bool {
        $sql = "DELETE FROM admin WHERE id = ?";

        try {
            execute_query($sql, [$id]);
            return true;
        } catch (Exception $e) {
            error_log("Delete admin error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Admin login
     */
    public static function login(string $email, string $password): ?array {
        $sql = "SELECT id, name, email, password FROM admin WHERE email = ?";

        try {
            $result = execute_query($sql, [$email]);

            if (empty($result)) {
                return null; // Admin not found
            }

            $admin = $result[0];
            $hashedInputPassword = hash('sha256', $password);

            if ($admin['password'] === $hashedInputPassword) {
                // Remove password from returned data for security
                unset($admin['password']);
                return $admin;
            }

            return null; // Password mismatch

        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if email already exists
     */
    public static function emailExists(string $email, ?int $excludeId = null): bool {
        if ($excludeId) {
            $sql = "SELECT id FROM admin WHERE email = ? AND id != ?";
            $params = [$email, $excludeId];
        } else {
            $sql = "SELECT id FROM admin WHERE email = ?";
            $params = [$email];
        }

        try {
            $result = execute_query($sql, $params);
            return !empty($result);
        } catch (Exception $e) {
            error_log("Email check error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Change password only
     */
    public static function changePassword(int $id, string $newPassword): bool {
        $hashedPassword = hash('sha256', $newPassword);
        $updatedAt = date('Y-m-d H:i:s');

        $sql = "UPDATE admin SET password = ?, updated_at = ? WHERE id = ?";

        try {
            execute_query($sql, [$hashedPassword, $updatedAt, $id]);
            return true;
        } catch (Exception $e) {
            error_log("Change password error: " . $e->getMessage());
            return false;
        }
    }
}

// Example usage:
/*
// Create admin
$created = AdminController::createAdmin('John Doe', 'john@example.com', 'password123');

// Login
$admin = AdminController::login('john@example.com', 'password123');
if ($admin) {
    echo "Login successful! Welcome " . $admin['name'];
} else {
    echo "Invalid credentials";
}

// Get all admins
$admins = AdminController::getAllAdmins();

// Update admin
$updated = AdminController::updateAdmin(1, 'Jane Doe', 'jane@example.com');

// Delete admin
$deleted = AdminController::deleteAdmin(1);
*/

?>