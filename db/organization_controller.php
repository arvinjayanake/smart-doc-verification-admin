<?php

require_once 'db_connection.php';

class OrganizationController {

    /**
     * Create a new organization
     */
    public static function createOrganization(string $name, string $address, string $mobile, int $status = 0): bool {
        $createdAt = date('Y-m-d H:i:s');

        $sql = "INSERT INTO organization (name, address, mobile, status, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?)";

        try {
            execute_query($sql, [$name, $address, $mobile, $status, $createdAt, $createdAt]);
            return true;
        } catch (Exception $e) {
            error_log("Create organization error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get organization by ID
     */
    public static function getOrganizationById(int $id): ?array {
        $sql = "SELECT * FROM organization WHERE id = ?";

        try {
            $result = execute_query($sql, [$id]);
            return $result[0] ?? null;
        } catch (Exception $e) {
            error_log("Get organization error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all organizations
     */
    public static function getAllOrganizations(): array {
        $sql = "SELECT * FROM organization ORDER BY created_at DESC";

        try {
            return execute_query($sql);
        } catch (Exception $e) {
            error_log("Get all organizations error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get organizations by status
     */
    public static function getOrganizationsByStatus(int $status): array {
        $sql = "SELECT * FROM organization WHERE status = ? ORDER BY name";

        try {
            return execute_query($sql, [$status]);
        } catch (Exception $e) {
            error_log("Get organizations by status error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get active organizations
     */
    public static function getActiveOrganizations(): array {
        return self::getOrganizationsByStatus(1);
    }

    /**
     * Update organization details
     */
    public static function updateOrganization(int $id, string $name, string $address, string $mobile, int $status): bool {
        $updatedAt = date('Y-m-d H:i:s');

        $sql = "UPDATE organization SET name = ?, address = ?, mobile = ?, status = ?, updated_at = ? WHERE id = ?";
        $params = [$name, $address, $mobile, $status, $updatedAt, $id];

        try {
            execute_query($sql, $params);
            return true;
        } catch (Exception $e) {
            error_log("Update organization error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete organization
     */
    public static function deleteOrganization(int $id): bool {
        $sql = "DELETE FROM organization WHERE id = ?";

        try {
            execute_query($sql, [$id]);
            return true;
        } catch (Exception $e) {
            error_log("Delete organization error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle organization status
     */
    public static function toggleStatus(int $id): bool {
        $organization = self::getOrganizationById($id);
        if (!$organization) {
            return false;
        }

        $newStatus = $organization['status'] == 1 ? 0 : 1;
        $updatedAt = date('Y-m-d H:i:s');

        $sql = "UPDATE organization SET status = ?, updated_at = ? WHERE id = ?";

        try {
            execute_query($sql, [$newStatus, $updatedAt, $id]);
            return true;
        } catch (Exception $e) {
            error_log("Toggle status error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Search organizations by name
     */
    public static function searchOrganizations(string $searchTerm): array {
        $sql = "SELECT * FROM organization WHERE name LIKE ? ORDER BY name";
        $searchParam = "%" . $searchTerm . "%";

        try {
            return execute_query($sql, [$searchParam]);
        } catch (Exception $e) {
            error_log("Search organizations error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Check if mobile number already exists
     */
    public static function mobileExists(string $mobile, ?int $excludeId = null): bool {
        if ($excludeId) {
            $sql = "SELECT id FROM organization WHERE mobile = ? AND id != ?";
            $params = [$mobile, $excludeId];
        } else {
            $sql = "SELECT id FROM organization WHERE mobile = ?";
            $params = [$mobile];
        }

        try {
            $result = execute_query($sql, $params);
            return !empty($result);
        } catch (Exception $e) {
            error_log("Mobile check error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get organizations count by status
     */
    public static function getOrganizationsCount(): array {
        $sql = "SELECT status, COUNT(*) as count FROM organization GROUP BY status";

        try {
            $result = execute_query($sql);
            $counts = [
                'total' => 0,
                'active' => 0,
                'inactive' => 0
            ];

            foreach ($result as $row) {
                $counts['total'] += $row['count'];
                if ($row['status'] == 1) {
                    $counts['active'] = $row['count'];
                } else {
                    $counts['inactive'] = $row['count'];
                }
            }

            return $counts;
        } catch (Exception $e) {
            error_log("Get organizations count error: " . $e->getMessage());
            return ['total' => 0, 'active' => 0, 'inactive' => 0];
        }
    }
}
