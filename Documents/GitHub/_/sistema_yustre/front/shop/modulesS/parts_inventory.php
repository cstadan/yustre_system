<?php require_once '../controllersS/parts_inventory_controller.php'; ?>
<?php $user_name = $_SESSION['user_name'] ?? 'User'; $user_rol = $_SESSION['user_rol'] ?? 'shop'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parts Inventory — Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/style_dashboard_shop.css">
    <link rel="stylesheet" href="../../../assets/css/style_parts_inventory_shop.css">
</head>

<body class="shop-module">
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    <button class="sidebar-toggle-btn" id="sidebarToggleBtn" onclick="toggleSidebar()" title="Toggle sidebar">&#x2039;</button>
    <div class="topbar"><button class="topbar-hamburger" onclick="openSidebar()" aria-label="Open menu"><span></span><span></span><span></span></button><span class="topbar-title">Yustre &mdash; Shop</span></div>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M3 10.5L12 3L21 10.5V20C21 20.552 20.552 21 20 21H15V15H9V21H4C3.448 21 3 20.552 3 20V10.5Z" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            <div class="sidebar-brand"><span class="sidebar-brand-name">Sistema Yustre</span><span class="sidebar-brand-sub">Shop Module</span></div>
            <button class="sidebar-close-btn" onclick="closeSidebar()" aria-label="Close menu"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
        </div>
        <ul class="nav-menu">
            <li class="nav-item"><a href="../../../dashboards/dashboard_shop.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><rect x="3" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="3" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/></svg></span><span class="nav-text">Dashboard</span></a></li>
            <li class="nav-item"><a href="work_orders.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 7H16M8 11H16M8 15H12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Work Orders</span></a></li>
            <li class="nav-item"><a href="assets.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><rect x="2" y="7" width="10" height="14" rx="1.5" stroke="currentColor" stroke-width="1.8"/><path d="M12 9h6a2 2 0 012 2v8a2 2 0 01-2 2h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M5 4V2M9 4V2M7 7V4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Assets</span></a></li>
            <li class="nav-item"><a href="parts_inventory.php" class="nav-link active"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M20 7H4C2.9 7 2 7.9 2 9V19C2 20.1 2.9 21 4 21H20C21.1 21 22 20.1 22 19V9C22 7.9 21.1 7 20 7Z" stroke="currentColor" stroke-width="1.8"/><path d="M16 7V5C16 3.9 15.1 3 14 3H10C8.9 3 8 3.9 8 5V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M12 12V16M10 14H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Parts Inventory</span></a></li>
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

    <!-- Theme-Toggle -->
    <script src="../../../assets/js/theme-toggle.js"></script>

    <div class="main-content" id="mainContent">

        <?php if ($view_part): ?>
        <!-- ════════════════════  DETAIL VIEW  ════════════════════ -->

        <div class="module-header-panel">
            <div>
                <h2 class="module-header-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" style="vertical-align:middle;margin-right:5px"><rect x="2" y="7" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Parts Inventory</h2>
                <p class="module-header-sub">Manage parts and consumables stock</p>
            </div>
        </div>

        <div class="prt-detail-wrap">

            <?php if ($error):   ?><div class="alert-msg error"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M4.93 4.93l14.14 14.14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> <?php echo $error; ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert-msg success"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> <?php echo $success; ?></div><?php endif; ?>

            <!-- Info Card -->
            <div class="prt-info-card">
                <div class="prt-detail-header">
                    <a href="parts_inventory.php" class="btn-back"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:5px"><path d="M19 12H5M5 12l7 7M5 12l7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> Back to Inventory</a>
                    <h1 class="prt-detail-title"><?php echo htmlspecialchars($view_part['name']); ?></h1>
                </div>
                <div class="prt-info-grid">
                    <div class="prt-info-row"><span class="prt-info-label">Code</span><span class="prt-info-value"><span class="code-badge"><?php echo htmlspecialchars($view_part['code']); ?></span></span></div>
                    <div class="prt-info-row"><span class="prt-info-label">Category</span><span class="prt-info-value"><?php echo htmlspecialchars($view_part['category'] ?: '—'); ?></span></div>
                    <div class="prt-info-row"><span class="prt-info-label">Unit</span><span class="prt-info-value"><?php echo htmlspecialchars($view_part['unit']); ?></span></div>
                    <div class="prt-info-row"><span class="prt-info-label">Min. Stock</span><span class="prt-info-value"><?php echo $view_part['min_stock']; ?> <?php echo $view_part['unit']; ?></span></div>
                    <div class="prt-info-row">
                        <span class="prt-info-label">Current Stock</span>
                        <span class="prt-info-value">
                            <?php
                            $stk = $view_part['stock'];
                            $min = $view_part['min_stock'];
                            if ($stk == 0) echo '<span class="stk-badge stk-out">OUT OF STOCK</span>';
                            elseif ($stk <= $min) echo '<span class="stk-badge stk-low">' . $stk . ' ' . $view_part['unit'] . ' — LOW</span>';
                            else echo '<span class="stk-badge stk-ok">' . $stk . ' ' . $view_part['unit'] . '</span>';
                            ?>
                        </span>
                    </div>
                    <?php if ($view_part['comments']): ?>
                        <div class="prt-info-row full"><span class="prt-info-label">Comments</span><span class="prt-info-value"><?php echo nl2br(htmlspecialchars($view_part['comments'])); ?></span></div>
                    <?php endif; ?>
                </div>
                <div class="prt-actions">
                    <button class="btn-prt-in"     onclick="openModal('inModal')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M12 3v12M8 11l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 19h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Stock In</button>
                    <button class="btn-prt-out"    onclick="openModal('outModal')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M12 21V9M8 13l4-4 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 5h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Stock Out</button>
                    <button class="btn-prt-edit"   onclick="openModal('editModal')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Edit</button>
                    <button class="btn-prt-delete" onclick="openDeleteModal(<?php echo $view_part['id']; ?>, '<?php echo addslashes($view_part['name']); ?>')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Delete</button>
                </div>
            </div>

            <!-- Movement History -->
            <div class="prt-history-card">
                <div class="prt-history-header">
                    <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" style="vertical-align:middle;margin-right:5px"><path d="M9 6h11M9 12h11M9 18h11M4 6h.01M4 12h.01M4 18h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Movement History <span class="prt-count"><?php echo count($movements); ?></span></h3>
                </div>
                <div class="prt-history-body">
                    <?php if (empty($movements)): ?>
                        <p class="prt-empty">No movements recorded yet.</p>
                    <?php else: ?>
                        <div class="prt-history-scroll">
                            <table class="prt-history-table">
                                <thead>
                                    <tr>
                                        <th>Date</th><th>Type</th><th>Qty</th>
                                        <th>Reason</th><th>Work Order</th><th>By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($movements as $mv): ?>
                                        <tr>
                                            <td class="td-date"><?php echo substr($mv['date'], 0, 16); ?></td>
                                            <td><span class="mv-badge mv-<?php echo $mv['type']; ?>"><?php echo strtoupper($mv['type']); ?></span></td>
                                            <td class="td-qty <?php echo $mv['type'] === 'in' ? 'qty-in' : 'qty-out'; ?>">
                                                <?php echo $mv['type'] === 'in' ? '+' : '-'; ?><?php echo $mv['quantity']; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($mv['reason'] ?? '—'); ?></td>
                                            <td><?php if ($mv['wo_code']): ?><span class="wo-ref"><?php echo htmlspecialchars($mv['order_number'] ?? $mv['wo_code']); ?></span><?php else: ?>—<?php endif; ?></td>
                                            <td><?php echo htmlspecialchars($mv['created_by'] ?? 'System'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Stock In Modal -->
        <div class="modal-overlay" id="inModal">
            <div class="modal-box small">
                <div class="modal-header">
                    <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M12 3v12M8 11l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 19h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Stock In</h3><button class="modal-close" onclick="closeModal('inModal')">&times;</button>
                </div>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="stock_in">
                    <input type="hidden" name="id" value="<?php echo $view_part['id']; ?>">
                    <div class="modal-body-form">
                        <div class="form-group"><label class="form-label">Quantity *</label><input type="number" name="quantity" class="form-input" min="1" required placeholder="0"></div>
                        <div class="form-group"><label class="form-label">Reason</label><input type="text" name="reason" class="form-input" value="Purchase/Receipt" placeholder="e.g. Purchase order #123"></div>
                    </div>
                    <div class="modal-footer-form">
                        <button type="button" class="btn-cancel" onclick="closeModal('inModal')">Cancel</button>
                        <button type="submit" class="btn-in">+ Add Stock</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stock Out Modal -->
        <div class="modal-overlay" id="outModal">
            <div class="modal-box small">
                <div class="modal-header">
                    <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M12 21V9M8 13l4-4 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 5h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Stock Out</h3><button class="modal-close" onclick="closeModal('outModal')">&times;</button>
                </div>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="stock_out">
                    <input type="hidden" name="id" value="<?php echo $view_part['id']; ?>">
                    <div class="modal-body-form">
                        <p class="stock-note">Available: <strong><?php echo $view_part['stock']; ?> <?php echo $view_part['unit']; ?></strong></p>
                        <div class="form-group"><label class="form-label">Quantity *</label><input type="number" name="quantity" class="form-input" min="1" max="<?php echo $view_part['stock']; ?>" required placeholder="0"></div>
                        <div class="form-group"><label class="form-label">Reason</label><input type="text" name="reason" class="form-input" placeholder="e.g. Used in repair, Damaged, etc."></div>
                    </div>
                    <div class="modal-footer-form">
                        <button type="button" class="btn-cancel" onclick="closeModal('outModal')">Cancel</button>
                        <button type="submit" class="btn-out">- Remove Stock</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal-overlay" id="editModal">
            <div class="modal-box large">
                <div class="modal-header">
                    <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Edit Part</h3><button class="modal-close" onclick="closeModal('editModal')">&times;</button>
                </div>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="edit_part">
                    <input type="hidden" name="id" value="<?php echo $view_part['id']; ?>">
                    <div class="modal-body-form">
                        <div class="autoid-note"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" stroke="currentColor" stroke-width="1.8"/><circle cx="7" cy="7" r="1.5" fill="currentColor"/></svg> Code: <strong><?php echo htmlspecialchars($view_part['code']); ?></strong></div>
                        <?php echo build_part_form($view_part); ?>
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
            <div>
                <h2 class="module-header-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" style="vertical-align:middle;margin-right:5px"><rect x="2" y="7" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Parts Inventory</h2>
                <p class="module-header-sub">Manage parts and consumables stock</p>
            </div>
        </div>

        <?php if ($error):   ?><div class="alert-msg error"   style="margin-bottom:16px"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M4.93 4.93l14.14 14.14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> <?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert-msg success" style="margin-bottom:16px"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> <?php echo $success; ?></div><?php endif; ?>

        <!-- Stats strip -->
        <?php
        $db2 = get_shop_db();
        $s_ok = $s_low = $s_out = 0;
        if ($db2) {
            $r = $db2->query("SELECT SUM(stock=0) as s_out, SUM(stock>0 AND stock<=min_stock) as s_low, SUM(stock>min_stock) as s_ok FROM parts");
            if ($r) { $row = $r->fetch_assoc(); $s_ok = $row['s_ok'] ?? 0; $s_low = $row['s_low'] ?? 0; $s_out = $row['s_out'] ?? 0; }
            $db2->close();
        }
        ?>
        <div class="prt-stats-strip">
            <a href="parts_inventory.php?stock_filter=ok<?php echo $filter_cat ? '&category=' . urlencode($filter_cat) : ''; ?>" class="prt-stat ok <?php echo $filter_stk === 'ok' ? 'active' : ''; ?>">
                <span class="prt-stat-num"><?php echo $s_ok; ?></span><span class="prt-stat-lbl"> In Stock</span>
            </a>
            <a href="parts_inventory.php?stock_filter=low<?php echo $filter_cat ? '&category=' . urlencode($filter_cat) : ''; ?>" class="prt-stat low <?php echo $filter_stk === 'low' ? 'active' : ''; ?>">
                <span class="prt-stat-num"><?php echo $s_low; ?></span><span class="prt-stat-lbl"> Low Stock</span>
            </a>
            <a href="parts_inventory.php?stock_filter=out<?php echo $filter_cat ? '&category=' . urlencode($filter_cat) : ''; ?>" class="prt-stat out <?php echo $filter_stk === 'out' ? 'active' : ''; ?>">
                <span class="prt-stat-num"><?php echo $s_out; ?></span><span class="prt-stat-lbl"> Out of Stock</span>
            </a>
            <?php if ($filter_stk): ?>
                <a href="parts_inventory.php<?php echo $filter_cat ? '?category=' . urlencode($filter_cat) : ''; ?>" class="prt-stat-clear">&times; Show All</a>
            <?php endif; ?>
        </div>

        <!-- Filters -->
        <div class="prt-filters">
            <form method="GET" id="filterForm">
                <div class="prt-filter-row">
                    <div class="prt-filter-field">
                        <label class="filter-label"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Search by code, name...</label>
                        <input type="text" name="search" class="search-input" placeholder="Type to search..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="prt-filter-field">
                        <label class="filter-label"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" stroke="currentColor" stroke-width="1.8"/></svg> Filter by Category</label>
                        <select name="category" class="filter-select" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <?php foreach (PART_CATEGORIES as $c): ?>
                                <option value="<?php echo htmlspecialchars($c); ?>" <?php echo $filter_cat === $c ? 'selected' : ''; ?>><?php echo htmlspecialchars($c); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if ($filter_stk): ?><input type="hidden" name="stock_filter" value="<?php echo htmlspecialchars($filter_stk); ?>"><?php endif; ?>
                    <div class="prt-filter-field" style="align-items:flex-end; gap:6px;">
                        <?php if ($search || $filter_cat): ?>
                            <a href="parts_inventory.php<?php echo $filter_stk ? '?stock_filter=' . $filter_stk : ''; ?>" class="btn-clear">&times; Clear</a>
                        <?php endif; ?>
                        <button type="submit" class="btn-search">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="prt-table-wrap">
            <div class="prt-table-header">
                <div class="prt-table-title-group">
                    <h3 class="prt-table-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" style="vertical-align:middle;margin-right:5px"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Parts List</h3>
                    <span class="prt-table-badge"><?php echo count($parts); ?> part<?php echo count($parts) !== 1 ? 's' : ''; ?></span>
                </div>
                <div class="prt-table-actions">
                    <button class="btn-prt-primary" onclick="openModal('addModal')">+ Add Part</button>
                </div>
            </div>
            <div class="prt-table-scroll">
                <table class="prt-table">
                    <thead>
                        <tr>
                            <th>Code</th><th>Name</th><th>Category</th>
                            <th>Unit</th><th>Stock</th><th>Min. Stock</th>
                            <th>Comments</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($parts)): ?>
                            <tr><td colspan="8" class="prt-table-empty">No parts found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($parts as $p):
                                $stk = $p['stock']; $min = $p['min_stock'];
                                if ($stk == 0)        { $stk_class = 'stk-row-out'; $stk_html = '<span class="stk-badge stk-out">OUT</span>'; }
                                elseif ($stk <= $min) { $stk_class = 'stk-row-low'; $stk_html = '<span class="stk-badge stk-low">' . $stk . '</span>'; }
                                else                  { $stk_class = '';             $stk_html = '<span class="stk-badge stk-ok">'  . $stk . '</span>'; }
                            ?>
                                <tr class="<?php echo $stk_class; ?>">
                                    <td><span class="code-sm"><?php echo htmlspecialchars($p['code']); ?></span></td>
                                    <td class="td-name"><a href="parts_inventory.php?view=<?php echo $p['id']; ?>" class="part-link"><?php echo htmlspecialchars($p['name']); ?></a></td>
                                    <td><span class="cat-badge"><?php echo htmlspecialchars($p['category'] ?: '—'); ?></span></td>
                                    <td><?php echo htmlspecialchars($p['unit']); ?></td>
                                    <td><?php echo $stk_html; ?></td>
                                    <td><?php echo $min; ?></td>
                                    <td class="td-comments"><?php echo htmlspecialchars(mb_strimwidth($p['comments'] ?? '', 0, 40, '…')); ?></td>
                                    <td><a href="parts_inventory.php?view=<?php echo $p['id']; ?>" class="btn-view">View</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Modal -->
        <div class="modal-overlay" id="addModal">
            <div class="modal-box large">
                <div class="modal-header">
                    <h3>+ Add Part</h3><button class="modal-close" onclick="closeModal('addModal')">&times;</button>
                </div>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="action" value="add_part">
                    <div class="modal-body-form">
                        <div class="autoid-note"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" stroke="currentColor" stroke-width="1.8"/><circle cx="7" cy="7" r="1.5" fill="currentColor"/></svg> Part Code will be generated automatically (PRT-XXXX)</div>
                        <?php echo build_part_form(); ?>
                        <div class="form-group">
                            <label class="form-label">Initial Stock</label>
                            <input type="number" name="stock" class="form-input" min="0" value="0" placeholder="0">
                        </div>
                    </div>
                    <div class="modal-footer-form">
                        <button type="button" class="btn-cancel" onclick="closeModal('addModal')">Cancel</button>
                        <button type="submit" class="btn-save">Add Part</button>
                    </div>
                </form>
            </div>
        </div>

    <?php endif; ?>

    <!-- Delete Modal (shared) -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box small">
            <div class="modal-header">
                <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:4px"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg> Delete Part</h3><button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
            </div>
            <div class="modal-body-form">
                <p class="delete-confirm-text">Delete <strong id="delPartName"></strong>? This will also remove all movement history.</p>
            </div>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="action" value="delete_part">
                <input type="hidden" name="id" id="delPartId">
                <div class="modal-footer-form">
                    <button type="button" class="btn-cancel" onclick="closeModal('deleteModal')">Cancel</button>
                    <button type="submit" class="btn-delete">Delete</button>
                </div>
            </form>
        </div>
    </div>

    </div><!-- /main-content -->

    <?php
    function build_part_form($part = null) {
        $v = $part ?? [];
        ob_start();
    ?>
        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Name *</label>
                <input type="text" name="name" class="form-input" value="<?php echo htmlspecialchars($v['name'] ?? ''); ?>" placeholder="e.g. Oil Filter 3/4" required>
            </div>
            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category" class="form-input">
                    <option value="">— Select —</option>
                    <?php foreach (PART_CATEGORIES as $c): ?>
                        <option value="<?php echo htmlspecialchars($c); ?>" <?php echo ($v['category'] ?? '') === $c ? 'selected' : ''; ?>><?php echo htmlspecialchars($c); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Unit</label>
                <select name="unit" class="form-input">
                    <?php foreach (PART_UNITS as $u): ?>
                        <option value="<?php echo $u; ?>" <?php echo ($v['unit'] ?? 'pcs') === $u ? 'selected' : ''; ?>><?php echo $u; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Min. Stock (alert threshold)</label>
                <input type="number" name="min_stock" class="form-input" min="0" value="<?php echo $v['min_stock'] ?? '0'; ?>" placeholder="0">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Comments</label>
            <textarea name="comments" class="form-input" rows="2" placeholder="Notes, supplier, part number..."><?php echo htmlspecialchars($v['comments'] ?? ''); ?></textarea>
        </div>
    <?php
        return ob_get_clean();
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openModal(id)  { document.getElementById(id).classList.add('active'); }
        function closeModal(id) { document.getElementById(id).classList.remove('active'); }
        function openDeleteModal(id, name) {
            document.getElementById('delPartId').value = id;
            document.getElementById('delPartName').textContent = name;
            openModal('deleteModal');
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