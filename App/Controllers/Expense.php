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

// getting expense limit for the category	
	public function getExpenseLimitAction(){
		
	//getting the category ID and date from fetch	
		$categoryID=$this->route_params['token'];   	
				
	// getting expense limit and sum from database
		$expenseInfo = new Budget();
		$expenseInfo->expLimit = round(Budget::getExpenseLimit($categoryID), 2);
		$expenseInfo->expSum = round(Budget::getSumForExpense($categoryID), 2);
		
		$expenseJSON=json_encode($expenseInfo);
		echo $expenseJSON;
	}
	
	
}