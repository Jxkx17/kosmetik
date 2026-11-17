<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require_post();

$_SESSION = [];
$p = session_get_cookie_params();
setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
session_destroy();
redirect('index.php');
