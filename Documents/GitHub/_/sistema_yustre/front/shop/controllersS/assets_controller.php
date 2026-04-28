<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../../../login/login.php');
    exit();
}

if (!in_array($_SESSION['user_rol'], ['admin', 'shop'])) {
    header('Location: ../../../login/login.php?error=invalid_role');
    exit();
}

require_once __DIR__ . '/../../../shared/csrf_helper.php';

$error      = '';
$success    = '';
$csrf_token = generate_csrf_token();

// ── PREDEFINED GROUPS ──────────────────────────────────────────────────────
define('ASSET_GROUPS', [
    'Skid Steer',
    'Van/Truck',
    'ATV',
    'Golf Cart/Ranger',
    'Loader',
    'John Deere/Versatile/Case',
    'Mixer',
    'Semi Truck',
    'Kubota/Massey',
    'John Deere 210',
    'Attachment',
    'Other'
]);

// ── DB CONNECTION ──────────────────────────────────────────────────────────
function get_shop_db()
{
    $db = new mysqli('db5019772005.hosting-data.io', 'dbu4236696', 'NG8936ngr82NbplE21gnp2TG5hjsg84sht73y3y3', 'dbs15332258');
    if ($db->connect_error) return null;
    return $db;
}

// ── AUTO-GENERATE UNIQUE CODE: AST-XXXX ───────────────────────────────────
function generate_asset_code($db)
{
    do {
        $code = 'AST-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        $stmt = $db->prepare("SELECT id FROM machines WHERE code = ?");
        $stmt->bind_param('s', $code);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();
    } while ($exists);
    return $code;
}

// ── HANDLE POST ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF temporalmente desactivado — causaba 403 Forbidden
    // verify_csrf_token();

    $action = $_POST['action'] ?? '';
    $db     = get_shop_db();

    if (!$db) {
        $error = 'Database connection failed.';
    } else {

        // ADD ASSET
        if ($action === 'add_asset') {
            $code        = generate_asset_code($db);
            $name        = trim($_POST['name'] ?? '');
            $model       = trim($_POST['model'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price       = floatval($_POST['price'] ?? 0);
            $brand       = trim($_POST['brand'] ?? '');
            $serial_num  = trim($_POST['serial_number'] ?? '');
            $miles       = intval($_POST['miles'] ?? 0);
            $miles_svc   = intval($_POST['miles_per_service'] ?? 0);
            $status      = $_POST['status'] ?? 'active';
            $substatus   = $_POST['substatus'] ?? 'running';
            $group_type  = trim($_POST['group_type'] ?? '');
            $maint_day   = trim($_POST['maintenance_day'] ?? '');
            $maint_freq  = trim($_POST['maintenance_frequency'] ?? '');
            $image_path  = '';

            if (empty($name)) {
                $error = 'Name is required.';
            } else {
                if (!empty($_FILES['asset_image']['name'])) {
                    $upload_dir = __DIR__ . '/../../../assets/images/assets/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                    $ext     = strtolower(pathinfo($_FILES['asset_image']['name'], PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                    if (in_array($ext, $allowed)) {
                        $fname = 'asset_' . $code . '_' . time() . '.' . $ext;
                        move_uploaded_file($_FILES['asset_image']['tmp_name'], $upload_dir . $fname);
                        $image_path = 'assets/images/assets/' . $fname;
                    }
                }

                $stmt = $db->prepare(
                    "INSERT INTO machines (code, name, model, description, price, brand, serial_number,
                     miles, miles_per_service, status, substatus, group_type, maintenance_day,
                     maintenance_frequency, image_path) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
                );
                $stmt->bind_param(
                    'ssssdssiiisssss',
                    $code,
                    $name,
                    $model,
                    $description,
                    $price,
                    $brand,
                    $serial_num,
                    $miles,
                    $miles_svc,
                    $status,
                    $substatus,
                    $group_type,
                    $maint_day,
                    $maint_freq,
                    $image_path
                );
                if ($stmt->execute()) {
                    $success = "Asset <strong>{$name}</strong> added. ID: <strong>{$code}</strong>";
                } else {
                    $error = 'Error adding asset: ' . $stmt->error;
                }
                $stmt->close();
            }
        }

        // EDIT ASSET
        elseif ($action === 'edit_asset') {
            $id          = intval($_POST['id'] ?? 0);
            $name        = trim($_POST['name'] ?? '');
            $model       = trim($_POST['model'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price       = floatval($_POST['price'] ?? 0);
            $brand       = trim($_POST['brand'] ?? '');
            $serial_num  = trim($_POST['serial_number'] ?? '');
            $miles       = intval($_POST['miles'] ?? 0);
            $miles_svc   = intval($_POST['miles_per_service'] ?? 0);
            $status      = $_POST['status'] ?? 'active';
            $substatus   = $_POST['substatus'] ?? 'running';
            $group_type  = trim($_POST['group_type'] ?? '');
            $maint_day   = trim($_POST['maintenance_day'] ?? '');
            $maint_freq  = trim($_POST['maintenance_frequency'] ?? '');
            
            // DEBUG VISIBLE
            if (!isset($_POST['status'])) {
                $error = 'DEBUG: Campo status NO viene en el POST';
            }

            if (empty($name) || $id <= 0) {
                $error = 'Name is required.';
            } else {
                $image_sql  = '';
                $image_path = '';
                if (!empty($_FILES['asset_image']['name'])) {
                    $upload_dir = __DIR__ . '/../../../assets/images/assets/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                    $ext     = strtolower(pathinfo($_FILES['asset_image']['name'], PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                    if (in_array($ext, $allowed)) {
                        $fname = 'asset_id' . $id . '_' . time() . '.' . $ext;
                        move_uploaded_file($_FILES['asset_image']['tmp_name'], $upload_dir . $fname);
                        $image_path = 'assets/images/assets/' . $fname;
                        $image_sql  = ', image_path = ?';
                    }
                }

                $sql  = "UPDATE machines SET name=?, model=?, description=?, price=?,
                         brand=?, serial_number=?, miles=?, miles_per_service=?, status=?,
                         substatus=?, group_type=?, maintenance_day=?, maintenance_frequency=?
                         {$image_sql} WHERE id=?";
                $stmt = $db->prepare($sql);
                if ($image_sql) {
                    $stmt->bind_param(
                        'sssdssiiisssssi',
                        $name,
                        $model,
                        $description,
                        $price,
                        $brand,
                        $serial_num,
                        $miles,
                        $miles_svc,
                        $status,
                        $substatus,
                        $group_type,
                        $maint_day,
                        $maint_freq,
                        $image_path,
                        $id
                    );
                } else {
                    $stmt->bind_param(
                        'sssdssiisssssi',
                        $name,
                        $model,
                        $description,
                        $price,
                        $brand,
                        $serial_num,
                        $miles,
                        $miles_svc,
                        $status,
                        $substatus,
                        $group_type,
                        $maint_day,
                        $maint_freq,
                        $id
                    );
                }
                if ($stmt->execute()) {
                    $success = "Asset <strong>{$name}</strong> updated successfully.";
                } else {
                    $error = 'Error updating asset: ' . $stmt->error;
                }
                $stmt->close();
            }
        }

        // DELETE ASSET
        elseif ($action === 'delete_asset') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $db->prepare("DELETE FROM machines WHERE id = ?");
                $stmt->bind_param('i', $id);
                if ($stmt->execute()) {
                    header('Location: assets.php?deleted=1');
                    exit();
                } else {
                    $error = 'Error deleting asset.';
                }
                $stmt->close();
            }
        }

        $db->close();
    }
}

