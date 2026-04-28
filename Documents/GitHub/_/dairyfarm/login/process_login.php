<?php
session_start();
require_once __DIR__ . '/../shared/csrf_helper.php';
$db_host = 'db5019772005.hosting-data.io';
$db_name = 'dbs15332258';
$db_user = 'dbu4236696';
$db_pass = 'NG8936ngr82NbplE21gnp2TG5hjsg84sht73y3y3';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF temporalmente desactivado — reactivar después
    // verify_csrf_token();
    $email    = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    if (empty($email) || empty($password)) {
        header('Location: login.php?error=empty_fields');
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: login.php?error=invalid_email');
        exit();
    }
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("SELECT id, email, password, name, rol FROM employees WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_rol']   = $user['rol'];
            $_SESSION['logged_in']  = true;
            switch ($user['rol']) {
                case 'admin':
                    header('Location: ../dashboards/dashboard_admin.php');
                    break;
                case 'clinic':
                    header('Location: ../dashboards/dashboard_clinic.php');
                    break;
                case 'shop':
                    header('Location: ../dashboards/dashboard_shop.php');
                    break;
                default:
                    header('Location: login.php?error=invalid_role');
                    break;
            }
            exit();
        } else {
            header('Location: login.php?error=invalid_credentials');
            exit();
        }
    } catch (PDOException $e) {
        header('Location: login.php?error=db_error');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}