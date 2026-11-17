<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
$u = require_login();
$me = db_one('SELECT fname, lname, address, city, pin, email FROM users WHERE id = ?', 'i', [$u['id']]);
if (!$me) {
    redirect('index.php');
}
$pageTitle = 'Mon compte';
$active = 'account';
require __DIR__ . '/includes/header.php';
?>
<section class="container page">
  <div class="page-head">
    <h1>Mon compte</h1>
    <p class="muted">Modifiez vos informations puis enregistrez.</p>
  </div>
  <form method="post" action="update.php" class="card form form-wide">
    <?= csrf_field() ?>
    <h2>Informations personnelles</h2>
    <div class="grid-2">
      <div class="field"><label for="fname">Prénom</label><input id="fname" name="fname" required maxlength="100" value="<?= e($me['fname']) ?>"></div>
      <div class="field"><label for="lname">Nom</label><input id="lname" name="lname" required maxlength="100" value="<?= e($me['lname']) ?>"></div>
    </div>
    <div class="field"><label for="address">Adresse</label><input id="address" name="address" required maxlength="255" value="<?= e($me['address']) ?>"></div>
    <div class="grid-2">
      <div class="field"><label for="city">Ville</label><input id="city" name="city" required maxlength="100" value="<?= e($me['city']) ?>"></div>
      <div class="field"><label for="pin">Code postal</label><input id="pin" name="pin" inputmode="numeric" pattern="\d{1,10}" required value="<?= e($me['pin']) ?>"></div>
    </div>
    <div class="field"><label for="email">E-mail</label><input type="email" id="email" name="email" required maxlength="255" value="<?= e($me['email']) ?>"></div>

    <h2>Sécurité</h2>
    <div class="field">
      <label for="pwd">Nouveau mot de passe <span class="muted">(laisser vide pour ne pas changer)</span></label>
      <input type="password" id="pwd" name="pwd" autocomplete="new-password" minlength="8">
    </div>
    <div class="field">
      <label for="opwd">Mot de passe actuel</label>
      <input type="password" id="opwd" name="opwd" autocomplete="current-password" aria-describedby="opwd-help">
      <small id="opwd-help" class="muted">Requis pour changer l'e-mail ou le mot de passe.</small>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn">Enregistrer</button>
      <a class="btn btn-ghost" href="orders.php">Voir mes commandes</a>
    </div>
  </form>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
