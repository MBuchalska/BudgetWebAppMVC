<?php
namespace App\Controllers;

use \Core\View;
use \App\Auth;
use App\Models\Budget;
use \App\Flash;


class Income extends Authenticated { 
	protected function before(){
		parent::before();
		$this->user = Auth::getUser();  
		$ID= $_SESSION['user_id'];  
		$this->incomeCat = Budget::getIncomeSettings($ID);

		
	}	
	// rendering the form
	public function newAction(){
		View::renderTemplate('Income/new.html',[
				'income'=>$this->incomeCat
		]);
	}
		
		
// adds income from the form		
	public function createAction(){
	
		$income = new Budget($_POST);
			
		
		if($income->saveIncome()){
			Flash::addMessage('Income added');
			$this->redirect('/Income/new');
		}
		else{
			View::renderTemplate('Income/new.html',[
				'income'=>$this->incomeCat
			]);
		}
			
	}
	
	
}