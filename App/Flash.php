<?php
namespace App;

class Flash{
	// zmienne przechowujące wiadomości 
	const SUCCESS = 'success';
	const INFO = 'info';
	const WARNING = 'warning';
	
	// funkcja dodająca nową wiadomość flash 
	public static function addMessage ($message, $type='success'){
		// Tworzymy sesyjną tabelę wiadomości
		if(!isset($_SESSION['flash_notifications'])){
			$_SESSION['flash_notifications']=[];
		}
		
		$_SESSION['flash_notifications'][]=[
			'body'=>$message,
			'type'=>$type
			];		
	}
	
	public static function getMessages(){
		if(isset($_SESSION['flash_notifications'])){
			$messages= $_SESSION['flash_notifications'];
			unset( $_SESSION['flash_notifications']);
			
			return $messages;
			
		}
	}
	
	
}