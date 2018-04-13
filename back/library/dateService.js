// source: https://www.w3schools.com/jsref/jsref_obj_date.asp
angular.module ('evenments').service ('dateService', function(){
	function newDate(){
		return {
			year: 2018,
			month: 1,
			day: 1,
			hour: 0,
			minute: 0,
		};
	}
	// choisir la date du jour
	function todayDate (date){
		var today = new Date();
		date.year = today.getFullYear();
		date.month = today.getMonth() +1;
		date.day = today.getDate();
		date.hour = today.getHours();
		date.minute = today.getMinutes();
	}
	// choisir la date par defaut, "blank", 0000/00/00, 00:00
	function blankDate (date){
		date.year =2018;
		date.month =1;
		date.day =1;
		date.hour =0;
		date.minute =0;
	}
	// les annees bissextiles. la premiere case represente decembre, le mois avant janvier
	const daysInMonth =[31, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
	function isBissextile (year){
		var bissextile = false;
		if (year %4 ==0){
			bissextile = true;
			if (year %100 ==0){
				bissextile = false;
				if (year %400 ==0) bissextile = true;
			}
		}
		return bissextile;
	}


	// mettre la date selectionnee en forme
	// transformer 1 en 01
	function addO (number){
		if (typeof (number) === 'string' && number.length ===1) number ='0'+ number;
		else if (typeof (number) === 'number' && number <10) number ='0'+ number;
		return number;
	}
	// transformer les champs en string
	function dayToString (date){
		let day = date.year +'/';
		day = day + addO (date.month) +'/';
		day = day + addO (date.day);
		return day;
	}
	function hourToString (date){
		let hour = addO (date.hour) +':';
		hour = hour + addO (date.minute);
		return hour;
	}
	// transformer des strings en champs d'un objet date
	function dayFromString (date, strDay){
		lstDay = strDay.split ('/');
		date.year = parseInt (lstDay[0]);
		date.month = parseInt (lstDay[1]);
		date.day = parseInt (lstDay[2]);
	}
	function hourFromString (date, strHour){
		lstHour = strHour.split (':');
		date.hour = parseInt (strHour[0]);
		date.minute = parseInt (strHour[1]);
	}
	// extraire la date d'un objet
	function fromObj (obj){
		var evtDate = newDate();
		if (obj.year) date.year = obj.year;
		if (obj.month) date.month = obj.month;
		if (obj.day) date.day = obj.day;
		if (obj.hour) date.hour = obj.hour;
		if (obj.minute) date.minute = obj.minute;
		return evtDate;
	}
	// comparer deux dates
	function comparDates (date1, date2){
		// nb jours entre deux dates
		var nbDays =0;
		nbDays += 365* (date1.year - date2.year);
		nbDays
	}
	return {
		newDate,
		todayDate,
		blankDate,
		dayToString,
		hourToString,
		dayFromString,
		hourFromString,
		fromObj
	}
});
angular.module ('evenments').filter ('dozen', function(){
	// ecrire les nombres sur deux chiffres, 03 au lieu de 3
	return function (number){
		var numberWithDozen = number.toString();
		if (numberWithDozen.length ===1) numberWithDozen ='0'+ numberWithDozen;
		return numberWithDozen;
	}
});