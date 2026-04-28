<?php
session_start();

// Verify if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login/login.php');
    exit();
}

// Only admin can access this page
if ($_SESSION['user_rol'] !== 'admin') {
    header('Location: ../login/login.php?error=invalid_role');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style_dashboard_admin.css">
</head>

<body>

    <!-- Header -->
    <div class="admin-header">
        <h1>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="26" height="26" fill="none">
                <path d="M12 3L4 7V12C4 16.418 7.582 20.398 12 21C16.418 20.398 20 16.418 20 12V7L12 3Z" stroke="white" stroke-width="1.8" stroke-linejoin="round" />
                <path d="M9 12L11 14L15 10" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
            </svg> Admin Panel
        </h1>
        <p>Select a section to manage</p>
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <?php echo htmlspecialchars($_SESSION['user_email']); ?>
        </div>
    </div>

    <!-- Cards -->
    <div class="cards-container">

        <!-- Clinic -->
        <a href="dashboard_clinic.php" class="admin-card">
            <span class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="50" height="50" fill="none">
                    <!-- Edificio -->
                    <rect x="3" y="6" width="18" height="15" rx="1" stroke="white" stroke-width="1.8" />
                    <!-- Techo/frente -->
                    <path d="M3 6L12 2L21 6" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    <!-- Cruz -->
                    <path d="M12 10V14" stroke="white" stroke-width="1.8" stroke-linecap="round" />
                    <path d="M10 12H14" stroke="white" stroke-width="1.8" stroke-linecap="round" />
                    <!-- Puerta -->
                    <rect x="9.5" y="17" width="5" height="4" rx="0.5" stroke="white" stroke-width="1.8" />
                </svg>
            </span>
            <div class="card-name">Clinic</div>
            <div class="card-desc">Cows, calves, medicines & colostrum</div>
        </a>

        <!-- Shop -->
        <a href="dashboard_shop.php" class="admin-card">
            <span class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="50" height="50" fill="none">
                    <!-- Caja -->
                    <rect x="2" y="10" width="20" height="11" rx="2" stroke="white" stroke-width="1.8" />
                    <!-- Asa -->
                    <path d="M8 10V7C8 5.343 9.343 4 11 4H13C14.657 4 16 5.343 16 7V10" stroke="white" stroke-width="1.8" stroke-linecap="round" />
                    <!-- Línea central -->
                    <line x1="2" y1="15" x2="22" y2="15" stroke="white" stroke-width="1.8" />
                </svg>
            </span>
            <div class="card-name">Shop</div>
            <div class="card-desc">Assets, work orders, parts & inventory</div>
        </a>

        <!-- Employees -->
        <a href="employees.php" class="admin-card">
            <span class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="50" height="50" fill="none">
                    <!-- Person -->
                    <circle cx="12" cy="8" r="3.5" stroke="white" stroke-width="1.8" />
                    <!-- Body -->
                    <path d="M5 20C5 16.686 8.134 14 12 14C15.866 14 19 16.686 19 20" stroke="white" stroke-width="1.8" stroke-linecap="round" />
                </svg>
            </span>
            <div class="card-name">Employees</div>
            <div class="card-desc">Add and manage employees</div>
        </a>

    </div>

    <!-- Logout -->
    <a href="../logout.php" class="logout-btn">
        <span class="nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none">
                <!-- Door frame -->
                <path d="M14 3H6C5.448 3 5 3.448 5 4V20C5 20.552 5.448 21 6 21H14" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                <!-- Arrow -->
                <path d="M16 8L21 12L16 16" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M21 12H9" stroke="white" stroke-width="1.8" stroke-linecap="round" />
            </svg>
        </span> Logout</a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>