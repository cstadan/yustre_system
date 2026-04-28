<?php
// ================================================
// CSRF HELPER
// Genera y verifica tokens para proteger formularios
// ================================================

function generate_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token(): void
{
    if (
        empty($_POST['csrf_token']) ||
        empty($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        http_response_code(403);
        die('<h2>⛔ Invalid request: CSRF token mismatch.</h2>');
    }
    // Regenerate after each POST to prevent reuse
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
