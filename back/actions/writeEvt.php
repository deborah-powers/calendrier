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
$textData = stripslashes ($textData);
// n'ayant pas trouve de fonction pour recuperer les accents, j'ai cree la mienne
function accent ($text){
	$text = str_replace ('u00e0', 'à', $text);
	$text = str_replace ('u00e9', 'é', $text);
	$text = str_replace ('u00e8', 'è', $text);
	$text = str_replace ('u00ea', 'ê', $text);
	$text = str_replace ('u00eb', 'ë', $text);
	$text = str_replace ('u00ee', 'î', $text);
	$text = str_replace ('u00ef', 'ï', $text);
	$text = str_replace ('u00f4', 'ô', $text);
	$text = str_replace ('u00fb', 'û', $text);
	return $text;
}
$textData = accent ($textData);
$nb= file_put_contents ($jsonFile, $textData);
echo $jsonFile;
?>