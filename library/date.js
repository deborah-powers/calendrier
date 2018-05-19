
// la premiere case represente decembre, le mois avant janvier
const daysInMonth =[31, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];


function newDate(){
	return {
		year: 2018,
		month: 1,
		day: 1,
		hour: 0,
		minute: 0,
	};
}
// extraire la date d'un objet
function fromObj (obj){
	var date = newDate();
	if (obj.year) date.year = obj.year;
	if (obj.month) date.month = obj.month;
	if (obj.day) date.day = obj.day;
	if (obj.hour) date.hour = obj.hour;
	if (obj.minute) date.minute = obj.minute;
	return date;
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
// les annees bissextiles
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
// modifier la date
function addMonth (date){
	date.month +=1;
	if (date.month >12){
		date.month =1;
		date.year +=1;
		}
	}
function addDay (date){
	date.day +=1;
	if ((date.month ==2) && (date.bissextile) && (date.day >29)){
		date.day =1;
		addMonth (date);
		}
	else if (date.day > daysInMonth [date.month]){
		date.day =1;
		addMonth (date);
		}
	}
function addWeek (date){
	for (d=0; d<7; d++){ addDay (date); }
}
// comparer deux dates
function comparDates (date1, date2){
	/* verifier si date1 > date2
		1: date1 > date2
		0: date1 = date2
		-1: date1 < date2
	*/
	var isSup =-1;
	if (date1.year > date2.year) isSup =1;
	else if (date1.year == date2.year){
		if (date1.month > date2.month) isSup =1;
		else if (date1.month == date2.month){
			if (date1.day > date2.day) isSup =1;
			else if (date1.day == date2.day){
				if (date1.hour > date2.hour) isSup =1;
				else if (date1.hour == date2.hour){
					if (date1.minute > date2.minute) isSup =1;
					else if (date1.minute == date2.minute) isSup =0;
				}
			}
		}
	}
	return isSup;
}
function diffDates (date1, date2){
	// si date2 > date1. toujours avoir la plus grande date en premier
	if ($date2 > date1) return comparDates (date2, date1);
	// cas simple, date1 > date2
	var nvDate = newDate();
	nvDate.year = date1.year - $date2.year;
	nvDate.month = date1.month - $date2.month;
	nvDate.day = date1.day - $date2.day;
	nvDate.hour = date1.hour - $date2.hour;
	nvDate.minute = date1.minute - $date2.minute;
	if (nvDate.minute <0){
		nvDate.minute +=60;
		nvDate.hour -=1;
	}
	if (nvDate.hour <0){
		nvDate.hour +=24;
		nvDate.day -=1;
	}
	if (nvDate.day <0){
		nvDate.day += daysInMonth [date1.month -1];
		if (date1.bissextile && date1.month >2) nvDate.day +=1;
		if ($date2.bissextile && $date2.month >2) nvDate.day -=1;
		nvDate.month -=1;
	}
	if (nvDate.month <0){
		nvDate.month +=12;
		nvDate.year -=1;
	}
	return nvDate;
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