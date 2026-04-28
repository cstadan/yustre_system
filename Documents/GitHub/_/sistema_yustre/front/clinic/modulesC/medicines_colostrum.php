<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) { header('Location: ../../../login/login.php'); exit(); }
if (!in_array($_SESSION['user_rol'], ['admin', 'clinic'])) { header('Location: ../../../login/login.php?error=invalid_role'); exit(); }
require_once '../controllersC/medicines_controller.php';
$user_name = $_SESSION['user_name'] ?? 'User';
$user_rol  = $_SESSION['user_rol']  ?? 'clinic';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicines &amp; Colostrum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/style_dashboard_clinic.css">
    <link rel="stylesheet" href="../../../assets/css/style_medicines_colostrum_clinic.css">
</head>
<body class="clinic-module">
<script src="../../../assets/js/theme-toggle.js"></script>
<?php if ($success_message): ?><div class="alert alert-success alert-floating alert-dismissible fade show"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> <?php echo $success_message; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if ($error_message): ?><div class="alert alert-danger alert-floating alert-dismissible fade show"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> <?php echo $error_message; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    <button class="sidebar-toggle-btn" id="sidebarToggleBtn" onclick="toggleSidebar()" title="Toggle sidebar">&#x2039;</button>
    <div class="topbar">
        <button class="topbar-hamburger" onclick="openSidebar()" aria-label="Open menu"><span></span><span></span><span></span></button>
        <span class="topbar-title">Yustre &mdash; Clinic</span>
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
            <li class="nav-item"><a href="cow_registry.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M6 8c0 0-1 12 6 12s6-12 6-12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M6 8c1-3 11-3 12 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M6 8L4 5M18 8l2-3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9 16h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Cow Registry</span></a></li>
            <li class="nav-item"><a href="medicines_colostrum.php" class="nav-link active"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><rect x="1" y="6" width="22" height="12" rx="6" stroke="currentColor" stroke-width="1.8"/><line x1="12" y1="6" x2="12" y2="18" stroke="currentColor" stroke-width="1.8"/></svg></span><span class="nav-text">Med &amp; Colostrum</span></a></li>
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
        <h2 class="welcome-title"><svg style="vertical-align:middle;margin-right:8px;width:26px;height:26px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="26" height="26" fill="none"><rect x="1" y="8" width="22" height="8" rx="4" stroke="currentColor" stroke-width="1.8"/><line x1="12" y1="8" x2="12" y2="16" stroke="currentColor" stroke-width="1.8"/></svg> Medicines &amp; Colostrum</h2>
        <p class="welcome-subtitle">Inventory and administration control</p>
    </div>

    <!-- MEDICINES -->
    <div class="section-container">
        <div class="section-title"><svg style="vertical-align:middle;margin-right:6px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="1" y="8" width="22" height="8" rx="4" stroke="currentColor" stroke-width="1.8"/><line x1="12" y1="8" x2="12" y2="16" stroke="currentColor" stroke-width="1.8"/></svg> Medicines</div>
        <form method="GET" action="medicines_colostrum.php">
            <div class="row g-3">
                <div class="col-md-5"><label class="filter-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Search by Varcode or Name</label><input type="text" name="med_search" class="search-input" placeholder="Type to search..." value="<?php echo htmlspecialchars($med_search); ?>"></div>
                <div class="col-md-3"><label class="filter-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M9 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V9l-6-6z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9 3v6h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Filter by Unit</label><select name="med_unit" class="filter-select"><option value="">All units</option><option value="ml" <?php echo $med_filter_unit=='ml'?'selected':''; ?>>ml</option><option value="g" <?php echo $med_filter_unit=='g'?'selected':''; ?>>g</option><option value="tablets" <?php echo $med_filter_unit=='tablets'?'selected':''; ?>>Tablets</option><option value="doses" <?php echo $med_filter_unit=='doses'?'selected':''; ?>>Doses</option></select></div>
                <input type="hidden" name="col_search" value="<?php echo htmlspecialchars($col_search); ?>">
                <input type="hidden" name="col_calf" value="<?php echo htmlspecialchars($col_filter_calf); ?>">
                <div class="col-md-2 d-flex align-items-end"><button type="submit" class="btn-search">Search</button></div>
            </div>
            <?php if (!empty($med_search)||!empty($med_filter_unit)): ?><a href="medicines_colostrum.php?col_search=<?php echo urlencode($col_search); ?>&col_calf=<?php echo urlencode($col_filter_calf); ?>" class="btn-clear">Clear</a><?php endif; ?>
        </form>
        <div class="table-header">
            <div class="table-title">Medicines List <span class="total-badge"><?php echo count($medicines); ?> records</span></div>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addMedModal"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Add Medicine</button>
        </div>
        <?php if (count($medicines) > 0): ?>
        <div class="table-scroll"><table class="data-table">
            <thead><tr><th>Varcode</th><th>Name</th><th>Stock</th><th>Unit</th><th>Last Price</th><th>Date</th></tr></thead>
            <tbody><?php foreach ($medicines as $med): ?>
            <tr onclick="openEditMedModal('<?php echo $med['id']; ?>','<?php echo htmlspecialchars(addslashes($med['varcode'])); ?>','<?php echo htmlspecialchars(addslashes($med['name'])); ?>','<?php echo $med['stock']; ?>','<?php echo htmlspecialchars($med['unit']); ?>')">
                <td><strong><?php echo htmlspecialchars($med['varcode']); ?></strong></td>
                <td><?php echo htmlspecialchars($med['name']); ?></td>
                <td><?php $s=$med['stock'];$c=$s>10?'stock-ok':($s>0?'stock-low':'stock-empty'); ?><span class="<?php echo $c; ?>"><?php echo $s; ?></span></td>
                <td><span class="unit-badge"><?php echo htmlspecialchars($med['unit']); ?></span></td>
                <td><?php echo $med['last_price']!==null?'$'.number_format($med['last_price'],2):'—'; ?></td>
                <td><?php echo date('m/d/Y', strtotime($med['date_register'])); ?></td>
            </tr><?php endforeach; ?>
            </tbody>
        </table></div>
        <?php else: ?><div class="no-data"><div style="display:flex;justify-content:center;margin-bottom:10px;opacity:.5;color:var(--text-muted)"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="38" height="38" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8 11h6M11 8v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></div>No medicines found</div><?php endif; ?>
    </div>

    <!-- COLOSTRUM -->
    <div class="section-container">
        <div class="section-title"><svg style="vertical-align:middle;margin-right:6px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="2" y="7" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2M12 12v4M10 14h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Colostrum</div>
        <form method="GET" action="medicines_colostrum.php">
            <div class="row g-3">
                <div class="col-md-5"><label class="filter-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Search by Varcode or Calf ID</label><input type="text" name="col_search" class="search-input" placeholder="Type to search..." value="<?php echo htmlspecialchars($col_search); ?>"></div>
                <div class="col-md-3"><label class="filter-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><ellipse cx="12" cy="14" rx="6" ry="4" stroke="currentColor" stroke-width="1.8"/><path d="M9 10V7M15 10V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Filter by Calf</label><select name="col_calf" class="filter-select"><option value="">All calves</option><?php foreach ($col_calves as $calf): ?><option value="<?php echo htmlspecialchars($calf); ?>" <?php echo $col_filter_calf==$calf?'selected':''; ?>><?php echo htmlspecialchars($calf); ?></option><?php endforeach; ?></select></div>
                <input type="hidden" name="med_search" value="<?php echo htmlspecialchars($med_search); ?>">
                <input type="hidden" name="med_unit" value="<?php echo htmlspecialchars($med_filter_unit); ?>">
                <div class="col-md-2 d-flex align-items-end"><button type="submit" class="btn-search">Search</button></div>
            </div>
            <?php if (!empty($col_search)||!empty($col_filter_calf)): ?><a href="medicines_colostrum.php?med_search=<?php echo urlencode($med_search); ?>&med_unit=<?php echo urlencode($med_filter_unit); ?>" class="btn-clear">Clear</a><?php endif; ?>
        </form>
        <div class="table-header">
            <div class="table-title">Colostrum Records <span class="total-badge"><?php echo count($colostrums); ?> records</span></div>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addColModal"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Add Record</button>
        </div>
        <?php if (count($colostrums) > 0): ?>
        <div class="table-scroll"><table class="data-table">
            <thead><tr><th>Varcode</th><th>Quantity (units)</th><th>Calf ID</th><th>Date</th></tr></thead>
            <tbody><?php foreach ($colostrums as $col): ?>
            <tr onclick="openEditColModal('<?php echo $col['id']; ?>','<?php echo htmlspecialchars(addslashes($col['varcode'])); ?>','<?php echo $col['quantity']; ?>','<?php echo htmlspecialchars($col['calf_id']); ?>')">
                <td><strong><?php echo htmlspecialchars($col['varcode']); ?></strong></td>
                <td><?php echo $col['quantity']; ?></td>
                <td><?php echo htmlspecialchars($col['calf_id']); ?></td>
                <td><?php echo date('m/d/Y', strtotime($col['date_register'])); ?></td>
            </tr><?php endforeach; ?>
            </tbody>
        </table></div>
        <?php else: ?><div class="no-data"><div style="display:flex;justify-content:center;margin-bottom:10px;opacity:.5;color:var(--text-muted)"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="38" height="38" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8 11h6M11 8v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></div>No colostrum records found</div><?php endif; ?>
    </div>
