<?php require_once '../controllersS/assets_controller.php'; ?>
<?php $user_name = $_SESSION['user_name'] ?? 'User'; $user_rol = $_SESSION['user_rol'] ?? 'shop'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assets — Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/style_dashboard_shop.css">
    <link rel="stylesheet" href="../../../assets/css/style_assets_shop.css">
</head>

<body class="shop-module">
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    <button class="sidebar-toggle-btn" id="sidebarToggleBtn" onclick="toggleSidebar()" title="Toggle sidebar">&#x2039;</button>
    <div class="topbar"><button class="topbar-hamburger" onclick="openSidebar()" aria-label="Open menu"><span></span><span></span><span></span></button><span class="topbar-title">Yustre &mdash; Shop</span></div>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M3 10.5L12 3L21 10.5V20C21 20.552 20.552 21 20 21H15V15H9V21H4C3.448 21 3 20.552 3 20V10.5Z" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            <div class="sidebar-brand"><span class="sidebar-brand-name">Sistema Yustre</span><span class="sidebar-brand-sub">Shop Module</span></div>
            <button class="sidebar-close-btn" onclick="closeSidebar()" aria-label="Close menu"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
        </div>
        <ul class="nav-menu">
            <li class="nav-item"><a href="../../../dashboards/dashboard_shop.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><rect x="3" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="3" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/></svg></span><span class="nav-text">Dashboard</span></a></li>
            <li class="nav-item"><a href="work_orders.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 7H16M8 11H16M8 15H12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Work Orders</span></a></li>
            <li class="nav-item"><a href="assets.php" class="nav-link active"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><rect x="2" y="7" width="10" height="14" rx="1.5" stroke="currentColor" stroke-width="1.8"/><path d="M12 9h6a2 2 0 012 2v8a2 2 0 01-2 2h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M5 4V2M9 4V2M7 7V4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Assets</span></a></li>
            <li class="nav-item"><a href="parts_inventory.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><path d="M20 7H4C2.9 7 2 7.9 2 9V19C2 20.1 2.9 21 4 21H20C21.1 21 22 20.1 22 19V9C22 7.9 21.1 7 20 7Z" stroke="currentColor" stroke-width="1.8"/><path d="M16 7V5C16 3.9 15.1 3 14 3H10C8.9 3 8 3.9 8 5V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M12 12V16M10 14H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Parts Inventory</span></a></li>
            <?php if ($_SESSION['user_rol'] === 'admin'): ?>
            <li class="nav-item bottom"><a href="../../../dashboards/dashboard_admin.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><path d="M12 3L4 7V12C4 16.418 7.582 20.398 12 21C16.418 20.398 20 16.418 20 12V7L12 3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span><span class="nav-text">Admin Panel</span></a></li>
            <?php endif; ?>
            <li class="nav-item <?php echo $_SESSION['user_rol'] !== 'admin' ? 'bottom' : ''; ?>">
                <a href="../../../logout.php" class="nav-link logout"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><path d="M14 3H6C5.448 3 5 3.448 5 4V20C5 20.552 5.448 21 6 21H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 8L21 12L16 16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12H9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Logout</span></a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar"><?php echo strtoupper(substr($user_name, 0, 1)); ?></div>
                <div class="sidebar-user-info"><span class="sidebar-user-name"><?php echo htmlspecialchars($user_name); ?></span><span class="sidebar-user-role"><?php echo ucfirst($user_rol); ?></span></div>
            </div>
            <div class="sidebar-theme-toggle" onclick="toggleTheme()" title="Toggle theme">
                <div class="sidebar-theme-toggle-icon"><svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><circle cx="12" cy="12" r="4" stroke="#f59e0b" stroke-width="2"/><path d="M12 2V4M12 20V22M4.22 4.22L5.64 5.64M18.36 18.36L19.78 19.78M2 12H4M20 12H22M4.22 19.78L5.64 18.36M18.36 5.64L19.78 4.22" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"/></svg><svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" stroke="#a78bfa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                <span class="sidebar-theme-toggle-label"></span>
            </div>
        </div>
    </nav>

    <!-- Theme Toggle -->
    <script src="../../../assets/js/theme-toggle.js"></script>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">

        <?php if ($view_asset): ?>
            <!-- DETAIL VIEW -->
            <div class="section-container">
                <div class="detail-header">
                    <a href="assets.php" class="btn-back">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none">
                            <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Back to Assets
                    </a>
                    <h1 class="detail-title"><?php echo htmlspecialchars($view_asset['name']); ?></h1>
                </div>

                <?php if ($error): ?><div class="alert-msg error"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M4.93 4.93l14.14 14.14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> <?php echo $error; ?></div><?php endif; ?>
                <?php if ($success): ?><div class="alert-msg success">&#10003; <?php echo $success; ?></div><?php endif; ?>

                <div class="detail-card">
                    <div class="detail-image-wrap">
                        <?php if (!empty($view_asset['image_path'])): ?>
                            <img src="../../../<?php echo htmlspecialchars($view_asset['image_path']); ?>" alt="Asset" class="detail-image">
                        <?php else: ?>
                            <div class="detail-no-image"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" style="vertical-align:middle;margin-right:6px"><rect x="2" y="7" width="10" height="14" rx="1.5" stroke="currentColor" stroke-width="1.8"/><path d="M12 9h6a2 2 0 012 2v8a2 2 0 01-2 2h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M5 4V2M9 4V2M7 7V4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></div>
                        <?php endif; ?>
                    </div>
                    <div class="detail-info">
                        <div class="detail-info-grid">
                            <div class="info-row"><span class="info-label">ID:</span><span class="info-value"><span class="code-badge"><?php echo htmlspecialchars($view_asset['code']); ?></span></span></div>
                            <div class="info-row"><span class="info-label">Model:</span><span class="info-value"><?php echo htmlspecialchars($view_asset['model'] ?: '&mdash;'); ?></span></div>
                            <div class="info-row"><span class="info-label">Brand:</span><span class="info-value"><?php echo htmlspecialchars($view_asset['brand'] ?: '&mdash;'); ?></span></div>
                            <div class="info-row"><span class="info-label">Serial Number:</span><span class="info-value"><?php echo htmlspecialchars($view_asset['serial_number'] ?: '&mdash;'); ?></span></div>
                            <div class="info-row"><span class="info-label">Miles / Hours:</span><span class="info-value"><?php echo number_format($view_asset['miles']); ?></span></div>
                            <div class="info-row"><span class="info-label">Miles per Service:</span><span class="info-value"><?php echo $view_asset['miles_per_service'] ? number_format($view_asset['miles_per_service']) : '&mdash;'; ?></span></div>
                            <div class="info-row"><span class="info-label">Price:</span><span class="info-value price-val">$<?php echo number_format($view_asset['price'], 2); ?></span></div>
                            <div class="info-row">
                                <span class="info-label">Status:</span>
                                <span class="info-value">
                                    <?php $sl = strtolower($view_asset['status']); ?>
                                    <span class="status-pill status-<?php echo $sl; ?>"><?php echo ucfirst($sl); ?></span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Substatus:</span>
                                <span class="info-value">
                                    <span class="substatus-pill"><?php echo ucwords(str_replace('_', ' ', $view_asset['substatus'] ?: '&mdash;')); ?></span>
                                </span>
                            </div>
                            <div class="info-row"><span class="info-label">Group:</span><span class="info-value"><?php echo htmlspecialchars($view_asset['group_type'] ?: '&mdash;'); ?></span></div>
                            <?php if ($view_asset['maintenance_day']): ?>
                                <div class="info-row"><span class="info-label">Maintenance Day:</span><span class="info-value"><?php echo htmlspecialchars($view_asset['maintenance_day']); ?></span></div>
                            <?php endif; ?>
                            <?php if ($view_asset['maintenance_frequency']): ?>
                                <div class="info-row"><span class="info-label">Maintenance Freq:</span><span class="info-value"><?php echo htmlspecialchars($view_asset['maintenance_frequency']); ?></span></div>
                            <?php endif; ?>
                            <?php if ($view_asset['description']): ?>
                                <div class="info-row full-row"><span class="info-label">Description:</span><span class="info-value"><?php echo htmlspecialchars($view_asset['description']); ?></span></div>
                            <?php endif; ?>
                        </div>
                        <div class="detail-actions">
                            <button class="btn-edit-detail" onclick="openEditModal()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Edit Asset</button>
                            <button class="btn-delete-detail" onclick="openDeleteModal(<?php echo $view_asset['id']; ?>, '<?php echo htmlspecialchars(addslashes($view_asset['name'])); ?>')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Delete</button>
                        </div>
                    </div>
                </div>

                <!-- Work Orders -->
                <div class="wo-section-grid">
                    <div class="wo-card">
                        <div class="wo-card-header pending">
                            <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" style="vertical-align:middle;margin-right:6px"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Active Work Orders <span class="wo-count"><?php echo count($wo_pending); ?></span></h3>
                        </div>
                        <div class="wo-card-body">
                            <?php if (empty($wo_pending)): ?>
                                <p class="wo-empty">No active work orders.</p>
                            <?php else: ?>
                                <?php foreach ($wo_pending as $wo): ?>
                                    <div class="wo-item wo-<?php echo $wo['status']; ?>">
                                        <div class="wo-item-code"><?php echo htmlspecialchars($wo['code']); ?></div>
                                        <div class="wo-item-desc"><?php echo htmlspecialchars($wo['description']); ?></div>
                                        <span class="wo-status-badge"><?php echo str_replace('_', ' ', ucfirst($wo['status'])); ?></span>
                                        <div class="wo-item-date"><?php echo $wo['opened_date']; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="wo-card">
                        <div class="wo-card-header completed">
                            <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> Completed Work Orders <span class="wo-count"><?php echo count($wo_completed); ?></span></h3>
                        </div>
                        <div class="wo-card-body">
                            <?php if (empty($wo_completed)): ?>
                                <p class="wo-empty">No completed work orders.</p>
                            <?php else: ?>
                                <?php foreach ($wo_completed as $wo): ?>
                                    <div class="wo-item wo-closed">
                                        <div class="wo-item-code"><?php echo htmlspecialchars($wo['code']); ?></div>
                                        <div class="wo-item-desc"><?php echo htmlspecialchars($wo['description']); ?></div>
                                        <div class="wo-item-date">Closed: <?php echo $wo['closed_date']; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal-overlay" id="editModal">
                <div class="modal-box large">
                    <div class="modal-header">
                        <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Edit Asset</h3>
                        <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <input type="hidden" name="action" value="edit_asset">
                        <input type="hidden" name="id" value="<?php echo $view_asset['id']; ?>">
                        <?php echo build_asset_form($view_asset); ?>
                        <div class="modal-footer-form">
                            <button type="button" class="btn-cancel" onclick="closeModal('editModal')">Cancel</button>
                            <button type="submit" class="btn-save">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal-overlay" id="deleteModal">
                <div class="modal-box small">
                    <div class="modal-header">
                        <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Delete Asset</h3>
                        <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
                    </div>
                    <div class="modal-body-form">
                        <p class="delete-confirm-text">Are you sure you want to delete <strong id="deleteAssetName"></strong>? This action cannot be undone.</p>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <input type="hidden" name="action" value="delete_asset">
                        <input type="hidden" name="id" id="deleteAssetId">
                        <div class="modal-footer-form">
                            <button type="button" class="btn-cancel" onclick="closeModal('deleteModal')">Cancel</button>
                            <button type="submit" class="btn-delete">Delete</button>
                        </div>
                    </form>
                </div>
            </div>

        <?php else: ?>
            <!-- LIST VIEW -->

            <?php if ($error): ?><div class="alert-msg error" style="margin-bottom:18px"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M4.93 4.93l14.14 14.14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> <?php echo $error; ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert-msg success" style="margin-bottom:18px"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> <?php echo $success; ?></div><?php endif; ?>

            <!-- MODULE HEADER PANEL -->
            <div class="module-header-panel">
                <div>
                    <h2 class="module-header-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" style="vertical-align:middle;margin-right:6px"><rect x="2" y="7" width="10" height="14" rx="1.5" stroke="currentColor" stroke-width="1.8"/><path d="M12 9h6a2 2 0 012 2v8a2 2 0 01-2 2h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M5 4V2M9 4V2M7 7V4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Assets Management</h2>
                    <p class="module-header-subtitle">Manage farm machinery, equipment, and tools</p>
                </div>
                <button class="btn-add-primary" onclick="openAddModal()">+ Add Asset</button>
            </div>

            <!-- Filters Panel -->
            <div class="filters-section">
                <form method="GET" id="filterForm">
                    <div class="search-row">
                        <div class="search-col">
                            <label class="filter-label"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Search by ID, Name, Brand, Serial</label>
                            <input type="text" name="search" class="search-input" placeholder="Type to search..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="search-col">
                            <label class="filter-label"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M18 20V10M12 20V4M6 20v-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Filter by Status</label>
                            <select name="filter_status" class="filter-select" onchange="submitWithGroups()">
                                <option value="">All Statuses</option>
                                <?php foreach (['active' => 'Active', 'inactive' => 'Inactive', 'sold' => 'Sold'] as $v => $l): ?>
                                    <option value="<?php echo $v; ?>" <?php echo $filter_sts === $v ? 'selected' : ''; ?>><?php echo $l; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="search-col">
                            <label class="filter-label"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" stroke="currentColor" stroke-width="1.8"/></svg> Filter by Group</label>
                            <div class="group-dropdown-wrap">
                                <button type="button" class="group-dropdown-btn" id="groupDropdownBtn" onclick="toggleGroupDropdown()">
                                    <span id="groupDropdownLabel"><?php
                                        $selected_groups = array_filter(explode(',', $_GET['groups'] ?? ''));
                                        echo count($selected_groups)
                                            ? count($selected_groups) . ' group' . (count($selected_groups) > 1 ? 's' : '') . ' selected'
                                            : 'All Groups';
                                    ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none">
                                        <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <input type="hidden" name="groups" id="groupsHidden" value="<?php echo htmlspecialchars(implode(',', $selected_groups)); ?>">
                            </div>
                        </div>
                        <div class="top-actions">
                            <?php if ($search !== '' || !empty($selected_groups) || $filter_sts !== ''): ?>
                                <a href="assets.php" class="btn-clear">&times; Clear</a>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn-search">Search</button>
                    </div>
                </form>
            </div>

            <div class="results-count">
                <?php echo count($assets); ?> asset<?php echo count($assets) !== 1 ? 's' : ''; ?> found
                <?php if (!empty($selected_groups)): ?> &mdash; Groups: <strong><?php echo htmlspecialchars(implode(', ', $selected_groups)); ?></strong><?php endif; ?>
                <?php if ($filter_sts): ?> &mdash; Status: <strong><?php echo ucfirst($filter_sts); ?></strong><?php endif; ?>
            </div>

            <?php if (empty($assets)): ?>
                <div class="no-data-state">
                    <div class="no-data-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" style="vertical-align:middle;margin-right:6px"><rect x="2" y="7" width="10" height="14" rx="1.5" stroke="currentColor" stroke-width="1.8"/><path d="M12 9h6a2 2 0 012 2v8a2 2 0 01-2 2h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M5 4V2M9 4V2M7 7V4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></div>
                    <p class="no-data-text">No assets found.<br>Add your first asset to get started.</p>
                    <button class="btn-add" onclick="openAddModal()">+ Add Asset</button>
                </div>
            <?php else: ?>
                <div class="assets-grid">
                    <?php foreach ($assets as $asset): ?>
                        <a href="assets.php?view=<?php echo $asset['id']; ?>" class="asset-card">
                            <div class="asset-card-image">
                                <?php if (!empty($asset['image_path'])): ?>
                                    <img src="../../../<?php echo htmlspecialchars($asset['image_path']); ?>" alt="<?php echo htmlspecialchars($asset['name']); ?>">
                                <?php else: ?>
                                    <div class="asset-no-image"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" style="vertical-align:middle;margin-right:6px"><rect x="2" y="7" width="10" height="14" rx="1.5" stroke="currentColor" stroke-width="1.8"/><path d="M12 9h6a2 2 0 012 2v8a2 2 0 01-2 2h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M5 4V2M9 4V2M7 7V4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></div>
                                <?php endif; ?>
                            </div>
                            <div class="asset-card-body">
                                <h3 class="asset-card-name"><?php echo htmlspecialchars($asset['name']); ?></h3>
                                <div class="asset-card-meta">
                                    <span class="meta-id"><?php echo htmlspecialchars($asset['code']); ?></span>
                                    <?php $sl = strtolower($asset['status']); ?>
                                    <span class="status-pill status-<?php echo $sl; ?>"><?php echo ucfirst($sl); ?></span>
                                </div>
                                <?php if ($asset['group_type']): ?>
                                    <div class="asset-card-group"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" stroke="currentColor" stroke-width="1.8"/></svg> <?php echo htmlspecialchars($asset['group_type']); ?></div>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Add Modal -->
            <div class="modal-overlay" id="addModal">
                <div class="modal-box large">
                    <div class="modal-header">
                        <h3>+ Add Asset</h3>
                        <button class="modal-close" onclick="closeModal('addModal')">&times;</button>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <input type="hidden" name="action" value="add_asset">
                        <?php echo build_asset_form(); ?>
                        <div class="modal-footer-form">
                            <button type="button" class="btn-cancel" onclick="closeModal('addModal')">Cancel</button>
                            <button type="submit" class="btn-save">Add Asset</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal-overlay" id="deleteModal">
                <div class="modal-box small">
                    <div class="modal-header">
                        <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Delete Asset</h3>
                        <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
                    </div>
                    <div class="modal-body-form">
                        <p class="delete-confirm-text">Are you sure you want to delete <strong id="deleteAssetName"></strong>?</p>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <input type="hidden" name="action" value="delete_asset">
                        <input type="hidden" name="id" id="deleteAssetId">
                        <div class="modal-footer-form">
                            <button type="button" class="btn-cancel" onclick="closeModal('deleteModal')">Cancel</button>
                            <button type="submit" class="btn-delete">Delete</button>
                        </div>
                    </form>
                </div>
            </div>

        <?php endif; ?>
    </div><!-- /main-content -->

    <!-- Group Dropdown Portal -->
    <div class="group-dropdown-menu" id="groupDropdownMenu" style="position:fixed; display:none; z-index:9999;">
        <div class="group-dropdown-actions">
            <button type="button" onclick="selectAllGroups()" class="group-action-btn">All</button>
            <button type="button" onclick="clearGroups()" class="group-action-btn">Clear</button>
        </div>
        <?php
        $selected_groups_portal = array_filter(explode(',', $_GET['groups'] ?? ''));
        foreach (ASSET_GROUPS as $g): ?>
            <label class="group-option-check">
                <input type="checkbox" name="group_check_portal" value="<?php echo htmlspecialchars($g); ?>"
                    <?php echo in_array($g, $selected_groups_portal) ? 'checked' : ''; ?>
                    onchange="updateGroupFilter()">
                <span><?php echo htmlspecialchars($g); ?></span>
            </label>
        <?php endforeach; ?>
    </div>

    <?php
    function build_asset_form($asset = null)
    {
        $v    = $asset ?? [];
        $days  = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        $freqs = ['Daily','Weekly','Bi-weekly','Monthly','Quarterly','Yearly'];
        ob_start();
    ?>
        <div class="modal-body-form">
            <?php if ($asset): ?>
                <div class="autoid-note"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" stroke="currentColor" stroke-width="1.8"/><circle cx="7" cy="7" r="1.5" fill="currentColor"/></svg> Asset ID: <strong><?php echo htmlspecialchars($asset['code']); ?></strong></div>
            <?php else: ?>
                <div class="autoid-note"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" stroke="currentColor" stroke-width="1.8"/><circle cx="7" cy="7" r="1.5" fill="currentColor"/></svg> Asset ID will be generated automatically</div>
            <?php endif; ?>
            <div class="form-row-2">
                <div class="form-group"><label class="form-label">Name *</label><input type="text" name="name" class="form-input" value="<?php echo htmlspecialchars($v['name'] ?? ''); ?>" placeholder="e.g. Kubota L2501" required></div>
                <div class="form-group"><label class="form-label">Model</label><input type="text" name="model" class="form-input" value="<?php echo htmlspecialchars($v['model'] ?? ''); ?>" placeholder="e.g. L2501 2022"></div>
            </div>
            <div class="form-row-2">
                <div class="form-group"><label class="form-label">Brand</label><input type="text" name="brand" class="form-input" value="<?php echo htmlspecialchars($v['brand'] ?? ''); ?>" placeholder="e.g. Kubota"></div>
                <div class="form-group"><label class="form-label">Serial Number</label><input type="text" name="serial_number" class="form-input" value="<?php echo htmlspecialchars($v['serial_number'] ?? ''); ?>" placeholder="SN123456"></div>
            </div>
            <div class="form-group" style="margin-bottom:14px"><label class="form-label">Description</label><textarea name="description" class="form-input" rows="2" placeholder="Brief description..."><?php echo htmlspecialchars($v['description'] ?? ''); ?></textarea></div>
            <div class="form-row-3">
                <div class="form-group"><label class="form-label">Price ($)</label><input type="number" name="price" class="form-input" step="0.01" min="0" value="<?php echo $v['price'] ?? '0'; ?>" placeholder="0.00"></div>
                <div class="form-group"><label class="form-label">Miles / Hours</label><input type="number" name="miles" class="form-input" min="0" value="<?php echo $v['miles'] ?? '0'; ?>"></div>
                <div class="form-group"><label class="form-label">Miles per Service</label><input type="number" name="miles_per_service" class="form-input" min="0" value="<?php echo $v['miles_per_service'] ?? '0'; ?>"></div>
            </div>
            <div class="form-row-3">
                <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-input"><?php foreach (['active'=>'Active','inactive'=>'Inactive','sold'=>'Sold'] as $val=>$lbl): ?><option value="<?php echo $val; ?>" <?php echo ($v['status']??'active')===$val?'selected':''; ?>><?php echo $lbl; ?></option><?php endforeach; ?></select></div>
                <div class="form-group"><label class="form-label">Substatus</label><select name="substatus" class="form-input"><?php foreach (['running'=>'Running','needs_repair'=>'Needs Repair','down'=>'Down'] as $val=>$lbl): ?><option value="<?php echo $val; ?>" <?php echo ($v['substatus']??'running')===$val?'selected':''; ?>><?php echo $lbl; ?></option><?php endforeach; ?></select></div>
                <div class="form-group"><label class="form-label">Group</label><select name="group_type" class="form-input"><option value="">&mdash; Select Group &mdash;</option><?php foreach (ASSET_GROUPS as $g): ?><option value="<?php echo htmlspecialchars($g); ?>" <?php echo ($v['group_type']??'')===$g?'selected':''; ?>><?php echo htmlspecialchars($g); ?></option><?php endforeach; ?></select></div>
            </div>
            <div class="form-row-2">
                <div class="form-group"><label class="form-label">Maintenance Day (Optional)</label><select name="maintenance_day" class="form-input"><option value="">&mdash; None &mdash;</option><?php foreach ($days as $d): ?><option value="<?php echo $d; ?>" <?php echo ($v['maintenance_day']??'')===$d?'selected':''; ?>><?php echo $d; ?></option><?php endforeach; ?></select></div>
                <div class="form-group"><label class="form-label">Maintenance Frequency (Optional)</label><select name="maintenance_frequency" class="form-input"><option value="">&mdash; None &mdash;</option><?php foreach ($freqs as $f): ?><option value="<?php echo $f; ?>" <?php echo ($v['maintenance_frequency']??'')===$f?'selected':''; ?>><?php echo $f; ?></option><?php endforeach; ?></select></div>
            </div>
            <div class="form-group"><label class="form-label"><?php echo $asset ? 'Update Image (Optional)' : 'Asset Image'; ?></label><input type="file" name="asset_image" class="form-input" accept="image/*"><?php if (!empty($v['image_path'])): ?><img src="../../../<?php echo htmlspecialchars($v['image_path']); ?>" style="width:80px;height:60px;object-fit:cover;border-radius:6px;margin-top:8px;"><?php endif; ?></div>
        </div>
    <?php
        return ob_get_clean();
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openAddModal()    { document.getElementById('addModal').classList.add('active'); }
        function openEditModal()   { document.getElementById('editModal').classList.add('active'); }
        function closeModal(id)    { document.getElementById(id).classList.remove('active'); }
        function openDeleteModal(id, name) { document.getElementById('deleteAssetId').value = id; document.getElementById('deleteAssetName').textContent = name; document.getElementById('deleteModal').classList.add('active'); }
        const dropdownMenu = document.getElementById('groupDropdownMenu');
        const dropdownBtn  = document.getElementById('groupDropdownBtn');
        let dropdownOpen   = false;
        function positionDropdown() { const rect = dropdownBtn.getBoundingClientRect(); dropdownMenu.style.top = (rect.bottom + 6) + 'px'; dropdownMenu.style.left = rect.left + 'px'; dropdownMenu.style.width = Math.max(rect.width, 240) + 'px'; }
        function toggleGroupDropdown() { dropdownOpen = !dropdownOpen; if (dropdownOpen) { positionDropdown(); dropdownMenu.style.display = 'block'; } else { dropdownMenu.style.display = 'none'; } }
        function closeDropdown() { dropdownOpen = false; dropdownMenu.style.display = 'none'; }
        window.addEventListener('scroll', () => { if (dropdownOpen) positionDropdown(); }, true);
        window.addEventListener('resize', () => { if (dropdownOpen) positionDropdown(); });
        function updateGroupFilter() { const checked = [...document.querySelectorAll('input[name="group_check_portal"]:checked')].map(c => c.value); document.getElementById('groupsHidden').value = checked.join(','); const label = checked.length ? checked.length + ' group' + (checked.length > 1 ? 's' : '') + ' selected' : 'All Groups'; document.getElementById('groupDropdownLabel').textContent = label; }
        function selectAllGroups() { document.querySelectorAll('input[name="group_check_portal"]').forEach(c => c.checked = true); updateGroupFilter(); }
        function clearGroups() { document.querySelectorAll('input[name="group_check_portal"]').forEach(c => c.checked = false); updateGroupFilter(); document.getElementById('filterForm').submit(); }
        document.addEventListener('click', function(e) { if (!e.target.closest('#groupDropdownMenu') && !e.target.closest('#groupDropdownBtn')) { closeDropdown(); } });
        document.querySelectorAll('.modal-overlay').forEach(m => { m.addEventListener('click', e => { if (e.target === m) m.classList.remove('active'); }); });
        function submitWithGroups() { const checked = [...document.querySelectorAll('input[name="group_check_portal"]:checked')].map(c => c.value); document.getElementById('groupsHidden').value = checked.join(','); document.getElementById('filterForm').submit(); }
    </script>
<script>

        const sidebar=document.getElementById('sidebar'),mainContent=document.getElementById('mainContent'),overlay=document.getElementById('sidebarOverlay'),toggleBtn=document.getElementById('sidebarToggleBtn');
        const SIDEBAR_W=270,COLLAPSED_W=72;
        function updateToggleBtn(c){if(window.innerWidth>768){toggleBtn.style.left=(c?COLLAPSED_W:SIDEBAR_W)-14+'px';toggleBtn.style.transform=c?'rotate(180deg)':'rotate(0deg)';}}
        const isCollapsed=localStorage.getItem('sidebarCollapsed')==='true';
        if(isCollapsed){sidebar.classList.add('collapsed');mainContent.classList.add('collapsed');}
        updateToggleBtn(isCollapsed);
        function toggleSidebar(){const c=sidebar.classList.toggle('collapsed');mainContent.classList.toggle('collapsed');localStorage.setItem('sidebarCollapsed',c);updateToggleBtn(c);}
        function openSidebar(){sidebar.classList.add('open');overlay.classList.add('active');document.body.style.overflow='hidden';}
        function closeSidebar(){sidebar.classList.remove('open');overlay.classList.remove('active');document.body.style.overflow='';}
        let resizeTimeout;
        window.addEventListener('resize',()=>{
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                if(window.innerWidth<=768){toggleBtn.style.display='none';}
                else{toggleBtn.style.display='flex';const wasOpen=sidebar.classList.contains('open');sidebar.classList.remove('open');overlay.classList.remove('active');document.body.style.overflow='';
                    if(wasOpen){sidebar.classList.remove('collapsed');mainContent.classList.remove('collapsed');localStorage.setItem('sidebarCollapsed','false');}
                    else{const s=localStorage.getItem('sidebarCollapsed')==='true';if(s){sidebar.classList.add('collapsed');mainContent.classList.add('collapsed');}else{sidebar.classList.remove('collapsed');mainContent.classList.remove('collapsed');}}
                    updateToggleBtn(sidebar.classList.contains('collapsed'));}
            }, 100);
        });
        if(window.innerWidth<=768)toggleBtn.style.display='none';
</script>
</body>
</html>