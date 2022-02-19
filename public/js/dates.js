//Expense and income - insert initial date, today

window.onload = getDate;

function getDate(){
	var today = new Date();
			
	var day = today.getDate();
	var month = today.getMonth()+1;
	var year = today.getFullYear();
		
		if (month<10) month="0"+month;
		if (day<10) day="0"+day;
		
	var DateNow = year+"-"+month+"-"+day;

	const dateInput = document.querySelector('.form__date');
	dateInput.value = DateNow;
}

//Balance - make date range visible in case of custom date range

var RangeOption = document.getElementById("BParameters");

RangeOption.addEventListener("click", function() {MakeDateRangeVisible()} );  

function MakeDateRangeVisible(){
	var DateRangeOption =document.getElementById("BParameters").value;
 	
	if (DateRangeOption == 41) $('.dates').css('display', 'block');
	else  $('.dates').css('display', 'none');
}
