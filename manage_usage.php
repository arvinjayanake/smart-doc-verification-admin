<?php

require_once 'admin_validation.php';
require_once 'db/usage_controller.php';
require_once 'db/organization_controller.php';

// Get filters from URL parameters
$organizationId = (int)($_GET['organization_id'] ?? 0);
$dateRange = $_GET['date_range'] ?? '';
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';

// Parse date range if provided
if (!empty($dateRange)) {
    $dates = explode(' - ', $dateRange);
    if (count($dates) === 2) {
        $startDate = date('Y-m-d', strtotime(trim($dates[0])));
        $endDate = date('Y-m-d', strtotime(trim($dates[1])));
    }
}

// Validate date range (max 365 days)
if (!empty($startDate) && !empty($endDate)) {
    $dateDiff = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);
    if ($dateDiff > 365) {
        $endDate = date('Y-m-d', strtotime($startDate . ' +365 days'));
    }
}

// Get organizations for dropdown
$organizations = OrganizationController::getAllOrganizations();

// Initialize empty data arrays
$summary = [
    'total_requests' => 0,
    'successful_requests' => 0,
    'failed_requests' => 0,
    'unique_doc_types' => 0,
    'active_days' => 0
];
$dailyTrends = [];
$hourlyPatterns = [];
$docTypes = [];
$statusSummary = [];
$recentUsage = [];
$successRate = 0;

// Only fetch data if at least one filter is selected
$hasFilters = $organizationId > 0 || (!empty($startDate) && !empty($endDate));

