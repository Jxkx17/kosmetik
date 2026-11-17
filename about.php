<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'À propos';
$active = 'about';
require __DIR__ . '/includes/header.php';
?>
<section class="container page prose">
  <h1>À propos de KOSMETIK</h1>
  <p>KOSMETIK est un projet de site de commerce électronique. Les produits présentés sont fictifs : ce projet donne un aperçu de ce à quoi ressemblerait la mise en œuvre réelle d'une boutique en ligne.</p>
  <p>Pourquoi KOSMETIK ? Pour conserver l'essence même du projet, centré sur la vente de produits cosmétiques.</p>
  <a class="btn" href="products.php">Voir les produits</a>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
