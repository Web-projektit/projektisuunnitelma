<?php 
$title = 'Sähköpostiosoitteen vahvistus';
//$css = 'kuvagalleria.css';
include "header.php";
include "activation.php";
?>
<div class="container"> 
<div class="jumbotron text-center">
<h1><?= $PALVELUNIMI ?></h1>
<div class="col-12 mb-5 text-center">
<?php echo $email_already_verified; ?>
<?php echo $email_verified; ?>
<?php echo $activation_error; ?>
</div>
<!--<p class="lead">If user account is verified then click on the following button to login.</p>-->
<a class="btn btn-lg btn-success" href="<?php echo "http://$PALVELIN/$PALVELU/login.php";?>">Kirjaudu</a>
</div>

</div>
<?php include "footer.html"; ?>