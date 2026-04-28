<?php
// Auth: require login + shop or admin role
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) { header('Location: ../login/login.php'); exit(); }
if (!in_array($_SESSION['user_rol'], ['admin', 'shop'])) { header('Location: ../login/login.php?error=invalid_role'); exit(); }

// Load shop alerts (low stock, overdue WOs, etc.)
require_once __DIR__ . '/../shared/alerts_shop_controller.php';

$user_name = $_SESSION['user_name'] ?? 'User';
$user_rol  = $_SESSION['user_rol']  ?? 'shop';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style_dashboard_shop.css">
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
        <button class="topbar-hamburger" onclick="openSidebar()" aria-label="Open menu"><span></span><span></span><span></span></button>
        <span class="topbar-title">Yustre &mdash; Shop</span>
    </div>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M3 10.5L12 3L21 10.5V20C21 20.552 20.552 21 20 21H15V15H9V21H4C3.448 21 3 20.552 3 20V10.5Z" stroke="rgba(255,220,160,0.9)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            <div class="sidebar-brand">
                <span class="sidebar-brand-name">Sistema Yustre</span>
                <span class="sidebar-brand-sub">Shop Module</span>
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
            <li class="nav-item"><a href="dashboard_shop.php" class="nav-link active"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><rect x="3" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="3" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/></svg></span><span class="nav-text">Dashboard</span></a></li>
            <li class="nav-item"><a href="../front/shop/modulesS/work_orders.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 7H16M8 11H16M8 15H12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Work Orders</span></a></li>
            <li class="nav-item"><a href="../front/shop/modulesS/assets.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><rect x="2" y="7" width="10" height="14" rx="1.5" stroke="currentColor" stroke-width="1.8"/><path d="M12 9h6a2 2 0 012 2v8a2 2 0 01-2 2h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M5 4V2M9 4V2M7 7V4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Assets</span></a></li>
            <li class="nav-item"><a href="../front/shop/modulesS/parts_inventory.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M20 7H4C2.9 7 2 7.9 2 9V19C2 20.1 2.9 21 4 21H20C21.1 21 22 20.1 22 19V9C22 7.9 21.1 7 20 7Z" stroke="currentColor" stroke-width="1.8"/><path d="M16 7V5C16 3.9 15.1 3 14 3H10C8.9 3 8 3.9 8 5V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M12 12V16M10 14H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Parts Inventory</span></a></li>

            <!-- Admin-only items -->
            <?php if ($_SESSION['user_rol'] === 'admin'): ?>
            <li class="nav-item bottom"><a href="dashboard_admin.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M12 3L4 7V12C4 16.418 7.582 20.398 12 21C16.418 20.398 20 16.418 20 12V7L12 3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span><span class="nav-text">Admin Panel</span></a></li>
            <li class="nav-item">
                <a href="employees.php" class="nav-link"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M2 21v-2a4 4 0 014-4h6a4 4 0 014 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M19 8v6M16 11h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Employees</span></a>
            </li>
            <?php endif; ?>

            <!-- Logout: pushed to bottom when not admin -->
            <li class="nav-item <?php echo $_SESSION['user_rol'] !== 'admin' ? 'bottom' : ''; ?>">
                <a href="../logout.php" class="nav-link logout"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M14 3H6C5.448 3 5 3.448 5 4V20C5 20.552 5.448 21 6 21H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 8L21 12L16 16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12H9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Logout</span></a>
            </li>
        </ul>

        <!-- Footer: user info + theme toggle -->
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

