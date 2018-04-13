angular.module ('evenments').controller ('controllerShowEvenments', function ($scope, $http, dateService){
	$scope.listEvenments =[];
	$scope.listEvenmentsMonth =[];
	$scope.listEvenmentsWeek =[];
	$scope.listEvenmentsDay =[];
	$scope.tableWeek =[ [], [], [], [], [], [], [] ];
	// date du jour
	var today = dateService.newDate();
	dateService.todayDate (today);
	// reperer les evenements du mois
	function findMonthEvt (evt){
		return (evt.year == today.year) && (evt.month == today.month);
	}
	// reperer les evenements de la semaine
	function findWeekEvt (evt){
		var res = false;
		if (evt.priority !== 'old'){
			evtDate = dateService.fromObj (evt);
		}
	}
	// reperer les evenements du jour
	function findDayEvt (evt){
		return (evt.year == today.year) && (evt.month == today.month) && (evt.day == today.day);
	}
	// recuperer les infos de la page php via un get
	const pagePhp = 'http://localhost/calendrier/back/showEvenments/dbExtract.php';
	$http.get (pagePhp).then (function (response){
		console.log ('response:', response.data);
		$scope.listEvenments = response.data;
		// rechercher les evenements du mois
		$scope.listEvenmentsMonth = response.data.filter (findMonthEvt);
		// rechercher les evenements de la semaine
		// rechercher les evenements du jour
		$scope.listEvenmentsDay = $scope.listEvenmentsMonth.filter (findDayEvt);
	});
});