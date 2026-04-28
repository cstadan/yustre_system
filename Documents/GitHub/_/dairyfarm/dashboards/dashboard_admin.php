<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) { header('Location: ../login/login.php'); exit(); }
if ($_SESSION['user_rol'] !== 'admin') { header('Location: ../login/login.php?error=invalid_role'); exit(); }
$user_name  = $_SESSION['user_name']  ?? 'Admin';
$user_email = $_SESSION['user_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — Dairy Farm CS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style_green_theme.css">
    <style>
        :root { --sidebar-width: 250px; }
        body { background: var(--body-bg); }
        .nav-section-label { font-size: 10px; font-weight: 700; color: rgba(255,255,255,0.38); text-transform: uppercase; letter-spacing: 1.2px; padding: 10px 18px 4px; display: block; white-space: nowrap; overflow: hidden; }
        .sidebar.collapsed .nav-section-label { opacity: 0; height: 0; padding: 0; }
        .nav-section-divider { height: 1px; background: rgba(255,255,255,0.07); margin: 6px 12px; }
        .nav-link.section-clinic .nav-icon { color: #4dd68a; }
        .nav-link.section-shop   .nav-icon { color: #60b8ff; }
        .nav-link.section-emp    .nav-icon { color: #f6c85f; }
        .nav-link.section-clinic:hover, .nav-link.section-clinic.active { background: rgba(77,214,138,0.15); }
        .nav-link.section-shop:hover,   .nav-link.section-shop.active   { background: rgba(96,184,255,0.15); }
        .nav-link.section-emp:hover,    .nav-link.section-emp.active     { background: rgba(246,200,95,0.15); }
        .admin-welcome { background: var(--welcome-bg); border: 1px solid var(--card-border); border-radius: 14px; padding: 28px 32px; margin-bottom: 26px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; }
        .admin-welcome-title { font-size: 22px; font-weight: 700; color: var(--text-main); margin: 0 0 4px; display: flex; align-items: center; gap: 10px; }
        .admin-welcome-sub { color: var(--text-muted); font-size: 13.5px; margin: 0; }
        .admin-badge { background: rgba(40,167,69,0.12); color: var(--accent); border: 1px solid rgba(40,167,69,0.25); padding: 6px 16px; border-radius: 20px; font-size: 12.5px; font-weight: 600; }
        .section-cards-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .section-card { background: var(--welcome-bg); border: 1px solid var(--card-border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.06); transition: box-shadow 0.25s ease, transform 0.25s ease; }
        .section-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.10); transform: translateY(-2px); }
        .section-card-header { padding: 18px 22px; display: flex; align-items: center; gap: 12px; }
        .section-card-header.clinic { background: linear-gradient(135deg, #1a5c2a, #28a745); }
        .section-card-header.shop   { background: linear-gradient(135deg, #0d3b6e, #1a73e8); }
        .section-card-header.emp    { background: linear-gradient(135deg, #7a5800, #d4a017); }
        .section-card-icon { width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,0.18); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .section-card-title { font-size: 17px; font-weight: 700; color: #fff; margin: 0 0 2px; }
        .section-card-sub { font-size: 12px; color: rgba(255,255,255,0.75); margin: 0; }
        .section-card-links { padding: 8px 0; }
        .section-card-link { display: flex; align-items: center; gap: 12px; padding: 11px 22px; color: var(--text-main); text-decoration: none; font-size: 13.5px; font-weight: 500; transition: background 0.15s ease; border-bottom: 1px solid var(--row-border); }
        .section-card-link:last-child { border-bottom: none; }
        .section-card-link:hover { background: var(--row-hover); }
        .link-icon { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .clinic-link .link-icon { background: rgba(40,167,69,0.1); color: #28a745; }
        .shop-link   .link-icon { background: rgba(26,115,232,0.1); color: #1a73e8; }
        .emp-link    .link-icon { background: rgba(212,160,23,0.1); color: #d4a017; }
        .link-arrow { margin-left: auto; color: var(--text-muted); opacity: 0; transition: opacity 0.15s, transform 0.15s; }
        .section-card-link:hover .link-arrow { opacity: 1; transform: translateX(3px); }
        @media (max-width: 768px) { .admin-welcome { padding: 20px; } .section-cards-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<script src="../assets/js/theme-toggle.js"></script>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
<button class="sidebar-toggle-btn" id="sidebarToggleBtn" onclick="toggleSidebar()" title="Toggle sidebar">&#x2039;</button>
<div class="topbar">
    <button class="topbar-hamburger" onclick="openSidebar()"><span></span><span></span><span></span></button>
    <span class="topbar-title">Dairy Farm CS &mdash; Admin</span>
</div>
<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M12 3L4 7V12C4 16.418 7.582 20.398 12 21C16.418 20.398 20 16.418 20 12V7L12 3Z" stroke="#fff" stroke-width="1.8" stroke-linejoin="round"/><path d="M9 12l2 2 4-4" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
        <div class="sidebar-brand"><span class="sidebar-brand-name">Dairy Farm CS</span><span class="sidebar-brand-sub">Admin Panel</span></div>
        <button class="sidebar-close-btn" onclick="closeSidebar()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
    </div>
    <ul class="nav-menu">
        <li class="nav-item"><a href="dashboard_admin.php" class="nav-link active"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><rect x="3" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="3" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/></svg></span><span class="nav-text">Dashboard</span></a></li>
        <li class="nav-item"><div class="nav-section-divider"></div></li>
        <!-- CLINIC -->
        <li class="nav-item"><span class="nav-section-label">Clinic</span></li>
        <li class="nav-item"><a href="dashboard_clinic.php" class="nav-link section-clinic"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><rect x="3" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="3" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/></svg></span><span class="nav-text">Clinic Dashboard</span></a></li>
        <li class="nav-item"><a href="../front/clinic/modulesC/cow_registry.php" class="nav-link section-clinic"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><path d="M6 8c0 0-1 12 6 12s6-12 6-12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M6 8c1-3 11-3 12 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M6 8L4 5M18 8l2-3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Cow Registry</span></a></li>
        <li class="nav-item"><a href="../front/clinic/modulesC/medicines_colostrum.php" class="nav-link section-clinic"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><rect x="1" y="6" width="22" height="12" rx="6" stroke="currentColor" stroke-width="1.8"/><line x1="12" y1="6" x2="12" y2="18" stroke="currentColor" stroke-width="1.8"/></svg></span><span class="nav-text">Med &amp; Colostrum</span></a></li>
        <li class="nav-item"><a href="../front/clinic/modulesC/calves.php" class="nav-link section-clinic"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><ellipse cx="12" cy="14" rx="6" ry="5" stroke="currentColor" stroke-width="1.8"/><path d="M9 9V6M15 9V6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="9" cy="5" r="1" fill="currentColor"/><circle cx="15" cy="5" r="1" fill="currentColor"/></svg></span><span class="nav-text">Calves</span></a></li>
        <li class="nav-item"><div class="nav-section-divider"></div></li>
        <!-- SHOP -->
        <li class="nav-item"><span class="nav-section-label">Shop</span></li>
        <li class="nav-item"><a href="dashboard_shop.php" class="nav-link section-shop"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><rect x="3" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="3" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/></svg></span><span class="nav-text">Shop Dashboard</span></a></li>
        <li class="nav-item"><a href="../front/shop/modulesS/assets.php" class="nav-link section-shop"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 12h8M12 8v8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Assets</span></a></li>
        <li class="nav-item"><a href="../front/shop/modulesS/work_orders.php" class="nav-link section-shop"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><path d="M9 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V9l-6-6z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9 3v6h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Work Orders</span></a></li>
        <li class="nav-item"><a href="../front/shop/modulesS/parts_inventory.php" class="nav-link section-shop"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><rect x="2" y="10" width="20" height="11" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 10V7C8 5.343 9.343 4 11 4H13C14.657 4 16 5.343 16 7V10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Parts &amp; Inventory</span></a></li>
        <li class="nav-item"><div class="nav-section-divider"></div></li>
        <!-- EMPLOYEES -->
        <li class="nav-item"><span class="nav-section-label">Employees</span></li>
        <li class="nav-item"><a href="employees.php" class="nav-link section-emp"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M2 21v-2a4 4 0 014-4h6a4 4 0 014 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M19 8v6M16 11h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Manage Employees</span></a></li>
        <li class="nav-item bottom"><a href="../logout.php" class="nav-link logout"><span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none"><path d="M14 3H6C5.448 3 5 3.448 5 4V20C5 20.552 5.448 21 6 21H14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 8L21 12L16 16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12H9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span class="nav-text">Logout</span></a></li>
    </ul>
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar"><?php echo strtoupper(substr($user_name,0,1)); ?></div>
            <div class="sidebar-user-info"><span class="sidebar-user-name"><?php echo htmlspecialchars($user_name); ?></span><span class="sidebar-user-role">Administrator</span></div>
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
    <div class="admin-welcome">
        <div>
            <h2 class="admin-welcome-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="26" height="26" fill="none"><path d="M12 3L4 7V12C4 16.418 7.582 20.398 12 21C16.418 20.398 20 16.418 20 12V7L12 3Z" stroke="#28a745" stroke-width="1.8" stroke-linejoin="round"/><path d="M9 12l2 2 4-4" stroke="#28a745" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>Admin Panel</h2>
            <p class="admin-welcome-sub">Welcome back, <strong><?php echo htmlspecialchars($user_name); ?></strong> &mdash; select a section to manage</p>
        </div>
        <div class="admin-badge"><?php echo htmlspecialchars($user_email); ?></div>
    </div>
    <div class="section-cards-row">
        <!-- CLINIC -->
        <div class="section-card">
            <div class="section-card-header clinic">
                <div class="section-card-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="26" height="26" fill="none"><rect x="3" y="6" width="18" height="15" rx="1" stroke="#fff" stroke-width="1.8"/><path d="M3 6L12 2L21 6" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/><path d="M12 10V14M10 12H14" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/></svg></div>
                <div><div class="section-card-title">Clinic</div><div class="section-card-sub">Cattle health management</div></div>
            </div>
            <div class="section-card-links">
                <a href="dashboard_clinic.php" class="section-card-link clinic-link"><span class="link-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none"><rect x="3" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="3" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/></svg></span>Clinic Dashboard<span class="link-arrow">→</span></a>
                <a href="../front/clinic/modulesC/cow_registry.php" class="section-card-link clinic-link"><span class="link-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none"><path d="M6 8c0 0-1 12 6 12s6-12 6-12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M6 8c1-3 11-3 12 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>Cow Registry<span class="link-arrow">→</span></a>
                <a href="../front/clinic/modulesC/medicines_colostrum.php" class="section-card-link clinic-link"><span class="link-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none"><rect x="1" y="6" width="22" height="12" rx="6" stroke="currentColor" stroke-width="1.8"/><line x1="12" y1="6" x2="12" y2="18" stroke="currentColor" stroke-width="1.8"/></svg></span>Med &amp; Colostrum<span class="link-arrow">→</span></a>
                <a href="../front/clinic/modulesC/calves.php" class="section-card-link clinic-link"><span class="link-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none"><ellipse cx="12" cy="14" rx="6" ry="5" stroke="currentColor" stroke-width="1.8"/><path d="M9 9V6M15 9V6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>Calves<span class="link-arrow">→</span></a>
            </div>
        </div>
        <!-- SHOP -->
        <div class="section-card">
            <div class="section-card-header shop">
                <div class="section-card-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="26" height="26" fill="none"><rect x="2" y="10" width="20" height="11" rx="2" stroke="#fff" stroke-width="1.8"/><path d="M8 10V7C8 5.343 9.343 4 11 4H13C14.657 4 16 5.343 16 7V10" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/><line x1="2" y1="15" x2="22" y2="15" stroke="#fff" stroke-width="1.8"/></svg></div>
                <div><div class="section-card-title">Shop</div><div class="section-card-sub">Assets &amp; maintenance</div></div>
            </div>
            <div class="section-card-links">
                <a href="dashboard_shop.php" class="section-card-link shop-link"><span class="link-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none"><rect x="3" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="3" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/></svg></span>Shop Dashboard<span class="link-arrow">→</span></a>
                <a href="../front/shop/modulesS/assets.php" class="section-card-link shop-link"><span class="link-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 12h8M12 8v8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>Assets<span class="link-arrow">→</span></a>
                <a href="../front/shop/modulesS/work_orders.php" class="section-card-link shop-link"><span class="link-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none"><path d="M9 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V9l-6-6z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9 3v6h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>Work Orders<span class="link-arrow">→</span></a>
                <a href="../front/shop/modulesS/parts_inventory.php" class="section-card-link shop-link"><span class="link-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none"><rect x="2" y="10" width="20" height="11" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 10V7C8 5.343 9.343 4 11 4H13C14.657 4 16 5.343 16 7V10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>Parts &amp; Inventory<span class="link-arrow">→</span></a>
            </div>
        </div>
        <!-- EMPLOYEES -->
        <div class="section-card">
            <div class="section-card-header emp">
                <div class="section-card-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="26" height="26" fill="none"><circle cx="9" cy="7" r="4" stroke="#fff" stroke-width="1.8"/><path d="M2 21v-2a4 4 0 014-4h6a4 4 0 014 4v2" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/><path d="M19 8v6M16 11h6" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/></svg></div>
                <div><div class="section-card-title">Employees</div><div class="section-card-sub">Staff management</div></div>
            </div>
            <div class="section-card-links">
                <a href="employees.php" class="section-card-link emp-link"><span class="link-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none"><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M2 21v-2a4 4 0 014-4h6a4 4 0 014 4v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M19 8v6M16 11h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>Manage Employees<span class="link-arrow">→</span></a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar=document.getElementById('sidebar'),mainContent=document.getElementById('mainContent'),overlay=document.getElementById('sidebarOverlay'),toggleBtn=document.getElementById('sidebarToggleBtn');
    const SIDEBAR_W=250,COLLAPSED_W=68;
    function updateToggleBtn(c){if(window.innerWidth>768){toggleBtn.style.left=(c?COLLAPSED_W:SIDEBAR_W)-13+'px';toggleBtn.style.transform=c?'rotate(180deg)':'rotate(0deg)';}}
    const isCollapsed=localStorage.getItem('sidebarCollapsed')==='true';
    if(isCollapsed){sidebar.classList.add('collapsed');mainContent.classList.add('collapsed');}
    updateToggleBtn(isCollapsed);
    function toggleSidebar(){const c=sidebar.classList.toggle('collapsed');mainContent.classList.toggle('collapsed');localStorage.setItem('sidebarCollapsed',c);updateToggleBtn(c);}
    function openSidebar(){sidebar.classList.add('open');overlay.classList.add('active');document.body.style.overflow='hidden';}
    function closeSidebar(){sidebar.classList.remove('open');overlay.classList.remove('active');document.body.style.overflow='';}
    let _lastWidth=window.innerWidth,resizeTimeout;
    window.addEventListener('resize',()=>{clearTimeout(resizeTimeout);resizeTimeout=setTimeout(()=>{const w=window.innerWidth;if(w===_lastWidth)return;_lastWidth=w;if(w<=768){toggleBtn.style.display='none';}else{toggleBtn.style.display='flex';sidebar.classList.remove('open');overlay.classList.remove('active');document.body.style.overflow='';const saved=localStorage.getItem('sidebarCollapsed')==='true';if(saved){sidebar.classList.add('collapsed');mainContent.classList.add('collapsed');}else{sidebar.classList.remove('collapsed');mainContent.classList.remove('collapsed');}updateToggleBtn(sidebar.classList.contains('collapsed'));}},100);});
    if(window.innerWidth<=768)toggleBtn.style.display='none';
</script>
</body>
</html>