</div>

<!-- Add Medicine Modal -->
<div class="modal fade" id="addMedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Add New Medicine</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form method="POST" action="medicines_colostrum.php"><input type="hidden" name="action" value="add_medicine"><input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="modal-body p-4"><div class="row g-3">
                <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M3 7V5a2 2 0 012-2h2M17 3h2a2 2 0 012 2v2M21 17v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2M7 12h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Varcode <span class="text-danger">*</span></label><input type="text" name="varcode" class="modal-input" placeholder="Scan or type" required></div>
                <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="1" y="8" width="22" height="8" rx="4" stroke="currentColor" stroke-width="1.8"/><line x1="12" y1="8" x2="12" y2="16" stroke="currentColor" stroke-width="1.8"/></svg> Name <span class="text-danger">*</span></label><input type="text" name="name" class="modal-input" placeholder="Medicine name" required></div>
                <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="2" y="7" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2M12 12v4M10 14h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Initial Stock <span class="text-danger">*</span></label><input type="number" name="stock" class="modal-input" min="0" placeholder="0" required></div>
                <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M9 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V9l-6-6z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9 3v6h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Unit <span class="text-danger">*</span></label><select name="unit" class="modal-input" required><option value="">Select unit...</option><option value="ml">ml</option><option value="g">g</option><option value="tablets">Tablets</option><option value="doses">Doses</option></select></div>
            </div></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-save">Save Medicine</button></div>
        </form>
    </div></div>
