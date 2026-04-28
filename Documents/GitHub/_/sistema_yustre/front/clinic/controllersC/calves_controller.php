<?php
// ================================================
// CALVES CONTROLLER
// Handles: DB connection, CRUD operations, filters
// ================================================

require_once __DIR__ . '/../../../shared/csrf_helper.php';

$db_host = 'db5019772005.hosting-data.io';
$db_name = 'dbs15332258';
$db_user = 'dbu4236696';
$db_pass = 'NG8936ngr82NbplE21gnp2TG5hjsg84sht73y3y3';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}

function generateCalfID($pdo)
{
    do {
        $letters = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2));
        $numbers = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        $id = $letters . $numbers;
        $stmt = $pdo->prepare("SELECT id FROM calves WHERE id = :id");
        $stmt->execute([':id' => $id]);
    } while ($stmt->rowCount() > 0);
    return $id;
}

$success_message = '';
$error_message   = '';
$csrf_token      = generate_csrf_token();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    verify_csrf_token();

    if ($_POST['action'] === 'add_calf') {
        $corral   = trim($_POST['corral']);
        $status   = trim($_POST['status']);
        $age      = intval($_POST['age']);
        $comments = trim($_POST['comments']);

        if (empty($corral) || empty($status) || $age < 0) {
            $error_message = 'Please fill in all required fields.';
        } else {
            try {
                $new_id = generateCalfID($pdo);
                $stmt = $pdo->prepare("INSERT INTO calves (id, corral, status, age, comments) VALUES (:id, :corral, :status, :age, :comments)");
                $stmt->execute([':id' => $new_id, ':corral' => $corral, ':status' => $status, ':age' => $age, ':comments' => $comments]);
                $success_message = "Calf added successfully! ID: <strong>$new_id</strong>";
            } catch (PDOException $e) {
                $error_message = "Error adding calf: " . $e->getMessage();
            }
        }
    } elseif ($_POST['action'] === 'edit_calf') {
        $id       = trim($_POST['edit_id']);
        $corral   = trim($_POST['corral']);
        $status   = trim($_POST['status']);
        $age      = intval($_POST['age']);
        $comments = trim($_POST['comments']);

        if (empty($corral) || empty($status) || $age < 0) {
            $error_message = 'Please fill in all required fields.';
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE calves SET corral=:corral, status=:status, age=:age, comments=:comments WHERE id=:id");
                $stmt->execute([':corral' => $corral, ':status' => $status, ':age' => $age, ':comments' => $comments, ':id' => $id]);
                $success_message = "Calf <strong>$id</strong> updated successfully!";
            } catch (PDOException $e) {
                $error_message = "Error updating calf: " . $e->getMessage();
            }
        }
    } elseif ($_POST['action'] === 'delete_calf') {
        $id = trim($_POST['delete_id']);
        try {
            $stmt = $pdo->prepare("DELETE FROM calves WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $success_message = "Calf <strong>$id</strong> deleted successfully!";
        } catch (PDOException $e) {
            $error_message = "Error deleting calf: " . $e->getMessage();
        }
    }
}

$search        = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_corral = isset($_GET['corral']) ? $_GET['corral'] : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$order_by      = isset($_GET['order'])  ? $_GET['order']  : 'id_asc';

$sql    = "SELECT * FROM calves WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (id LIKE :search OR corral LIKE :search OR age LIKE :search)";
    $params[':search'] = "%$search%";
}
if (!empty($filter_corral)) {
    $sql .= " AND corral = :corral";
    $params[':corral'] = $filter_corral;
}
if (!empty($filter_status)) {
    $sql .= " AND status = :status";
    $params[':status'] = $filter_status;
}

switch ($order_by) {
    case 'id_desc':
        $sql .= " ORDER BY id DESC";
        break;
    case 'age_asc':
        $sql .= " ORDER BY age ASC";
        break;
    case 'age_desc':
        $sql .= " ORDER BY age DESC";
        break;
    default:
        $sql .= " ORDER BY id ASC";
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$calves = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt_corrals = $pdo->query("SELECT DISTINCT corral FROM calves ORDER BY corral");
$corrals      = $stmt_corrals->fetchAll(PDO::FETCH_COLUMN);
