<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use App\Models\Budget;


class ExpenseSettings extends Authenticated {
	
	protected function before(){
		parent::before();
		$this->user = Auth::getUser();
		
		$ID= $_SESSION['user_id'];  
		$this->expenseCat = Budget::getExpenseSettings($ID);
	}
	
	public function showAction(){
		View::renderTemplate('ExpenseSettings/show.html', [
				'expense'=>$this->expenseCat
		]);
	}
	
	
	// deleting expense category
	
	public function deleteformAction(){
		View::renderTemplate('/ExpenseSettings/deleteform.html', [
				'expense'=>$this->expenseCat
		]);
	}
		
	public function deleteAction(){
		
		$delete = new Budget($_POST);
				
		if($delete->deleteExpenseCat()){
			Flash::addMessage('Skasowano kategorię');
			$this->redirect('/ExpenseSettings/show');
		}
		else{
			View::renderTemplate('ExpenseSettings/show.html',[
				'expense'=>$this->expenseCat
			]);
		}		
	}
	
	
// adding a new category
	
	public function addcategoryAction(){
		View::renderTemplate('/ExpenseSettings/addcategory.html', [
				'expense'=>$this->expenseCat
		]);
	}
	
	
	public function addAction(){
		
		$addCategory = new Budget($_POST);
			
		if($addCategory->addExpenseCat()){
			Flash::addMessage('Dodano kategorię wydatków');
			$this->redirect('/ExpenseSettings/show');
		}
		else{
			Flash::addMessage('Nie dodano kategorii. Masz już taką na liście', $type='info');
			View::renderTemplate('ExpenseSettings/show.html',[
				'expense'=>$this->expenseCat
			]);
		}		
	}
	
	
// changing a category name
	
	public function changecategoryAction(){
		View::renderTemplate('/ExpenseSettings/changecategory.html', [
				'expense'=>$this->expenseCat
		]);
	}
	
	public function changeAction(){
		
		$changeCategory = new Budget($_POST);
			
		if($changeCategory->changeExpenseCat()){
			Flash::addMessage('Zmieniono nazwę kategorii wydatków');
			$this->redirect('/ExpenseSettings/show');
		}
		else{
			Flash::addMessage('Nie wprowadzono zmian. Masz już taką na liście', $type='info');
			View::renderTemplate('ExpenseSettings/show.html',[
				'expense'=>$this->expenseCat
			]);
		}		
	}
	
	
	// setting a monthly limit for the category
		public function generatelimitAction(){
		View::renderTemplate('/ExpenseSettings/generatelimit.html', [
				'expense'=>$this->expenseCat
		]);
	}
	
		public function generateAction(){
		
		$expenseLimit = new Budget($_POST);
			
		if($expenseLimit->setExpenseLimit()){
			Flash::addMessage('Zmieniono limit wydatków');
			$this->redirect('/ExpenseSettings/show');
		}
		
		else{
			View::renderTemplate('ExpenseSettings/show.html',[
				'expense'=>$this->expenseCat
			]);
		}		
	}
	
	
}