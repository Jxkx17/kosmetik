<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
if (current_user()) {
    redirect('index.php');
}
$pageTitle = 'Créer un compte';
$active = 'register';
require __DIR__ . '/includes/header.php';
?>
<section class="container auth">
  <div class="card auth-card auth-card-wide">
    <h1>Créer un compte</h1>
    <p class="muted">Quelques informations pour vos livraisons.</p>
    <form method="post" action="insert.php" class="form">
      <?= csrf_field() ?>
      <div class="grid-2">
        <div class="field">
          <label for="fname">Prénom</label>
          <input type="text" id="fname" name="fname" autocomplete="given-name" required maxlength="100" value="<?= e(old('fname')) ?>">
        </div>
        <div class="field">
          <label for="lname">Nom</label>
          <input type="text" id="lname" name="lname" autocomplete="family-name" required maxlength="100" value="<?= e(old('lname')) ?>">
        </div>
      </div>
      <div class="field">
        <label for="address">Adresse</label>
        <input type="text" id="address" name="address" autocomplete="street-address" required maxlength="255" value="<?= e(old('address')) ?>">
      </div>
      <div class="grid-2">
        <div class="field">
          <label for="city">Ville</label>
          <input type="text" id="city" name="city" autocomplete="address-level2" required maxlength="100" value="<?= e(old('city')) ?>" placeholder="Cotonou">
        </div>
        <div class="field">
          <label for="pin">Code postal</label>
          <input type="text" id="pin" name="pin" inputmode="numeric" pattern="\d{1,10}" autocomplete="postal-code" required value="<?= e(old('pin')) ?>">
        </div>
      </div>
      <div class="field">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" autocomplete="email" required maxlength="255" value="<?= e(old('email')) ?>" placeholder="vous@exemple.com">
      </div>
      <div class="field">
        <label for="pwd">Mot de passe</label>
        <div class="password-wrap">
          <input type="password" id="pwd" name="pwd" autocomplete="new-password" required minlength="8" aria-describedby="pwd-help">
          <button type="button" class="toggle-password" aria-label="Afficher le mot de passe">Afficher</button>
        </div>
        <small id="pwd-help" class="muted">8 caractères minimum.</small>
      </div>
      <button type="submit" class="btn btn-block">Créer mon compte</button>
    </form>
    <p class="auth-switch">Déjà inscrit ? <a href="login.php">Se connecter</a></p>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
