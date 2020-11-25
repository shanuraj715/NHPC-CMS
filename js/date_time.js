


//getFullYear()	Get the year as a four digit number (yyyy)
//getMonth()	Get the month as a number (0-11)
//getDate()	Get the day as a number (1-31)
//getHours()	Get the hour (0-23)
//getMinutes()	Get the minute (0-59)
//getSeconds()	Get the second (0-59)
//getMilliseconds()	Get the millisecond (0-999)
//getTime()	Get the time (milliseconds since January 1, 1970)
//getDay()	Get the weekday as a number (0-6)




function getYear()
{
	var d=new Date();
    var year = d.getFullYear();
    return year;
}

function getMonth()
{
	var d=new Date();
    var month = d.getMonth() + 1;
    return month;
}

function getMonthName()
{
	var d=new Date();
    var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var month = monthNames[getMonth() - 1];
    return month;
}

function getDate()
{
	var d=new Date();
	var date = d.getDate();
	return date;
}

function getDay()
{
	var d=new Date();
	var days = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
	var day = days[d.getDay()];
	return day;
}

function getHours()
{
	var d=new Date();
	var hour = d.getHours();
	return hour;
}

function getMinutes()
{
	var d=new Date();
	var mins = d.getMinutes();
	return mins;
}

function getSeconds()
{
	var d=new Date();
	var sec = d.getSeconds();
	return sec;
}