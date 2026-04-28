<?php require_once '../controllersS/work_orders_controller.php'; ?>
<?php $user_name = $_SESSION['user_name'] ?? 'User'; $user_rol = $_SESSION['user_rol'] ?? 'shop'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Orders — Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/style_dashboard_shop.css">
    <link rel="stylesheet" href="../../../assets/css/style_work_orders_shop.css">
</head>

<body class="shop-module">

    <!-- Theme Toggle -->
    <script src="../../../assets/js/theme-toggle.js"></script>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    <button class="sidebar-toggle-btn" id="sidebarToggleBtn" onclick="toggleSidebar()" title="Toggle sidebar">&#x2039;</button>
    <div class="topbar"><button class="topbar-hamburger" onclick="openSidebar()" aria-label="Open menu"><span></span><span></span><span></span></button><span class="topbar-title">Yustre &mdash; Shop</span></div>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M3 10.5L12 3L21 10.5V20C21 20.552 20.552 21 20 21H15V15H9V21H4C3.448 21 3 20.552 3 20V10.5Z" stroke="rgba(255,220,160,0.9)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            <div class="sidebar-brand"><span class="sidebar-brand-name">Sistema Yustre</span><span class="sidebar-brand-sub">Shop Module</span></div>
            <button class="sidebar-close-btn" onclick="closeSidebar()" aria-label="Close menu"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
        </div>
        <ul class="nav-menu">
            <li class="nav-item"><a href="../../../dashboards/dashboard_shop.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><rect x="3" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="3" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/></svg></span><span class="nav-text">Dashboard</span></a></li>
            <li class="nav-item"><a href="work_orders.php" class="nav-link active"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 7H16M8 11H16M8 15H12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Work Orders</span></a></li>
            <li class="nav-item"><a href="assets.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><rect x="2" y="7" width="10" height="14" rx="1.5" stroke="currentColor" stroke-width="1.8"/><path d="M12 9h6a2 2 0 012 2v8a2 2 0 01-2 2h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M5 4V2M9 4V2M7 7V4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Assets</span></a></li>
            <li class="nav-item"><a href="parts_inventory.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M20 7H4C2.9 7 2 7.9 2 9V19C2 20.1 2.9 21 4 21H20C21.1 21 22 20.1 22 19V9C22 7.9 21.1 7 20 7Z" stroke="currentColor" stroke-width="1.8"/><path d="M16 7V5C16 3.9 15.1 3 14 3H10C8.9 3 8 3.9 8 5V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M12 12V16M10 14H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Parts Inventory</span></a></li>
            <?php if ($_SESSION['user_rol'] === 'admin'): ?>
            <li class="nav-item bottom"><a href="../../../dashboards/dashboard_admin.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M12 3L4 7V12C4 16.418 7.582 20.398 12 21C16.418 20.398 20 16.418 20 12V7L12 3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span><span class="nav-text">Admin Panel</span></a></li>
            <?php endif; ?>

            <li class="nav-item <?php echo $_SESSION['user_rol'] !== 'admin' ? 'bottom' : ''; ?>">
                <a href="../../../logout.php" class="nav-link logout"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M14 3H6C5.448 3 5 3.448 5 4V20C5 20.552 5.448 21 6 21H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 8L21 12L16 16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12H9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Logout</span></a>
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

    <div class="main-content" id="mainContent">

        <?php if ($view_wo): ?>
        <!-- ════════════════════  DETAIL VIEW  ════════════════════ -->

        <div class="module-header-panel">
            <h2 class="module-header-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" style="vertical-align:middle;margin-right:5px"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>Work Orders</h2>
            <p class="module-header-sub">Active and in-progress work orders</p>
        </div>

        <div class="wo-detail-container">

            <?php if ($error): ?><div class="alert-msg error"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M4.93 4.93l14.14 14.14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg><?php echo $error; ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert-msg success"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg><?php echo $success; ?></div><?php endif; ?>

            <div class="wo-detail-card">
                <div class="wo-detail-header">
                    <a href="work_orders.php" class="btn-back"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:5px"><path d="M19 12H5M5 12l7 7M5 12l7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> Back to Work Orders</a>
                    <div class="wo-detail-title-wrap">
                        <h1 class="wo-detail-title"><?php echo htmlspecialchars($view_wo['order_number'] ?? $view_wo['code']); ?></h1>
                        <span class="wo-badge-type type-<?php echo $view_wo['type']; ?>"><?php echo $WO_TYPES[$view_wo['type']] ?? ucfirst($view_wo['type']); ?></span>
                        <span class="wo-badge-priority pri-<?php echo $view_wo['priority']; ?>"><?php echo $WO_PRIORITIES[$view_wo['priority']] ?? ucfirst($view_wo['priority']); ?></span>
                        <span class="wo-badge-status sts-<?php echo $view_wo['status']; ?>"><?php echo $WO_STATUSES[$view_wo['status']] ?? ucfirst($view_wo['status']); ?></span>
                    </div>
                </div>
                <div class="wo-detail-grid">
                    <div class="wo-info-row"><span class="wo-info-label">Order Number</span><span class="wo-info-value code-badge"><?php echo htmlspecialchars($view_wo['order_number'] ?? $view_wo['code']); ?></span></div>
                    <div class="wo-info-row"><span class="wo-info-label">Internal ID</span><span class="wo-info-value">#<?php echo $view_wo['id']; ?></span></div>
                    <div class="wo-info-row"><span class="wo-info-label">Asset</span><span class="wo-info-value"><strong><?php echo htmlspecialchars($view_wo['asset_name'] ?? '—'); ?></strong> <span class="text-muted small">(<?php echo htmlspecialchars($view_wo['asset_code'] ?? ''); ?>)</span></span></div>
                    <div class="wo-info-row"><span class="wo-info-label">Type</span><span class="wo-info-value"><?php echo $WO_TYPES[$view_wo['type']] ?? '—'; ?></span></div>
                    <div class="wo-info-row"><span class="wo-info-label">Priority</span><span class="wo-info-value"><span class="wo-badge-priority pri-<?php echo $view_wo['priority']; ?>"><?php echo $WO_PRIORITIES[$view_wo['priority']] ?? '—'; ?></span></span></div>
                    <div class="wo-info-row"><span class="wo-info-label">Status</span><span class="wo-info-value"><span class="wo-badge-status sts-<?php echo $view_wo['status']; ?>"><?php echo $WO_STATUSES[$view_wo['status']] ?? '—'; ?></span></span></div>
                    <div class="wo-info-row"><span class="wo-info-label">Created By</span><span class="wo-info-value"><?php echo htmlspecialchars($view_wo['created_by'] ?? 'System'); ?></span></div>
                    <div class="wo-info-row"><span class="wo-info-label">Opened Date</span><span class="wo-info-value"><?php echo $view_wo['opened_date'] ?? '—'; ?></span></div>
                    <div class="wo-info-row">
                        <span class="wo-info-label">Due Date</span>
                        <span class="wo-info-value <?php
                            if (!empty($view_wo['due_date'])) {
                                if ($view_wo['due_date'] < date('Y-m-d')) echo 'text-overdue';
                                elseif ($view_wo['due_date'] <= date('Y-m-d', strtotime('+3 days'))) echo 'text-due-soon';
                            }
                        ?>">
                            <?php if (!empty($view_wo['due_date'])): ?>
                                <?php if ($view_wo['due_date'] < date('Y-m-d')): ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="1.8"/><path d="M12 9v4M12 17h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg><?php echo $view_wo['due_date']; ?> <span class="overdue-tag">OVERDUE</span>
                                <?php elseif ($view_wo['due_date'] <= date('Y-m-d', strtotime('+3 days'))): ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:3px"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg><?php echo $view_wo['due_date']; ?> <span class="due-soon-tag">DUE SOON</span>
                                <?php else: ?><?php echo $view_wo['due_date']; ?>
                                <?php endif; ?>
                            <?php else: ?>—<?php endif; ?>
                        </span>
                    </div>
                    <?php if ($view_wo['closed_date']): ?>
                        <div class="wo-info-row"><span class="wo-info-label">Closed Date</span><span class="wo-info-value"><?php echo $view_wo['closed_date']; ?></span></div>
                    <?php endif; ?>
                    <div class="wo-info-row full"><span class="wo-info-label">Description</span><span class="wo-info-value"><?php echo nl2br(htmlspecialchars($view_wo['description'])); ?></span></div>
                    <?php if ($view_wo['result']): ?>
                        <div class="wo-info-row full"><span class="wo-info-label">Result / Notes</span><span class="wo-info-value"><?php echo nl2br(htmlspecialchars($view_wo['result'])); ?></span></div>
                    <?php endif; ?>
                </div>

                <div class="wo-detail-actions">
                    <?php if ($view_wo['status'] !== 'closed'): ?>
                        <button class="btn-wo-edit" onclick="openEditModal()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>Edit / Update Status</button>
                    <?php endif; ?>
                    <button class="btn-wo-delete" onclick="openDeleteModal(<?php echo $view_wo['id']; ?>, '<?php echo htmlspecialchars(addslashes($view_wo['order_number'] ?? $view_wo['code'])); ?>')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>Delete</button>
                </div>
            </div>
        </div>

        <!-- PARTS USED -->
        <div class="wo-parts-section">
            <div class="wo-parts-header">
                <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" style="vertical-align:middle;margin-right:5px"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>Parts Used <span class="wo-parts-count"><?php echo count($wo_parts); ?></span></h3>
                <?php if ($view_wo['status'] !== 'closed' && !empty($all_parts)): ?>
                    <button class="btn-add-part" onclick="openModal('addPartModal')">+ Add Part</button>
                <?php endif; ?>
            </div>

            <?php if (empty($wo_parts)): ?>
                <p class="wo-parts-empty">No parts added to this work order yet.</p>
            <?php else: ?>
                <div class="wo-parts-table-wrap">
                    <table class="wo-parts-table">
                        <thead>
                            <tr>
                                <th>Part</th><th>Code</th><th>Qty</th><th>Unit</th><th>Notes</th><th>Added</th>
                                <?php if ($view_wo['status'] !== 'closed'): ?><th></th><?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($wo_parts as $wp): ?>
                                <tr>
                                    <td class="td-part-name"><a href="parts_inventory.php?view=<?php echo $wp['part_id']; ?>" class="part-ref"><?php echo htmlspecialchars($wp['part_name']); ?></a></td>
                                    <td><span class="part-code-sm"><?php echo htmlspecialchars($wp['part_code']); ?></span></td>
                                    <td class="td-qty-used"><strong><?php echo $wp['quantity']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($wp['unit']); ?></td>
                                    <td class="td-notes"><?php echo htmlspecialchars($wp['notes'] ?: '—'); ?></td>
                                    <td class="td-date"><?php echo substr($wp['date_register'], 0, 10); ?></td>
                                    <?php if ($view_wo['status'] !== 'closed'): ?>
                                        <td>
                                            <form method="POST" style="margin:0">
                                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                                <input type="hidden" name="action" value="remove_wo_part">
                                                <input type="hidden" name="wop_id" value="<?php echo $wp['id']; ?>">
                                                <input type="hidden" name="wo_id" value="<?php echo $view_wo['id']; ?>">
                                                <button type="submit" class="btn-rm-part" onclick="return confirm('Remove this part? Stock will be restored.')">&times;</button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Add Part Modal -->
        <div class="modal-overlay" id="addPartModal">
            <div class="modal-box large">
                <div class="modal-header">
                    <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" style="vertical-align:middle;margin-right:5px"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>Add Part to Work Order</h3><button class="modal-close" onclick="closeModal('addPartModal')">&times;</button>
                </div>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="add_wo_part">
                    <input type="hidden" name="wo_id" value="<?php echo $view_wo['id']; ?>">
                    <div class="modal-body-form">
                        <div class="form-group">
                            <label class="form-label">Part *</label>
                            <select name="part_id" class="form-input" required onchange="updateStockInfo(this)">
                                <option value="">— Select part —</option>
                                <?php foreach ($all_parts as $ap): ?>
                                    <option value="<?php echo $ap['id']; ?>" data-stock="<?php echo $ap['stock']; ?>" data-unit="<?php echo htmlspecialchars($ap['unit']); ?>">
                                        <?php echo htmlspecialchars($ap['name']); ?> (<?php echo $ap['code']; ?>) — Stock: <?php echo $ap['stock']; ?> <?php echo $ap['unit']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div id="stockInfo" class="stock-info-line" style="display:none"></div>
                        </div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">Quantity *</label>
                                <input type="number" name="quantity" id="partQty" class="form-input" min="1" required placeholder="0">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Notes (optional)</label>
                                <input type="text" name="notes" class="form-input" placeholder="e.g. Replaced oil filter">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer-form">
                        <button type="button" class="btn-cancel" onclick="closeModal('addPartModal')">Cancel</button>
                        <button type="submit" class="btn-save">Add Part &amp; Deduct Stock</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal-overlay" id="editModal">
            <div class="modal-box large">
                <div class="modal-header">
                    <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>Update Work Order</h3><button class="modal-close" onclick="closeModal('editModal')">&times;</button>
                </div>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="update_wo">
                    <input type="hidden" name="id" value="<?php echo $view_wo['id']; ?>">
                    <div class="modal-body-form">
                        <div class="form-row-3">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-input">
                                    <?php foreach ($WO_STATUSES as $v => $l): ?>
                                        <option value="<?php echo $v; ?>" <?php echo $view_wo['status'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Priority</label>
                                <select name="priority" class="form-input">
                                    <?php foreach ($WO_PRIORITIES as $v => $l): ?>
                                        <option value="<?php echo $v; ?>" <?php echo $view_wo['priority'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-input">
                                    <?php foreach ($WO_TYPES as $v => $l): ?>
                                        <option value="<?php echo $v; ?>" <?php echo $view_wo['type'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:14px">
                            <label class="form-label">Due Date (Optional)</label>
                            <input type="date" name="due_date" class="form-input" value="<?php echo $view_wo['due_date'] ?? ''; ?>">
                        </div>
                        <div class="form-group" style="margin-bottom:14px">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-input" rows="3"><?php echo htmlspecialchars($view_wo['description']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Result / Notes (optional)</label>
                            <textarea name="result" class="form-input" rows="3" placeholder="Describe what was done..."><?php echo htmlspecialchars($view_wo['result'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer-form">
                        <button type="button" class="btn-cancel" onclick="closeModal('editModal')">Cancel</button>
                        <button type="submit" class="btn-save">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

    <?php else: ?>
        <!-- ════════════════════  LIST VIEW  ════════════════════ -->

        <div class="module-header-panel">
            <h2 class="module-header-title">
                <?php echo $history_mode ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" style="vertical-align:middle;margin-right:5px"><path d="M9 6h11M9 12h11M9 18h11M4 6h.01M4 12h.01M4 18h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>Work Orders — History' : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" style="vertical-align:middle;margin-right:5px"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>Work Orders'; ?>
            </h2>
            <p class="module-header-sub">
                <?php echo $history_mode ? 'All closed/completed work orders' : 'Active and in-progress work orders'; ?>
            </p>
        </div>

        <?php if ($error): ?><div class="alert-msg error" style="margin-bottom:16px"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M4.93 4.93l14.14 14.14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert-msg success" style="margin-bottom:16px"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg><?php echo $success; ?></div><?php endif; ?>

        <!-- Filters -->
        <div class="wo-filters">
            <form method="GET" id="filterForm">
                <?php if ($history_mode): ?><input type="hidden" name="history" value="1"><?php endif; ?>
                <div class="wo-filter-row">
                    <div class="wo-filter-field">
                        <label class="filter-label"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>Search order #, asset...</label>
                        <input type="text" name="search" class="search-input" placeholder="Type to search..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="wo-filter-field">
                        <label class="filter-label"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" style="vertical-align:middle;margin-right:5px"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>Filter by Type</label>
                        <select name="type" class="filter-select" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            <?php foreach ($WO_TYPES as $v => $l): ?>
                                <option value="<?php echo $v; ?>" <?php echo $filter_type === $v ? 'selected' : ''; ?>><?php echo $l; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="wo-filter-field">
                        <label class="filter-label"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>Filter by Priority</label>
                        <select name="priority" class="filter-select" onchange="this.form.submit()">
                            <option value="">All Priorities</option>
                            <?php foreach ($WO_PRIORITIES as $v => $l): ?>
                                <option value="<?php echo $v; ?>" <?php echo $filter_pri === $v ? 'selected' : ''; ?>><?php echo $l; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if ($history_mode): ?>
                    <div class="wo-filter-field">
                        <label class="filter-label"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" style="vertical-align:middle;margin-right:5px"><path d="M9 6h11M9 12h11M9 18h11M4 6h.01M4 12h.01M4 18h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>Filter by Status</label>
                        <select name="status" class="filter-select" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <?php foreach ($WO_STATUSES as $v => $l): ?>
                                <option value="<?php echo $v; ?>" <?php echo $filter_sts === $v ? 'selected' : ''; ?>><?php echo $l; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="wo-filter-field" style="align-items:flex-end; gap:6px;">
                        <?php if ($search || $filter_type || $filter_sts || $filter_pri): ?>
                            <a href="work_orders.php<?php echo $history_mode ? '?history=1' : ''; ?>" class="btn-clear">&times; Clear</a>
                        <?php endif; ?>
                        <button type="submit" class="btn-search">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Stats strip -->
        <?php if (!$history_mode): ?>
            <?php
                $db2 = get_shop_db();
                $stats = ['open' => 0, 'in_progress' => 0, 'waiting_parts' => 0];
                if ($db2) {
                    $r = $db2->query("SELECT status, COUNT(*) as c FROM work_orders WHERE status != 'closed' GROUP BY status");
                    if ($r) while ($row = $r->fetch_assoc()) {
                        if (isset($stats[$row['status']])) $stats[$row['status']] += $row['c'];
                    }
                    $db2->close();
                }
            ?>
            <div class="wo-stats-strip">
                <a href="work_orders.php?status=open<?php echo $search ? '&search='.urlencode($search) : ''; ?><?php echo $filter_type ? '&type='.urlencode($filter_type) : ''; ?><?php echo $filter_pri ? '&priority='.urlencode($filter_pri) : ''; ?>" class="wo-stat open <?php echo $filter_sts === 'open' ? 'active' : ''; ?>"><span class="wo-stat-num"><?php echo $stats['open']; ?></span><span class="wo-stat-lbl">Open</span></a>
                <a href="work_orders.php?status=in_progress<?php echo $search ? '&search='.urlencode($search) : ''; ?><?php echo $filter_type ? '&type='.urlencode($filter_type) : ''; ?><?php echo $filter_pri ? '&priority='.urlencode($filter_pri) : ''; ?>" class="wo-stat in_progress <?php echo $filter_sts === 'in_progress' ? 'active' : ''; ?>"><span class="wo-stat-num"><?php echo $stats['in_progress']; ?></span><span class="wo-stat-lbl">In Progress</span></a>
                <a href="work_orders.php?status=waiting_parts<?php echo $search ? '&search='.urlencode($search) : ''; ?><?php echo $filter_type ? '&type='.urlencode($filter_type) : ''; ?><?php echo $filter_pri ? '&priority='.urlencode($filter_pri) : ''; ?>" class="wo-stat waiting <?php echo $filter_sts === 'waiting_parts' ? 'active' : ''; ?>"><span class="wo-stat-num"><?php echo $stats['waiting_parts']; ?></span><span class="wo-stat-lbl">Waiting Parts</span></a>
                <?php if ($filter_sts): ?>
                <a href="work_orders.php<?php echo ($search || $filter_type || $filter_pri) ? '?'.http_build_query(array_filter(['search'=>$search,'type'=>$filter_type,'priority'=>$filter_pri])) : ''; ?>" class="wo-stat-clear">&times; Show All</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Table -->
        <div class="wo-table-wrap">
            <div class="wo-table-header">
                <div class="wo-table-title-group">
                    <h3 class="wo-table-title">
                        <?php echo $history_mode ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" style="vertical-align:middle;margin-right:5px"><path d="M9 6h11M9 12h11M9 18h11M4 6h.01M4 12h.01M4 18h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>Order History' : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" style="vertical-align:middle;margin-right:5px"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>Work Orders'; ?>
                    </h3>
                    <span class="wo-table-badge"><?php echo count($work_orders); ?> order<?php echo count($work_orders) !== 1 ? 's' : ''; ?></span>
                </div>
                <div class="wo-table-actions">
                    <?php if ($history_mode): ?>
                        <a href="work_orders.php" class="btn-wo-secondary"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:5px"><path d="M19 12H5M5 12l7 7M5 12l7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> Active Orders</a>
                    <?php else: ?>
                        <a href="work_orders.php?history=1" class="btn-wo-secondary"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" style="vertical-align:middle;margin-right:5px"><path d="M9 6h11M9 12h11M9 18h11M4 6h.01M4 12h.01M4 18h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>History</a>
                        <button class="btn-wo-primary" onclick="openCreateModal()">+ Create New Order</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="wo-table-scroll">
                <table class="wo-table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Order Number</th><th>Type</th><th>Priority</th>
                            <th>Status</th><th>Asset</th><th>Due Date</th>
                            <th>Created By</th><th>Created At</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($work_orders)): ?>
                            <tr><td colspan="10" class="wo-table-empty">No work orders found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($work_orders as $wo): ?>
                                <tr class="wo-row priority-row-<?php echo $wo['priority']; ?>">
                                    <td class="td-id">#<?php echo $wo['id']; ?></td>
                                    <td class="td-code">
                                        <a href="work_orders.php?view=<?php echo $wo['id']; ?>" class="wo-link">
                                            <?php echo htmlspecialchars($wo['order_number'] ?? $wo['code']); ?>
                                        </a>
                                    </td>
                                    <td><span class="wo-badge-type type-<?php echo $wo['type']; ?>"><?php echo $WO_TYPES[$wo['type']] ?? ucfirst($wo['type']); ?></span></td>
                                    <td><span class="wo-badge-priority pri-<?php echo $wo['priority']; ?>"><?php echo $WO_PRIORITIES[$wo['priority']] ?? ucfirst($wo['priority']); ?></span></td>
                                    <td><span class="wo-badge-status sts-<?php echo $wo['status']; ?>"><?php echo $WO_STATUSES[$wo['status']] ?? ucfirst($wo['status']); ?></span></td>
                                    <td class="td-asset">
                                        <?php if ($wo['asset_name']): ?>
                                            <a href="assets.php?view=<?php echo $wo['machine_id']; ?>" class="asset-link"><?php echo htmlspecialchars($wo['asset_name']); ?></a>
                                        <?php else: ?>—<?php endif; ?>
                                    </td>
                                    <td class="td-due <?php
                                        if (!empty($wo['due_date'])) {
                                            if ($wo['due_date'] < date('Y-m-d')) echo 'due-overdue';
                                            elseif ($wo['due_date'] <= date('Y-m-d', strtotime('+3 days'))) echo 'due-soon';
                                        }
                                    ?>">
                                        <?php if (!empty($wo['due_date'])): ?>
                                            <?php if ($wo['due_date'] < date('Y-m-d')): ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="1.8"/><path d="M12 9v4M12 17h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg><?php echo $wo['due_date']; ?>
                                            <?php elseif ($wo['due_date'] <= date('Y-m-d', strtotime('+3 days'))): ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:3px"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg><?php echo $wo['due_date']; ?>
                                            <?php else: ?><?php echo $wo['due_date']; ?><?php endif; ?>
                                        <?php else: ?>—<?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($wo['created_by'] ?? 'System'); ?></td>
                                    <td class="td-date"><?php echo $wo['date_register'] ? date('Y-m-d H:i', strtotime($wo['date_register'])) : '—'; ?></td>
                                    <td class="td-actions">
                                        <a href="work_orders.php?view=<?php echo $wo['id']; ?>" class="btn-view">View</a>
                                        <button class="btn-del" onclick="openDeleteModal(<?php echo $wo['id']; ?>, '<?php echo htmlspecialchars(addslashes($wo['order_number'] ?? $wo['code'])); ?>')">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Modal -->
        <div class="modal-overlay" id="createModal">
            <div class="modal-box large">
                <div class="modal-header">
                    <h3>+ Create New Work Order</h3><button class="modal-close" onclick="closeModal('createModal')">&times;</button>
                </div>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="create_wo">
                    <div class="modal-body-form">
                        <div class="autoid-note"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" stroke="currentColor" stroke-width="1.8"/><circle cx="7" cy="7" r="1.5" fill="currentColor"/></svg>Order Number will be generated automatically (WO-YYYYMMDD-XXXX)</div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">Asset *</label>
                                <select name="machine_id" class="form-input" required>
                                    <option value="">— Select Asset —</option>
                                    <?php foreach ($all_assets as $a): ?>
                                        <option value="<?php echo $a['id']; ?>"><?php echo htmlspecialchars($a['name']); ?> (<?php echo htmlspecialchars($a['code']); ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-row-2">
                                <div class="form-group">
                                    <label class="form-label">Opened Date</label>
                                    <input type="date" name="opened_date" class="form-input" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Due Date (Optional)</label>
                                    <input type="date" name="due_date" class="form-input" min="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-input">
                                    <?php foreach ($WO_TYPES as $v => $l): ?>
                                        <option value="<?php echo $v; ?>"><?php echo $l; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Priority</label>
                                <select name="priority" class="form-input">
                                    <?php foreach ($WO_PRIORITIES as $v => $l): ?>
                                        <option value="<?php echo $v; ?>" <?php echo $v === 'medium' ? 'selected' : ''; ?>><?php echo $l; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description *</label>
                            <textarea name="description" class="form-input" rows="4" placeholder="Describe the work to be done..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer-form">
                        <button type="button" class="btn-cancel" onclick="closeModal('createModal')">Cancel</button>
                        <button type="submit" class="btn-save">Create Work Order</button>
                    </div>
                </form>
            </div>
        </div>

    <?php endif; ?>

    <!-- Delete Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box small">
            <div class="modal-header">
                <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>Delete Work Order</h3><button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
            </div>
            <div class="modal-body-form">
                <p class="delete-confirm-text">Are you sure you want to delete <strong id="deleteWoName"></strong>? This cannot be undone.</p>
            </div>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="action" value="delete_wo">
                <input type="hidden" name="id" id="deleteWoId">
                <div class="modal-footer-form">
                    <button type="button" class="btn-cancel" onclick="closeModal('deleteModal')">Cancel</button>
                    <button type="submit" class="btn-delete">Delete</button>
                </div>
            </form>
        </div>
    </div>

    </div><!-- /main-content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openCreateModal() { document.getElementById('createModal').classList.add('active'); }
    function openEditModal()   { document.getElementById('editModal').classList.add('active'); }
    function closeModal(id)    { document.getElementById(id).classList.remove('active'); }
    function openModal(id)     { document.getElementById(id).classList.add('active'); }

    function updateStockInfo(sel) {
        const opt  = sel.options[sel.selectedIndex];
        const info = document.getElementById('stockInfo');
        const qty  = document.getElementById('partQty');
        if (!opt.value) { info.style.display = 'none'; return; }
        const stock = opt.dataset.stock;
        const unit  = opt.dataset.unit;
        info.style.display = 'block';
        info.innerHTML = `Available: <strong>${stock} ${unit}</strong>`;
        info.className = 'stock-info-line ' + (parseInt(stock) === 0 ? 'si-out' : parseInt(stock) <= 5 ? 'si-low' : 'si-ok');
        qty.max = stock;
    }

    function openDeleteModal(id, name) {
        document.getElementById('deleteWoId').value  = id;
        document.getElementById('deleteWoName').textContent = name;
        document.getElementById('deleteModal').classList.add('active');
    }

    document.querySelectorAll('.modal-overlay').forEach(m => {
        m.addEventListener('click', e => { if (e.target === m) m.classList.remove('active'); });
    });
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