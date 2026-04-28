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

$WO_TYPES      = ['preventive' => 'Preventive', 'corrective' => 'Corrective', 'inspection' => 'Inspection', 'emergency' => 'Emergency'];
$WO_PRIORITIES = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'critical' => 'Critical'];
$WO_STATUSES   = ['open' => 'Open', 'in_progress' => 'In Progress', 'waiting_parts' => 'Waiting Parts', 'closed' => 'Closed'];

function get_shop_db()
{
    $db = new mysqli('db5019772005.hosting-data.io', 'dbu4236696', 'NG8936ngr82NbplE21gnp2TG5hjsg84sht73y3y3', 'dbs15332258');
    if ($db->connect_error) return null;
    return $db;
}

// Auto-generate order number: WO-YYYYMMDD-XXXX
function generate_wo_code($db)
{
    do {
        $code = 'WO-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        $stmt = $db->prepare("SELECT id FROM work_orders WHERE code = ? OR order_number = ?");
        $stmt->bind_param('ss', $code, $code);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();
    } while ($exists);
    return $code;
}

// ── HANDLE POST ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token();
    $action = $_POST['action'] ?? '';
    $db = get_shop_db();

    if (!$db) {
        $error = 'Database connection failed.';
    } else {

        // CREATE WORK ORDER
        if ($action === 'create_wo') {
            $machine_id  = intval($_POST['machine_id'] ?? 0);
            $type        = $_POST['type'] ?? 'corrective';
            $priority    = $_POST['priority'] ?? 'medium';
            $description = trim($_POST['description'] ?? '');
            $opened_date = $_POST['opened_date'] ?? date('Y-m-d');
            $due_date    = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
            $created_by  = $_SESSION['user_name'] ?? 'System';
            $code        = generate_wo_code($db);

            if (!$machine_id || empty($description)) {
                $error = 'Asset and description are required.';
            } else {
                $stmt = $db->prepare(
                    "INSERT INTO work_orders (code, order_number, machine_id, type, priority, description, status, opened_date, due_date, created_by)
                     VALUES (?,?,?,?,?,?,'open',?,?,?)"
                );
                $stmt->bind_param('sssssssss', $code, $code, $machine_id, $type, $priority, $description, $opened_date, $due_date, $created_by);
                if ($stmt->execute()) {
                    $success = "Work Order <strong>{$code}</strong> created successfully.";
                } else {
                    $error = 'Error creating work order: ' . $stmt->error;
                }
                $stmt->close();
            }
        }

        // UPDATE STATUS / CLOSE
        elseif ($action === 'update_wo') {
            $id          = intval($_POST['id'] ?? 0);
            $status      = $_POST['status'] ?? 'open';
            $priority    = $_POST['priority'] ?? 'medium';
            $type        = $_POST['type'] ?? 'corrective';
            $description = trim($_POST['description'] ?? '');
            $result      = trim($_POST['result'] ?? '');
            $due_date    = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
            $closed_date = ($status === 'closed') ? date('Y-m-d') : null;

            if ($id <= 0) {
                $error = 'Invalid work order.';
            } else {
                $stmt = $db->prepare(
                    "UPDATE work_orders SET status=?, priority=?, type=?, description=?, result=?, due_date=?, closed_date=? WHERE id=?"
                );
                $stmt->bind_param('sssssssi', $status, $priority, $type, $description, $result, $due_date, $closed_date, $id);
                if ($stmt->execute()) {
                    $success = 'Work order updated successfully.';
                } else {
                    $error = 'Error updating: ' . $stmt->error;
                }
                $stmt->close();
            }
        }

        // ── ADD PART TO WO ─────────────────────────────────────────────────
        elseif ($action === 'add_wo_part') {
            $wo_id   = intval($_POST['wo_id'] ?? 0);
            $part_id = intval($_POST['part_id'] ?? 0);
            $qty     = intval($_POST['quantity'] ?? 0);
            $notes   = trim($_POST['notes'] ?? '');
            $by      = $_SESSION['user_name'] ?? 'System';

            if ($wo_id <= 0 || $part_id <= 0 || $qty <= 0) {
                $error = 'Select a part and enter a valid quantity.';
            } else {
                $s0 = $db->prepare("SELECT stock, name FROM parts WHERE id = ?");
                $s0->bind_param('i', $part_id);
                $s0->execute();
                $part_row = $s0->get_result()->fetch_assoc();
                $s0->close();

                if (!$part_row) {
                    $error = 'Part not found.';
                } elseif ($part_row['stock'] < $qty) {
                    $error = "Not enough stock for {$part_row['name']}. Available: {$part_row['stock']}";
                } else {
                    $db->begin_transaction();
                    try {
                        $s1 = $db->prepare("INSERT INTO work_order_parts (work_order_id, part_id, quantity, notes) VALUES (?,?,?,?)");
                        $s1->bind_param('iiis', $wo_id, $part_id, $qty, $notes);
                        $s1->execute(); $s1->close();

                        $s2 = $db->prepare("UPDATE parts SET stock = stock - ? WHERE id = ?");
                        $s2->bind_param('ii', $qty, $part_id);
                        $s2->execute(); $s2->close();

                        $reason = 'Used in WO';
                        $s3 = $db->prepare("INSERT INTO parts_movements (part_id, type, quantity, work_order_id, reason, created_by, date) VALUES (?, 'out', ?, ?, ?, ?, CURDATE())");
                        $s3->bind_param('iiiss', $part_id, $qty, $wo_id, $reason, $by);
                        $s3->execute(); $s3->close();

                        $db->commit();
                        $success = "Part <strong>{$part_row['name']}</strong> &times; {$qty} added to work order.";
                    } catch (Exception $e) {
                        $db->rollback();
                        $error = 'Error adding part to work order.';
                    }
                }
            }
        }

        // ── REMOVE PART FROM WO ────────────────────────────────────────────
        elseif ($action === 'remove_wo_part') {
            $wop_id = intval($_POST['wop_id'] ?? 0);
            $wo_id  = intval($_POST['wo_id'] ?? 0);
            $by     = $_SESSION['user_name'] ?? 'System';

            if ($wop_id > 0) {
                $s0 = $db->prepare("SELECT part_id, quantity FROM work_order_parts WHERE id = ?");
                $s0->bind_param('i', $wop_id);
                $s0->execute();
                $wop = $s0->get_result()->fetch_assoc();
                $s0->close();

                if ($wop) {
                    $db->begin_transaction();
                    try {
                        $s1 = $db->prepare("DELETE FROM work_order_parts WHERE id = ?");
                        $s1->bind_param('i', $wop_id);
                        $s1->execute(); $s1->close();

                        $s2 = $db->prepare("UPDATE parts SET stock = stock + ? WHERE id = ?");
                        $s2->bind_param('ii', $wop['quantity'], $wop['part_id']);
                        $s2->execute(); $s2->close();

                        $reason = 'Removed from WO';
                        $s3 = $db->prepare("INSERT INTO parts_movements (part_id, type, quantity, work_order_id, reason, created_by, date) VALUES (?, 'in', ?, ?, ?, ?, CURDATE())");
                        $s3->bind_param('iiiss', $wop['part_id'], $wop['quantity'], $wo_id, $reason, $by);
                        $s3->execute(); $s3->close();

                        $db->commit();
                        $success = 'Part removed and stock restored.';
                    } catch (Exception $e) {
                        $db->rollback();
                        $error = 'Error removing part.';
                    }
                }
            }
            $db->close();
            header("Location: work_orders.php?view={$wo_id}&msg=removed");
            exit();
        }

        // DELETE
        elseif ($action === 'delete_wo') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $db->prepare("DELETE FROM work_orders WHERE id = ?");
                $stmt->bind_param('i', $id);
                if ($stmt->execute()) {
                    header('Location: work_orders.php?deleted=1');
                    exit();
                } else {
                    $error = 'Error deleting work order.';
                }
                $stmt->close();
            }
        }

        $db->close();
    }
}