</div>

<!-- Edit Medicine Modal -->
<div class="modal fade" id="editMedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Edit Medicine &mdash; <span id="editMedTitle"></span></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body p-4">
            <form method="POST" action="medicines_colostrum.php" id="editMedForm"><input type="hidden" name="action" value="edit_medicine"><input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>"><input type="hidden" name="edit_id" id="edit_med_id">
                <div class="row g-3">
                    <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M3 7V5a2 2 0 012-2h2M17 3h2a2 2 0 012 2v2M21 17v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2M7 12h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Varcode <span class="text-danger">*</span></label><input type="text" name="varcode" id="edit_med_varcode" class="modal-input" required></div>
                    <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="1" y="8" width="22" height="8" rx="4" stroke="currentColor" stroke-width="1.8"/><line x1="12" y1="8" x2="12" y2="16" stroke="currentColor" stroke-width="1.8"/></svg> Name <span class="text-danger">*</span></label><input type="text" name="name" id="edit_med_name" class="modal-input" required></div>
                    <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="2" y="7" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2M12 12v4M10 14h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Current Stock</label><input type="text" id="edit_med_stock_display" class="modal-input" disabled style="opacity:.5"></div>
                    <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M9 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V9l-6-6z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9 3v6h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Unit <span class="text-danger">*</span></label><select name="unit" id="edit_med_unit" class="modal-input" required><option value="ml">ml</option><option value="g">g</option><option value="tablets">Tablets</option><option value="doses">Doses</option></select></div>
                </div>
            </form>
            <hr class="divider">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <strong style="font-size:14px;"><svg style="vertical-align:middle;margin-right:5px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><line x1="3" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="1.8"/><path d="M16 10a4 4 0 01-8 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Purchase History</strong>
                <button class="btn-add" style="padding:6px 14px;font-size:13px;" onclick="showPurchaseForm()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Add Purchase</button>
            </div>
            <form method="POST" action="medicines_colostrum.php" id="purchaseForm" style="display:none;"><input type="hidden" name="action" value="add_purchase"><input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>"><input type="hidden" name="medicine_id" id="purchase_med_id">
                <div class="row g-2 mb-3">
                    <div class="col-md-4"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 17h6M17 14v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Quantity <span class="text-danger">*</span></label><input type="number" name="quantity" class="modal-input" min="1" placeholder="e.g. 50" required></div>
                    <div class="col-md-4"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M9 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V9l-6-6z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9 3v6h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Price <span class="text-danger">*</span></label><input type="number" name="price" class="modal-input" min="0" step="0.01" placeholder="e.g. 120.50" required></div>
                    <div class="col-md-4"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Date <span class="text-danger">*</span></label><input type="date" name="date" class="modal-input" required></div>
                </div>
                <div class="d-flex gap-2 mb-3"><button type="submit" class="btn-save" style="padding:7px 18px;font-size:13px;">Save Purchase</button><button type="button" class="btn btn-secondary btn-sm" onclick="hidePurchaseForm()">Cancel</button></div>
            </form>
            <div id="purchaseHistoryContainer"><div class="no-history">Loading history...</div></div>
        </div>
        <div class="modal-footer d-flex justify-content-between">
            <button type="button" class="btn btn-danger" onclick="deleteMedicine()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Delete</button>
            <div><button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button><button type="submit" form="editMedForm" class="btn-save">Save Changes</button></div>
        </div>
    </div></div>
