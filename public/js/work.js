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

// Balance - calculate the balance and show how is your budget

var table = document.getElementById("summaryTab");
var incomes = table.rows[0].cells[1].innerHTML;
var expenses = table.rows[1].cells[1].innerHTML;


var balance = incomes - expenses;
balance = balance.toFixed(2);
document.getElementById("FinalBalance").innerHTML = balance;

if (balance > 0) {
	document.getElementById("yourBudget").innerHTML="Doskonale! Zarabiasz więcej niż wydajesz!";
	$('#yourBudget').css('color','green');
}
else if (balance < 0){
	document.getElementById("yourBudget").innerHTML="Niedobrze. Trzeba zminiejszyć wydatki";
	$('#yourBudget').css('color','red');
}
else if (balance = 0) document.getElementById("yourBudget").innerHTML="Jesteś mistrzem prowadzenia budżetu!";


