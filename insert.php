<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require_post();

$data = [
    'fname'   => post_str('fname', 100),
    'lname'   => post_str('lname', 100),
    'address' => post_str('address'),
    'city'    => post_str('city', 100),
    'pin'     => post_str('pin', 10),
    'email'   => mb_strtolower(post_str('email')),
];
$pwd = (string) ($_POST['pwd'] ?? '');
$_SESSION['old'] = $data;

$errors = [];
foreach (['fname' => 'Prénom', 'lname' => 'Nom', 'address' => 'Adresse', 'city' => 'Ville'] as $k => $label) {
    if ($data[$k] === '') $errors[] = "$label requis.";
}
if (!preg_match('/^\d{1,10}$/', $data['pin'])) $errors[] = 'Code postal invalide.';
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail invalide.';
if (strlen($pwd) < 8) $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';

if (!$errors && db_one('SELECT id FROM users WHERE email = ?', 's', [$data['email']])) {
    $errors[] = 'Un compte existe déjà avec cet e-mail.';
}

if ($errors) {
    foreach ($errors as $err) flash('error', $err);
    redirect('register.php');
}

db_query(
    'INSERT INTO users (fname, lname, address, city, pin, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)',
    'ssssiss',
    [$data['fname'], $data['lname'], $data['address'], $data['city'], (int) $data['pin'], $data['email'], password_hash($pwd, PASSWORD_DEFAULT)]
);

unset($_SESSION['old']);
flash('success', 'Compte créé ! Vous pouvez maintenant vous connecter.');
$_SESSION['old'] = ['email' => $data['email']];
redirect('login.php');
