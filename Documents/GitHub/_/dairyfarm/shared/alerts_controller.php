<?php
// ================================================
// ALERTS CONTROLLER
// Queries: low medicine stock + calves without colostrum
// Used by: dashboard_clinic.php
// ================================================
$alerts = [];
// ── MEDICINES DB ─────────────────────────────────
try {
    $pdo_med = new PDO("mysql:host=db5019772005.hosting-data.io;dbname=dbs15332258;charset=utf8", 'dbu4236696', 'NG8936ngr82NbplE21gnp2TG5hjsg84sht73y3y3');
    $pdo_med->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Stock bajo (menos de 10 unidades)
    $stmt = $pdo_med->query("SELECT name, stock, unit FROM medicines WHERE stock < 10 ORDER BY stock ASC");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $med) {
        $severity = $med['stock'] == 0 ? 'danger' : 'warning';
        $label    = $med['stock'] == 0 ? 'OUT OF STOCK' : 'LOW STOCK';
        $alerts[] = [
            'severity' => $severity,
            'icon'     => $med['stock'] == 0 ? '🚨' : '⚠️',
            'label'    => $label,
            'message'  => "<strong>{$med['name']}</strong> — {$med['stock']} {$med['unit']} remaining",
            'module'   => 'Medicines',
        ];
    }
} catch (PDOException $e) {
    // Silently skip if DB unavailable
}
// ── CALVES + COLOSTRUM DB ─────────────────────────
try {
    $pdo_calves = new PDO("mysql:host=db5019772005.hosting-data.io;dbname=dbs15332258;charset=utf8", 'dbu4236696', 'NG8936ngr82NbplE21gnp2TG5hjsg84sht73y3y3');
    $pdo_calves->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo_col = new PDO("mysql:host=db5019772005.hosting-data.io;dbname=dbs15332258;charset=utf8", 'dbu4236696', 'NG8936ngr82NbplE21gnp2TG5hjsg84sht73y3y3');
    $pdo_col->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // IDs de becerros con colostrum registrado
    $col_ids = $pdo_col->query("SELECT DISTINCT calf_id FROM colostrum")
        ->fetchAll(PDO::FETCH_COLUMN);
    // Becerros sin colostrum
    if (!empty($col_ids)) {
        $placeholders = implode(',', array_fill(0, count($col_ids), '?'));
        $stmt = $pdo_calves->prepare("SELECT id FROM calves WHERE id NOT IN ($placeholders) ORDER BY id");
        $stmt->execute($col_ids);
    } else {
        $stmt = $pdo_calves->query("SELECT id FROM calves ORDER BY id");
    }
    $no_colostrum = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($no_colostrum as $calf_id) {
        $alerts[] = [
            'severity' => 'info',
            'icon'     => '🍼',
            'label'    => 'NO COLOSTRUM',
            'message'  => "Calf <strong>{$calf_id}</strong> has no colostrum record",
            'module'   => 'Calves',
        ];
    }
} catch (PDOException $e) {
    // Silently skip if DB unavailable
}