<?php
// cross-origin, a modifier pour updater les evts
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// dependences
include ('../library/event.class.php');
include ('../library/database.class.php');
include ('../library/tablesNames.php');
// A) recuperer les infos du formulaire envoyees par angular
// angularData est une string
$angularData = file_get_contents ('php://input');
// objet evt
$evt = new event();
$evt->fromJsonString ($angularData);
// TODO supprimer cette ligne de test
// verifier que l'enregistrement n'est pas une erreur
$result =2;
$message = "l'evenement n'a pas de titre";
if ($evt->title){
	$evtArray = $evt->toArray();
	$db = new database();
	$db->connect();
	$result = $db->postArray ($tableEvt, $evtArray);
}
if ($result ==1) $message = "l'evenement a ete enregistre dans la bdd";
elseif ($result ==0) $message = "l'evenement n'a pas put etre enregistre dans la bdd";
echo json_encode ($message);
?>