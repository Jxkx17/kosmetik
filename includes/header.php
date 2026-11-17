<?php
/** @var string $pageTitle  @var string $active */
$pageTitle = $pageTitle ?? '';
$active    = $active ?? '';
$user      = current_user();
$count     = cart_count();

$links = [
    'products' => ['products.php', 'Produits'],
    'about'    => ['about.php', 'À propos'],
    'contact'  => ['contact.php', 'Contact'],
];
if ($user) {
    $links['orders'] = ['orders.php', 'Mes commandes'];
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ? $pageTitle . ' · ' . $CONFIG['app_name'] : $CONFIG['app_name']) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Inter:wght@400;500;600&display=swap">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/app.js" defer></script>
</head>
<body>
<a class="skip-link" href="#main">Aller au contenu</a>
<header class="site-header">
  <div class="container nav">
    <a class="brand" href="index.php"><?= e($CONFIG['app_name']) ?></a>
    <button class="nav-toggle" aria-expanded="false" aria-controls="nav-menu" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
    <nav id="nav-menu" class="nav-menu" aria-label="Navigation principale">
      <ul>
        <?php foreach ($links as $key => [$href, $label]): ?>
          <li><a href="<?= e($href) ?>"<?= $active === $key ? ' aria-current="page"' : '' ?>><?= e($label) ?></a></li>
        <?php endforeach; ?>
        <?php if ($user && is_admin()): ?>
          <li><a href="admin.php"<?= $active === 'admin' ? ' aria-current="page"' : '' ?>>Administration</a></li>
        <?php endif; ?>
      </ul>
      <ul class="nav-actions">
        <li>
          <a class="cart-link" href="cart.php"<?= $active === 'cart' ? ' aria-current="page"' : '' ?>>
            Panier<?php if ($count > 0): ?> <span class="badge" aria-label="<?= $count ?> article(s)"><?= $count ?></span><?php endif; ?>
          </a>
        </li>
        <?php if ($user): ?>
          <li><a href="account.php"<?= $active === 'account' ? ' aria-current="page"' : '' ?>>Bonjour, <?= e($user['fname']) ?></a></li>
          <li>
            <form method="post" action="logout.php" class="inline-form">
              <?= csrf_field() ?>
              <button type="submit" class="link-button">Déconnexion</button>
            </form>
          </li>
        <?php else: ?>
          <li><a href="login.php"<?= $active === 'login' ? ' aria-current="page"' : '' ?>>Connexion</a></li>
          <li><a class="btn btn-small" href="register.php">Créer un compte</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>

<?php $flashes = take_flashes(); if ($flashes): ?>
  <div class="container flashes" role="status" aria-live="polite">
    <?php foreach ($flashes as $f): ?>
      <div class="alert alert-<?= e($f['type']) ?>">
        <span><?= e($f['msg']) ?></span>
        <button type="button" class="alert-close" aria-label="Fermer">&times;</button>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<main id="main">