if ($hasFilters) {
    // Get analytics data
    $summary = UsageController::getUsageSummary($organizationId, $startDate, $endDate);
    $dailyTrends = UsageController::getDailyUsageTrends($organizationId, $startDate, $endDate);
    $hourlyPatterns = UsageController::getHourlyUsagePatterns($organizationId, $startDate, $endDate);
    $docTypes = UsageController::getDocumentTypeDistribution($organizationId, $startDate, $endDate);
    $statusSummary = UsageController::getStatusSummary($organizationId, $startDate, $endDate);
    $recentUsage = UsageController::getRecentUsage($organizationId, $startDate, $endDate, 10);

    // Calculate percentages for metrics
    $successRate = $summary['total_requests'] > 0
        ? round(($summary['successful_requests'] / $summary['total_requests']) * 100, 2)
        : 0;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Smart Doc • Usage Analytics</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/styles_manage_usage.css"/>
</head>
<body>
<div class="layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="brand">
            <i class="fa-solid fa-shield-halved brand-icon" aria-hidden="true"></i>
            <span class="brand-text">Smart Doc Admin</span>
        </div>
        <nav class="menu">
            <a href="manage_admin.php"><i class="fa-solid fa-users-gear"></i><span>System Admins</span></a>
            <a href="manage_organization.php"><i class="fa-solid fa-building-columns"></i><span>Organizations</span></a>
            <a href="manage_org_users.php"><i class="fa-solid fa-users"></i><span>Org Users</span></a>
            <a href="manage_access_token.php"><i class="fa-solid fa-key"></i><span>Access Tokens</span></a>
            <a class="active" href="manage_usage.php"><i class="fa-solid fa-chart-line"></i><span>Usage Analytics</span></a>
            <a href="admin_logout.php"><i class="fa-solid fa-sign-out"></i><span>Log Out</span></a>
        </nav>
    </aside>

    <!-- Content -->
    <main class="content">
        <header class="topbar">
            <h1>Usage Analytics</h1>
            <span class="subtitle">API Usage Statistics and Trends</span>
        </header>

        <section class="page">
            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" action="manage_usage.php" id="filterForm">
                    <div class="filter-grid">
                        <div>
                            <label>Organization</label>
                            <select name="organization_id" class="filter-select">
                                <option value="0">All Organizations</option>
                                <?php foreach ($organizations as $org): ?>
                                    <option value="<?php echo $org['id']; ?>"
                                        <?php echo $organizationId == $org['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($org['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label>Date Range</label>
                            <input type="text" name="date_range" id="dateRange"
                                   value="<?php echo !empty($dateRange) ? htmlspecialchars($dateRange) : ''; ?>"
                                   class="daterangepicker-input"
                                   placeholder="Select date range"/>
                            <input type="hidden" name="start_date" value="<?php echo $startDate; ?>"/>
                            <input type="hidden" name="end_date" value="<?php echo $endDate; ?>"/>
                        </div>

                        <div>
                            <button type="submit" class="btn primary">
                                <i class="fa-solid fa-filter"></i> Apply Filters
                            </button>
                            <a href="manage_usage.php" class="btn">
                                <i class="fa-solid fa-rotate-left"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <?php if (!$hasFilters): ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fa-solid fa-chart-bar"></i>
                    <h3>No filters selected</h3>
                    <p>Please select an organization and/or date range to view usage analytics.</p>
                </div>
            <?php else: ?>
                <!-- Summary Metrics -->
                <div class="metric-grid">
                    <div class="metric-card">
                        <div class="metric-value"><?php echo number_format($summary['total_requests']); ?></div>
                        <div class="metric-label">Total Requests</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-value"><?php echo number_format($summary['successful_requests']); ?></div>
                        <div class="metric-label">Successful Requests</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-value"><?php echo $successRate; ?>%</div>
                        <div class="metric-label">Success Rate</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-value"><?php echo number_format($summary['unique_doc_types']); ?></div>
                        <div class="metric-label">Document Types</div>
                    </div>
                </div>

                <!-- Charts Grid -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Daily Trends Chart -->
                    <div class="chart-container">
                        <div class="chart-title">Daily Usage Trends</div>
                        <canvas id="dailyTrendsChart" height="250"></canvas>
                    </div>

                    <!-- Status Distribution Chart -->
                    <div class="chart-container">
                        <div class="chart-title">Request Status Distribution</div>
                        <canvas id="statusChart" height="250"></canvas>
                    </div>

                    <!-- Hourly Patterns Chart -->
                    <div class="chart-container">
                        <div class="chart-title">Hourly Usage Patterns</div>
                        <canvas id="hourlyChart" height="250"></canvas>
                    </div>

                    <!-- Document Types Chart -->
                    <div class="chart-container">
                        <div class="chart-title">Document Type Distribution</div>
                        <canvas id="docTypesChart" height="250"></canvas>
                    </div>
                </div>

                <!-- Recent Activity Table -->
                <div class="chart-container">
                    <div class="chart-title">Recent Activity</div>
                    <table class="data-table">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Organization</th>
                            <th>Document Type</th>
                            <th>Status</th>
                            <th>Token</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($recentUsage)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px;">
                                    No usage data found for the selected filters
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentUsage as $usage): ?>
                                <tr>
                                    <td><?php echo date('M j, Y H:i', strtotime($usage['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($usage['organization_name']); ?></td>
                                    <td><?php echo htmlspecialchars($usage['doc_type'] ?? 'N/A'); ?></td>
                                    <td>
                                            <span class="status-<?php echo $usage['status'] == 1 ? 'success' : 'failed'; ?>">
                                                <?php echo $usage['status_text']; ?>
                                            </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($usage['token_name']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <script>
                    // Chart data from PHP
                    const chartData = {
                        dailyTrends: {
                            dates: <?php echo json_encode(array_column($dailyTrends, 'date')); ?>,
                            total: <?php echo json_encode(array_column($dailyTrends, 'total_requests')); ?>,
                            success: <?php echo json_encode(array_column($dailyTrends, 'successful_requests')); ?>,
                            failed: <?php echo json_encode(array_column($dailyTrends, 'failed_requests')); ?>
                        },
                        statusSummary: {
                            labels: ['Successful', 'Failed'],
                            data: <?php echo json_encode(array_column($statusSummary, 'count')); ?>,
                            colors: ['#10b981', '#ef4444']
                        },
                        hourlyPatterns: {
                            hours: <?php echo json_encode(array_column($hourlyPatterns, 'hour')); ?>,
                            requests: <?php echo json_encode(array_column($hourlyPatterns, 'total_requests')); ?>,
                            success: <?php echo json_encode(array_column($hourlyPatterns, 'successful_requests')); ?>
                        },
                        docTypes: {
                            labels: <?php echo json_encode(array_column($docTypes, 'doc_type')); ?>,
                            counts: <?php echo json_encode(array_column($docTypes, 'request_count')); ?>,
                            successRates: <?php echo json_encode(array_column($docTypes, 'success_rate')); ?>
                        }
                    };

                    // Initialize Charts
                    document.addEventListener('DOMContentLoaded', function () {
                        // Daily Trends Line Chart
                        if (chartData.dailyTrends.dates.length > 0) {
                            new Chart(document.getElementById('dailyTrendsChart'), {
                                type: 'line',
                                data: {
                                    labels: chartData.dailyTrends.dates,
                                    datasets: [
                                        {
                                            label: 'Total Requests',
                                            data: chartData.dailyTrends.total,
                                            borderColor: '#3b82f6',
                                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                            fill: true
                                        },
                                        {
                                            label: 'Successful',
                                            data: chartData.dailyTrends.success,
                                            borderColor: '#10b981',
                                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                            fill: true
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {position: 'top'}
                                    }
                                }
                            });
                        }

                        // Status Donut Chart
                        if (chartData.statusSummary.data.length > 0) {
                            new Chart(document.getElementById('statusChart'), {
                                type: 'doughnut',
                                data: {
                                    labels: chartData.statusSummary.labels,
                                    datasets: [{
                                        data: chartData.statusSummary.data,
                                        backgroundColor: chartData.statusSummary.colors
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {position: 'top'}
                                    }
                                }
                            });
                        }

                        // Hourly Patterns Bar Chart
                        if (chartData.hourlyPatterns.hours.length > 0) {
                            new Chart(document.getElementById('hourlyChart'), {
                                type: 'bar',
                                data: {
                                    labels: chartData.hourlyPatterns.hours.map(h => h + ':00'),
                                    datasets: [{
                                        label: 'Total Requests',
                                        data: chartData.hourlyPatterns.requests,
                                        backgroundColor: '#6366f1'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {display: false}
                                    },
                                    scales: {
                                        x: {
                                            title: {display: true, text: 'Hour of Day'}
                                        },
                                        y: {
                                            title: {display: true, text: 'Requests'}
                                        }
                                    }
                                }
                            });
                        }

                        // Document Types Bar Chart
                        if (chartData.docTypes.labels.length > 0) {
                            new Chart(document.getElementById('docTypesChart'), {
                                type: 'bar',
                                data: {
                                    labels: chartData.docTypes.labels,
                                    datasets: [{
                                        label: 'Request Count',
                                        data: chartData.docTypes.counts,
                                        backgroundColor: '#8b5cf6'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    indexAxis: 'y',
                                    plugins: {
                                        legend: {display: false}
                                    }
                                }
                            });
                        }
                    });
                </script>
            <?php endif; ?>
        </section>
    </main>
</div>

<script>
    // Initialize date range picker
    $(document).ready(function () {
        $('#dateRange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        $('#dateRange').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            $('input[name="start_date"]').val(picker.startDate.format('YYYY-MM-DD'));
            $('input[name="end_date"]').val(picker.endDate.format('YYYY-MM-DD'));
        });

        $('#dateRange').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            $('input[name="start_date"]').val('');
            $('input[name="end_date"]').val('');
        });

        // Set initial values if they exist
        <?php if (!empty($startDate) && !empty($endDate)): ?>
        $('#dateRange').val('<?php echo $startDate . " - " . $endDate; ?>');
        <?php endif; ?>
    });
</script>
</body>
</html>