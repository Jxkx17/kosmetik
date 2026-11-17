<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Contact';
$active = 'contact';
require __DIR__ . '/includes/header.php';
$mail = $CONFIG['contact_email'];
?>
<section class="container page prose">
  <h1>Contact</h1>
  <p>Une question sur un produit ou une commande ? Écrivez-nous, nous répondons sous 48 h.</p>
  <a class="btn" href="mailto:<?= e($mail) ?>"><?= e($mail) ?></a>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
