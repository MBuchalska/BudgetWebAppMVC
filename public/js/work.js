//getting the value of incomes 

var incomesTable = document.getElementById("incomeTable");
var incomeRows = incomesTable.rows.length;
var incomeSum = 0;

for (var i =1; i < incomeRows; i++) { 
	incomeSum+=parseFloat(incomesTable.rows[i].cells[1].innerHTML);
}

incomeSum = incomeSum.toFixed(2);
document.getElementById("IncomeSum").innerHTML = incomeSum;


//getting the value of expenses 

var expensesTable = document.getElementById("expenseTable");
var expenseRows = expensesTable.rows.length;
var expenseSum = 0;

for (var i =1; i < expenseRows; i++) { 
	expenseSum+=parseFloat(expensesTable.rows[i].cells[1].innerHTML);
}

expenseSum = expenseSum.toFixed(2);
document.getElementById("ExpenseSum").innerHTML = expenseSum;


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


// Balance - generating the chart

//var x =  expensesTable.rows[1].cells[0].innerHTML;
//var y =  parseFloat(expensesTable.rows[2].cells[1].innerHTML);

//document.getElementById("temp2").innerHTML = x;

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);


function drawChart() {
var data = google.visualization.arrayToDataTable([
        ['Wydatek', 'Kwota'],		  
          ['Sleep',     256],
          ['Eat',      235],
          [expensesTable.rows[1].cells[0].innerHTML,  2],
          ['Watch TV', 2],
          ['Sleep',    7],

]);

var options = {
  title: 'Zestawienie wydatków',
  backgroundColor: '#ecc9c2',
  fontSize: 18,
  fontName: 'Lato',
};

var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
}