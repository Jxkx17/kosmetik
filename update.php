<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require_post();
$u = require_login();

$me = db_one('SELECT email, password FROM users WHERE id = ?', 'i', [$u['id']]);
$fname   = post_str('fname', 100);
$lname   = post_str('lname', 100);
$address = post_str('address');
$city    = post_str('city', 100);
$pin     = post_str('pin', 10);
$email   = mb_strtolower(post_str('email'));
$newPwd  = (string) ($_POST['pwd'] ?? '');
$curPwd  = (string) ($_POST['opwd'] ?? '');

$errors = [];
if ($fname === '' || $lname === '' || $address === '' || $city === '') $errors[] = 'Tous les champs sont requis.';
if (!preg_match('/^\d{1,10}$/', $pin)) $errors[] = 'Code postal invalide.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail invalide.';
if ($newPwd !== '' && strlen($newPwd) < 8) $errors[] = 'Le nouveau mot de passe doit contenir au moins 8 caractères.';

$sensitive = ($email !== $me['email']) || $newPwd !== '';
if (!$errors && $sensitive) {
    $stored = $me['password'];
    $valid = password_get_info($stored)['algo'] !== null ? password_verify($curPwd, $stored) : hash_equals($stored, $curPwd);
    if (!$valid) $errors[] = 'Mot de passe actuel incorrect.';
}
if (!$errors && $email !== $me['email'] && db_one('SELECT id FROM users WHERE email = ? AND id <> ?', 'si', [$email, $u['id']])) {
    $errors[] = 'Cet e-mail est déjà utilisé.';
}

if ($errors) {
    foreach ($errors as $err) flash('error', $err);
    redirect('account.php');
}

$db = db();
$db->begin_transaction();
db_query('UPDATE users SET fname = ?, lname = ?, address = ?, city = ?, pin = ?, email = ? WHERE id = ?',
    'ssssisi', [$fname, $lname, $address, $city, (int) $pin, $email, $u['id']]);
if ($email !== $me['email']) {
    db_query('UPDATE orders SET email = ? WHERE email = ?', 'ss', [$email, $me['email']]);
}
if ($newPwd !== '') {
    db_query('UPDATE users SET password = ? WHERE id = ?', 'si', [password_hash($newPwd, PASSWORD_DEFAULT), $u['id']]);
}
$db->commit();

if ($sensitive) session_regenerate_id(true);
$_SESSION['fname'] = $fname;
$_SESSION['email'] = $email;
flash('success', 'Vos informations ont été mises à jour.');
redirect('account.php');
