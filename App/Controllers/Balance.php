<?php
namespace App\Controllers;

use \Core\View;
use \App\Auth;
use App\Models\Budget;
use \App\Flash;


class Balance extends Authenticated { 
	protected function before(){
		parent::before();
		$this->user = Auth::getUser();  
		$ID= $_SESSION['user_id'];  
				
	}	
	// rendering the form
	public function newAction(){
		View::renderTemplate('Balance/new.html');
	}
		
		
// gets income and expense data from the budget model		
	public function createAction(){
		$ID= $_SESSION['user_id'];
		
		$dateRange = new Budget($_POST);
		$Date1= $dateRange->calculateBeginingDate();
		$Date2= $dateRange->calculateEndingDate();
		
		$this->incomes = Budget::getIncomesByDate($Date1, $Date2, $ID);
		$this->expenses = Budget::getExpensesByDate($Date1, $Date2, $ID);
		
		View::renderTemplate('Balance/balance.html',[
				'expense'=>$this->expenses, 'income'=>$this->incomes
		]);
		
		
	}
	

}