<?php
include 'asetukset.php';
include 'db.php';
include 'rememberme.php';
$loggedIn = secure_page();
$username = $_SESSION['username'] ?? 'Guest';
$projekti_id = $_GET['projekti'] ?? '';

$kentat = ['nimi','kuvaus','asemointi','rajaus','aloitus','lopetus','tyokalu'];
$kentat_suomi = $kentat;
$pakolliset = ['nimi','kuvaus','asemointi','aloitus','lopetus'];
$patterns = ['nimi' => '/^[a-zA-Z0-9åäöÅÄÖ_ \-]{1,50}$/',
             'kuvaus' => '/^[^<>]{1,300}$/',
             'asemointi' => '/^[^<>]{1,300}$/',
             'rajaus' => '/^[^<>]{1,300}$/',
             'aloitus' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',
             'lopetus' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',
             'tyokalu' => '/^[a-zA-Z]+[a-zA-Z0-9åäöÅÄÖ_ \-]{0,50}$/'];

$title = 'Projektisuunnitelma';
$css = 'projektisuunnitelma.css';
include 'virheilmoitukset.php';
$virheilmoitukset_json = json_encode($virheilmoitukset);
echo "<script>
const virheilmoitukset = $virheilmoitukset_json;
const username = '$username';
const projekti_id = '$projekti_id';
</script>";
include 'header.php';
include 'kasittelija_projektisuunnitelma.php';

function tyokalut(){
  $tyyppi = "";    
  //show checkboxes for programming tools 
  $tyokalut = ["CSS" => "Selain", "Bootstrap" => "Selain" , "Material-UI" => "Selain", 
  "Javascript" => "Selain", "React" => "Selain", "Typescript" => "Selain",
  "PHP" => "Palvelin", "Flask" => "Palvelin", "Node.js" => "Palvelin", "Express" => "Palvelin",
  "MySQL" => "Tietokanta", "Firebase" => "Tietokanta", "MongoDB" => "Tietokanta", "PostgreSQL" => "Tietokanta",
  "Azure" => "Pilvipalvelin", "Heroku" => "Pilvipalvelin", "Xampp" => "Kehityspalvelin", 
  "Gmail" => "Sähköpostipalvelin", "Mailtrap" => "Sähköpostipalvelin", 
  "Visual Studio Code" => "Ohjelmointi", "Notepad++" => "Ohjelmointi", 
  "GitHub" => "Versiohallinta", 
  "Copilot" => "Tekoäly", "ChatGPT" => "Tekoäly"];

  foreach($tyokalut as $tyokalu => $tyokalutyyppi){
    $valineet[$tyokalutyyppi][] = $tyokalu;
    }
  
  foreach($valineet as $tyyppi => $tyokalut){
    $tyyppi = ucfirst($tyyppi);
    echo '<h6 class="d-inline">'.$tyyppi.'</h6>';
    echo '<button id="'.$tyyppi.'" type="button" title="Lisää uusi työkalu" class="addInput mx-2">+</button>';
    echo '<ul class="checkboxList">';
    foreach ($tyokalut as $i => $tyokalu){
      echo '<li class="form-check">';
      echo '<label class="form-check-label">';
      echo '<input class="form-check-input" type="checkbox" name="'.$tyyppi.'[]" value="'.$tyokalu.'" id="'.$tyokalu.'">';
      echo $tyokalu;
      echo '</label>';
      echo '</li>'; 
      }
    echo '</ul>';
    }};
?>
<div class="container mt-3">
<form method="post" class="needs-validation" novalidate>
<fieldset class="mb-3"> 
<legend>Projektisuunnitelma</legend>   
<input type="hidden" name="projekti_id" value="<?= $projekti_id; ?>">
<div class="commentBox">
<label for="nimi" class="form-label">Projektin nimi</label>    
<input type="text" id="nimi" name="nimi" class="form-control d-inline-block w-50 mb-1" placeholder="Projektin nimi" 
       value="<?= arvo('nimi') ?>" pattern="<?= pattern('nimi') ?>" required>
<div class="invalid-feedback">
<?= $errors['nimi'] ?? ""; ?>    
</div>
<button type="button" title="Lisää kommentti" class="openChat commentButton-input"><i class="fas fa-comment"></i></button>
<span class="lastComment-input" hidden></span>
<div class="chatBox" hidden>
<div class="comments"></div>
<input id="comment_nimi" type="text" class="commentInput w-50" minlength="2">
<div class="invalid-feedback"></div>
</div>
</div>

