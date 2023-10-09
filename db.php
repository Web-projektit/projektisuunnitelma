<?php
$yhteys = new mysqli($db_server, $db_username, $db_password, $DB);
if ($yhteys->connect_error) {
   die("Yhteyden muodostaminen epäonnistui: " . $yhteys->connect_error);
   }
$yhteys->set_charset("utf8");

function db_connect(){
return $GLOBALS['yhteys'];   
}

function db_query($query){
$yhteys = db_connect();
$virhe = false;
$result = null;
try {
   $result = $yhteys->query($query);
   if (!$result) throw new Exception ($yhteys->error);
   }
catch (Exception $e) {
   $virhe = $e->getMessage().". ";
   } 
return [$result,$virhe];
}
?>