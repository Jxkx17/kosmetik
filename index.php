<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
$featured = db_all('SELECT id, product_name, product_img_name, price FROM products WHERE qty > 0 ORDER BY id LIMIT 3');
$active = 'home';
require __DIR__ . '/includes/header.php';
?>
<section class="hero">
  <div class="slideshow" aria-roledescription="carrousel" aria-label="Nos univers">
    <img class="slide is-active" src="images/kos.jpg" alt="">
    <img class="slide" src="images/kos1.jpg" alt="">
    <img class="slide" src="images/kos2.jpg" alt="">
  </div>
  <div class="hero-overlay"></div>
  <div class="container hero-content">
    <p class="eyebrow">Cosmétiques naturels</p>
    <h1>Prenez soin de votre peau, simplement.</h1>
    <p class="hero-sub">Baumes, lotions et parfums sélectionnés avec soin. Paiement à la livraison.</p>
    <a class="btn btn-light" href="products.php">Découvrir la boutique</a>
  </div>
  <div class="slide-dots" role="tablist" aria-label="Choisir une image">
    <button class="dot is-active" aria-label="Image 1"></button>
    <button class="dot" aria-label="Image 2"></button>
    <button class="dot" aria-label="Image 3"></button>
  </div>
</section>

<section class="container features">
  <div><h3>Ingrédients naturels</h3><p class="muted">Des formules douces pour les peaux sensibles.</p></div>
  <div><h3>Paiement à la livraison</h3><p class="muted">Vous payez seulement à la réception.</p></div>
  <div><h3>Service client</h3><p class="muted">Une question ? <a href="contact.php">Écrivez-nous</a>.</p></div>
</section>

<?php if ($featured): ?>
<section class="container page">
  <div class="page-head row-between">
    <h2>Nos incontournables</h2>
    <a href="products.php">Tout voir →</a>
  </div>
  <div class="product-grid">
    <?php foreach ($featured as $p): ?>
      <a class="product-card product-card-link" href="products.php">
        <div class="product-media"><img src="images/products/<?= e(basename($p['product_img_name'])) ?>" alt="<?= e($p['product_name']) ?>" loading="lazy" width="400" height="400"></div>
        <div class="product-body row-between"><span class="product-name"><?= e($p['product_name']) ?></span><span class="price"><?= e(money($p['price'])) ?></span></div>
      </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
