<?php
// ================================================
// MEDICINES & COLOSTRUM CONTROLLER
// Handles: DB connections, CRUD operations, filters
// ================================================

require_once __DIR__ . '/../../../shared/csrf_helper.php';

$db_host = 'db5019772005.hosting-data.io';
$db_name = 'dbs15332258';
$db_user = 'dbu4236696';
$db_pass = 'NG8936ngr82NbplE21gnp2TG5hjsg84sht73y3y3';

// Medicines DB
try {
    $pdo_med = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo_med->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Medicines DB error: " . $e->getMessage());
}

// Colostrum DB (misma BD)
try {
    $pdo_col = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo_col->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Colostrum DB error: " . $e->getMessage());
}

// Calves DB (misma BD, para dropdown)
try {
    $pdo_calves  = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo_calves->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $calves_list = $pdo_calves->query("SELECT id FROM calves ORDER BY id")->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $calves_list = [];
}

$success_message = '';
$error_message   = '';
$csrf_token      = generate_csrf_token();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    verify_csrf_token();

    // Add Medicine
    if ($_POST['action'] === 'add_medicine') {
        $varcode = trim($_POST['varcode']);
        $name    = trim($_POST['name']);
        $stock   = intval($_POST['stock']);
        $unit    = trim($_POST['unit']);

        if (empty($varcode) || empty($name) || empty($unit) || $stock < 0) {
            $error_message = 'Please fill in all required fields.';
        } else {
            $stmt = $pdo_med->prepare("INSERT INTO medicines (varcode, name, stock, unit) VALUES (:varcode, :name, :stock, :unit)");
            $stmt->execute([':varcode' => $varcode, ':name' => $name, ':stock' => $stock, ':unit' => $unit]);
            $success_message = "Medicine <strong>$name</strong> added successfully!";
        }
    }

    // Edit Medicine
    elseif ($_POST['action'] === 'edit_medicine') {
        $id      = intval($_POST['edit_id']);
        $varcode = trim($_POST['varcode']);
        $name    = trim($_POST['name']);
        $unit    = trim($_POST['unit']);

        if (empty($varcode) || empty($name) || empty($unit)) {
            $error_message = 'Please fill in all required fields.';
        } else {
            $stmt = $pdo_med->prepare("UPDATE medicines SET varcode=:varcode, name=:name, unit=:unit WHERE id=:id");
            $stmt->execute([':varcode' => $varcode, ':name' => $name, ':unit' => $unit, ':id' => $id]);
            $success_message = "Medicine <strong>$name</strong> updated successfully!";
        }
    }

    // Delete Medicine
    elseif ($_POST['action'] === 'delete_medicine') {
        $id   = intval($_POST['delete_id']);
        $stmt = $pdo_med->prepare("SELECT name FROM medicines WHERE id=:id");
        $stmt->execute([':id' => $id]);
        $med  = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($med) {
            $pdo_med->prepare("DELETE FROM medicines WHERE id=:id")->execute([':id' => $id]);
            $success_message = "Medicine <strong>{$med['name']}</strong> deleted successfully!";
        }
    }

    // Add Purchase
    elseif ($_POST['action'] === 'add_purchase') {
        $medicine_id = intval($_POST['medicine_id']);
        $quantity    = intval($_POST['quantity']);
        $price       = floatval($_POST['price']);
        $date        = trim($_POST['date']);

        if ($quantity <= 0 || $price < 0 || empty($date)) {
            $error_message = 'Please fill in all purchase fields correctly.';
        } else {
            $stmt = $pdo_med->prepare("INSERT INTO purchase_history (medicine_id, quantity, price, date) VALUES (:mid, :qty, :price, :date)");
            $stmt->execute([':mid' => $medicine_id, ':qty' => $quantity, ':price' => $price, ':date' => $date]);
            $pdo_med->prepare("UPDATE medicines SET stock = stock + :qty WHERE id = :id")
                ->execute([':qty' => $quantity, ':id' => $medicine_id]);
            $success_message = "Purchase recorded and stock updated successfully!";
        }
    }

    // Add Colostrum
    elseif ($_POST['action'] === 'add_colostrum') {
        $varcode  = trim($_POST['varcode']);
        $quantity = intval($_POST['quantity']);
        $calf_id  = trim($_POST['calf_id']);

        if (empty($varcode) || $quantity <= 0 || empty($calf_id)) {
            $error_message = 'Please fill in all required colostrum fields.';
        } else {
            $stmt = $pdo_col->prepare("INSERT INTO colostrum (varcode, quantity, calf_id) VALUES (:varcode, :quantity, :calf_id)");
            $stmt->execute([':varcode' => $varcode, ':quantity' => $quantity, ':calf_id' => $calf_id]);
            $success_message = "Colostrum record added successfully!";
        }
    }

    // Edit Colostrum
    elseif ($_POST['action'] === 'edit_colostrum') {
        $id       = intval($_POST['edit_id']);
        $varcode  = trim($_POST['varcode']);
        $quantity = intval($_POST['quantity']);
        $calf_id  = trim($_POST['calf_id']);

        if (empty($varcode) || $quantity <= 0 || empty($calf_id)) {
            $error_message = 'Please fill in all required colostrum fields.';
        } else {
            $stmt = $pdo_col->prepare("UPDATE colostrum SET varcode=:varcode, quantity=:quantity, calf_id=:calf_id WHERE id=:id");
            $stmt->execute([':varcode' => $varcode, ':quantity' => $quantity, ':calf_id' => $calf_id, ':id' => $id]);
            $success_message = "Colostrum record updated successfully!";
        }
    }

    // Delete Colostrum
    elseif ($_POST['action'] === 'delete_colostrum') {
        $id = intval($_POST['delete_id']);
        $pdo_col->prepare("DELETE FROM colostrum WHERE id=:id")->execute([':id' => $id]);
        $success_message = "Colostrum record deleted successfully!";
    }
}

