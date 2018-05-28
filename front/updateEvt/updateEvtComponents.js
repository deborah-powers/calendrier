angular.module ('events').component ('date', {
	templateUrl: 'components/componentDate.html',
	bindings: { date: '=' },
	controller: 'controllerUpdateEvt'
});
angular.module ('events').component ('place', {
	templateUrl: 'components/componentPlace.html',
	bindings: { place: '=' },
	controller: 'controllerUpdateEvt'
});
angular.module ('events').component ('contact', {
	templateUrl: 'components/componentContact.html',
	bindings: { contact: '=' },
	controller: 'controllerUpdateEvt'
});
angular.module ('events').component ('message', {
	templateUrl: 'components/componentMessage.html',
	bindings: { message: '=' },
	controller: 'controllerUpdateEvt'
});