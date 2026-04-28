<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) { header('Location: ../../../login/login.php'); exit(); }
if (!in_array($_SESSION['user_rol'], ['admin', 'clinic'])) { header('Location: ../../../login/login.php?error=invalid_role'); exit(); }
require_once '../controllersC/cow_controller.php';
$user_name = $_SESSION['user_name'] ?? 'User';
$user_rol  = $_SESSION['user_rol']  ?? 'clinic';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cow Registry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/style_dashboard_clinic.css">
    <link rel="stylesheet" href="../../../assets/css/style_cow_regis_clinic.css">
</head>
<body class="clinic-module">
<script src="../../../assets/js/theme-toggle.js"></script>

<?php if ($success_message): ?>
<div class="alert alert-success alert-floating alert-dismissible fade show"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> <?php echo $success_message; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($error_message): ?>
<div class="alert alert-danger alert-floating alert-dismissible fade show"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> <?php echo $error_message; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    <button class="sidebar-toggle-btn" id="sidebarToggleBtn" onclick="toggleSidebar()" title="Toggle sidebar">&#x2039;</button>
    <div class="topbar">
        <button class="topbar-hamburger" onclick="openSidebar()" aria-label="Open menu"><span></span><span></span><span></span></button>
        <span class="topbar-title">Dairy Farm CS &mdash; Clinic</span>
    </div>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header" >
            <div class="sidebar-logo"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M3 10.5L12 3L21 10.5V20C21 20.552 20.552 21 20 21H15V15H9V21H4C3.448 21 3 20.552 3 20V10.5Z" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            <div class="sidebar-brand">
                <span class="sidebar-brand-name">Sistema Yustre</span>
                <span class="sidebar-brand-sub">Clinic Module</span>
            </div>
            <button class="sidebar-close-btn" onclick="closeSidebar()" aria-label="Close menu">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none">
                    <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        <ul class="nav-menu">
            <li class="nav-item"><a href="../../../dashboards/dashboard_clinic.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><rect x="3" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="3" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/></svg></span><span class="nav-text">Dashboard</span></a></li>
            <li class="nav-item"><a href="cow_registry.php" class="nav-link active"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M6 8c0 0-1 12 6 12s6-12 6-12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M6 8c1-3 11-3 12 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M6 8L4 5M18 8l2-3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9 16h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Cow Registry</span></a></li>
            <li class="nav-item"><a href="medicines_colostrum.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><rect x="1" y="6" width="22" height="12" rx="6" stroke="currentColor" stroke-width="1.8"/><line x1="12" y1="6" x2="12" y2="18" stroke="currentColor" stroke-width="1.8"/></svg></span><span class="nav-text">Med &amp; Colostrum</span></a></li>
            <li class="nav-item"><a href="calves.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><ellipse cx="12" cy="14" rx="6" ry="5" stroke="currentColor" stroke-width="1.8"/><path d="M8 14c0-4 8-4 8 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9 9V6M15 9V6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="9" cy="5" r="1" fill="currentColor"/><circle cx="15" cy="5" r="1" fill="currentColor"/></svg></span><span class="nav-text">Calves</span></a></li>
            <?php if ($_SESSION["user_rol"] === "admin"): ?>
            <li class="nav-item bottom"><a href="../../../dashboards/dashboard_admin.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M12 3L4 7V12C4 16.418 7.582 20.398 12 21C16.418 20.398 20 16.418 20 12V7L12 3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span><span class="nav-text">Admin Panel</span></a></li>
            <?php endif; ?>
            <li class="nav-item <?php echo $_SESSION['user_rol'] !== 'admin' ? 'bottom' : ''; ?>">
                <a href="../../../logout.php" class="nav-link logout"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M14 3H6C5.448 3 5 3.448 5 4V20C5 20.552 5.448 21 6 21H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 8L21 12L16 16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12H9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Logout</span></a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar"><?php echo strtoupper(substr($user_name, 0, 1)); ?></div>
                <div class="sidebar-user-info">
                    <span class="sidebar-user-name"><?php echo htmlspecialchars($user_name); ?></span>
                    <span class="sidebar-user-role"><?php echo ucfirst($user_rol); ?></span>
                </div>
            </div>
            <div class="sidebar-theme-toggle" onclick="toggleTheme()" title="Toggle theme">
                <div class="sidebar-theme-toggle-icon">
                    <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><circle cx="12" cy="12" r="4" stroke="#f59e0b" stroke-width="2"/><path d="M12 2V4M12 20V22M4.22 4.22L5.64 5.64M18.36 18.36L19.78 19.78M2 12H4M20 12H22M4.22 19.78L5.64 18.36M18.36 5.64L19.78 4.22" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"/></svg>
                    <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" stroke="#a78bfa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <span class="sidebar-theme-toggle-label"></span>
            </div>
        </div>
    </nav>
