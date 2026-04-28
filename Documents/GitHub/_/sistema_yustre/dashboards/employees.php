<?php
// Auth: admin only
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login/login.php');
    exit();
}
if ($_SESSION['user_rol'] !== 'admin') {
    header('Location: ../login/login.php?error=invalid_role');
    exit();
}

// DB connection
$db_host = 'db5019772005.hosting-data.io';
$db_name = 'dbs15332258';
$db_user = 'dbu4236696';
$db_pass = 'NG8936ngr82NbplE21gnp2TG5hjsg84sht73y3y3';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}

$success_message = '';
$error_message   = '';

// --- Handle POST actions ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    // Add new employee
    if ($_POST['action'] === 'add_employee') {
        $name     = trim($_POST['name']);
        $email    = trim($_POST['email']);
        $password = trim($_POST['password']);
        $rol      = trim($_POST['rol']);

        if (empty($name) || empty($email) || empty($password) || empty($rol)) {
            $error_message = 'Please fill in all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Invalid email format.';
        } elseif (strlen($password) < 4) {
            $error_message = 'Password must be at least 4 characters.';
        } else {
            // Check for duplicate email
            $check = $pdo->prepare("SELECT id FROM employees WHERE email = :email");
            $check->execute([':email' => $email]);
            if ($check->rowCount() > 0) {
                $error_message = 'An employee with that email already exists.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO employees (name, email, password, rol) VALUES (:name, :email, :password, :rol)");
                $stmt->execute([':name' => $name, ':email' => $email, ':password' => $hash, ':rol' => $rol]);
                $success_message = "Employee <strong>$name</strong> added successfully!";
            }
        }
    }

    // Edit existing employee
    elseif ($_POST['action'] === 'edit_employee') {
        $id       = intval($_POST['edit_id']);
        $name     = trim($_POST['name']);
        $email    = trim($_POST['email']);
        $rol      = trim($_POST['rol']);
        $password = trim($_POST['password']);

        if (empty($name) || empty($email) || empty($rol)) {
            $error_message = 'Please fill in all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Invalid email format.';
        } else {
            // Check email not taken by another employee
            $check = $pdo->prepare("SELECT id FROM employees WHERE email = :email AND id != :id");
            $check->execute([':email' => $email, ':id' => $id]);
            if ($check->rowCount() > 0) {
                $error_message = 'That email is already used by another employee.';
            } else {
                if (!empty($password)) {
                    // Update with new password
                    if (strlen($password) < 4) {
                        $error_message = 'Password must be at least 4 characters.';
                    } else {
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("UPDATE employees SET name=:name, email=:email, rol=:rol, password=:password WHERE id=:id");
                        $stmt->execute([':name' => $name, ':email' => $email, ':rol' => $rol, ':password' => $hash, ':id' => $id]);
                        $success_message = "Employee <strong>$name</strong> updated (including password)!";
                    }
                } else {
                    // Update without changing password
                    $stmt = $pdo->prepare("UPDATE employees SET name=:name, email=:email, rol=:rol WHERE id=:id");
                    $stmt->execute([':name' => $name, ':email' => $email, ':rol' => $rol, ':id' => $id]);
                    $success_message = "Employee <strong>$name</strong> updated successfully!";
                }
            }
        }
    }

    // Delete employee
    elseif ($_POST['action'] === 'delete_employee') {
        $id = intval($_POST['delete_id']);
        // Prevent self-deletion
        if ($id === intval($_SESSION['user_id'])) {
            $error_message = 'You cannot delete your own account.';
        } else {
            $stmt = $pdo->prepare("SELECT name FROM employees WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $emp = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($emp) {
                $pdo->prepare("DELETE FROM employees WHERE id = :id")->execute([':id' => $id]);
                $success_message = "Employee <strong>{$emp['name']}</strong> deleted successfully!";
            } else {
                $error_message = 'Employee not found.';
            }
        }
    }
}

// --- Fetch employees with optional filters ---
$search     = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_rol = isset($_GET['rol'])    ? $_GET['rol']          : '';

$sql    = "SELECT * FROM employees WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (id LIKE :search OR name LIKE :search OR email LIKE :search)";
    $params[':search'] = "%$search%";
}
if (!empty($filter_rol)) {
    $sql .= " AND rol = :rol";
    $params[':rol'] = $filter_rol;
}

