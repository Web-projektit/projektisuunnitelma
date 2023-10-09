<?php 
include "asetukset.php";
include "db.php";
include "rememberme.php";
if ($loggedIn = loggedIn()) {
    header("location: profiili.php");
    exit;
    }
$title = 'Kirjautuminen';
$css = 'login.css';

/* Lomakkeen kentät, nimet samat kuin users-taulussa. */
$kentat = ['email','password','rememberme'];
$kentat_suomi = ['sähköpostiosoite','salasana','muista minut'];
$pakolliset = ['email','password'];
include "virheilmoitukset.php";
$virheilmoitukset_json = json_encode($virheilmoitukset);
include "header.php"; 
include "kasittelija_login.php";
$email ??= "";
echo "<script>
const virheilmoitukset = $virheilmoitukset_json;
const email = '$email';
</script>";
?>
<div class="container">

<form method="post" autocomplete="on" class="mb-3 needs-validation" novalidate>    
<fieldset>
<legend>Kirjautuminen</legend>

<div class="row">
<label for="email" class="col-sm-4 form-label">Sähköpostiosoite</label>
<div class="col-sm-8">
<input type="email" class="mb-1 form-control <?= is_invalid('email'); ?>" name="email" id="email" 
       placeholder="etunimi.sukunimi@palvelu.fi" value="<?= arvo("email"); ?>"
       pattern="<?= pattern('email'); ?>" autofocus required>
<div class="invalid-feedback">
<?= $errors['email'] ?? ""; ?>    
</div>
</div>
</div>

<div class="row">
<label for="password" class="col-sm-4 form-label">Salasana</label>
<div class="col-sm-8">
<input type="password" class="mb-1 form-control <?= is_invalid('password'); ?>" name="password" id="password" 
       placeholder="salasana" pattern="<?= pattern('password'); ?>" required>
<div class="invalid-feedback">
<?= $errors['password'] ?? ""; ?>    
</div>
</div>
</div>


<div class="row offset-sm-4">
<div class="form-check ms-2">
<input class="form-check-input" type="checkbox" value="checked" <?= nayta_rememberme('rememberme'); ?> id="rememberme" name="rememberme"/>
<label class="form-check-label" for="rememberme">Muista minut</label>
<div class="invalid-feedback">
<?= $errors['rememberme'] ?? ""; ?>    
</div>
</div>
</div>


<div class="div-button">
<input type="submit" name="painike" class="offset-sm-4 mt-3 mb-2 btn btn-primary" value="Kirjaudu">  
</div>

<div class="row offset-sm-4">
<a href="forgotpassword.php">Unohtuiko salasana</a>
</div>

<div class="row offset-sm-4">
<!--<p class="mt-2 pt-1 mb-0">Käyttäjätunnus puuttuu?-->
<a href="signup.php">Rekisteröidy</a>
</div>

</fieldset>
</form>

<?php 
if (isset($errors['email']) && $errors['email'] == $virheilmoitukset['verificationRequiredErr']) {
  $display = "d-block";
  $message = $virheilmoitukset['verificationRequiredErr'];
  $success = "danger";
  $nayta_linkki = '
  <div id="ilmoitukset_email" class="alert alert-info alert-dismissible fade show d-block" role="alert">
  <p><a id="confirmLink" href="#">Lähetä vahvistuslinkki tarvittaessa uudestaan</a></p>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
?>
<div id="ilmoitukset" class="alert alert-<?= $success ;?> alert-dismissible fade show <?= $display ?? ""; ?>" role="alert">
<p><?= $message; ?></p>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<?= $nayta_linkki ?? ""; ?>

</div>
<?php
include('footer.html');
?>