<!-- Main content -->
<div class="main-content" id="mainContent">

    <!-- Welcome header -->
    <div class="welcome-section">
        <h2 class="welcome-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" style="vertical-align:middle;margin-right:8px"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z" stroke="#f5e6c8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
        <p class="welcome-subtitle">Shop &amp; Workshop Management</p>
        <div class="user-badge"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" style="vertical-align:middle;margin-right:6px"><rect x="2" y="4" width="20" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M2 7l10 7 10-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg><?php echo htmlspecialchars($_SESSION['user_email']); ?></div>
    </div>

    <!-- Dashboard cards: calendar + alerts -->
    <div class="dashboard-grid-home">
        <div class="dashboard-card-large">
            <div class="card-header-custom">
                <h3 class="card-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" style="vertical-align:middle;margin-right:8px"><rect x="3" y="4" width="18" height="18" rx="2" stroke="#f5e6c8" stroke-width="1.8"/><path d="M16 2v4M8 2v4M3 10h18" stroke="#f5e6c8" stroke-width="1.8" stroke-linecap="round"/></svg>Activity Calendar</h3>
            </div>
            <!-- Populated by calendar.js -->
            <div class="card-body-custom calendar-body" id="calendarBody"><p class="placeholder-text">Loading calendar...</p></div>
        </div>

        <div class="dashboard-card-large">
            <div class="card-header-custom alerts-header">
                <h3 class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" style="vertical-align:middle;margin-right:8px"><path d="M12 2a7 7 0 017 7c0 3.87-1.5 6-3 8H8c-1.5-2-3-4.13-3-8a7 7 0 017-7z" stroke="#f5e6c8" stroke-width="1.8"/><path d="M9 19a3 3 0 006 0" stroke="#f5e6c8" stroke-width="1.8" stroke-linecap="round"/></svg>Alerts
                    <?php if (!empty($alerts)): ?><span class="alerts-badge"><?php echo count($alerts); ?></span><?php endif; ?>
                </h3>
            </div>
            <div class="card-body-custom alerts-body">
                <?php if (empty($alerts)): ?>
                    <div class="alerts-empty">
                        <span class="alerts-empty-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        <p class="alerts-empty-text">All good! No alerts at this time.</p>
                    </div>
                <?php else: ?>
                    <!-- Alert list from alerts_shop_controller.php -->
                    <div class="alerts-list">
                        <?php foreach ($alerts as $alert): ?>
                            <div class="alert-item alert-<?php echo $alert['severity']; ?>">
                                <span class="alert-icon"><?php echo $alert['icon']; ?></span>
                                <div class="alert-content">
                                    <span class="alert-label"><?php echo $alert['label']; ?></span>
                                    <p class="alert-message"><?php echo $alert['message']; ?></p>
                                </div>
                                <span class="alert-module"><?php echo $alert['module']; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Weather widget -->
    <div class="dashboard-card-large weather-card" style="margin-top:25px;min-height:auto;">
        <div class="card-header-custom" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <h3 class="card-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" style="vertical-align:middle;margin-right:8px"><path d="M18 10a6 6 0 00-12 0c0 3 2 5 2 5h8s2-2 2-5z" stroke="#f5e6c8" stroke-width="1.8"/><path d="M12 2v2M4.22 4.22l1.42 1.42M20 12h2M2 12h2M19.78 4.22l-1.42 1.42" stroke="#f5e6c8" stroke-width="1.8" stroke-linecap="round"/></svg>Weather &mdash; <span id="weatherLocation">Loading...</span></h3>
            <div class="weather-search-wrap">
                <input type="text" id="cityInput" class="weather-search-input" placeholder="Search city..."/>
                <button onclick="searchCity()" class="weather-search-btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/><path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
            </div>
        </div>
        <!-- City search results dropdown -->
        <div id="cityResults" class="city-results" style="display:none;"></div>
        <!-- Populated by weather.js -->
        <div class="card-body-custom weather-body" id="weatherBody"><p class="placeholder-text">Loading weather data...</p></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/weather.js"></script>
<!-- Session user exposed for calendar.js -->
<script>const SESSION_USER = "<?php echo htmlspecialchars($_SESSION['user_email']); ?>";</script>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script src="../assets/js/calendar.js"></script>

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
        const c = sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('collapsed');
        localStorage.setItem('sidebarCollapsed', c);
        updateToggleBtn(c);
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
</script>
</body>
</html>