<div class="commentBox">    
<label for="kuvaus" class="form-label">Projektin kuvaus</label>
<textarea id="kuvaus" name="kuvaus" class="form-control d-inline-block w-75" rows="3" 
          value="<?= arvo('kuvaus') ?>" pattern="<?= pattern('kuvaus') ?>"required></textarea>
<div class="invalid-feedback">
<?= $errors['kuvaus'] ?? ""; ?>    
</div>
<button type="button" title="Lisää kommentti" class="openChat commentButton"><i class="fas fa-comment"></i></button>
<span class="lastComment" hidden></span> <!-- Element to display the last comment -->
<div class="chatBox" hidden>
<div class="comments"></div>
<input id="comment_kuvaus" type="text" class="commentInput w-75">
<div class="invalid-feedback"></div>
</div>
</div>

<div class="commentBox">
<label for="asemointi" class="form-label tooltip-label">Asemointi
<span class="tooltip">Kenelle .. <br>
	Mihin tarpeeseen .. <br>
	Tässä on ratkaisu, joka sisältää .. <br> 
	Verrattuna olemassa oleviin palveluihin, kuten .. <br>
	Tässä palvelussa on ..</span>
</label>
<textarea id="asemointi" name="asemointi" class="form-control d-inline-block w-75" rows="3" 
          value="<?= arvo('asemointi') ?>" pattern="<?= pattern('asemointi') ?>"required></textarea>
<div class="invalid-feedback">
<?= $errors['asemointi'] ?? ""; ?>    
</div>
<button type="button" title="Lisää kommentti" class="openChat commentButton"><i class="fas fa-comment"></i></button>
<span class="lastComment" hidden></span> <!-- Element to display the last comment -->
<div class="chatBox" hidden>
<div class="comments"></div>
<input id="comment_asemointi" type="text" class="commentInput w-75">
<div class="invalid-feedback"></div>
</div>
</div>

<div class="commentBox">
<label for="rajaus" class="form-label tooltip-label">Rajaus
<span class="tooltip">Mitä ei toteuteta</span>
</label>
<textarea id="rajaus" name="rajaus" class="form-control d-inline-block w-75" rows="3"
          value="<?= arvo('rajaus') ?>" pattern="<?= pattern('rajaus') ?>"></textarea>
<div class="invalid-feedback">
<?= $errors['rajaus'] ?? ""; ?>    
</div>
<button type="button" title="Lisää kommentti" class="openChat commentButton"><i class="fas fa-comment"></i></button>
<span class="lastComment" hidden></span> <!-- Element to display the last comment -->
<div class="chatBox" hidden>
<div class="comments"></div>
<input id="comment_rajaus" type="text" class="commentInput w-75">
<div class="invalid-feedback"></div>
</div> 
</div>   

<label for="aloitus" class="form-label">Aloituspäivämäärä</label>      
<input type="date" id="aloitus" name="aloitus" class="form-control w-auto mb-1" placeholder="pv.kk.vuosi" 
       value="<?= arvo('aloitus') ?>" pattern="<?= pattern('aloitus') ?>" required>
<div class="invalid-feedback">
<?= $errors['aloitus'] ?? ""; ?>    
</div>

<label for="lopetus" class="form-label">Lopetuspäivämäärä</label>
<input type="date" id="lopetus" name="lopetus" class="form-control w-auto mb-1" placeholder="pv.kk.vuosi" 
       value="<?= arvo('aloitus') ?>" pattern="<?= pattern('aloitus') ?>" required>    
<div class="invalid-feedback">
<?= $errors['lopetus'] ?? ""; ?>    
</div>

<p class="form-label mb-2">Työkalut</p>
<div id="tyokalut">
    <?php 
    tyokalut();
    ?>
<!-- Hidden template -->
<li id="inputTemplate" class="mb-2" style="display: none;">
<input type="text" id="template" class="form-control d-inline-block w-50" placeholder="Työkalu"
       pattern="<?= pattern('tyokalu') ?>">    
<button type="button" title="Poista työkalu" class="remove-icon">&#8722;</button>
<div class="invalid-feedback">
<?= $errors['tyokalu'] ?? "Testi"; ?>    
</div>
</li>
</div>

</div>
<button name="painike" type="submit" class="btn btn-primary">Tallenna</button>
</fieldset>
</form>

<div  id="ilmoitukset" class="alert alert-<?= $success ;?> alert-dismissible fade show <?= $display ?? ""; ?>" role="alert">
<p><?= $message; ?></p>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

</div>
<?php
include('footer.html')
?>
