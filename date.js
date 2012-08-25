days = new Array(
"Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"
);
months = new Array(
"Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Decembre"
);

function renderDate(){
	var mydate = new Date();
	var year = mydate.getYear();
	if (year < 2000) {
		if (document.all)
			year = "19" + year;
		else
			year += 1900;
	}
	var day = mydate.getDay();
	var month = mydate.getMonth();
	var daym = mydate.getDate();
	if (daym < 10)
		daym = "0" + daym;
	var hours = mydate.getHours();
	var minutes = mydate.getMinutes();
	var dn = "AM";
	if (hours >= 12) {
		dn = "PM";
		hours = hours - 12;
	}
	if (hours == 0)
		hours = 12;
	if (minutes <= 9)
		minutes = "0" + minutes;
	document.writeln(days[day]," ",daym," ",months[month]," ",year,"  |  ",hours,":",minutes," ",dn," ");
}

renderDate();
