<?php
/* ALOITUS */   
$display = "d-none";
$message = "";
$success = "success";
$ilmoitukset['errorMsg'] = 'Virhe tietokantayhteydessä. '; 
debuggeri("POST:".var_export($_POST,true));

if (isset($_POST['painike'])){
   foreach ($_POST as $kentta => $arvo) {
      if (in_array($kentta, $pakolliset) and empty($arvo)) {
          $errors[$kentta] = $virheilmoitukset[$kentta]['valueMissing'];
          }
      else {
         if (isset($patterns[$kentta]) and !preg_match($patterns[$kentta], $arvo)) {
            $errors[$kentta] = $virheilmoitukset[$kentta]['patternMismatch'];
            }
         else {
            if (is_array($arvo)) $$kentta = $arvo;
            else $$kentta = $yhteys->real_escape_string(strip_tags(trim($arvo)));
            } 
         }
      }

   if (!$errors){
      $query = "SELECT id,token FROM users LEFT JOIN signup_tokens ON users_id = users.id WHERE email = '$email'";
      debuggeri($query);
      [$result,$virhe] = db_query($query);
      if ($virhe) {
         $message =  $ilmoitukset['errorMsg'];
         $success = "danger";
         $display = "d-block";
         }
      else {
        if ($result->num_rows){
        /* Käyttäjä löytyi sähköpostiosoitteen perusteella */    
            [$user_id,$token] = $result->fetch_row();
            if (!$token){
                $token = bin2hex(random_bytes(16));
                $query = "INSERT INTO signup_tokens (users_id, token) VALUES ('$user_id', '$token')";
                debuggeri($query);
                [$result,$virhe] = db_query($query);
                if ($virhe) {
                    $message =  $ilmoitukset['errorMsg'];
                    $success = "danger";
                    }
                }
            if (!$virhe){
                    $msg = "Vahvista sähköpostiosoitteesi alla olevasta linkistä:<br><br>";
                    $msg.= "<a href='http://$PALVELIN/$PALVELU/verification.php?token=$token'>Vahvista sähköpostiosoite</a>";
                    $msg.= "<br><br>t. $PALVELUOSOITE";
                    $subject = "Vahvista sähköpostiosoite";
                    $lahetetty = posti($email,$msg,$subject);
                    }  

            if ($lahetetty){
                    $message = "Tiedot on tallennettu. Sinulle on lähetty vahvistuspyyntö antamaasi sähköpostiosoitteeseen. 
                    Vahvista siinä olevasta linkistä sähköpostiosoitteesi.";
                    }
            else {
               $message = "Vahvistuslinkin lähetys epäonnistui. Tee pyyntö myöhemmin uudestaan.";
               $success = "danger"; 
                }
            
            }
          } // Tietokantakysely onnistui
        $display = "d-block";
        }
    }
?>