</div>

<!-- Add Colostrum Modal -->
<div class="modal fade" id="addColModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Add Colostrum Record</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form method="POST" action="medicines_colostrum.php"><input type="hidden" name="action" value="add_colostrum"><input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="modal-body p-4"><div class="row g-3">
                <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M3 7V5a2 2 0 012-2h2M17 3h2a2 2 0 012 2v2M21 17v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2M7 12h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Varcode <span class="text-danger">*</span></label><input type="text" name="varcode" class="modal-input" placeholder="Scan or type" required></div>
                <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 17h6M17 14v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Quantity <span class="text-danger">*</span></label><input type="number" name="quantity" class="modal-input" min="1" placeholder="e.g. 3" required></div>
                <div class="col-12"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><ellipse cx="12" cy="14" rx="6" ry="4" stroke="currentColor" stroke-width="1.8"/><path d="M9 10V7M15 10V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Calf <span class="text-danger">*</span></label><select name="calf_id" class="modal-input" required><option value="">Select calf...</option><?php foreach ($calves_list as $calf_id): ?><option value="<?php echo htmlspecialchars($calf_id); ?>"><?php echo htmlspecialchars($calf_id); ?></option><?php endforeach; ?></select></div>
            </div></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-save">Save Record</button></div>
        </form>
    </div></div>
</div>

