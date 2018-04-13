angular.module ('evenments').controller ('controllerCreateEvenment', function ($scope, $http, dateService){
	// la date
	$scope.todayDate = dateService.todayDate;
	$scope.blankDate = dateService.blankDate;
	// option prédéfinies
	$scope.place ={};
	$scope.contact ={};
	$scope.message ={};
	$scope.message.priority = 'low';
	$scope.date = dateService.newDate();
	$scope.date.recurrence = 'no';
	$scope.todayDate ($scope.date);
	// enregistrer le message
	$scope.createEvt = function(){
	// creer un objet json contenant les champs de la bdd
		var evenment ={
			// champs de la date
			year:			$scope.date.year,
			month:			$scope.date.month,
			day:			$scope.date.day,
			hour:			$scope.date.hour,
			minute:			$scope.date.minute,
			recurrence:		$scope.date.recurrence,
			// champs du lieu
			city:			$scope.place.city,
			adress:			$scope.place.adress,
			itinerary:		$scope.place.itinerary,
			// champs du contact
			contact_name:	$scope.contact.contact_name,
			contact_coord:	$scope.contact.contact_coord,
			// champs des infos
			title:			$scope.message.title,
			note:			$scope.message.content,
			tags:			$scope.message.tags,
			priority:		$scope.message.priority
		};
		// console.log ('evenment', evenment);
		const pagePhp = 'http://localhost/calendrier/back/createEvenment/dbInsert.php';
		// const config ={ responseType: "json" };
		$http.post (pagePhp, evenment).then (function (response){
			console.log ('response:', response.data);
		});
	}
});