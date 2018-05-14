<?php
include ('dbExtract.php');
include ('../library/dayevt.class.php');
// liste des jours
$listDay =[];
$nbDay =0;
$listDay[] = new dayevt();
$listDay [$nbDay]->year = $listEvt[0]->year;
$listDay [$nbDay]->month = $listEvt[0]->month;
$listDay [$nbDay]->day = $listEvt[0]->day;
// classer les evenements selon leur jour
foreach ($listEvt as $evt){
	// un nouveau jour
	if ($listDay [$nbDay]->year !== $evt->year || $listDay [$nbDay]->month !== $evt->month || $listDay [$nbDay]->day !== $evt->day)
	{
		$listDay[] = new dayevt();
		$nbDay +=1;
		$listDay [$nbDay]->year = $evt->year;
		$listDay [$nbDay]->month = $evt->month;
		$listDay [$nbDay]->day = $evt->day;
	}
	// rajouter l'evenement dans la liste du jour
	$listDay [$nbDay]->listEvt[] = $evt;
}
// echo renvoi les infos
echo json_encode ($listDay);
?>