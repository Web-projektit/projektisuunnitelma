<?php
/* ALOITUS */   
$display = "d-none";
$message = "";
$success = "success";
$ilmoitukset['errorMsg'] = 'Kirjautuminen epäonnistui. '; 
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

   debuggeri($errors);
   if (!$errors){
      $query = "SELECT users.id,firstname,lastname,password,is_active,nimi FROM users LEFT JOIN roles ON role_id = roles.id WHERE email = '$email'";
      debuggeri($query);
      [$result,$message] = db_query($query);
      if ($message) {
         $success = "danger";
         $display = "d-block";
         }
      elseif (!$result->num_rows) {
         debuggeri("$email:$virheilmoitukset[accountNotExistErr]");
         $message =  $ilmoitukset['errorMsg'];
         $success = "danger";
         $display = "d-block";
         }
      else {
         [$id,$firstname,$lastname,$password_hash,$is_active,$role] = $result->fetch_row();
         if (password_verify($password, $password_hash)){
            if ($is_active){
               if (!session_id()) session_start();
               $_SESSION["loggedIn"] = $role ?: "user";
               $_SESSION["user_id"] = $id;
               $_SESSION["username"] = $firstname.$lastname[0];
               if ($rememberme) rememberme($id);
               if (isset($_SESSION['next_page'])){
                  $location = $_SESSION['next_page'];
                  unset($_SESSION['next_page']);
                  }   
               else $location = OLETUSSIVU;
               debuggeri("location: $location");   
               header("location: $location");
               exit;
               }      
            else {
               $errors['email'] = $virheilmoitukset['verificationRequiredErr'];
               }
            }
         else {
            $errors['password'] = $virheilmoitukset['emailPwdErr'];
            }
         }  
      }  
   }   
?>