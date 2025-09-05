<?php

require_once 'db_connection.php';

class UsageController {

    /**
     * Get usage summary for analytics dashboard
     */
    public static function getUsageSummary(int $organizationId = 0, string $startDate = '', string $endDate = ''): array {
        $whereClause = "WHERE 1=1";
        $params = [];

        // Add organization filter
        if ($organizationId > 0) {
            $whereClause .= " AND a.organization_id = ?";
            $params[] = $organizationId;
        }

        // Add date range filter
        if (!empty($startDate) && !empty($endDate)) {
            $whereClause .= " AND u.created_at BETWEEN ? AND ?";
            $params[] = $startDate . ' 00:00:00';
            $params[] = $endDate . ' 23:59:59';
        }

        $sql = "SELECT 
                COUNT(u.id) as total_requests,
                SUM(CASE WHEN u.status = 1 THEN 1 ELSE 0 END) as successful_requests,
                SUM(CASE WHEN u.status = 0 THEN 1 ELSE 0 END) as failed_requests,
                COUNT(DISTINCT u.doc_type) as unique_doc_types,
                COUNT(DISTINCT DATE(u.created_at)) as active_days
                FROM usage u
                INNER JOIN access_token a ON u.access_token_id = a.id
                $whereClause";

        try {
            $result = execute_query($sql, $params);
            return $result[0] ?? [
                'total_requests' => 0,
                'successful_requests' => 0,
                'failed_requests' => 0,
                'unique_doc_types' => 0,
                'active_days' => 0
            ];
        } catch (Exception $e) {
            error_log("Get usage summary error: " . $e->getMessage());
            return [
                'total_requests' => 0,
                'successful_requests' => 0,
                'failed_requests' => 0,
                'unique_doc_types' => 0,
                'active_days' => 0
            ];
        }
    }