$sql .= " ORDER BY name ASC";
$stmt      = $pdo->prepare($sql);
$stmt->execute($params);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Sidebar user info
$user_name    = $_SESSION['user_name'] ?? 'Admin';
$user_rol     = $_SESSION['user_rol']  ?? 'admin';
$user_initial = strtoupper(substr($user_name, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees — Yustre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style_employees.css">
</head>

<body class="shop-module">

<!-- Apply saved theme before render to avoid flash -->
<script src="../assets/js/theme-toggle.js"></script>

    <!-- Overlay: closes sidebar on mobile tap-outside -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Toggle button: visible on desktop, hidden on mobile -->
    <button class="sidebar-toggle-btn" id="sidebarToggleBtn" onclick="toggleSidebar()" title="Toggle sidebar">&#x2039;</button>

    <!-- Topbar: mobile only -->
    <div class="topbar">
        <button class="topbar-hamburger" onclick="openSidebar()" aria-label="Open menu">
            <span></span><span></span><span></span>
        </button>
        <div class="topbar-logo"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M3 10.5L12 3L21 10.5V20C21 20.552 20.552 21 20 21H15V15H9V21H4C3.448 21 3 20.552 3 20V10.5Z" stroke="rgba(255,220,160,0.9)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
        <span class="topbar-title">Yustre &mdash; Admin</span>
    </div>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M3 10.5L12 3L21 10.5V20C21 20.552 20.552 21 20 21H15V15H9V21H4C3.448 21 3 20.552 3 20V10.5Z" stroke="rgba(255,220,160,0.9)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            <div class="sidebar-brand">
                <span class="sidebar-brand-name">Sistema Yustre</span>
                <span class="sidebar-brand-sub">Admin Panel</span>
            </div>
            <!-- Close button: mobile only -->
            <button class="sidebar-close-btn" onclick="closeSidebar()" aria-label="Close menu">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none">
                    <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <!-- Nav links -->
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="employees.php" class="nav-link active">
                    <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M2 21v-2a4 4 0 014-4h6a4 4 0 014 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M19 8v6M16 11h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                    <span class="nav-text">Employees</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../dashboards/dashboard_clinic.php" class="nav-link">
                    <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><rect x="2" y="7" width="10" height="14" rx="1.5" stroke="currentColor" stroke-width="1.8"/><path d="M12 9h6a2 2 0 012 2v8a2 2 0 01-2 2h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M5 4V2M9 4V2M7 7V4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                    <span class="nav-text">Clinic</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../dashboards/dashboard_shop.php" class="nav-link">
                    <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M20 7H4C2.9 7 2 7.9 2 9V19C2 20.1 2.9 21 4 21H20C21.1 21 22 20.1 22 19V9C22 7.9 21.1 7 20 7Z" stroke="currentColor" stroke-width="1.8"/><path d="M16 7V5C16 3.9 15.1 3 14 3H10C8.9 3 8 3.9 8 5V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                    <span class="nav-text">Shop</span>
                </a>
            </li>
            <!-- Admin Panel pushed to bottom -->
            <li class="nav-item bottom">
                <a href="../dashboards/dashboard_admin.php" class="nav-link">
                    <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M12 3L4 7V12C4 16.418 7.582 20.398 12 21C16.418 20.398 20 16.418 20 12V7L12 3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    <span class="nav-text">Admin Panel</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../logout.php" class="nav-link logout">
                    <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M14 3H6C5.448 3 5 3.448 5 4V20C5 20.552 5.448 21 6 21H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 8L21 12L16 16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12H9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                    <span class="nav-text">Logout</span>
                </a>
            </li>
        </ul>

        <!-- Footer: user info + theme toggle -->
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar"><?php echo $user_initial; ?></div>
                <div class="sidebar-user-info">
                    <span class="sidebar-user-name"><?php echo htmlspecialchars($user_name); ?></span>
                    <span class="sidebar-user-role"><?php echo ucfirst($user_rol); ?></span>
                </div>
            </div>
            <div class="sidebar-theme-toggle" onclick="toggleTheme()" title="Toggle theme">
                <div class="sidebar-theme-toggle-icon">
                    <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none">
                        <circle cx="12" cy="12" r="4" stroke="#f59e0b" stroke-width="2"/>
                        <path d="M12 2V4M12 20V22M4.22 4.22L5.64 5.64M18.36 18.36L19.78 19.78M2 12H4M20 12H22M4.22 19.78L5.64 18.36M18.36 5.64L19.78 4.22" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none">
                        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" stroke="#a78bfa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <span class="sidebar-theme-toggle-label"></span>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <div class="main-content" id="mainContent">

        <!-- Floating alerts (auto-dismiss after 4s) -->
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-floating alert-dismissible fade show">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-floating alert-dismissible fade show">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Page header -->
        <div class="page-header">
            <div>
                <div class="page-title"><svg class="title-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M2 21v-2a4 4 0 014-4h6a4 4 0 014 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="19" cy="8" r="3" stroke="currentColor" stroke-width="1.8"/><path d="M22 21v-1a3 3 0 00-3-3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Employees</div>
                <div class="page-subtitle">Add, edit and remove system users</div>
            </div>
        </div>

        <!-- Filter form: search + role -->
        <div class="card-section">
            <form method="GET" action="employees.php">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="filter-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Search by ID, Name or Email</label>
                        <input type="text" name="search" class="filter-input" placeholder="Type to search..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="filter-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M3 6h18M7 12h10M11 18h2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Filter by Role</label>
                        <select name="rol" class="filter-select">
                            <option value="">All roles</option>
                            <option value="admin"  <?php echo $filter_rol == 'admin'  ? 'selected' : ''; ?>>Admin</option>
                            <option value="clinic" <?php echo $filter_rol == 'clinic' ? 'selected' : ''; ?>>Clinic</option>
                            <option value="shop"   <?php echo $filter_rol == 'shop'   ? 'selected' : ''; ?>>Shop</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn-search">Search</button>
                    </div>
                </div>
                <?php if (!empty($search) || !empty($filter_rol)): ?>
                    <a href="employees.php" class="btn-clear">Clear filters</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Employee table -->
        <div class="card-section">
            <div class="table-header">
                <div class="table-title">
                    Employee List
                    <span class="total-badge"><?php echo count($employees); ?> records</span>
                </div>
                <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Add Employee
                </button>
            </div>

            <?php if (count($employees) > 0): ?>
                <div class="table-scroll">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Date Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Row click opens edit modal -->
                            <?php foreach ($employees as $emp): ?>
                                <tr onclick="openEditModal(
                                    '<?php echo $emp['id']; ?>',
                                    '<?php echo htmlspecialchars(addslashes($emp['name'])); ?>',
                                    '<?php echo htmlspecialchars(addslashes($emp['email'])); ?>',
                                    '<?php echo htmlspecialchars($emp['rol']); ?>'
                                )">
                                    <td><strong>#<?php echo $emp['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($emp['name']); ?></td>
                                    <td><?php echo htmlspecialchars($emp['email']); ?></td>
                                    <td>
                                        <span class="rol-badge rol-<?php echo $emp['rol']; ?>">
                                            <?php echo ucfirst($emp['rol']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo isset($emp['date_register']) ? date('m/d/Y', strtotime($emp['date_register'])) : '—'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <div class="no-data-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="40" height="40" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8 11h6M11 8v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></div>
                    No employees found matching the search criteria
                </div>
            <?php endif; ?>
        </div>

    </div><!-- /main-content -->

    <!-- Add Employee Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Add New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="employees.php">
                    <input type="hidden" name="action" value="add_employee">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M4 20v-1a8 8 0 0116 0v1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="modal-input" placeholder="Full name" required>
                            </div>
                            <div class="col-12">
                                <label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="2" y="4" width="20" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M2 8l10 6 10-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="modal-input" placeholder="email@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 11V7a4 4 0 018 0v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="modal-input" placeholder="Min. 4 characters" required>
                            </div>
                            <div class="col-md-6">
                                <label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M4 20v-1a8 8 0 0116 0v1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M15 8h4M17 6v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Role <span class="text-danger">*</span></label>
                                <select name="rol" class="modal-input" required>
                                    <option value="">Select role...</option>
                                    <option value="admin">Admin</option>
                                    <option value="clinic">Clinic</option>
                                    <option value="shop">Shop</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-save">Save Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Edit Employee — <span id="editModalTitle"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="employees.php">
                    <input type="hidden" name="action" value="edit_employee">
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M4 20v-1a8 8 0 0116 0v1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="edit_name" class="modal-input" required>
                            </div>
                            <div class="col-12">
                                <label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="2" y="4" width="20" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M2 8l10 6 10-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="edit_email" class="modal-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 11V7a4 4 0 018 0v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> New Password</label>
                                <input type="password" name="password" class="modal-input" placeholder="Leave blank to keep current">
                                <div class="hint-text">Leave empty to keep current password</div>
                            </div>
                            <div class="col-md-6">
                                <label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M4 20v-1a8 8 0 0116 0v1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M15 8h4M17 6v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Role <span class="text-danger">*</span></label>
                                <select name="rol" id="edit_rol" class="modal-input" required>
                                    <option value="admin">Admin</option>
                                    <option value="clinic">Clinic</option>
                                    <option value="shop">Shop</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-danger" onclick="deleteEmployee()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Delete</button>
                        <div>
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-save">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Hidden delete form (submitted via JS confirm) -->
    <form method="POST" action="employees.php" id="deleteForm">
        <input type="hidden" name="action" value="delete_employee">
        <input type="hidden" name="delete_id" id="delete_id">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // --- Sidebar state ---
        const sidebar     = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const overlay     = document.getElementById('sidebarOverlay');
        const toggleBtn   = document.getElementById('sidebarToggleBtn');
        const SIDEBAR_W   = 270;   // matches --sidebar-width in CSS
        const COLLAPSED_W = 72;    // matches --sidebar-collapsed in CSS

        // Reposition the toggle button based on sidebar state
        function updateToggleBtn(collapsed) {
            if (window.innerWidth > 768) {
                toggleBtn.style.left      = (collapsed ? COLLAPSED_W : SIDEBAR_W) - 14 + 'px';
                toggleBtn.style.transform = collapsed ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        }

        // Restore sidebar state from last session
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) { sidebar.classList.add('collapsed'); mainContent.classList.add('collapsed'); }
        updateToggleBtn(isCollapsed);

        // Desktop: collapse/expand
        function toggleSidebar() {
            const collapsed = sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', collapsed);
            updateToggleBtn(collapsed);
        }

        // Mobile: open/close with overlay
        function openSidebar()  { sidebar.classList.add('open');    overlay.classList.add('active');    document.body.style.overflow = 'hidden'; }
        function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('active'); document.body.style.overflow = '';       }

        // Resize handler:
        // _lastWidth guards against mobile browser URL-bar show/hide triggering
        // a false resize event (only height changes, width stays the same).
        let _lastWidth = window.innerWidth;
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                const w = window.innerWidth;
                if (w === _lastWidth) return; // height-only change — ignore
                _lastWidth = w;

                if (w <= 768) {
                    toggleBtn.style.display = 'none';
                    // Sidebar starts closed on mobile, regardless of desktop state
                    // Removed auto-open logic to prevent issues with zoom
                } else {
                    toggleBtn.style.display = 'flex';
                    const wasOpen = sidebar.classList.contains('open');
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                    if (wasOpen) {
                        // Coming back from mobile open → restore expanded
                        sidebar.classList.remove('collapsed');
                        mainContent.classList.remove('collapsed');
                        localStorage.setItem('sidebarCollapsed', 'false');
                    } else {
                        // Restore saved desktop state
                        const saved = localStorage.getItem('sidebarCollapsed') === 'true';
                        if (saved) { sidebar.classList.add('collapsed');    mainContent.classList.add('collapsed');    }
                        else       { sidebar.classList.remove('collapsed'); mainContent.classList.remove('collapsed'); }
                    }
                    updateToggleBtn(sidebar.classList.contains('collapsed'));
                }
            }, 100); // Debounce resize events by 100ms to handle rapid zoom changes
        });

        // Hide toggle button on initial mobile load
        if (window.innerWidth <= 768) toggleBtn.style.display = 'none';

        // Auto-dismiss floating alerts after 4s
        setTimeout(() => {
            document.querySelectorAll('.alert-floating').forEach(a => {
                a.classList.remove('show');
                setTimeout(() => a.remove(), 300);
            });
        }, 4000);

        // Populate and open edit modal on row click
        function openEditModal(id, name, email, rol) {
            document.getElementById('edit_id').value          = id;
            document.getElementById('editModalTitle').textContent = name;
            document.getElementById('edit_name').value        = name;
            document.getElementById('edit_email').value       = email;
            const select = document.getElementById('edit_rol');
            for (let opt of select.options) opt.selected = opt.value === rol;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        // Confirm then submit hidden delete form
        function deleteEmployee() {
            const id   = document.getElementById('edit_id').value;
            const name = document.getElementById('edit_name').value;
            if (confirm(`Are you sure you want to delete employee "${name}"? This action cannot be undone.`)) {
                document.getElementById('delete_id').value = id;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>