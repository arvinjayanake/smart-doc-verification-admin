<?php
require_once 'admin_validation.php';
require_once 'db/organization_controller.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Smart Doc • System Admins</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
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
            <a href="manage_admin.php"><i class="fa-solid fa-users-gear"></i><span>System Admins</span></a>
            <a class="active" href="manage_organization.php"><i class="fa-solid fa-building-columns"></i><span>Organizations</span></a>
            <a href="manage_org_users.php"><i class="fa-solid fa-users"></i><span>Org Users</span></a>
            <a href="manage_access_token.php"><i class="fa-solid fa-key"></i><span>Access Tokens</span></a>
            <a href="manage_usage.php"><i class="fa-solid fa-chart-line"></i><span>Usage</span></a>
            <a href="admin_logout.php"><i class="fa-solid fa-sign-out"></i><span>Log Out</span></a>
        </nav>
    </aside>

    <!-- Content -->
    <main class="content">
        <header class="topbar">
            <h1>Organizations</h1>
            <a href="manage_organization_add.php" class="btn">
                <i class="fa-solid fa-plus"></i> Add Organization
            </a>
        </header>

        <section class="page">
            <div class="card">
                <div class="card-head">
                    <h2 class="card-title">Organizations</h2>
                </div>

                <div class="table-wrap">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th class="actions-col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $organizations = OrganizationController::getAllOrganizations();
                        foreach ($organizations as $org) {
                            ?>

                            <!-- Example row -->
                            <tr>
                                <td><?php echo $org['name'] ?></td>
                                <td><?php echo $org['mobile'] ?></td>
                                <td><?php echo $org['status'] == 1 ? "Active" : "Inactive" ?></td>
                                <td><?php echo $org['created_at'] ?></td>
                                <td class="actions">
                                    <form method="get" action="manage_organization_edit.php?id=<?php echo $org['id'] ?>"
                                          class="inline-form">
                                        <input type="hidden" name="id" value="<?php echo $org['id'] ?>"/>
                                        <button class="btn" type="submit">
                                            <i class="fa-solid fa-edit"></i> Edit
                                        </button>
                                    </form>
                                    <form method="get" action="manage_organization_delete.php?id=<?php echo $org['id'] ?>"
                                          class="inline-form">
                                        <input type="hidden" name="id" value="<?php echo $org['id'] ?>"/>
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