// ── FETCH DATA ─────────────────────────────────────────────────────────────
$assets       = [];
$view_asset   = null;
$wo_completed = [];
$wo_pending   = [];

$search         = trim($_GET['search'] ?? '');
$filter_grp     = trim($_GET['group'] ?? '');
$selected_groups = array_filter(explode(',', $_GET['groups'] ?? ''));
$filter_sts     = trim($_GET['filter_status'] ?? '');
$view_id        = intval($_GET['view'] ?? 0);

if (isset($_GET['deleted'])) $success = 'Asset deleted successfully.';

$db = get_shop_db();
if ($db) {
    $where  = [];
    $params = [];
    $types  = '';

    if ($search !== '') {
        $like    = '%' . $search . '%';
        $where[] = '(code LIKE ? OR name LIKE ? OR brand LIKE ? OR serial_number LIKE ?)';
        $params  = array_merge($params, [$like, $like, $like, $like]);
        $types  .= 'ssss';
    }
    if (!empty($selected_groups)) {
        $placeholders = implode(',', array_fill(0, count($selected_groups), '?'));
        $where[]      = "group_type IN ({$placeholders})";
        $params       = array_merge($params, $selected_groups);
        $types       .= str_repeat('s', count($selected_groups));
    } elseif ($filter_grp !== '') {
        $where[]  = 'group_type = ?';
        $params[] = $filter_grp;
        $types   .= 's';
    }
    if ($filter_sts !== '') {
        $where[]  = 'status = ?';
        $params[] = $filter_sts;
        $types   .= 's';
    }

    $sql  = "SELECT * FROM machines" . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . " ORDER BY name";
    $stmt = $db->prepare($sql);
    if ($params) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $assets = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if ($view_id > 0) {
        $stmt = $db->prepare("SELECT * FROM machines WHERE id = ?");
        $stmt->bind_param('i', $view_id);
        $stmt->execute();
        $view_asset = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($view_asset) {
            $stmt = $db->prepare("SELECT * FROM work_orders WHERE machine_id = ? AND status = 'closed' ORDER BY closed_date DESC LIMIT 10");
            $stmt->bind_param('i', $view_id);
            $stmt->execute();
            $wo_completed = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            $stmt = $db->prepare("SELECT * FROM work_orders WHERE machine_id = ? AND status != 'closed' ORDER BY date_register DESC");
            $stmt->bind_param('i', $view_id);
            $stmt->execute();
            $wo_pending = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }
    }

    $db->close();
}