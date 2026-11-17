<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require_post();

$email    = post_str('email');
$password = (string) ($_POST['pwd'] ?? '');
$_SESSION['old'] = ['email' => $email];

// Limitation simple des tentatives (anti force brute)
$lockedUntil = $_SESSION['login_locked_until'] ?? 0;
if ($lockedUntil > time()) {
    flash('error', 'Trop de tentatives. Réessayez dans ' . ceil(($lockedUntil - time()) / 60) . ' minute(s).');
    redirect('login.php');
}

$user = db_one('SELECT id, email, password, fname, type FROM users WHERE email = ?', 's', [$email]);

$ok = false;
if ($user) {
    $stored = $user['password'];
    if (password_get_info($stored)['algo'] !== null) {
        $ok = password_verify($password, $stored);
    } else {
        // Ancien mot de passe en clair : vérifier puis migrer vers un hash.
        $ok = hash_equals($stored, $password);
    }
    if ($ok && (password_get_info($stored)['algo'] === null || password_needs_rehash($stored, PASSWORD_DEFAULT))) {
        db_query('UPDATE users SET password = ? WHERE id = ?', 'si', [password_hash($password, PASSWORD_DEFAULT), $user['id']]);
    }
} else {
    password_verify($password, password_hash('dummy', PASSWORD_DEFAULT)); // temps de réponse constant
}

if (!$ok) {
    $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
    if ($_SESSION['login_attempts'] >= 5) {
        $_SESSION['login_locked_until'] = time() + 300;
        $_SESSION['login_attempts'] = 0;
    }
    flash('error', 'E-mail ou mot de passe incorrect.');
    redirect('login.php');
}

login_user($user);
unset($_SESSION['old']);
flash('success', 'Bienvenue, ' . $user['fname'] . ' !');
$next = $_SESSION['after_login'] ?? ($user['type'] === 'admin' ? 'admin.php' : 'products.php');
unset($_SESSION['after_login']);
redirect(in_array($next, ['cart.php', 'admin.php', 'products.php', 'orders.php'], true) ? $next : 'products.php');
