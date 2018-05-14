<?php
// cross-origin, a modifier pour updater les evts
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// dependences
include ('../library/evenment.class.php');
include ('../library/dbConnect.php');
// recuperer les donnees de la bdd
$result = getTableContent ($tableEvenments, $mysqli);
$tableFields = $result[0];
$tableData = $result[1];
$jsonData = tableToJsonArray ($tableFields, $tableData);
// transformer les pseudo-json en objets evenment
$listEvt =[];
foreach ($jsonData as $value){
	$evt = new evenment();
	$evt->fromArray ($value);
	$listEvt[] = $evt;
}
// gerer le temps
$today = new evtDate();
$today->today();
foreach ($listEvt as $evt){
	$evtDate = $evt->toDate();
	if ($evtDate < $today){
	// reperer les evenements passes. passer la priorite a old
		if ($evt->recurrence == 'no') $evt->priority = 'old';
	// la recurrence. quand la date d'un évênement est dépassé, trouver sa prochaine occurence
		else{
			switch ($evt->recurrence){
				case 'year':
					$evtDate->year +=1;
					break;
				case 'month':
					$evtDate->addMonth();
					break;
				case 'week':
					$evtDate->addweek();
					break;
				case 'day':
					$evtDate->addDay();
			}
			$evt->fromDate ($evtDate);
		}
		$message = updateObjToDb ($tableEvenments, $evt, $mysqli);
	}
}
// trier les evt par date
function sortByDate ($evt1, $evt2){
	$date1 = $evt1->toDate();
	$date2 = $evt2->toDate();
	$res =-1;
	if ($date1 < $date2) $res =1;
	return $res;
}
$resSort = usort ($listEvt, sortByDate);
?>