// ── FETCH DATA ─────────────────────────────────────────────────────────────
$work_orders  = [];
$all_assets   = [];
$all_parts    = [];
$wo_parts     = [];
$view_wo      = null;
$history_mode = isset($_GET['history']);

$search      = trim($_GET['search'] ?? '');
$filter_type = trim($_GET['type'] ?? '');
$filter_sts  = trim($_GET['status'] ?? '');
$filter_pri  = trim($_GET['priority'] ?? '');
$view_id     = intval($_GET['view'] ?? 0);

if (isset($_GET['deleted'])) $success = 'Work order deleted successfully.';

$db = get_shop_db();
if ($db) {
    $res = $db->query("SELECT id, name, code FROM machines ORDER BY name");
    if ($res) $all_assets = $res->fetch_all(MYSQLI_ASSOC);

    $res = $db->query("SELECT id, code, name, stock, unit FROM parts WHERE stock > 0 ORDER BY name");
    if ($res) $all_parts = $res->fetch_all(MYSQLI_ASSOC);

    if ($view_id > 0) {
        $stmt = $db->prepare(
            "SELECT wo.*, m.name AS asset_name, m.code AS asset_code
             FROM work_orders wo
             LEFT JOIN machines m ON wo.machine_id = m.id
             WHERE wo.id = ?"
        );
        $stmt->bind_param('i', $view_id);
        $stmt->execute();
        $view_wo = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $stmt = $db->prepare(
            "SELECT wop.*, p.name AS part_name, p.code AS part_code, p.unit
             FROM work_order_parts wop
             JOIN parts p ON wop.part_id = p.id
             WHERE wop.work_order_id = ?
             ORDER BY wop.date_register DESC"
        );
        $stmt->bind_param('i', $view_id);
        $stmt->execute();
        $wo_parts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    $where  = [];
    $params = [];
    $types  = '';

    if ($history_mode) {
        $where[] = "wo.status = 'closed'";
    } else {
        $where[] = "wo.status != 'closed'";
    }

    if ($search !== '') {
        $like    = '%' . $search . '%';
        $where[] = '(wo.code LIKE ? OR wo.description LIKE ? OR m.name LIKE ? OR wo.created_by LIKE ?)';
        $params  = array_merge($params, [$like, $like, $like, $like]);
        $types  .= 'ssss';
    }
    if ($filter_type !== '') { $where[] = 'wo.type = ?';     $params[] = $filter_type; $types .= 's'; }
    if ($filter_sts  !== '') { $where[] = 'wo.status = ?';   $params[] = $filter_sts;  $types .= 's'; }
    if ($filter_pri  !== '') { $where[] = 'wo.priority = ?'; $params[] = $filter_pri;  $types .= 's'; }

    $sql = "SELECT wo.*, m.name AS asset_name, m.code AS asset_code
            FROM work_orders wo
            LEFT JOIN machines m ON wo.machine_id = m.id"
        . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
        . " ORDER BY wo.date_register DESC";

    $stmt = $db->prepare($sql);
    if ($params) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $work_orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $db->close();
}