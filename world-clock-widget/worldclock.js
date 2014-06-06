function getMonthDays(year) {
  var monthDays = new Array("31","28","31","30","31","30","31","31","30","31","30","31");
  if(year%4 == 0) { monthDays[1] = "29"; }
  if(year%100 == 0 && year%400 != 0) { monthDays[1] = "28"; }
  return monthDays;
}

function getGMTDateTime() {
  if(!serverclock) {
    var clientDateTime = new Date();
    var gmtDateTime = clientDateTime.getTime() + (clientDateTime.getTimezoneOffset() * 60 * 1000);
  } else {
    var serverDateTime = new Date(
      gmttime[0],
      gmttime[1],
	  gmttime[2],
	  gmttime[3],
	  gmttime[4],
	  gmttime[5],
	  0
	);
	var gmtDateTime = serverDateTime.getTime();
  }
  return new Date(gmtDateTime);
}

function getDateTime(zone, dst) {
  var gmtDateTime = getGMTDateTime();  
  var dateTime = new Array();
  dateTime["day"] = gmtDateTime.getDate();
  dateTime["month"] = gmtDateTime.getMonth();
  dateTime["year"] = gmtDateTime.getYear();
  dateTime["hour"] = gmtDateTime.getHours() + parseInt(zone);
  dateTime["min"] = parseInt(getMinutes()) + parseInt((zone - parseInt(zone)) * 60);
  dateTime["sec"] = getSeconds();
  return updateClock(dateTime, dst);
}

function updateClock(dateTime, dst) {
  if(dst == true) {
    dateTime["hour"] += 1;
  }
  
  if(dateTime["sec"] >= 60) {
    dateTime["sec"] -= 60;
    dateTime["min"] += 1;
  }

  if(dateTime["sec"] < 0) {
    dateTime["sec"] += 60;
    dateTime["min"] -= 1;
  }
  
  if(dateTime["min"] >= 60) {
    dateTime["min"] -= 60;
    dateTime["hour"] += 1;
  }

  if(dateTime["min"] < 0) {
    dateTime["min"] += 60;
    dateTime["hour"] -= 1;
  }

  if (dateTime["hour"] >= 24) {
    dateTime["hour"] -= 24;
    dateTime["day"] += 1;
  }
  
  if (dateTime["hour"] < 0) {
    dateTime["hour"] += 24
    dateTime["day"] -= 1
  }

  if(dateTime["year"] < 1000) {
    dateTime["year"] += 1900;
  }
  
  var monthDays = getMonthDays(dateTime["year"]);
  
  if (dateTime["day"] <= 0) {
    if (dateTime["month"] == 0) {
      dateTime["month"] = 11;
      dateTime["year"] -= 1;
    } else {
      dateTime["month"] -= 1;
    }
    dateTime["day"] = monthDays[dateTime["month"]];
  }

  if(dateTime["day"] > monthDays[dateTime["month"]]) {
    dateTime["day"] = 1;
    if(dateTime["month"] == 11) {
      dateTime["month"] = 0;
      dateTime["year"] += 1;
    } else {
      dateTime["month"] += 1;
    }
  }
  
  return dateTime;
}

function getMinutes() {
  var min = getGMTDateTime().getMinutes()
  return ( (min < 10) ? "0" : "" ) + min ;
}

function getSeconds() {
  var sec = getGMTDateTime().getSeconds()
  return ( (sec < 10) ? "0" : "" ) + sec ;
}

function getFormattedDateTime(datetime, dateformat, timeformat) {
  var newdate = new Date(
	datetime["year"],
	datetime["month"],
	datetime["day"],
	datetime["hour"],
	datetime["min"],
	datetime["sec"],
	0
  );
  return "<span class='timer-date'>"+newdate.toString(dateformat)+"</span><span class='timer-time'>"+newdate.toString(timeformat)+((datetime["dst"]!=0)?" DST":"</span>");
}

function worldClock(zone, dst, dateformat, timeformat) {
  var dateTime = getDateTime(zone, dst);
  dateTime["dst"] = (dst==true) ? 1 : 0;
  return getFormattedDateTime(dateTime, dateformat, timeformat);
}
