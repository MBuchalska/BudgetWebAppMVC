<?php

namespace App\Models;

use PDO;
use \Core\View;

/**
 * User model
 *
 * PHP version 7.0
 */
class Budget extends \Core\Model
{

    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     *
     * @param array $data  Initial property values (optional)
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

// selecting setting for the income for the user
   public static function getIncomeSettings($ID){
	   
		$sql = "SELECT iset.categoryID, icat.incomeCatName FROM income_categories AS icat, income_settings AS iset WHERE iset.userID=:id AND iset.categoryID=icat.incomeCatID ORDER BY iset.categoryID ASC";
			
		$db = static::getDB();
		$stmt=$db->prepare($sql);
			
		$stmt->bindValue(':id', $ID, PDO::PARAM_STR);
      	$stmt->execute();
				
		return $stmt->fetchAll();   // w ten sposób do kontrolera obsługującego income zostanie przekazana tabelka z nazwami i kategoriami 
   }
   
   public function saveIncome(){
		$ID= $_SESSION['user_id'];
	  	   
		$sql="INSERT INTO incomes VALUES (NULL, :id, :categoryID, :data, :amount, :comment)";
		
		$db = static::getDB();
		$stmt=$db->prepare($sql);
		
		$stmt->bindValue(':id', $ID, PDO::PARAM_STR);
		$stmt->bindValue(':categoryID', $this->whatIncome, PDO::PARAM_STR);
		$stmt->bindValue(':data', $this->incDate, PDO::PARAM_STR);
		$stmt->bindValue(':amount', $this->incomevalue, PDO::PARAM_STR);
		$stmt->bindValue(':comment', $this->IncComment, PDO::PARAM_STR);
		
		$stmt->execute();
			
		return true;
   }
   
   public static function getExpenseSettings($ID){
	   $sql = "SELECT ec.expenseCatName, es.categoryID FROM expense_categories AS ec, expense_settings AS es WHERE es.userID=:id AND es.categoryID=ec.expenseCatID ORDER BY ec.expenseCatID ASC";
			
		$db = static::getDB();
		$stmt=$db->prepare($sql);
			
		$stmt->bindValue(':id', $ID, PDO::PARAM_STR);
      	$stmt->execute();
							
		return $stmt->fetchAll();
   }
   
   public static function getWayOfPPaySettings($ID){
	    $sql = "SELECT pim.payMethCatName, pset.categoryID FROM pay_method_categories AS pim, pay_method_settings AS pset WHERE pset.userID=:id AND pset.categoryID=pim.payMethCatID ORDER BY pset.categoryID ASC";
			
		$db = static::getDB();
		$stmt=$db->prepare($sql);
			
		$stmt->bindValue(':id', $ID, PDO::PARAM_STR);
      	$stmt->execute();
				
		return $stmt->fetchAll();
   }
   
	public function saveExpense(){
		$ID= $_SESSION['user_id'];
	  	   
		$sql="INSERT INTO expenses VALUES (NULL, :id, :categoryID, :wayID, :data, :amount, :comment)";
		
		$db = static::getDB();
		$stmt=$db->prepare($sql);
		
		$stmt->bindValue(':id', $ID, PDO::PARAM_STR);
		$stmt->bindValue(':categoryID', $this->expCategory, PDO::PARAM_STR);
		$stmt->bindValue(':wayID', $this->HowToPay, PDO::PARAM_STR);
		$stmt->bindValue(':data', $this->expDate, PDO::PARAM_STR);
		$stmt->bindValue(':amount', $this->expencevalue, PDO::PARAM_STR);
		$stmt->bindValue(':comment', $this->ExpComment, PDO::PARAM_STR);
		
		$stmt->execute();
			
		return true;
	}
	
	
}
