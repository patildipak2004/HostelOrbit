<?php
ini_set('session.gc_maxlifetime', 86400);
ini_set('session.cookie_lifetime', 86400);
ini_set('session.use_only_cookies', 1);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1000);
session_name('SMARTHOSTEL_SESSION');
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 86400)) {
    session_unset();
    session_destroy();
    header('Location: ../login.php');
    exit;
}
$_SESSION['last_activity'] = time();

session_regenerate_id(true);

function getRealIP() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
        return $_SERVER['HTTP_X_REAL_IP'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
?>
