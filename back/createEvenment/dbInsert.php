<?php
// cross-origin, a modifier pour updater les evts
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// dependences
include ('../library/evenment.class.php');
include ('../library/dbConnect.php');
// A) recuperer les infos du formulaire envoyees par angular
// angularData est une string
$angularData = file_get_contents ('php://input');
// objet evt
$evt = new evenment();
$evt->fromJsonString ($angularData);
// verifier que l'enregistrement n'est pas une erreur
$message = "l'evenement n'a pas de titre";
if ($evt->title) $message = postObjToDb ($tableEvenments, $evt, $mysqli);
echo json_encode ($message);
?>