<div class="main-content" id="mainContent">
    <div class="welcome-section">
        <h2 class="welcome-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="26" height="26" fill="none" style="vertical-align:middle;margin-right:8px"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M6 8c0 0-1 12 6 12s6-12 6-12" stroke="var(--title-gold)" stroke-width="1.8" stroke-linecap="round"/><path d="M6 8c1-3 11-3 12 0" stroke="var(--title-gold)" stroke-width="1.8" stroke-linecap="round"/><path d="M6 8L4 5M18 8l2-3" stroke="var(--title-gold)" stroke-width="1.8" stroke-linecap="round"/><path d="M9 16h6" stroke="var(--title-gold)" stroke-width="1.8" stroke-linecap="round"/></svg></svg> Cow Registry</h2>
        <p class="welcome-subtitle">Cattle management and control</p>
    </div>
    <div class="filters-section">
        <form method="GET" action="cow_registry.php">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="filter-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Search by ID, Corral or Age</label>
                    <input type="text" name="search" class="search-input" placeholder="Type to search..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-2">
                    <label class="filter-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M3 9h18M9 9v12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Filter by Corral</label>
                    <select name="corral" class="filter-select">
                        <option value="">All corrals</option>
                        <?php foreach ($corrals as $corral): ?>
                        <option value="<?php echo htmlspecialchars($corral); ?>" <?php echo $filter_corral==$corral?'selected':''; ?>>Corral <?php echo htmlspecialchars($corral); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="filter-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M12 8v4l3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Filter by Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All statuses</option>
                        <option value="Active" <?php echo $filter_status=='Active'?'selected':''; ?>>Active</option>
                        <option value="Sick" <?php echo $filter_status=='Sick'?'selected':''; ?>>Sick</option>
                        <option value="Pregnant" <?php echo $filter_status=='Pregnant'?'selected':''; ?>>Pregnant</option>
                        <option value="Sold" <?php echo $filter_status=='Sold'?'selected':''; ?>>Sold</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="filter-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M4 6h16M4 12h10M4 18h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Order by</label>
                    <select name="order" class="filter-select">
                        <option value="id_desc" <?php echo $order_by=='id_desc'?'selected':''; ?>>ID (A-Z)</option>
                        <option value="id_asc" <?php echo $order_by=='id_asc'?'selected':''; ?>>ID (Z-A)</option>
                        <option value="age_desc" <?php echo $order_by=='age_desc'?'selected':''; ?>>Age (Oldest first)</option>
                        <option value="age_asc" <?php echo $order_by=='age_asc'?'selected':''; ?>>Age (Youngest first)</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn-search">Search</button>
                </div>
            </div>
            <?php if (!empty($search)||!empty($filter_corral)||!empty($filter_status)||$order_by!='id_desc'): ?>
            <div class="mt-3"><a href="cow_registry.php" class="btn-clear">Clear filters</a></div>
            <?php endif; ?>
        </form>
    </div>
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Cow List <span class="total-badge"><?php echo count($cows); ?> records</span></h3>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addCowModal"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Add Cow</button>
        </div>
        <?php if (count($cows) > 0): ?>
        <div class="table-scroll">
            <table class="data-table">
                <thead><tr><th>ID</th><th>Corral</th><th>Status</th><th>Age</th><th>Comments</th><th>Date Registered</th></tr></thead>
                <tbody>
                    <?php foreach ($cows as $cow): ?>
                    <tr style="cursor:pointer;" onclick="openEditModal('<?php echo htmlspecialchars($cow['id']); ?>','<?php echo htmlspecialchars($cow['corral']); ?>','<?php echo htmlspecialchars($cow['status']); ?>','<?php echo htmlspecialchars($cow['age']); ?>','<?php echo htmlspecialchars(addslashes($cow['comments'])); ?>')">
                        <td><strong><?php echo htmlspecialchars($cow['id']); ?></strong></td>
                        <td>Corral <?php echo htmlspecialchars($cow['corral']); ?></td>
                        <td><?php $s=strtolower($cow['status']);$c='status-active';if($s=='sick')$c='status-sick';elseif($s=='pregnant')$c='status-pregnant';elseif($s=='sold')$c='status-sold'; ?><span class="status-badge <?php echo $c; ?>"><?php echo htmlspecialchars($cow['status']); ?></span></td>
                        <td><?php echo htmlspecialchars($cow['age']); ?> yrs</td>
                        <td><?php echo htmlspecialchars($cow['comments']); ?></td>
                        <td><?php echo date('m/d/Y', strtotime($cow['date_register'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="no-data"><div style="display:flex;justify-content:center;margin-bottom:10px;opacity:.5;color:var(--text-muted)"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="38" height="38" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8 11h6M11 8v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></div>No cows found matching the search criteria</div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Cow Modal -->
<div class="modal fade" id="addCowModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Add New Cow</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST" action="cow_registry.php">
                <input type="hidden" name="action" value="add_cow">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M3 9h18M9 9v12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Corral <span class="text-danger">*</span></label><input type="text" name="corral" class="modal-input" placeholder="e.g. A1" required></div>
                        <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M12 8v4l3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Status <span class="text-danger">*</span></label><select name="status" class="modal-input" required><option value="">Select status...</option><option value="Active">Active</option><option value="Sick">Sick</option><option value="Pregnant">Pregnant</option><option value="Sold">Sold</option></select></div>
                        <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Age (years) <span class="text-danger">*</span></label><input type="number" name="age" class="modal-input" placeholder="e.g. 3" min="0" max="30" required></div>
                        <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="2" y="5" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 10h8M8 14h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> ID</label><input type="text" class="modal-input" value="Auto-generated" disabled style="opacity:.5"></div>
                        <div class="col-12"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M8 9h8M8 13h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/></svg> Comments</label><textarea name="comments" class="modal-input" rows="3" placeholder="Optional notes..."></textarea></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-save">Save Cow</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Cow Modal -->
<div class="modal fade" id="editCowModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Edit Cow &mdash; <span id="editModalTitle"></span></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST" action="cow_registry.php">
                <input type="hidden" name="action" value="edit_cow">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M3 9h18M9 9v12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Corral <span class="text-danger">*</span></label><input type="text" name="corral" id="edit_corral" class="modal-input" required></div>
                        <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M12 8v4l3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Status <span class="text-danger">*</span></label><select name="status" id="edit_status" class="modal-input" required><option value="Active">Active</option><option value="Sick">Sick</option><option value="Pregnant">Pregnant</option><option value="Sold">Sold</option></select></div>
                        <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Age (years) <span class="text-danger">*</span></label><input type="number" name="age" id="edit_age" class="modal-input" min="0" max="30" required></div>
                        <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="2" y="5" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 10h8M8 14h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> ID</label><input type="text" id="edit_id_display" class="modal-input" disabled style="opacity:.5"></div>
                        <div class="col-12"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M8 9h8M8 13h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/></svg> Comments</label><textarea name="comments" id="edit_comments" class="modal-input" rows="3"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-danger" onclick="deleteCow()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Delete</button>
                    <div><button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-save">Save Changes</button></div>
                </div>
            </form>
        </div>
    </div>
</div>
<form method="POST" action="cow_registry.php" id="deleteForm"><input type="hidden" name="action" value="delete_cow"><input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>"><input type="hidden" name="delete_id" id="delete_id"></form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    setTimeout(()=>{document.querySelectorAll('.alert-floating').forEach(a=>{a.classList.remove('show');setTimeout(()=>a.remove(),300);});},4000);
    function openEditModal(id,corral,status,age,comments){document.getElementById('edit_id').value=id;document.getElementById('edit_id_display').value=id;document.getElementById('editModalTitle').textContent=id;document.getElementById('edit_corral').value=corral;document.getElementById('edit_age').value=age;document.getElementById('edit_comments').value=comments;const s=document.getElementById('edit_status');for(let o of s.options)o.selected=o.value===status;new bootstrap.Modal(document.getElementById('editCowModal')).show();}
    function deleteCow(){const id=document.getElementById('edit_id').value;if(confirm(`Delete cow ${id}? This cannot be undone.`)){document.getElementById('delete_id').value=id;document.getElementById('deleteForm').submit();}}

        const sidebar     = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const overlay     = document.getElementById('sidebarOverlay');
        const toggleBtn   = document.getElementById('sidebarToggleBtn');
        const SIDEBAR_W=270, COLLAPSED_W=72;

        function updateToggleBtn(c) {
            if (window.innerWidth > 768) {
                toggleBtn.style.left = (c ? COLLAPSED_W : SIDEBAR_W) - 14 + 'px';
                toggleBtn.style.transform = c ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        }
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) { sidebar.classList.add('collapsed'); mainContent.classList.add('collapsed'); }
        updateToggleBtn(isCollapsed);

        function toggleSidebar() {
            const c = sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', c);
            updateToggleBtn(c);
        }
        function openSidebar() { sidebar.classList.add('open'); overlay.classList.add('active'); document.body.style.overflow='hidden'; }
        function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('active'); document.body.style.overflow=''; }
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                if (window.innerWidth <= 768) {
                    toggleBtn.style.display = 'none';
                    // Sidebar starts closed on mobile, regardless of desktop state
                    // Removed auto-open logic to prevent issues with zoom
                } else {
                    toggleBtn.style.display = 'flex';
                    const wasOpen = sidebar.classList.contains('open');
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                    // Restore desktop state from localStorage — don't force open
                    const savedCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                    if (savedCollapsed) {
                        sidebar.classList.add('collapsed');
                        mainContent.classList.add('collapsed');
                    } else {
                        sidebar.classList.remove('collapsed');
                        mainContent.classList.remove('collapsed');
                    }
                    updateToggleBtn(sidebar.classList.contains('collapsed'));
                }
            }, 100); // Debounce resize events by 100ms to handle rapid zoom changes
        });
        if (window.innerWidth <= 768) toggleBtn.style.display = 'none';

</script>
</body>
</html>