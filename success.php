<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
$kind = $_SESSION['success'] ?? null;
unset($_SESSION['success']);
if ($kind === null) {
    redirect('index.php');
}
$pageTitle = 'Merci';
require __DIR__ . '/includes/header.php';
?>
<section class="container page">
  <div class="card empty success">
    <div class="success-icon" aria-hidden="true">✓</div>
    <?php if ($kind === 'order'): ?>
      <h1>Commande confirmée</h1>
      <p>Merci ! Vous réglerez à la livraison. Un récapitulatif vous a été envoyé par e-mail (pensez à vérifier vos spams).</p>
      <div class="form-actions center">
        <a class="btn" href="orders.php">Voir mes commandes</a>
        <a class="btn btn-ghost" href="products.php">Continuer mes achats</a>
      </div>
    <?php else: ?>
      <h1>Modifications enregistrées</h1>
      <div class="form-actions center"><a class="btn" href="admin.php">Retour à l'administration</a></div>
    <?php endif; ?>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