// FILTERS - MEDICINES
$med_search      = isset($_GET['med_search']) ? trim($_GET['med_search']) : '';
$med_filter_unit = isset($_GET['med_unit'])   ? $_GET['med_unit']        : '';

$med_sql = "SELECT m.*,
            (SELECT ph.price FROM purchase_history ph
             WHERE ph.medicine_id = m.id
             ORDER BY ph.date DESC, ph.id DESC LIMIT 1) AS last_price
           FROM medicines m WHERE 1=1";
$med_params = [];

if (!empty($med_search)) {
    $med_sql .= " AND (varcode LIKE :search OR name LIKE :search)";
    $med_params[':search'] = "%$med_search%";
}
if (!empty($med_filter_unit)) {
    $med_sql .= " AND unit = :unit";
    $med_params[':unit'] = $med_filter_unit;
}
$med_sql .= " ORDER BY name ASC";

$stmt_med  = $pdo_med->prepare($med_sql);
$stmt_med->execute($med_params);
$medicines = $stmt_med->fetchAll(PDO::FETCH_ASSOC);

// FILTERS - COLOSTRUM
$col_search      = isset($_GET['col_search']) ? trim($_GET['col_search']) : '';
$col_filter_calf = isset($_GET['col_calf'])   ? $_GET['col_calf']        : '';

$col_sql    = "SELECT * FROM colostrum WHERE 1=1";
$col_params = [];

if (!empty($col_search)) {
    $col_sql .= " AND (varcode LIKE :search OR calf_id LIKE :search)";
    $col_params[':search'] = "%$col_search%";
}
if (!empty($col_filter_calf)) {
    $col_sql .= " AND calf_id = :calf_id";
    $col_params[':calf_id'] = $col_filter_calf;
}
$col_sql .= " ORDER BY date_register DESC";

$stmt_col   = $pdo_col->prepare($col_sql);
$stmt_col->execute($col_params);
$colostrums = $stmt_col->fetchAll(PDO::FETCH_ASSOC);

$col_calves = $pdo_col->query("SELECT DISTINCT calf_id FROM colostrum ORDER BY calf_id")->fetchAll(PDO::FETCH_COLUMN);