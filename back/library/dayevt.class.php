<?php
// liste d'evenements d'une journee
class dayevt{
	// la date
	public $year = 2018;
	public $month = 1;
	public $day = 1;
	public $listEvt =[];
	// reperer les evenements du jour
	public function fromEvtList ($listEvt){
		foreach ($listEvt as $evt){
			if ($this->year == $evt->year && $this->month == $evt->month && $this->day == $evt->day)
				$this->listEvt[] = $evt;
		}
	}
}
?>