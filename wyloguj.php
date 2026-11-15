<?php
session_start();

$_SESSION = [];                                   // wyczyść dane sesji
if (ini_get('session.use_cookies')) {             // usuń ciasteczko sesji
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();

header('Location: login.php');
exit;