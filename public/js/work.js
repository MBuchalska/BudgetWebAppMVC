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
var expenseLabels = [];
var expenseValues = [];

for (var i =1; i < expenseRows; i++) { 
	expenseSum+=parseFloat(expensesTable.rows[i].cells[1].innerHTML);
	expenseLabels.push(expensesTable.rows[i].cells[0].innerHTML);
	expenseValues.push(parseFloat(expensesTable.rows[i].cells[1].innerHTML));
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
else if (balance == 0) document.getElementById("yourBudget").innerHTML="Jesteś mistrzem prowadzenia budżetu!";


// Balance - generating the chart

  const data = {
    labels: expenseLabels,
    datasets: [{
      label: 'Wykres wydatków',
      backgroundColor: [
		  'rgb(255, 99, 132)', 
		  'rgb(54, 162, 235)',
		  'rgb(255, 204, 0)' ,
		  'rgb(255, 105, 86)' ,
		  'rgb(155, 205, 86)' 
	  ],
      borderColor: '#130838',
	  borderWidth: 0.8,
	  color: '#130838',
      data: expenseValues,
    }]
  };

  const config = {
    type: 'pie',
    data: data,
    options: {}
  };
  
  const myChart = new Chart(
    document.getElementById('piechart'),
    config
  );