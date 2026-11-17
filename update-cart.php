<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require_post();

$action = $_POST['action'] ?? '';
$id     = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$back   = ($_POST['return'] ?? '') === 'products' ? 'products.php' : 'cart.php';
$cart   = cart();

if ($action === 'empty') {
    unset($_SESSION['cart']);
    flash('info', 'Votre panier a été vidé.');
    redirect('cart.php');
}

if ($id === false || $id === null) {
    redirect($back);
}

$product = db_one('SELECT product_name, qty FROM products WHERE id = ?', 'i', [$id]);
if (!$product) {
    unset($cart[$id]);
    $_SESSION['cart'] = $cart;
    redirect($back);
}

$current = $cart[$id] ?? 0;
switch ($action) {
    case 'add':
        if ($current + 1 <= (int) $product['qty']) {
            $cart[$id] = $current + 1;
            if ($back === 'products.php') flash('success', $product['product_name'] . ' ajouté au panier.');
        } else {
            flash('error', 'Stock insuffisant pour ' . $product['product_name'] . '.');
        }
        break;
    case 'remove':
        if ($current > 1) $cart[$id] = $current - 1; else unset($cart[$id]);
        break;
    case 'delete':
        unset($cart[$id]);
        break;
}

if ($cart) $_SESSION['cart'] = $cart; else unset($_SESSION['cart']);
redirect($back);
