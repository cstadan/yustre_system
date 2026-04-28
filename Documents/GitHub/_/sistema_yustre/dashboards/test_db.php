<?php
echo "<h2>Diagnóstico completo</h2>";

// 1. Verificar extensiones PHP
echo "<h3>1. Extensiones PHP:</h3>";
echo "password_hash disponible: " . (function_exists('password_hash') ? '✅ SÍ' : '❌ NO') . "<br>";
echo "password_verify disponible: " . (function_exists('password_verify') ? '✅ SÍ' : '❌ NO') . "<br>";

// 2. Generar un hash nuevo
echo "<h3>2. Generar hash nuevo:</h3>";
$nueva_password = '123456';
$nuevo_hash = password_hash($nueva_password, PASSWORD_DEFAULT);
echo "Password: 123456<br>";
echo "Hash generado: " . $nuevo_hash . "<br>";

// 3. Verificar el hash nuevo
echo "<h3>3. Verificar hash recién generado:</h3>";
if (password_verify('123456', $nuevo_hash)) {
    echo "✅ El hash nuevo funciona correctamente<br>";
} else {
    echo "❌ El hash nuevo NO funciona (problema grave de PHP)<br>";
}

// 4. Conectar a BD
echo "<h3>4. Conexión a base de datos:</h3>";
$db_host = 'localhost';
$db_name = 'login_yustre';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión exitosa<br>";

    // 5. Obtener usuario
    echo "<h3>5. Usuario en base de datos:</h3>";
    $stmt = $pdo->prepare("SELECT id, nombre, email, password FROM usuarios WHERE email = 'admin@example.com'");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "ID: " . $user['id'] . "<br>";
        echo "Nombre: " . $user['nombre'] . "<br>";
        echo "Email: " . $user['email'] . "<br>";
        echo "Hash guardado: " . $user['password'] . "<br>";
        echo "Longitud del hash: " . strlen($user['password']) . " caracteres<br>";

        // 6. Verificar el hash de la BD
        echo "<h3>6. Verificar hash de BD con password '123456':</h3>";
        if (password_verify('123456', $user['password'])) {
            echo "✅ CORRECTO - La password coincide<br>";
        } else {
            echo "❌ INCORRECTO - La password NO coincide<br>";
        }

        // 7. Actualizar con hash nuevo
        echo "<h3>7. Solución - Actualizar con hash nuevo:</h3>";
        echo "<form method='POST'>";
        echo "<button type='submit' name='actualizar' class='btn'>Actualizar password a '123456'</button>";
        echo "</form>";

        if (isset($_POST['actualizar'])) {
            $stmt_update = $pdo->prepare("UPDATE usuarios SET password = :password WHERE email = 'admin@example.com'");
            $stmt_update->execute(['password' => $nuevo_hash]);
            echo "<br><div class='success'>✅ Password actualizada correctamente. <a href='test_db.php'>Recargar página</a></div>";
        }
    } else {
        echo "❌ Usuario no encontrado<br>";
    }
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
        background: #f5f5f5;
        max-width: 800px;
        margin: 0 auto;
    }

    h2 {
        color: #333;
        border-bottom: 3px solid #28a745;
        padding-bottom: 10px;
    }

    h3 {
        color: #666;
        margin-top: 20px;
        background: white;
        padding: 10px;
        border-left: 4px solid #007bff;
    }

    .btn {
        background: #28a745;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
    }

    .btn:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .success {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #c3e6cb;
        margin-top: 10px;
    }

    form {
        margin-top: 15px;
    }
</style>