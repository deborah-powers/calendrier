angular.module ('evenments').controller ('controllerShowEvenments', function ($scope, $http, dateService){
	var listEvenments =[];
	$scope.listEvenments =[];
	// date du jour
	var today = dateService.newDate();
	dateService.todayDate (today);
	// reperer les evenements du mois
	function findMonthEvt (evt){
		return (evt.year == today.year) && (evt.month == today.month);
	}
	$scope.showMonthEvt = function(){
		$scope.listEvenments =[];
		$scope.listEvenments = listEvenments.filter (findMonthEvt);
	}
	// reperer les evenements de la semaine
	var nextWeek = dateService.newDate();
	dateService.todayDate (nextWeek);
	dateService.addWeek (nextWeek);
	function findWeekEvt (evt){
		var res = false;
		if (evt.priority !== 'old'){
			evtDate = dateService.fromObj (evt);
			var comparToday = dateService.comparDates (evtDate, today);
			var comparNextWeek = dateService.comparDates (evtDate, nextWeek);
			if ((comparToday >=0) && (comparNextWeek ==-1)) res = true;
		}
		return res;
	}
	$scope.showWeekEvt = function(){
		$scope.listEvenments =[];
		$scope.listEvenments = listEvenments.filter (findWeekEvt);
	}
	// reperer les evenements du jour
	function findDayEvt (evt){
		return (evt.year == today.year) && (evt.month == today.month) && (evt.day == today.day);
	}
	$scope.showDayEvt = function(){
		$scope.listEvenments =[];
		$scope.listEvenments = listEvenments.filter (findDayEvt);
	}
	// recuperer tous les evenements
	$scope.showAllEvt = function(){
		$scope.listEvenments =[];
		$scope.listEvenments = listEvenments;
	}
	// recuperer les infos de la page php via un get
	const pagePhp = 'http://localhost/calendrier/back/showEvenments/showEvt.php';
	$http.get (pagePhp).then (function (response){
		console.log ('response:', response.data);
		// tous les evenements
		listEvenments = response.data;
		$scope.listEvenments = response.data;
	});
});