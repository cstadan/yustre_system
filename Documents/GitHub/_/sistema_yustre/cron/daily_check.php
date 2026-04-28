<?php
// ================================================
// CRON JOB - DAILY STOCK CHECK
// Runs every day at 7:00 AM
// ================================================
// IONOS Cron Job Configuration:
// Command: /usr/bin/php /homepages/1/d4299673130/htdocs/sistema_yustre/cron/daily_check.php
// Schedule: 0 7 * * *
// ================================================

// Admin email to receive alerts
$admin_email = 'adan.test00@gmail.com';
$alerts = [];

// IONOS Database credentials
$db_host = 'db5019772005.hosting-data.io';
$db_name = 'dbs15332258';
$db_user = 'dbu4236696';
$db_pass = 'NG8936ngr82NbplE21gnp2TG5hjsg84sht73y3y3';

// ── MEDICINES - Stock bajo ────────────────────────
try {
    $pdo_med = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo_med->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo_med->query("SELECT name, stock, unit FROM medicines WHERE stock < 10 ORDER BY stock ASC");
    $meds = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($meds as $med) {
        $label    = $med['stock'] == 0 ? '[OUT OF STOCK]' : '[LOW STOCK]';
        $alerts[] = "{$label} {$med['name']} — {$med['stock']} {$med['unit']} remaining";
    }
} catch (PDOException $e) {
    $alerts[] = "[ERROR] Could not connect to medicines database: " . $e->getMessage();
}

// ── CALVES - Sin colostrum ────────────────────────
try {
    $pdo_calves = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo_calves->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo_col = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo_col->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $col_ids = $pdo_col->query("SELECT DISTINCT calf_id FROM colostrum")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($col_ids)) {
        $placeholders = implode(',', array_fill(0, count($col_ids), '?'));
        $stmt = $pdo_calves->prepare("SELECT id FROM calves WHERE id NOT IN ($placeholders)");
        $stmt->execute($col_ids);
    } else {
        $stmt = $pdo_calves->query("SELECT id FROM calves");
    }
    
    $no_colostrum = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($no_colostrum as $calf_id) {
        $alerts[] = "[NO COLOSTRUM] Calf {$calf_id} has no colostrum record";
    }
} catch (PDOException $e) {
    $alerts[] = "[ERROR] Could not connect to calves database: " . $e->getMessage();
}

// ── SEND EMAIL if there are alerts ───────────────
if (!empty($alerts)) {
    $date    = date('Y-m-d H:i');
    $subject = "⚠️ Sistema Yustre — Daily Alert Report ({$date})";
    
    $body  = "Daily alert report generated at {$date}\n";
    $body .= str_repeat("=", 50) . "\n\n";
    
    foreach ($alerts as $alert) {
        $body .= "• {$alert}\n";
    }
    
    $body .= "\n" . str_repeat("=", 50) . "\n";
    $body .= "Sistema Yustre - Cattle Management System\n";
    $body .= "This is an automated email. Do not reply.\n";
    
    $headers = "From: sistema@u-storage-cs.com\r\n";
    $headers .= "Reply-To: noreply@u-storage-cs.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send email
    $mail_sent = mail($admin_email, $subject, $body, $headers);
    
    if ($mail_sent) {
        echo "[" . date('Y-m-d H:i:s') . "] ✓ Email sent to {$admin_email} with " . count($alerts) . " alert(s).\n";
    } else {
        echo "[" . date('Y-m-d H:i:s') . "] ✗ Failed to send email.\n";
    }
} else {
    echo "[" . date('Y-m-d H:i:s') . "] ✓ No alerts found. No email sent.\n";
}

// Log execution
$log_file = __DIR__ . '/cron_log.txt';
$log_msg = date('Y-m-d H:i:s') . " - Cron executed. Alerts: " . count($alerts) . "\n";
file_put_contents($log_file, $log_msg, FILE_APPEND);
?>