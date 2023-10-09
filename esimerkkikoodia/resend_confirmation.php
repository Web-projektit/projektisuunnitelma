<?php 
include "asetukset.php";
include "db.php";
include "rememberme.php";
if ($loggedIn = loggedIn()) {
    header("location: profiili.php");
    exit;
    }
$title = 'Vahvistuslinkkipyyntö';
//$css = 'login.css';

/* Lomakkeen kentät, nimet samat kuin users-taulussa. */
$kentat = ['email'];
$kentat_suomi = ['sähköpostiosoite'];
$pakolliset = ['email'];
include "virheilmoitukset.php";
$virheilmoitukset_json = json_encode($virheilmoitukset);
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";
include "header.php"; 
include "posti.php";
include('kasittelija_resend_confirmation.php');
?>
<div class="container">

<form method="post" autocomplete="on" class="mb-3 needs-validation" novalidate>    
<fieldset>
<legend>Sähköpostin vahvistuslinkkipyyntö</legend>

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

<div class="div-button">
<input type="submit" name="painike" class="offset-sm-4 mt-3 mb-2 btn btn-primary" value="Lähetä vahvistuslinkki">  
</div>

</fieldset>
</form>

<div id="ilmoitukset" class="alert alert-<?= $success ;?> alert-dismissible fade show <?= $display ?? ""; ?>" role="alert">
<p><?= $message; ?></p>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

</div>
<?php
include('footer.html');
?>
