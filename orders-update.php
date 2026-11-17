<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require_post();
$u = require_login();

$cart = cart();
if (!$cart) {
    redirect('cart.php');
}

$db = db();
$db->begin_transaction();
try {
    $ordered = [];
    foreach ($cart as $id => $quantity) {
        $id = (int) $id;
        $quantity = (int) $quantity;
        if ($quantity < 1) continue;

        // Verrouille la ligne pour éviter la survente en cas de commandes simultanées
        $p = db_one('SELECT id, product_code, product_name, product_desc, qty, price FROM products WHERE id = ? FOR UPDATE', 'i', [$id]);
        if (!$p || (int) $p['qty'] < $quantity) {
            $db->rollback();
            flash('error', 'Stock insuffisant pour ' . ($p['product_name'] ?? 'un article') . '. Ajustez votre panier.');
            redirect('cart.php');
        }
        $cost = $p['price'] * $quantity;
        db_query('INSERT INTO orders (product_code, product_name, product_desc, price, units, total, email) VALUES (?, ?, ?, ?, ?, ?, ?)',
            'sssdids', [$p['product_code'], $p['product_name'], $p['product_desc'], $p['price'], $quantity, $cost, $u['email']]);
        db_query('UPDATE products SET qty = qty - ? WHERE id = ?', 'ii', [$quantity, $id]);
        $ordered[] = [$p['product_name'], $quantity, $cost];
    }
    $db->commit();
} catch (Throwable $ex) {
    $db->rollback();
    throw $ex;
}

unset($_SESSION['cart']);

// Reçu par e-mail (si PHPMailer est installé)
require __DIR__ . '/includes/mailer.php';
$body = '<h2>Merci pour votre commande</h2><ul>';
$sum = 0;
foreach ($ordered as [$name, $q, $c]) {
    $body .= '<li>' . e($name) . ' × ' . (int) $q . ' — ' . e(money($c)) . '</li>';
    $sum += $c;
}
$body .= '</ul><p><strong>Total : ' . e(money($sum)) . '</strong></p>';
send_email($u['email'], 'Votre commande ' . $CONFIG['app_name'], $body);

$_SESSION['success'] = 'order';
redirect('success.php');
