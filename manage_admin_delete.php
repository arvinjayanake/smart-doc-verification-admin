<?php
    require_once 'db\admin_controller.php';

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $admin = AdminController::getAdminById($id);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        AdminController::deleteAdmin($id);
        header('Location: manage_admin.php'); exit;
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Smart Doc • Delete Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Professional font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <!-- Font Awesome (icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/styles_delete_admin.css" />
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
            <h1>Delete Admin</h1>
            <a href="manage_admin.php" class="btn">
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
                            <span>Name</span>
                            <input type="text" value="<?php echo $admin['name'] ?>" readonly />
                        </label>

                        <label>
                            <span>Email</span>
                            <input type="text" value="<?php echo $admin['email'] ?>" readonly />
                        </label>
                    </div>

                    <div class="grid-2">
                        <label>
                            <span>Created At</span>
                            <input type="text" value="<?php echo $admin['created_at'] ?>" readonly />
                        </label>

                        <label>
                            <span>Updated At</span>
                            <input type="text" value="<?php echo $admin['updated_at'] ?>" readonly />
                        </label>
                    </div>

                    <div class="delete-note">
                        <i class="fa-solid fa-circle-info"></i>
                        This action cannot be undone. The admin account and its access will be permanently removed.
                    </div>

                    <!-- Delete form -->
                    <form method="post" action="manage_admin_delete.php?id=<?php echo $id ?>" class="form-actions">
                        <a href="manage_admin.php" class="btn">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </a>
                        <button type="submit" class="btn danger">
                            <i class="fa-solid fa-trash"></i> Delete Admin
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
