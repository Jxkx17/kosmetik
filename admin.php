<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require_admin();
$products = db_all('SELECT id, product_code, product_name, product_img_name, qty, price FROM products ORDER BY id');
$pageTitle = 'Administration';
$active = 'admin';
require __DIR__ . '/includes/header.php';
?>
<section class="container page">
  <div class="page-head">
    <h1>Gestion des stocks</h1>
    <p class="muted">Saisissez une variation (ex. <code>10</code> pour ajouter, <code>-3</code> pour retirer), puis enregistrez.</p>
  </div>
  <form method="post" action="admin-update.php" class="card table-wrap">
    <?= csrf_field() ?>
    <table class="table">
      <thead><tr><th>Produit</th><th>Code</th><th class="num">Prix</th><th class="num">Stock</th><th class="num">Variation</th></tr></thead>
      <tbody>
        <?php foreach ($products as $p): ?>
          <tr>
            <td data-label="Produit" class="admin-product">
              <img src="images/products/<?= e(basename($p['product_img_name'])) ?>" alt="" width="40" height="40">
              <?= e($p['product_name']) ?>
            </td>
            <td data-label="Code"><?= e($p['product_code']) ?></td>
            <td data-label="Prix" class="num"><?= e(money($p['price'])) ?></td>
            <td data-label="Stock" class="num"><span class="<?= (int) $p['qty'] <= 10 ? 'low-stock' : '' ?>"><?= (int) $p['qty'] ?></span></td>
            <td data-label="Variation" class="num">
              <label class="sr-only" for="q<?= (int) $p['id'] ?>">Variation de stock pour <?= e($p['product_name']) ?></label>
              <input class="input-small" type="number" step="1" min="-99999" max="99999" id="q<?= (int) $p['id'] ?>" name="quantity[<?= (int) $p['id'] ?>]" placeholder="0">
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="form-actions"><button type="submit" class="btn">Enregistrer les stocks</button></div>
  </form>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
