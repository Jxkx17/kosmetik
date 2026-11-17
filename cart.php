<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';

$cart  = cart();
$lines = [];
$total = 0.0;
if ($cart) {
    $ids = array_map('intval', array_keys($cart));
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $rows = db_all("SELECT id, product_code, product_name, product_img_name, qty, price FROM products WHERE id IN ($placeholders)", str_repeat('i', count($ids)), $ids);
    foreach ($rows as $r) {
        $q = (int) $cart[$r['id']];
        $cost = $r['price'] * $q;
        $total += $cost;
        $lines[] = $r + ['quantity' => $q, 'cost' => $cost];
    }
}
if (!current_user()) $_SESSION['after_login'] = 'cart.php';

$pageTitle = 'Panier';
$active = 'cart';
require __DIR__ . '/includes/header.php';

function cart_btn(string $action, int $id, string $label, string $aria, string $class = 'qty-btn'): string
{
    return '<form method="post" action="update-cart.php" class="inline-form">' . csrf_field()
        . '<input type="hidden" name="action" value="' . e($action) . '"><input type="hidden" name="id" value="' . $id . '">'
        . '<button type="submit" class="' . e($class) . '" aria-label="' . e($aria) . '">' . $label . '</button></form>';
}
?>
<section class="container page">
  <div class="page-head"><h1>Votre panier</h1></div>

  <?php if (!$lines): ?>
    <div class="card empty">
      <p>Votre panier est vide.</p>
      <a class="btn" href="products.php">Découvrir nos produits</a>
    </div>
  <?php else: ?>
  <div class="cart-layout">
    <ul class="cart-list card">
      <?php foreach ($lines as $l): ?>
        <li class="cart-item">
          <img src="images/products/<?= e(basename($l['product_img_name'])) ?>" alt="" width="72" height="72">
          <div class="cart-info">
            <strong><?= e($l['product_name']) ?></strong>
            <span class="muted small"><?= e($l['product_code']) ?> · <?= e(money($l['price'])) ?> / unité</span>
          </div>
          <div class="qty">
            <?= cart_btn('remove', (int) $l['id'], '−', 'Retirer un ' . $l['product_name']) ?>
            <span aria-live="polite"><?= $l['quantity'] ?></span>
            <?= $l['quantity'] < (int) $l['qty'] ? cart_btn('add', (int) $l['id'], '+', 'Ajouter un ' . $l['product_name']) : '<button class="qty-btn" disabled aria-label="Stock maximum atteint">+</button>' ?>
          </div>
          <span class="cart-cost"><?= e(money($l['cost'])) ?></span>
          <?= cart_btn('delete', (int) $l['id'], '&times;', 'Supprimer ' . $l['product_name'], 'remove-btn') ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <aside class="card summary">
      <h2>Récapitulatif</h2>
      <div class="summary-row"><span>Sous-total</span><span><?= e(money($total)) ?></span></div>
      <div class="summary-row"><span>Livraison</span><span>Paiement à la livraison</span></div>
      <div class="summary-row summary-total"><span>Total</span><span><?= e(money($total)) ?></span></div>
      <?php if (current_user()): ?>
        <form method="post" action="orders-update.php" data-confirm="Confirmer la commande de <?= e(money($total)) ?> ?">
          <?= csrf_field() ?>
          <button type="submit" class="btn btn-block">Commander (paiement à la livraison)</button>
        </form>
      <?php else: ?>
        <a class="btn btn-block" href="login.php">Se connecter pour commander</a>
      <?php endif; ?>
      <a class="btn btn-ghost btn-block" href="products.php">Continuer mes achats</a>
      <form method="post" action="update-cart.php" data-confirm="Vider le panier ?">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="empty">
        <button type="submit" class="link-button danger">Vider le panier</button>
      </form>
    </aside>
  </div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
