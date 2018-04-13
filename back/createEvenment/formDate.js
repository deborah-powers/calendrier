// source: https://www.w3schools.com/jsref/jsref_obj_date.asp
angular.module ('createEvenement').service ('dateService', function(){
	/* transformer 1 en 01
	number peut etre un nombre ou une chaine de caractere representant un nombre */
	function addO (number){
		if (typeof (number) === 'string' && number.length ===1) number ='0'+ number;
		else if (typeof (number) === 'number' && number <10) number ='0'+ number;
		return number;
	}
	function newDate(){
		return {
			year: 2018,
			month: 1,
			day: 1,
			hour: 0,
			minute: 0,
		};
	}
	var date ={
			year: 2018,
			month: 1,
			day: 1,
			hour: 0,
			minute: 0,
		};
	// mettre la date selectionnee en forme, transformer 1 en 01
	function chooseDate (date){
		console.log (typeof (date.month));
		date.month = addO (date.month);
		date.day = addO (date.day);
		date.hour = addO (date.hour);
		date.minute = addO (date.minute);
	}
	// choisir la date du jour
	function todayDate (date){
		var today = new Date();
		date.year = today.getFullYear();
		date.month = today.getMonth() +1;
		date.day = today.getDate();
		date.hour = today.getHours();
		date.minute = today.getMinutes();
//		chooseDate (date);
	}
	// choisir la date par defaut, "blank", 0000/00/00, 00:00
	function blankDate (date){
		date.year =0;
		date.month =0;
		date.day =0;
		date.hour =0;
		date.minute =0;
	}
	return {
		newDate,
		todayDate,
		blankDate
	}
});
angular.module ('createEvenement').filter ('dozen', function(){
	// ecrire les nombres sur deux chiffres, 03 au lieu de 3
	return function (number){
		var numberWithDozen = number.toString();
		if (numberWithDozen.length ===1) numberWithDozen ='0'+ numberWithDozen;
		return numberWithDozen;
	}
});