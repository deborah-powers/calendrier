angular.module ('evenments').component ('date', {
	templateUrl: 'createEvenment/formDate.html',
	bindings: { date: '=' },
	controller: 'controllerCreateEvenment'
});
angular.module ('evenments').component ('place', {
	templateUrl: 'createEvenment/formPlace.html',
	bindings: { place: '=' },
	controller: 'controllerCreateEvenment'
});
angular.module ('evenments').component ('contact', {
	templateUrl: 'createEvenment/formContact.html',
	bindings: { contact: '=' },
	controller: 'controllerCreateEvenment'
});
angular.module ('evenments').component ('message', {
	templateUrl: 'createEvenment/formMessage.html',
	bindings: { message: '=' },
	controller: 'controllerCreateEvenment'
});