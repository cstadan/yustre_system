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

define('PART_CATEGORIES', ['Filtros','Aceites','Eléctrico','Hidráulico','Otros']);
define('PART_UNITS',      ['pcs','L','gal','kg','m','ft','box','set']);

function get_shop_db() {
    $db = new mysqli('db5019772005.hosting-data.io', 'dbu4236696', 'NG8936ngr82NbplE21gnp2TG5hjsg84sht73y3y3', 'dbs15332258');
    if ($db->connect_error) return null;
    return $db;
}

function generate_part_code($db) {
    do {
        $code = 'PRT-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        $stmt = $db->prepare("SELECT id FROM parts WHERE code = ?");
        $stmt->bind_param('s', $code);
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

        // ── ADD PART ───────────────────────────────────────────────
        if ($action === 'add_part') {
            $code      = generate_part_code($db);
            $name      = trim($_POST['name'] ?? '');
            $category  = trim($_POST['category'] ?? '');
            $unit      = trim($_POST['unit'] ?? 'pcs');
            $stock     = intval($_POST['stock'] ?? 0);
            $min_stock = intval($_POST['min_stock'] ?? 0);
            $comments  = trim($_POST['comments'] ?? '');

            if (empty($name)) {
                $error = 'Part name is required.';
            } else {
                $stmt = $db->prepare(
                    "INSERT INTO parts (code, name, category, unit, stock, min_stock, comments)
                     VALUES (?,?,?,?,?,?,?)"
                );
                $stmt->bind_param('ssssiis', $code, $name, $category, $unit, $stock, $min_stock, $comments);
                if ($stmt->execute()) {
                    $part_id = $db->insert_id;
                    // Log initial stock as "in" movement if stock > 0
                    if ($stock > 0) {
                        $reason = 'Initial stock';
                        $by     = $_SESSION['user_name'] ?? 'System';
                        $s2 = $db->prepare(
                            "INSERT INTO parts_movements (part_id, type, quantity, reason, created_by)
                             VALUES (?, 'in', ?, ?, ?)"
                        );
                        $s2->bind_param('iiss', $part_id, $stock, $reason, $by);
                        $s2->execute();
                        $s2->close();
                    }
                    $success = "Part <strong>{$name}</strong> added. ID: <strong>{$code}</strong>";
                } else {
                    $error = 'Error adding part: ' . $stmt->error;
                }
                $stmt->close();
            }
        }

        // ── EDIT PART ──────────────────────────────────────────────
        elseif ($action === 'edit_part') {
            $id        = intval($_POST['id'] ?? 0);
            $name      = trim($_POST['name'] ?? '');
            $category  = trim($_POST['category'] ?? '');
            $unit      = trim($_POST['unit'] ?? 'pcs');
            $min_stock = intval($_POST['min_stock'] ?? 0);
            $comments  = trim($_POST['comments'] ?? '');

            if (empty($name) || $id <= 0) {
                $error = 'Part name is required.';
            } else {
                $stmt = $db->prepare(
                    "UPDATE parts SET name=?, category=?, unit=?, min_stock=?, comments=? WHERE id=?"
                );
                $stmt->bind_param('sssisi', $name, $category, $unit, $min_stock, $comments, $id);
                if ($stmt->execute()) {
                    $success = "Part <strong>{$name}</strong> updated.";
                } else {
                    $error = 'Error updating part.';
                }
                $stmt->close();
            }
        }

        // ── STOCK IN ───────────────────────────────────────────────
        elseif ($action === 'stock_in') {
            $id     = intval($_POST['id'] ?? 0);
            $qty    = intval($_POST['quantity'] ?? 0);
            $reason = trim($_POST['reason'] ?? 'Purchase/Receipt');
            $by     = $_SESSION['user_name'] ?? 'System';

            if ($id <= 0 || $qty <= 0) {
                $error = 'Valid quantity required.';
            } else {
                $db->begin_transaction();
                try {
                    $s1 = $db->prepare("UPDATE parts SET stock = stock + ? WHERE id = ?");
                    $s1->bind_param('ii', $qty, $id);
                    $s1->execute();
                    $s1->close();

                    $s2 = $db->prepare(
                        "INSERT INTO parts_movements (part_id, type, quantity, reason, created_by)
                         VALUES (?, 'in', ?, ?, ?)"
                    );
                    $s2->bind_param('iiss', $id, $qty, $reason, $by);
                    $s2->execute();
                    $s2->close();
                    $db->commit();
                    $success = "Added <strong>{$qty}</strong> units to stock.";
                } catch (Exception $e) {
                    $db->rollback();
                    $error = 'Error updating stock.';
                }
            }
        }

        // ── STOCK OUT (manual) ─────────────────────────────────────
        elseif ($action === 'stock_out') {
            $id     = intval($_POST['id'] ?? 0);
            $qty    = intval($_POST['quantity'] ?? 0);
            $reason = trim($_POST['reason'] ?? 'Manual adjustment');
            $by     = $_SESSION['user_name'] ?? 'System';

            if ($id <= 0 || $qty <= 0) {
                $error = 'Valid quantity required.';
            } else {
                // Check enough stock
                $s0 = $db->prepare("SELECT stock FROM parts WHERE id = ?");
                $s0->bind_param('i', $id);
                $s0->execute();
                $current = $s0->get_result()->fetch_assoc()['stock'] ?? 0;
                $s0->close();

                if ($qty > $current) {
                    $error = "Not enough stock. Current: {$current}";
                } else {
                    $db->begin_transaction();
                    try {
                        $s1 = $db->prepare("UPDATE parts SET stock = stock - ? WHERE id = ?");
                        $s1->bind_param('ii', $qty, $id);
                        $s1->execute();
                        $s1->close();

                        $s2 = $db->prepare(
                            "INSERT INTO parts_movements (part_id, type, quantity, reason, created_by)
                             VALUES (?, 'out', ?, ?, ?)"
                        );
                        $s2->bind_param('iiss', $id, $qty, $reason, $by);
                        $s2->execute();
                        $s2->close();
                        $db->commit();
                        $success = "Removed <strong>{$qty}</strong> units from stock.";
                    } catch (Exception $e) {
                        $db->rollback();
                        $error = 'Error updating stock.';
                    }
                }
            }
        }

        // ── DELETE PART ────────────────────────────────────────────
        elseif ($action === 'delete_part') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $db->prepare("DELETE FROM parts WHERE id = ?");
                $stmt->bind_param('i', $id);
                if ($stmt->execute()) {
                    header('Location: parts_inventory.php?deleted=1');
                    exit();
                } else {
                    $error = 'Error deleting part.';
                }
                $stmt->close();
            }
        }

        $db->close();
    }
}

