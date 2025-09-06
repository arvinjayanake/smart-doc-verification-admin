<?php
require_once 'admin_validation.php';
require_once 'db\organization_controller.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $status= $_POST['status'] ?? '0';

    OrganizationController::createOrganization($name, $address, $mobile, $status);

    header('Location: manage_organization.php'); exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Smart Doc • Add Admin</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Professional font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <!-- Font Awesome (icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/styles_add_admin.css" />
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
            <a href="admin_logout.php"><i class="fa-solid fa-sign-out"></i><span>Log Out</span></a>
        </nav>
    </aside>

    <!-- Content -->
    <main class="content">
        <header class="topbar">
            <h1>Add Organization</h1>
            <a href="manage_organization.php" class="btn">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </a>
        </header>

        <section class="page">
            <div class="card">
                <div class="card-head">
                    <h2 class="card-title">New Organization</h2>
                </div>

                <form method="post" action="manage_organization_add.php" class="form-block">
                    <div class="grid-2">
                        <label>
                            <span>Name</span>
                            <input type="text" name="name" maxlength="100" placeholder="Organization Name" required />
                        </label>

                        <label>
                            <span>Address</span>
                            <input type="text" name="address" maxlength="200" placeholder="Colombo" required />
                        </label>
                    </div>

                    <div class="grid-2">
                        <label>
                            <span>Mobile</span>
                            <input type="number" name="mobile" placeholder="0771775727" maxlength="10" required />
                        </label>

                        <label>
                            <span>Status</span>
                            <select name="status" required>
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </label>
                    </div>

                    <div class="form-actions">
                        <a href="manage_organization.php" class="btn">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </a>
                        <button type="submit" class="btn primary">
                            <i class="fa-solid fa-floppy-disk"></i> Create Organization
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>
</body>
</html>
