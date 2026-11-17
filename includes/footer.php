</main>
<footer class="site-footer">
  <div class="container footer-inner">
    <p class="brand brand-small"><?= e($CONFIG['app_name']) ?></p>
    <nav aria-label="Liens de pied de page">
      <a href="products.php">Produits</a>
      <a href="about.php">À propos</a>
      <a href="contact.php">Contact</a>
    </nav>
    <p class="muted">&copy; <?= date('Y') ?> <?= e($CONFIG['app_name']) ?>. Tous droits réservés.</p>
  </div>
</footer>
<?php unset($_SESSION['old']); ?>
</body>
</html>
