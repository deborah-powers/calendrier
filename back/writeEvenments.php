<?php
// cross-origin, a modifier pour updater les evts
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// angularData est une string
$angularData = file_get_contents ('php://input');
// recuperer les deux objets qu'elle contient, le chemin du dossier courrant et les evenements
$jsonObj = json_decode ($angularData);
$jsonFile = $jsonObj[0].'evenments.json';
$jsonData = $jsonObj[1];
// ecrire dans le fichier
$textData = json_encode ($jsonData);
$textData = utf8_encode ($textData);
// TODO decoder les strings
$nb= file_put_contents ($jsonFile, $textData);
echo json_encode ($textData);
?>