<!-- Edit Colostrum Modal -->
<div class="modal fade" id="editColModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Edit Colostrum Record</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form method="POST" action="medicines_colostrum.php"><input type="hidden" name="action" value="edit_colostrum"><input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>"><input type="hidden" name="edit_id" id="edit_col_id">
            <div class="modal-body p-4"><div class="row g-3">
                <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M3 7V5a2 2 0 012-2h2M17 3h2a2 2 0 012 2v2M21 17v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2M7 12h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Varcode <span class="text-danger">*</span></label><input type="text" name="varcode" id="edit_col_varcode" class="modal-input" required></div>
                <div class="col-md-6"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 17h6M17 14v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Quantity <span class="text-danger">*</span></label><input type="number" name="quantity" id="edit_col_quantity" class="modal-input" min="1" required></div>
                <div class="col-12"><label class="modal-label"><svg class="label-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><ellipse cx="12" cy="14" rx="6" ry="4" stroke="currentColor" stroke-width="1.8"/><path d="M9 10V7M15 10V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Calf <span class="text-danger">*</span></label><select name="calf_id" id="edit_col_calf" class="modal-input" required><option value="">Select calf...</option><?php foreach ($calves_list as $calf_id): ?><option value="<?php echo htmlspecialchars($calf_id); ?>"><?php echo htmlspecialchars($calf_id); ?></option><?php endforeach; ?></select></div>
            </div></div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger" onclick="deleteColostrum()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Delete</button>
                <div><button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-save">Save Changes</button></div>
            </div>
        </form>
    </div></div>
</div>

<form method="POST" action="medicines_colostrum.php" id="deleteMedForm"><input type="hidden" name="action" value="delete_medicine"><input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>"><input type="hidden" name="delete_id" id="delete_med_id"></form>
<form method="POST" action="medicines_colostrum.php" id="deleteColForm"><input type="hidden" name="action" value="delete_colostrum"><input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>"><input type="hidden" name="delete_id" id="delete_col_id"></form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    setTimeout(()=>{document.querySelectorAll('.alert-floating').forEach(a=>{a.classList.remove('show');setTimeout(()=>a.remove(),300);});},4000);
    const purchaseHistory = <?php $all_history=$pdo_med->query("SELECT ph.*,m.name as med_name FROM purchase_history ph JOIN medicines m ON ph.medicine_id=m.id ORDER BY ph.date DESC")->fetchAll(PDO::FETCH_ASSOC);echo json_encode($all_history); ?>;
    function openEditMedModal(id,varcode,name,stock,unit){document.getElementById('edit_med_id').value=id;document.getElementById('editMedTitle').textContent=name;document.getElementById('edit_med_varcode').value=varcode;document.getElementById('edit_med_name').value=name;document.getElementById('edit_med_stock_display').value=stock+' units';document.getElementById('purchase_med_id').value=id;const s=document.getElementById('edit_med_unit');for(let o of s.options)o.selected=o.value===unit;loadPurchaseHistory(id);hidePurchaseForm();new bootstrap.Modal(document.getElementById('editMedModal')).show();}
    function loadPurchaseHistory(medId){const c=document.getElementById('purchaseHistoryContainer');const r=purchaseHistory.filter(r=>r.medicine_id==medId);if(!r.length){c.innerHTML='<div class="no-history">No purchases recorded yet.</div>';return;}let h='<table class="history-table"><thead><tr><th>Date</th><th>Quantity</th><th>Price</th></tr></thead><tbody>';r.forEach(r=>{h+=`<tr><td>${r.date}</td><td>${r.quantity}</td><td>$${parseFloat(r.price).toFixed(2)}</td></tr>`;});h+='</tbody></table>';c.innerHTML=h;}
    function showPurchaseForm(){document.getElementById('purchaseForm').style.display='block';}
    function hidePurchaseForm(){document.getElementById('purchaseForm').style.display='none';}
    function deleteMedicine(){const id=document.getElementById('edit_med_id').value;const name=document.getElementById('edit_med_name').value;if(confirm(`Delete medicine "${name}"? This cannot be undone.`)){document.getElementById('delete_med_id').value=id;document.getElementById('deleteMedForm').submit();}}
    function openEditColModal(id,varcode,quantity,calf_id){document.getElementById('edit_col_id').value=id;document.getElementById('edit_col_varcode').value=varcode;document.getElementById('edit_col_quantity').value=quantity;const s=document.getElementById('edit_col_calf');for(let o of s.options)o.selected=o.value===calf_id;new bootstrap.Modal(document.getElementById('editColModal')).show();}
    function deleteColostrum(){const id=document.getElementById('edit_col_id').value;if(confirm('Delete this colostrum record? This cannot be undone.')){document.getElementById('delete_col_id').value=id;document.getElementById('deleteColForm').submit();}}

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