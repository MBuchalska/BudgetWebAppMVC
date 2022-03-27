// making limit table visible

document.getElementById('expenseValue').addEventListener('click', MakeLimitsVisible);  
document.getElementById('expCategory').addEventListener('change', BringMeTheLimit);  
document.getElementById('expenseValue').addEventListener('change', MakeCalculations);  
document.getElementById('expCategory').addEventListener('change', MakeCalculations);  

function MakeLimitsVisible(){
	
	$('.expenseLimit').css('display', 'block');
	
}

// Chatching the change of the category

function BringMeTheLimit(){
	 var expID = document.getElementById('expCategory').value;
	 var url = '/Expense/getExpenseLimit/'+parseInt(expID);
	 
	 fetch(url, { 
       method:'GET' })
      .then(response => response.json())
	  .then(data => {
			var expenseLimits = data;
	
			document.getElementById('expenseLimit').innerHTML = expenseLimits.expLimit;
			document.getElementById('usedAmount').innerHTML = expenseLimits.expSum;			
	  });  
	  
	  MakeCalculations();
	  
}


function MakeCalculations(){
		var expenseLimit = parseFloat(document.getElementById('expenseLimit').innerHTML);
		var expenseSumForNow = parseFloat(document.getElementById('usedAmount').innerHTML);
		var newExpense=parseFloat(document.getElementById('expenseValue').value);
				
		var sum = expenseSumForNow + newExpense;
		var diff = expenseLimit-(expenseSumForNow + newExpense);
		
		document.getElementById('sum1').innerHTML = sum.toFixed(2);
		document.getElementById('diff1').innerHTML = diff.toFixed(2);
		
		if (expenseLimit != 0){
			if (diff > 0) {
				$('.expenseLimit').css('color', 'green');
			}
			
			else if (diff < 0){
				$('.expenseLimit').css('color', 'red');
			}
		}
		
}

