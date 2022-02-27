<?php
namespace App\Controllers;

use \Core\View;
use \App\Auth;
use App\Models\Budget;
use \App\Flash;


class Balancedetails extends Authenticated { 
	protected function before(){
		parent::before();
		$this->user = Auth::getUser();  
		$ID= $_SESSION['user_id'];  
				
	}	
	// rendering the form
	public function newAction(){
		View::renderTemplate('Balancedetails/new.html');
	}
		
		
// gets income and expense data from the budget model		
	public function createAction(){
		$ID= $_SESSION['user_id'];
		
		$dateRange = new Budget($_POST);
		$Date1= $dateRange->calculateBeginingDate();
		$Date2= $dateRange->calculateEndingDate();
		
		$this->incomes = Budget::getIncomesDetailsByDate($Date1, $Date2, $ID);
		$this->expenses = Budget::getExpensesDetailsByDate($Date1, $Date2, $ID);
		
		View::renderTemplate('Balancedetails/balance.html',[
				'expense'=>$this->expenses, 'income'=>$this->incomes
		]);
		
		
	}
	

}