    /**
     * Get daily usage trends for line chart
     */
    public static function getDailyUsageTrends(int $organizationId = 0, string $startDate = '', string $endDate = ''): array {
        $whereClause = "WHERE 1=1";
        $params = [];

        if ($organizationId > 0) {
            $whereClause .= " AND a.organization_id = ?";
            $params[] = $organizationId;
        }

        if (!empty($startDate) && !empty($endDate)) {
            $whereClause .= " AND u.created_at BETWEEN ? AND ?";
            $params[] = $startDate . ' 00:00:00';
            $params[] = $endDate . ' 23:59:59';
        }

        $sql = "SELECT 
                DATE(u.created_at) as date,
                COUNT(u.id) as total_requests,
                SUM(CASE WHEN u.status = 1 THEN 1 ELSE 0 END) as successful_requests,
                SUM(CASE WHEN u.status = 0 THEN 1 ELSE 0 END) as failed_requests
                FROM usage u
                INNER JOIN access_token a ON u.access_token_id = a.id
                $whereClause
                GROUP BY DATE(u.created_at)
                ORDER BY date";

        try {
            return execute_query($sql, $params);
        } catch (Exception $e) {
            error_log("Get daily usage trends error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get hourly usage patterns for bar chart
     */
    public static function getHourlyUsagePatterns(int $organizationId = 0, string $startDate = '', string $endDate = ''): array {
        $whereClause = "WHERE 1=1";
        $params = [];

        if ($organizationId > 0) {
            $whereClause .= " AND a.organization_id = ?";
            $params[] = $organizationId;
        }

        if (!empty($startDate) && !empty($endDate)) {
            $whereClause .= " AND u.created_at BETWEEN ? AND ?";
            $params[] = $startDate . ' 00:00:00';
            $params[] = $endDate . ' 23:59:59';
        }

        $sql = "SELECT 
                HOUR(u.created_at) as hour,
                COUNT(u.id) as total_requests,
                SUM(CASE WHEN u.status = 1 THEN 1 ELSE 0 END) as successful_requests
                FROM usage u
                INNER JOIN access_token a ON u.access_token_id = a.id
                $whereClause
                GROUP BY HOUR(u.created_at)
                ORDER BY hour";

        try {
            return execute_query($sql, $params);
        } catch (Exception $e) {
            error_log("Get hourly usage patterns error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get document type distribution for pie chart
     */
    public static function getDocumentTypeDistribution(int $organizationId = 0, string $startDate = '', string $endDate = ''): array {
        $whereClause = "WHERE u.doc_type IS NOT NULL";
        $params = [];

        if ($organizationId > 0) {
            $whereClause .= " AND a.organization_id = ?";
            $params[] = $organizationId;
        }

        if (!empty($startDate) && !empty($endDate)) {
            $whereClause .= " AND u.created_at BETWEEN ? AND ?";
            $params[] = $startDate . ' 00:00:00';
            $params[] = $endDate . ' 23:59:59';
        }

        $sql = "SELECT 
                u.doc_type,
                COUNT(u.id) as request_count,
                SUM(CASE WHEN u.status = 1 THEN 1 ELSE 0 END) as successful_count,
                ROUND((SUM(CASE WHEN u.status = 1 THEN 1 ELSE 0 END) / COUNT(u.id)) * 100, 2) as success_rate
                FROM usage u
                INNER JOIN access_token a ON u.access_token_id = a.id
                $whereClause
                GROUP BY u.doc_type
                ORDER BY request_count DESC";

        try {
            return execute_query($sql, $params);
        } catch (Exception $e) {
            error_log("Get document type distribution error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get status code summary for donut chart
     */
    public static function getStatusSummary(int $organizationId = 0, string $startDate = '', string $endDate = ''): array {
        $whereClause = "WHERE 1=1";
        $params = [];

        if ($organizationId > 0) {
            $whereClause .= " AND a.organization_id = ?";
            $params[] = $organizationId;
        }

        if (!empty($startDate) && !empty($endDate)) {
            $whereClause .= " AND u.created_at BETWEEN ? AND ?";
            $params[] = $startDate . ' 00:00:00';
            $params[] = $endDate . ' 23:59:59';
        }

        $sql = "SELECT 
                u.status,
                COUNT(u.id) as count,
                ROUND((COUNT(u.id) / (SELECT COUNT(*) FROM usage u2 
                                     INNER JOIN access_token a2 ON u2.access_token_id = a2.id 
                                     $whereClause)) * 100, 2) as percentage
                FROM usage u
                INNER JOIN access_token a ON u.access_token_id = a.id
                $whereClause
                GROUP BY u.status
                ORDER BY u.status";

        try {
            return execute_query($sql, $params);
        } catch (Exception $e) {
            error_log("Get status summary error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get top organizations by usage (if no organization filter)
     */
    public static function getTopOrganizations(string $startDate = '', string $endDate = '', int $limit = 10): array {
        $whereClause = "";
        $params = [];

        if (!empty($startDate) && !empty($endDate)) {
            $whereClause = "WHERE u.created_at BETWEEN ? AND ?";
            $params[] = $startDate . ' 00:00:00';
            $params[] = $endDate . ' 23:59:59';
        }

        $sql = "SELECT 
                o.id,
                o.name as organization_name,
                COUNT(u.id) as total_requests,
                SUM(CASE WHEN u.status = 1 THEN 1 ELSE 0 END) as successful_requests,
                ROUND((SUM(CASE WHEN u.status = 1 THEN 1 ELSE 0 END) / COUNT(u.id)) * 100, 2) as success_rate
                FROM usage u
                INNER JOIN access_token a ON u.access_token_id = a.id
                INNER JOIN organization o ON a.organization_id = o.id
                $whereClause
                GROUP BY o.id, o.name
                ORDER BY total_requests DESC
                LIMIT ?";

        $params[] = $limit;

        try {
            return execute_query($sql, $params);
        } catch (Exception $e) {
            error_log("Get top organizations error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get recent usage activity for table
     */
    public static function getRecentUsage(int $organizationId = 0, string $startDate = '', string $endDate = '', int $limit = 50): array {
        $whereClause = "WHERE 1=1";
        $params = [];

        if ($organizationId > 0) {
            $whereClause .= " AND a.organization_id = ?";
            $params[] = $organizationId;
        }

        if (!empty($startDate) && !empty($endDate)) {
            $whereClause .= " AND u.created_at BETWEEN ? AND ?";
            $params[] = $startDate . ' 00:00:00';
            $params[] = $endDate . ' 23:59:59';
        }

        $sql = "SELECT 
                u.*,
                a.name as token_name,
                o.name as organization_name,
                CASE 
                    WHEN u.status = 1 THEN 'Success'
                    ELSE 'Failed'
                END as status_text
                FROM usage u
                INNER JOIN access_token a ON u.access_token_id = a.id
                INNER JOIN organization o ON a.organization_id = o.id
                $whereClause
                ORDER BY u.created_at DESC
                LIMIT ?";

        $params[] = $limit;

        try {
            return execute_query($sql, $params);
        } catch (Exception $e) {
            error_log("Get recent usage error: " . $e->getMessage());
            return [];
        }
    }
}