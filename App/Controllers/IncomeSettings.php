<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use App\Models\Budget;


class IncomeSettings extends Authenticated {
	
	protected function before(){
		parent::before();
		$this->user = Auth::getUser();
		
		$ID= $_SESSION['user_id'];  
		$this->incomeCat = Budget::getIncomeSettings($ID);
	}
	
	public function showAction(){
		View::renderTemplate('IncomeSettings/show.html', [
				'income'=>$this->incomeCat
		]);
	}
	
	
	// deleting income category
	
	public function deleteformAction(){
		View::renderTemplate('/IncomeSettings/deleteform.html', [
				'income'=>$this->incomeCat
		]);
	}
		
	public function deleteAction(){
		
		$delete = new Budget($_POST);
				
		if($delete->deleteIncomeCat()){
			Flash::addMessage('Skasowano kategorię');
			$this->redirect('/IncomeSettings/show');
		}
		else{
			View::renderTemplate('IncomeSettings/show.html',[
				'income'=>$this->incomeCat
			]);
		}		
	}
	
	
// adding a new category
	
	public function addcategoryAction(){
		View::renderTemplate('/IncomeSettings/addcategory.html', [
				'income'=>$this->incomeCat
		]);
	}
	
	
	public function addAction(){
		
		$addCategory = new Budget($_POST);
			
		if($addCategory->addIncomeCat()){
			Flash::addMessage('Dodano kategorię przychodów');
			$this->redirect('/IncomeSettings/show');
		}
		else{
			Flash::addMessage('Nie dodano kategorii. Masz już taką na liście', $type='info');
			View::renderTemplate('IncomeSettings/show.html',[
				'income'=>$this->incomeCat
			]);
		}		
	}
	
	
// changing a category name
	
	public function changecategoryAction(){
		View::renderTemplate('/IncomeSettings/changecategory.html', [
				'income'=>$this->incomeCat
		]);
	}
	
	public function changeAction(){
		
		$changeCategory = new Budget($_POST);
			
		if($changeCategory->changeIncomeCat()){
			Flash::addMessage('Zmieniono nazwę kategorii przychodów');
			$this->redirect('/IncomeSettings/show');
		}
		else{
			Flash::addMessage('Nie wprowadzono zmian. Masz już taką na liście', $type='info');
			View::renderTemplate('IncomeSettings/show.html',[
				'income'=>$this->incomeCat
			]);
		}		
	}
	
	
	
}