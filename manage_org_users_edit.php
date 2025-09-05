<?php
require_once 'db\organization_user_controller.php';
require_once 'db\organization_controller.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$org_user = OrganizationUserController::getOrganizationUserById($id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $organization_id = $_POST['organization_id'] ?? '0';
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $hashed_password = hash('sha256', $password);

    OrganizationUserController::updateOrganizationUser($id, $organization_id, $name, $email, $hashed_password);
    header('Location: manage_org_users.php');
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Smart Doc • New Organization User</title>
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
            <h1>New Organization User</h1>
            <a href="manage_org_users.php" class="btn">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </a>
        </header>

        <section class="page">
            <div class="card">
                <div class="card-head">
                    <h2 class="card-title">New Organization User</h2>
                </div>

                <form method="post" action="manage_org_users_edit.php?id=<?php echo $org_user['id'] ?>" class="form-block">
                    <div class="grid-2">
                        <label>
                            <span>Status</span>
                            <select name="organization_id" required>
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
                            <input type="text" name="name" value="<?php echo $org_user['name'] ?>" placeholder="Jane Admin" required/>
                        </label>

                        <label>
                            <span>Email</span>
                            <input type="email" name="email" <?php echo $org_user['email'] ?> placeholder="admin@example.com" required/>
                        </label>
                    </div>

                    <div class="grid-2">
                        <label>
                            <span>Password</span>
                            <input type="password" name="password" placeholder="Minimum 6 characters" minlength="6" required />
                        </label>

                        <label>
                            <span>Confirm Password</span>
                            <input type="password" name="password_confirm" placeholder="Re-enter password" minlength="6" required />
                        </label>
                    </div>

                    <div class="form-actions">
                        <a href="manage_org_users.php" class="btn">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </a>
                        <button type="submit" class="btn primary">
                            <i class="fa-solid fa-floppy-disk"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>
</body>
</html>
