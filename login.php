<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
if (current_user()) {
    redirect('index.php');
}
$pageTitle = 'Connexion';
$active = 'login';
require __DIR__ . '/includes/header.php';
?>
<section class="container auth">
  <div class="card auth-card">
    <h1>Connexion</h1>
    <p class="muted">Heureux de vous revoir.</p>
    <form method="post" action="verify.php" class="form" novalidate>
      <?= csrf_field() ?>
      <div class="field">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" autocomplete="email" required value="<?= e(old('email')) ?>" placeholder="vous@exemple.com">
      </div>
      <div class="field">
        <label for="pwd">Mot de passe</label>
        <div class="password-wrap">
          <input type="password" id="pwd" name="pwd" autocomplete="current-password" required>
          <button type="button" class="toggle-password" aria-label="Afficher le mot de passe">Afficher</button>
        </div>
      </div>
      <button type="submit" class="btn btn-block">Se connecter</button>
    </form>
    <p class="auth-switch">Pas encore de compte ? <a href="register.php">Créer un compte</a></p>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
