<?php
namespace App\Controllers;

use \Core\View;
use \App\Auth;
use App\Models\Budget;
use \App\Flash;


class Expense extends Authenticated { 
	protected function before(){
		parent::before();
		$this->user = Auth::getUser();  
		$ID= $_SESSION['user_id'];  
		
		$this->expenseCat = Budget::getExpenseSettings($ID);
		$this->wayOfPayCat = Budget::getWayOfPPaySettings($ID);
		
	}	
	
	// rendering the form
	public function newAction(){
		View::renderTemplate('Expense/new.html',[
				'expense'=>$this->expenseCat, 'payment'=>$this->wayOfPayCat
		]);
	}
		
	// adding the expence from the form	
	public function createAction(){
	
		$expense = new Budget($_POST);
		
		if($expense->saveExpense()){
			Flash::addMessage('Expense added');
			$this->redirect('/Expense/new');
		}
		else{
			View::renderTemplate('Expense/new.html',[
				'expense'=>$this->expenseCat, 'payment'=>$this->wayOfPayCat
			]);
		}
			
	}
	
	
}