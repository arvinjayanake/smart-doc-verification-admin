<?php
require_once 'db\access_token_controller.php';
require_once 'db\organization_controller.php';
$defaultExpire = date('Y-m-d\TH:i', strtotime('+90 days'));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $organization_id = (int)($_POST['organization_id'] ?? '0');
    $name = trim($_POST['name'] ?? '');
    $expireDateString = $_POST['expire_date'] ?? '';

    // Convert and validate the date
    $expireDate = DateTime::createFromFormat('Y-m-d\TH:i', $expireDateString);

    if ($expireDate === false) {
        // Handle invalid date format
        die("Invalid date format!");
    }

    // Create the access token
    $result = AccessTokenController::createAccessToken($organization_id, $name, $expireDate);

    if ($result) {
        header('Location: manage_access_token.php');
        exit;
    } else {
        // Handle creation failure
        die("Failed to create access token!");
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Smart Doc • New Organization Access Token</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!-- Professional font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <!-- Font Awesome (icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/styles_add_admin.css"/>
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
            <a href="manage_usage.php"><i class="fa-solid fa-chart-line"></i><span>Usage</span></a>
        </nav>
        <div class="sidebar-foot">
            <span><i class="fa-regular fa-circle-check"></i> v1.0</span>
        </div>
    </aside>

    <!-- Content -->
    <main class="content">
        <header class="topbar">
            <h1>New Organization Access Token</h1>
            <a href="manage_access_token.php" class="btn">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </a>
        </header>

        <section class="page">
            <div class="card">
                <div class="card-head">
                    <h2 class="card-title">New Access Token</h2> <!-- Fixed title -->
                </div>

                <form method="post" action="manage_access_token_add.php" class="form-block">
                    <div class="grid-2">
                        <label>
                            <span>Organization *</span> <!-- Added asterisk -->
                            <select name="organization_id" required>
                                <option value="">Select Organization</option> <!-- Default option -->
                                <?php
                                $organizations = OrganizationController::getAllOrganizations();
                                foreach ($organizations as $org) {
                                    ?>
                                    <option value="<?php echo $org['id'] ?>"><?php echo htmlspecialchars($org['name']) ?></option>
                                <?php } ?>
                            </select>
                        </label>

                        <label>
                            <span>Token Name *</span> <!-- Added asterisk -->
                            <input type="text" name="name" placeholder="e.g., Production API Token" required />
                        </label>
                    </div>

                    <div class="grid-2">
                        <label>
                            <span>Expire Date & Time *</span>
                            <input type="datetime-local" name="expire_date"
                                   value="<?php echo $defaultExpire; ?>"
                                   min="<?php echo date('Y-m-d\TH:i'); ?>"
                                   required />
                            <small style="display: block; margin-top: 5px; color: #666;">
                                Token will automatically expire after this date
                            </small>
                        </label>
                    </div>

                    <div class="form-actions">
                        <a href="manage_access_token.php" class="btn">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </a>
                        <button type="submit" class="btn primary">
                            <i class="fa-solid fa-key"></i> Create Access Token
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>
</body>
</html>