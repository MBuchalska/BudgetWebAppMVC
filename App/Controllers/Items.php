<?php
namespace App\Controllers;

use \Core\View;
use \App\Auth;


class Items extends Authenticated { 
	protected function before(){
		parent::before();
		$this->user = Auth::getUser();
	}	
		
	public function indexAction(){
		View::renderTemplate('Items/index.html',[
				'user'=>$this->user
		]);
	}
		
	public function menuAction(){
		View::renderTemplate('Items/menu.html', [
				'user'=>$this->user
		]);
	}
	
	
	
	
}