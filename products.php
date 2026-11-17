<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
$products = db_all('SELECT id, product_code, product_name, product_desc, product_img_name, qty, price FROM products ORDER BY id');
$cart = cart();
$pageTitle = 'Produits';
$active = 'products';
require __DIR__ . '/includes/header.php';
?>
<section class="container page">
  <div class="page-head">
    <h1>Nos produits</h1>
    <p class="muted">Des soins pensés pour toutes les peaux.</p>
  </div>
  <?php if (!$products): ?>
    <p class="empty">Aucun produit pour le moment.</p>
  <?php else: ?>
  <div class="product-grid">
    <?php foreach ($products as $p):
      $inCart = $cart[$p['id']] ?? 0;
      $left = (int) $p['qty'] - $inCart; ?>
      <article class="product-card">
        <div class="product-media">
          <img src="images/products/<?= e(basename($p['product_img_name'])) ?>" alt="<?= e($p['product_name']) ?>" loading="lazy" width="400" height="400">
          <?php if ((int) $p['qty'] === 0): ?><span class="tag tag-out">Rupture</span>
          <?php elseif ((int) $p['qty'] <= 10): ?><span class="tag">Plus que <?= (int) $p['qty'] ?></span><?php endif; ?>
        </div>
        <div class="product-body">
          <p class="product-code"><?= e($p['product_code']) ?></p>
          <h2 class="product-name"><?= e($p['product_name']) ?></h2>
          <p class="product-desc"><?= e($p['product_desc']) ?></p>
          <div class="product-foot">
            <span class="price"><?= e(money($p['price'])) ?></span>
            <?php if ($left > 0): ?>
              <form method="post" action="update-cart.php">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                <input type="hidden" name="return" value="products">
                <button type="submit" class="btn btn-small">Ajouter<?= $inCart ? " ($inCart)" : '' ?></button>
              </form>
            <?php elseif ((int) $p['qty'] > 0): ?>
              <span class="muted small">Stock max. au panier</span>
            <?php else: ?>
              <button class="btn btn-small" disabled>Indisponible</button>
            <?php endif; ?>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
