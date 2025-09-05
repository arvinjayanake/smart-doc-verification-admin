<?php

require_once 'db_connection.php';

class OrganizationUserController {

    /**
     * Create a new organization user
     */
    public static function createOrganizationUser(int $organizationId, string $name, string $email, string $password): bool {
        $hashedPassword = hash('sha256', $password);
        $createdAt = date('Y-m-d H:i:s');

        $sql = "INSERT INTO organization_user (organization_id, name, email, password, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?)";

        try {
            execute_query($sql, [$organizationId, $name, $email, $hashedPassword, $createdAt, $createdAt]);
            return true;
        } catch (Exception $e) {
            error_log("Create organization user error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get organization user by ID
     */
    public static function getOrganizationUserById(int $id): ?array {
        $sql = "SELECT ou.*, o.name as organization_name 
                FROM organization_user ou 
                LEFT JOIN organization o ON ou.organization_id = o.id 
                WHERE ou.id = ?";

        try {
            $result = execute_query($sql, [$id]);
            return $result[0] ?? null;
        } catch (Exception $e) {
            error_log("Get organization user error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all organization users
     */
    public static function getAllOrganizationUsers(): array {
        $sql = "SELECT ou.*, o.name as organization_name 
                FROM organization_user ou 
                LEFT JOIN organization o ON ou.organization_id = o.id 
                ORDER BY ou.created_at DESC";

        try {
            return execute_query($sql);
        } catch (Exception $e) {
            error_log("Get all organization users error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get users by organization ID
     */
    public static function getUsersByOrganization(int $organizationId): array {
        $sql = "SELECT ou.*, o.name as organization_name 
                FROM organization_user ou 
                LEFT JOIN organization o ON ou.organization_id = o.id 
                WHERE ou.organization_id = ? 
                ORDER BY ou.name";

        try {
            return execute_query($sql, [$organizationId]);
        } catch (Exception $e) {
            error_log("Get users by organization error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update organization user details
     */
    public static function updateOrganizationUser(int $id, int $organizationId, string $name, string $email, ?string $password = null): bool {
        $updatedAt = date('Y-m-d H:i:s');

        if ($password) {
            $hashedPassword = hash('sha256', $password);
            $sql = "UPDATE organization_user SET organization_id = ?, name = ?, email = ?, password = ?, updated_at = ? WHERE id = ?";
            $params = [$organizationId, $name, $email, $hashedPassword, $updatedAt, $id];
        } else {
            $sql = "UPDATE organization_user SET organization_id = ?, name = ?, email = ?, updated_at = ? WHERE id = ?";
            $params = [$organizationId, $name, $email, $updatedAt, $id];
        }

        try {
            execute_query($sql, $params);
            return true;
        } catch (Exception $e) {
            error_log("Update organization user error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete organization user
     */
    public static function deleteOrganizationUser(int $id): bool {
        $sql = "DELETE FROM organization_user WHERE id = ?";

        try {
            execute_query($sql, [$id]);
            return true;
        } catch (Exception $e) {
            error_log("Delete organization user error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Organization user login
     */
    public static function login(string $email, string $password): ?array {
        $sql = "SELECT ou.*, o.name as organization_name, o.status as organization_status 
                FROM organization_user ou 
                LEFT JOIN organization o ON ou.organization_id = o.id 
                WHERE ou.email = ?";

        try {
            $result = execute_query($sql, [$email]);

            if (empty($result)) {
                return null; // User not found
            }

            $user = $result[0];
            $hashedInputPassword = hash('sha256', $password);

            if ($user['password'] === $hashedInputPassword) {
                // Check if organization is active
                if ($user['organization_status'] != 1) {
                    return null; // Organization is inactive
                }

                // Remove password from returned data for security
                unset($user['password']);
                return $user;
            }

            return null; // Password mismatch

        } catch (Exception $e) {
            error_log("Organization user login error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if email already exists in organization
     */
    public static function emailExists(string $email, ?int $excludeId = null): bool {
        if ($excludeId) {
            $sql = "SELECT id FROM organization_user WHERE email = ? AND id != ?";
            $params = [$email, $excludeId];
        } else {
            $sql = "SELECT id FROM organization_user WHERE email = ?";
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
     * Check if email exists in specific organization
     */
    public static function emailExistsInOrganization(string $email, int $organizationId, ?int $excludeId = null): bool {
        if ($excludeId) {
            $sql = "SELECT id FROM organization_user WHERE email = ? AND organization_id = ? AND id != ?";
            $params = [$email, $organizationId, $excludeId];
        } else {
            $sql = "SELECT id FROM organization_user WHERE email = ? AND organization_id = ?";
            $params = [$email, $organizationId];
        }

        try {
            $result = execute_query($sql, $params);
            return !empty($result);
        } catch (Exception $e) {
            error_log("Email in organization check error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Change password only
     */
    public static function changePassword(int $id, string $newPassword): bool {
        $hashedPassword = hash('sha256', $newPassword);
        $updatedAt = date('Y-m-d H:i:s');

        $sql = "UPDATE organization_user SET password = ?, updated_at = ? WHERE id = ?";

        try {
            execute_query($sql, [$hashedPassword, $updatedAt, $id]);
            return true;
        } catch (Exception $e) {
            error_log("Change password error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get organization users count
     */
    public static function getUsersCount(): int {
        $sql = "SELECT COUNT(*) as count FROM organization_user";

        try {
            $result = execute_query($sql);
            return (int)($result[0]['count'] ?? 0);
        } catch (Exception $e) {
            error_log("Get users count error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get users count by organization
     */
    public static function getUsersCountByOrganization(int $organizationId): int {
        $sql = "SELECT COUNT(*) as count FROM organization_user WHERE organization_id = ?";

        try {
            $result = execute_query($sql, [$organizationId]);
            return (int)($result[0]['count'] ?? 0);
        } catch (Exception $e) {
            error_log("Get users count by organization error: " . $e->getMessage());
            return 0;
        }
    }
}