// ── FETCH DATA ─────────────────────────────────────────────────────────────
$parts      = [];
$view_part  = null;
$movements  = [];

$search      = trim($_GET['search'] ?? '');
$filter_cat  = trim($_GET['category'] ?? '');
$filter_stk  = trim($_GET['stock_filter'] ?? ''); // 'ok','low','out'
$view_id     = intval($_GET['view'] ?? 0);

if (isset($_GET['deleted'])) $success = 'Part deleted successfully.';

$db = get_shop_db();
if ($db) {

    // Single part detail + movements
    if ($view_id > 0) {
        $stmt = $db->prepare("SELECT * FROM parts WHERE id = ?");
        $stmt->bind_param('i', $view_id);
        $stmt->execute();
        $view_part = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($view_part) {
            $stmt = $db->prepare(
                "SELECT pm.*, wo.order_number, wo.code AS wo_code
                 FROM parts_movements pm
                 LEFT JOIN work_orders wo ON pm.work_order_id = wo.id
                 WHERE pm.part_id = ?
                 ORDER BY pm.date DESC
                 LIMIT 50"
            );
            $stmt->bind_param('i', $view_id);
            $stmt->execute();
            $movements = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }
    }

    // Build WHERE for list
    $where  = [];
    $params = [];
    $types  = '';

    if ($search !== '') {
        $like    = '%' . $search . '%';
        $where[] = '(code LIKE ? OR name LIKE ? OR comments LIKE ?)';
        $params  = array_merge($params, [$like, $like, $like]);
        $types  .= 'sss';
    }
    if ($filter_cat !== '') {
        $where[]  = 'category = ?';
        $params[] = $filter_cat;
        $types   .= 's';
    }
    if ($filter_stk === 'out') {
        $where[] = 'stock = 0';
    } elseif ($filter_stk === 'low') {
        $where[] = 'stock > 0 AND stock <= min_stock';
    } elseif ($filter_stk === 'ok') {
        $where[] = 'stock > min_stock';
    }

    $sql  = "SELECT * FROM parts" . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . " ORDER BY name";
    $stmt = $db->prepare($sql);
    if ($params) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $parts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $db->close();
}