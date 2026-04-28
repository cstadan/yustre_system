<?php
// ================================================
// ALERTS SHOP CONTROLLER
// ================================================

$alerts = [];

try {
    $shop_db = new mysqli('db5019772005.hosting-data.io', 'dbu4236696', 'NG8936ngr82NbplE21gnp2TG5hjsg84sht73y3y3', 'dbs15332258');

    if (!$shop_db->connect_error) {

        // 1. Parts OUT OF STOCK
        $res = $shop_db->query("SELECT name, stock, unit FROM parts WHERE stock = 0");
        if ($res) while ($row = $res->fetch_assoc()) {
            $alerts[] = [
                'severity' => 'danger',
                'icon' => '🚨',
                'label' => 'OUT OF STOCK',
                'message' => $row['name'] . ' — 0 ' . $row['unit'] . ' remaining',
                'module' => 'Parts'
            ];
        }

        // 2. Parts LOW STOCK
        $res = $shop_db->query("SELECT name, stock, min_stock, unit FROM parts WHERE stock > 0 AND stock <= min_stock");
        if ($res) while ($row = $res->fetch_assoc()) {
            $alerts[] = [
                'severity' => 'warning',
                'icon' => '⚠️',
                'label' => 'LOW STOCK',
                'message' => $row['name'] . ' — ' . $row['stock'] . ' ' . $row['unit'] . ' left (min: ' . $row['min_stock'] . ')',
                'module' => 'Parts'
            ];
        }

        // 3. Work Orders EMERGENCY
        $res = $shop_db->query(
            "SELECT wo.code, wo.order_number, m.name AS machine_name
             FROM work_orders wo LEFT JOIN machines m ON wo.machine_id = m.id
             WHERE wo.type = 'emergency' AND wo.status != 'closed'"
        );
        if ($res) while ($row = $res->fetch_assoc()) {
            $on = $row['order_number'] ?? $row['code'];
            $alerts[] = [
                'severity' => 'danger',
                'icon' => '🚨',
                'label' => 'EMERGENCY ORDER',
                'message' => $on . ' — ' . ($row['machine_name'] ?? 'Unknown') . ' needs urgent attention',
                'module' => 'Work Orders'
            ];
        }

        // 4. Work Orders CRITICAL priority
        $res = $shop_db->query(
            "SELECT wo.code, wo.order_number, m.name AS machine_name
             FROM work_orders wo LEFT JOIN machines m ON wo.machine_id = m.id
             WHERE wo.priority = 'critical' AND wo.status != 'closed'"
        );
        if ($res) while ($row = $res->fetch_assoc()) {
            $on = $row['order_number'] ?? $row['code'];
            $alerts[] = [
                'severity' => 'danger',
                'icon' => '🔴',
                'label' => 'CRITICAL PRIORITY',
                'message' => $on . ' — ' . ($row['machine_name'] ?? 'Unknown'),
                'module' => 'Work Orders'
            ];
        }

        // 5. Work Orders WAITING PARTS
        $res = $shop_db->query(
            "SELECT wo.code, wo.order_number, m.name AS machine_name
             FROM work_orders wo LEFT JOIN machines m ON wo.machine_id = m.id
             WHERE wo.status = 'waiting_parts'"
        );
        if ($res) while ($row = $res->fetch_assoc()) {
            $on = $row['order_number'] ?? $row['code'];
            $alerts[] = [
                'severity' => 'info',
                'icon' => '🔧',
                'label' => 'WAITING PARTS',
                'message' => $on . ' — ' . ($row['machine_name'] ?? 'Unknown') . ' waiting for parts',
                'module' => 'Work Orders'
            ];
        }

        // 6. Work Orders HIGH priority (max 3)
        $res = $shop_db->query(
            "SELECT wo.code, wo.order_number, m.name AS machine_name
             FROM work_orders wo LEFT JOIN machines m ON wo.machine_id = m.id
             WHERE wo.priority = 'high' AND wo.status NOT IN ('closed','waiting_parts')
             LIMIT 3"
        );
        if ($res) while ($row = $res->fetch_assoc()) {
            $on = $row['order_number'] ?? $row['code'];
            $alerts[] = [
                'severity' => 'warning',
                'icon' => '🟠',
                'label' => 'HIGH PRIORITY',
                'message' => $on . ' — ' . ($row['machine_name'] ?? 'Unknown'),
                'module' => 'Work Orders'
            ];
        }

        // 7. Work Orders OVERDUE
        $res = $shop_db->query(
            "SELECT wo.code, wo.order_number, wo.due_date, m.name AS machine_name
             FROM work_orders wo LEFT JOIN machines m ON wo.machine_id = m.id
             WHERE wo.due_date IS NOT NULL AND wo.due_date < CURDATE() AND wo.status != 'closed'"
        );
        if ($res) while ($row = $res->fetch_assoc()) {
            $on = $row['order_number'] ?? $row['code'];
            $alerts[] = [
                'severity' => 'danger',
                'icon' => '📅',
                'label' => 'OVERDUE',
                'message' => $on . ' — ' . ($row['machine_name'] ?? 'Unknown') . ' (due: ' . $row['due_date'] . ')',
                'module' => 'Work Orders'
            ];
        }

        // 8. Work Orders DUE SOON (within 3 days)
        $res = $shop_db->query(
            "SELECT wo.code, wo.order_number, wo.due_date, m.name AS machine_name
             FROM work_orders wo LEFT JOIN machines m ON wo.machine_id = m.id
             WHERE wo.due_date IS NOT NULL
               AND wo.due_date >= CURDATE()
               AND wo.due_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)
               AND wo.status != 'closed'"
        );
        if ($res) while ($row = $res->fetch_assoc()) {
            $on = $row['order_number'] ?? $row['code'];
            $alerts[] = [
                'severity' => 'warning',
                'icon' => '⏰',
                'label' => 'DUE SOON',
                'message' => $on . ' — ' . ($row['machine_name'] ?? 'Unknown') . ' (due: ' . $row['due_date'] . ')',
                'module' => 'Work Orders'
            ];
        }

        $shop_db->close();
    }
} catch (Exception $e) {
    // Silent fail
}