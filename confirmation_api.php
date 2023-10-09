<?php
// Cross-Origin Resource Sharing (CORS) -headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
include "asetukset.php";
include "debuggeri.php";
include "db.php";
include "posti.php";
include "virheilmoitukset.php";
$ilmoitukset['errorMsg'] = 'Virhe tietokannassa. '; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    debuggeri(__FILE__.",".var_export($data,true));
    $email = $data['email'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email address']);
        exit();
        }

    $query = "SELECT id,token FROM users LEFT JOIN signup_tokens ON users_id = users.id WHERE email = '$email'";
    debuggeri($query);
    [$result,$message] = db_query($query);
    if (!$message) {
      if ($result->num_rows){
        /* Käyttäjä löytyi sähköpostiosoitteen perusteella */    
        [$user_id,$token] = $result->fetch_row();
        if (!$token) {
            $token = bin2hex(random_bytes(16));
            $query = "INSERT INTO signup_tokens (users_id, token) VALUES ('$user_id', '$token')";
            debuggeri($query);
            [$result,$message] = db_query($query);
            }
        if (!$message) {
            $msg = "Vahvista sähköpostiosoitteesi alla olevasta linkistä:<br><br>";
            $msg.= "<a href='http://$PALVELIN/$PALVELU/verification.php?token=$token'>Vahvista sähköpostiosoite</a>";
            $msg.= "<br><br>t. $PALVELUOSOITE";
            $subject = "Vahvista sähköpostiosoite";
            $lahetetty = posti($email,$msg,$subject);
            }  

        if ($lahetetty) {
            $message = $virheilmoitukset['accountExistsMsg'];
            http_response_code(200);
            echo json_encode(['OK' => $message]);
            }
        
        else {
            $message = $virheilmoitukset['emailErr'];
            http_response_code(500);
            echo json_encode(['error' => $message]);
            }
      }
    else {
        $message = $virheilmoitukset['accountNotExistErr'];
        http_response_code(500);
        echo json_encode(['error' => $message]);
        }
        }
    }
else {
    http_response_code(405);
    echo json_encode(['error' => 'Väärä kyselymetodi']);
    }
exit;
?>
