<?php
require_once 'db\admin_controller.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Smart Doc • System Admins</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!-- Professional font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <!-- Font Awesome (icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/styles.css"/>
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
            <a class="active" href="manage_admin.php"><i
                        class="fa-solid fa-users-gear"></i><span>System Admins</span></a>
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
            <h1>System Admins</h1>
            <a href="manage_admin_add.php" class="btn">
                <i class="fa-solid fa-plus"></i> Add Admin
            </a>
        </header>

        <section class="page">
            <div class="card">
                <div class="card-head">
                    <h2 class="card-title">Admins</h2>
                </div>

                <div class="table-wrap">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th class="actions-col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $admins = AdminController::getAllAdmins();
                        foreach ($admins as $admin) {
                            ?>

                            <!-- Example row -->
                            <tr>
                                <td><?php echo $admin['name'] ?></td>
                                <td><?php echo $admin['email'] ?></td>
                                <td><?php echo $admin['created_at'] ?></td>
                                <td><?php echo $admin['updated_at'] ?></td>
                                <td class="actions">
                                    <form method="get" action="manage_admin_delete.php?id=<?php echo $admin['id'] ?>" class="inline-form">
                                        <input type="hidden" name="id" value="<?php echo $admin['id'] ?>"/>
                                        <button class="btn danger" type="submit">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
