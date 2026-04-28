<?php
$db_host = 'localhost';
$db_name = 'login_yustre';
$db_user = 'root';
$db_pass = '';

$message = '';
$type    = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}

// Load employees for dropdown
$employees = $pdo->query("SELECT id, name, email, rol FROM employees ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm']);

    if (empty($email) || empty($password) || empty($confirm)) {
        $message = 'All fields are required.';
        $type    = 'error';
    } elseif ($password !== $confirm) {
        $message = 'Passwords do not match.';
        $type    = 'error';
    } elseif (strlen($password) < 4) {
        $message = 'Password must be at least 4 characters.';
        $type    = 'error';
    } else {
        $stmt = $pdo->prepare("SELECT id, name FROM employees WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $message = "No employee found with email: $email";
            $type    = 'error';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $upd  = $pdo->prepare("UPDATE employees SET password = :hash WHERE email = :email");
            $upd->execute([':hash' => $hash, ':email' => $email]);
            $message = "Password updated for <strong>{$user['name']}</strong> ({$email})";
            $type    = 'success';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #003d82, #007bff);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            width: 100%;
            max-width: 440px;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
        }

        .card-title {
            font-size: 22px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .card-subtitle {
            font-size: 13px;
            color: #999;
            margin-bottom: 25px;
        }

        label {
            font-weight: 500;
            font-size: 14px;
            color: #555;
            margin-bottom: 6px;
        }

        .form-control,
        select.form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .btn-reset {
            background: #007bff;
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s;
            margin-top: 5px;
        }

        .btn-reset:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .alert-box {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }

        .employee-badge {
            display: inline-block;
            background: #e9ecef;
            color: #495057;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 12px;
            margin-left: 6px;
        }

        .rol-admin {
            background: #cce5ff;
            color: #004085;
        }

        .rol-clinic {
            background: #d4edda;
            color: #155724;
        }

        .rol-workshop {
            background: #fff3cd;
            color: #856404;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-title">🔑 Reset Password</div>
        <div class="card-subtitle">Update employee password directly in the database</div>

        <?php if ($message): ?>
            <div class="alert-box alert-<?php echo $type; ?>">
                <?php echo $type === 'success' ? '✅' : '❌'; ?> <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>👤 Employee</label>
                <select name="email" class="form-control" required>
                    <option value="">Select employee...</option>
                    <?php foreach ($employees as $emp): ?>
                        <option value="<?php echo htmlspecialchars($emp['email']); ?>"
                            <?php echo (isset($_POST['email']) && $_POST['email'] == $emp['email']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($emp['name']); ?> — <?php echo htmlspecialchars($emp['email']); ?>
                            (<?php echo htmlspecialchars($emp['rol']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label>🔒 New Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter new password" required>
            </div>

            <div class="mb-4">
                <label>🔒 Confirm Password</label>
                <input type="password" name="confirm" class="form-control" placeholder="Repeat new password" required>
            </div>

            <button type="submit" class="btn-reset">Update Password</button>
        </form>

        <div class="text-center mt-4">
            <a href="login.php" style="color:#007bff; font-size:13px; text-decoration:none;">← Back to Login</a>
        </div>
    </div>
</body>

</html>