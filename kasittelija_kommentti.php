<?php
include 'asetukset.php';
include 'db.php';
include 'rememberme.php';
include 'debuggeri.php';
$kentat = ['project','field','comment'];
$kentat_suomi = ['projekti','kenttä','kommentti'];
$patterns = ['project' => '/^[0-9]{1,10}$/',
             'field' => '/^[a-zA-Z_-]{1,25}$/',
             'comment' => '/^[^<>]{1,300}$/'];
$pakolliset = ['project','field','comment'];
include 'virheilmoitukset.php';
$loggedIn = secure_page();

debuggeri("POST:".var_export($_POST,true));

if (isset($_POST['project_id'], $_POST['field'], $_POST['comment'])){
    $lisays = false;
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
    if (empty($errors)) {
        $username = $_SESSION['username'] ?? 'Guest';
        $user_id = $_SESSION['user_id'] ?? '';
        if ($user_id && $username == 'Guest'){
            $query = "SELECT firstname,lastname FROM users WHERE id = '$user_id'";
            debuggeri($query);
            $result = $yhteys->query($query);
            if ($result->num_rows) {
                [$firstname,$lastname] = $result->fetch_row();
                $username = $firstname.$lastname[0];
                }
            }
        $query = "INSERT INTO comments (project_id, user_id, username, field, comment) VALUES (
            '$project_id', '$user_id', '$username', '$field', '$comment') 
            ON DUPLICATE KEY UPDATE 
            project_id = VALUES(project_id),
            user_id = VALUES(user_id),
            username = VALUES(username),
            field = VALUES(field),
            comment = VALUES(comment)"; 
        debuggeri($query);    
        [$result,$virhe] = db_query($query);
        if ($result) $lisays = $yhteys->affected_rows;   
        $message = ($lisays) ? "OK" : "Virhe: $virhe";   
        }
    else $message = reset($errors);     
    echo json_encode($message);
    exit;    
}

?>