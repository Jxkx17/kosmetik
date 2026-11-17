<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
$u = require_login();
$orders = db_all('SELECT id, product_code, product_name, price, units, total, date FROM orders WHERE email = ? ORDER BY date DESC, id DESC', 's', [$u['email']]);
$pageTitle = 'Mes commandes';
$active = 'orders';
require __DIR__ . '/includes/header.php';
?>
<section class="container page">
  <div class="page-head"><h1>Mes commandes</h1></div>
  <?php if (!$orders): ?>
    <div class="card empty">
      <p>Vous n'avez encore passé aucune commande.</p>
      <a class="btn" href="products.php">Découvrir nos produits</a>
    </div>
  <?php else: ?>
    <div class="card table-wrap">
      <table class="table">
        <thead><tr><th>N°</th><th>Date</th><th>Produit</th><th class="num">Prix unitaire</th><th class="num">Qté</th><th class="num">Total</th></tr></thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td data-label="N°">#<?= (int) $o['id'] ?></td>
              <td data-label="Date"><?= e(date('d/m/Y H:i', strtotime($o['date']))) ?></td>
              <td data-label="Produit"><?= e($o['product_name']) ?> <span class="muted small"><?= e($o['product_code']) ?></span></td>
              <td data-label="Prix unitaire" class="num"><?= e(money($o['price'])) ?></td>
              <td data-label="Qté" class="num"><?= (int) $o['units'] ?></td>
              <td data-label="Total" class="num"><strong><?= e(money($o['total'])) ?></strong></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
