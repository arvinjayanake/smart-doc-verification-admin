<?php
require_once 'admin_validation.php';
require_once 'db\organization_user_controller.php';
require_once 'db\organization_controller.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$org_user = OrganizationUserController::getOrganizationUserById($id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    OrganizationUserController::deleteOrganizationUser($id);
    header('Location: manage_org_users.php');
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Smart Doc • Delete Organization User</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!-- Professional font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <!-- Font Awesome (icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/styles_delete_admin.css"/>
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
            <h1>Organization User</h1>
            <a href="manage_org_users.php" class="btn">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </a>
        </header>

        <section class="page">
            <div class="card danger-card">
                <div class="card-head">
                    <h2 class="card-title">
                        <i class="fa-solid fa-triangle-exclamation"></i> Confirm Deletion
                    </h2>
                </div>

                <!-- Read-only details (render values from server) -->
                <div class="form-block">
                    <div class="grid-2">
                        <label>
                            <span>Status</span>
                            <select name="organization_id" disabled>
                                <?php
                                $organizations = OrganizationController::getAllOrganizations();
                                foreach ($organizations as $org) {
                                    ?>
                                    <option value="<?php echo $org['id'] ?>" <?php echo ($org['id'] == $org_user['organization_id']) ? 'selected' : ''; ?>><?php echo $org['name'] ?></option>
                                <?php } ?>
                            </select>
                        </label>
                    </div>

                    <div class="grid-2">
                        <label>
                            <span>Name</span>
                            <input type="text" name="name" value="<?php echo $org_user['name'] ?>" placeholder="Jane Admin" readonly/>
                        </label>

                        <label>
                            <span>Email</span>
                            <input type="email" name="email" <?php echo $org_user['email'] ?> placeholder="admin@example.com" readonly/>
                        </label>
                    </div>

                    <!-- Delete form -->
                    <form method="post" action="manage_org_user_delete.php?id=<?php echo $id ?>" class="form-actions">
                        <a href="manage_org_users.php" class="btn">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </a>
                        <button type="submit" class="btn danger">
                            <i class="fa-solid fa-trash"></i> Delete Organization User
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
