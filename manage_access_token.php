<?php
require_once 'admin_validation.php';
require_once 'db\access_token_controller.php';
require_once 'db\organization_controller.php';

// Get filter parameters from URL
$organization_id = isset($_GET['organization_id']) ? (int)$_GET['organization_id'] : 0;
$search = $_GET['search'] ?? '';

// Get all organizations for the dropdown
$organizations = OrganizationController::getAllOrganizations();

// Get tokens based on filters
if ($organization_id > 0) {
    $tokens = AccessTokenController::getAccessTokensByOrganization($organization_id);
} else {
    $tokens = AccessTokenController::getAllAccessTokens();
}

// If search is provided, filter the results
if (!empty($search)) {
    $tokens = array_filter($tokens, function($token) use ($search) {
        return stripos($token['name'], $search) !== false;
    });
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Smart Doc • Access Tokens</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!-- Professional font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <!-- Font Awesome (icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/styles_manage_org_users.css"/>
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
            <a class="active" href="manage_access_token.php"><i class="fa-solid fa-key"></i><span>Access Tokens</span></a>
            <a href="manage_usage.php"><i class="fa-solid fa-chart-line"></i><span>Usage</span></a>
            <a href="admin_logout.php"><i class="fa-solid fa-sign-out"></i><span>Log Out</span></a>
        </nav>
    </aside>

    <!-- Content -->
    <main class="content">
        <header class="topbar">
            <h1>Organization Access Tokens</h1>
            <a href="manage_access_token_add.php" class="btn">
                <i class="fa-solid fa-plus"></i> Add Access Token
            </a>
        </header>

        <section class="page">
            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" action="manage_access_token.php">
                    <div class="filter-grid">
                        <div>
                            <label>Filter by Organization</label>
                            <select name="organization_id" class="filter-select">
                                <option value="0">All Organizations</option>
                                <?php foreach ($organizations as $org): ?>
                                    <option value="<?php echo $org['id']; ?>"
                                        <?php echo $organization_id == $org['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($org['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label>Search Access Tokens</label>
                            <input type="text" name="search" placeholder="Search by name, email or organization..."
                                   value="<?php echo htmlspecialchars($search); ?>" class="filter-input">
                        </div>

                        <div>
                            <button type="submit" class="filter-btn">
                                <i class="fa-solid fa-filter"></i> Apply Filters
                            </button>
                        </div>
                    </div>
                </form>

                <?php if ($organization_id > 0 || !empty($search)): ?>
                    <div class="filter-info">
                        <strong>Active Filters:</strong>
                        <?php if ($organization_id > 0): ?>
                            <?php
                            $selected_org = array_filter($organizations, function($org) use ($organization_id) {
                                return $org['id'] == $organization_id;
                            });
                            $selected_org = reset($selected_org);
                            ?>
                            <span class="filter-tag">Organization: <?php echo htmlspecialchars($selected_org['name']); ?></span>
                        <?php endif; ?>

                        <?php if (!empty($search)): ?>
                            <span class="filter-tag">Search: "<?php echo htmlspecialchars($search); ?>"</span>
                        <?php endif; ?>

                        <a href="manage_access_token.php" class="filter-reset">
                            <i class="fa-solid fa-times"></i> Clear Filters
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card">
                <div class="card-head">
                    <h2 class="card-title">Organization Access Tokens</h2>
                    <span class="card-subtitle">Showing <?php echo count($tokens); ?> user(s)</span>
                </div>

                <div class="table-wrap">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Organization</th>
                            <th>Name</th>
                            <th>Token</th>
                            <th>Created At</th>
                            <th class="actions-col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($tokens)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px;">
                                    <i class="fa-solid fa-users-slash" style="font-size: 48px; color: #6c757d; margin-bottom: 15px;"></i>
                                    <h3>No users found</h3>
                                    <p>No organization users match your current filters.</p>
                                    <a href="manage_access_token.php" class="btn">
                                        <i class="fa-solid fa-times"></i> Clear Filters
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($tokens as $token):
                                $tokenOrg = OrganizationController::getOrganizationById($token['organization_id'])
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($tokenOrg['name']); ?></td>
                                    <td><?php echo $token['name']; ?></td>
                                    <td><?php echo $token['token']; ?></td>
                                    <td><?php echo date('M j, Y', strtotime($token['expire_date'])); ?></td>
                                    <td class="actions">
                                        <a href="manage_access_token_delete.php?id=<?php echo $token['id']; ?>"
                                           class="btn danger"
                                           onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars(addslashes($token['name'])); ?>?')">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>