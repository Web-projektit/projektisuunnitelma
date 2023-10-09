<?php
$display = "d-none";
$message = "";
$success = "success";
$lisays  = false;

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
if (empty($errors)) {
    $user_id = $_SESSION['user_id'] ?? '';
    if (empty($user_id)){
        $display = "d-block";
        $message = "Kirjautunen puuttuu.";
        $success = "danger";
      }
    }

if (empty($errors)) {
    $query = "INSERT INTO projektit (id,user_id,nimi,kuvaus,asemointi,rajaus,aloitus,lopetus) VALUES (
        '$projekti_id', '$user_id', '$nimi', '$kuvaus', $asemointi, 
        '$rajaus', '$aloitus', '$lopetus') 
        ON DUPLICATE KEY UPDATE
        user_id = VALUES(user_id),
        nimi = VALUES(nimi),
        kuvaus = VALUES(kuvaus),
        asemointi = VALUES(asemointi),
        rajaus = VALUES(rajaus),
        aloitus = VALUES(aloitus),
        lopetus = VALUES(lopetus),
        id = LAST_INSERT_ID(id)";
    debuggeri($query);
    try {
        $result = $yhteys->query($query);
        $lisays = $yhteys->affected_rows;
        $projekti_id = $yhteys->insert_id;
        }
    catch (Exception $e) {
        $message = $e->getMessage();
        $success = "danger";
        $display = "d-block";
        }
    }

if ($lisays) {
    $query = "SHOW COLUMNS FROM tyokalut LIKE 'kategoria'";
    $result = $yhteys->query($query);
    $row = $result->fetch_assoc();
    $str = $row['Type'];
    preg_match('/enum\((.*)\)$/',$str,$matches);
    $kategoriaArr = explode(",",$matches[1]);
    debuggeri("$kategoriaArr:".var_export($kategoriaArr,true));
    foreach ($kategoriaArr AS $kategoria){
        $kategoria = ucfirst(trim($kategoria,"'"));
        $tyokalut = $$kategoria ?? [];
        foreach ($tyokalut as $tyokalu) {
            $query = "INSERT INTO tyokalut (tyokalu, kategoria) 
                VALUES ('$tyokalu','$kategoria')
                ON DUPLICATE KEY UPDATE 
                tyokalu = VALUES(tyokalu), 
                kategoria = VALUES(kategoria),
                id = LAST_INSERT_ID(id)";
            debuggeri($query);    
            $result = $yhteys->query($query);
            $lisays = $yhteys->affected_rows;
            $tyokalu_id = $yhteys->insert_id;
            $query = "INSERT INTO projektit_tyokalut 
                    (projekti_id, tyokalu_id) 
                VALUES ($projekti_id,$tyokalu_id)
                ON DUPLICATE KEY UPDATE 
                projektit_id = VALUES(projektit_id), 
                tyokalut_id = VALUES(tyokalut_id)";  
            debuggeri($query);    
            $result = $yhteys->query($query);
            $lisays = $yhteys->affected_rows;
            }
        }
    }
if ($lisays) {
    $message = "Tallennus onnistui!";
    }
else {
    $message.= "Tallennus epäonnistui!";
    $success = "danger";
    }
$display = "d-block";

/*
var_export($_POST);
var_export($_FILES);
echo "<br>";
var_export($errors);*/
}


?>