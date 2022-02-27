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
	
	public function calculateBeginingDate(){
		$monthNow=date('n');
		$yearNow=date('Y');
				
		if($monthNow==1) $monthPrev = 12;
		else $monthPrev=$monthNow-1;
		
		$balanceType =$this->BParameters;
		
		//this month
		if($balanceType==11){
			$string=$yearNow.'-'.$monthNow.'-01'; 
			$time=strtotime($string);
			$Date1=date('Y-m-d',$time);
			
			return $Date1;
		}
		
		// previous month
		else if($balanceType==21){
			$string=$yearNow.'-'.$monthPrev.'-01'; 
			$time=strtotime($string);
			$Date1=date('Y-m-d',$time);
			
			return $Date1;			
		}
		
		// this year
		else if($balanceType==31){
			$string=$yearNow.'-01-01'; 
			$time=strtotime($string);
			$Date1=date('Y-m-d',$time);
			
			return $Date1;
		}
		
		//custom range
		else if($balanceType==41){
			return $this->Date1;
		}		
	}
	
	public function calculateEndingDate(){
		$monthNow=date('n');
		$yearNow=date('Y');
		
		if($monthNow==1) $monthPrev = 12;
		else $monthPrev=$monthNow-1;
		
		$balanceType =$this->BParameters;
		
		//this month
		if($balanceType==11){		
			$lastDay=$this->LastDay($monthNow, $yearNow);						
			
			$string=$yearNow.'-'.$monthNow.'-'.$lastDay; 
			$time=strtotime($string);
			$Date2=date('Y-m-d',$time);
			
			return $Date2;
		}
		
		// previous month
		else if($balanceType==21){
			$lastDay=$this->LastDay($monthPrev, $yearNow);
						
			$string=$yearNow.'-'.$monthPrev.'-'.$lastDay; 
			$time=strtotime($string);
			$Date2=date('Y-m-d',$time);
			
			return $Date2;			
		}
		
		// this year
		else if($balanceType==31){
			$string=$yearNow.'-12-31'; 
			$time=strtotime($string);
			$Date2=date('Y-m-d',$time);
			
			return $Date2;
		}
		
		//custom range
		else if($balanceType==41){
			return $this->Date2;
		}		
	}
	
	protected function LastDay($parameter, $yearNow){
		if(($parameter==2)&&((($yearNow%4==0)&&($yearNow%100!=0))||($yearNow%400==0))) $lastDay=29;
		else if ($parameter==2) $lastDay =28;
		else if (($parameter==1)||($parameter==3)||($parameter==5)||($parameter==7)||($parameter==8)||($parameter==10)||($parameter==12)) $lastDay =31;
		else $lastDay =30;
						
	return $lastDay;
	}
	
	
	public static function getIncomesByDate($Date1, $Date2, $ID){
		
		$sql = "SELECT icat.incomeCatName, SUM(inc.amount) AS sum FROM  income_categories AS icat, incomes AS inc WHERE userID=:id AND inc.data >=:data1 AND inc.data<=:data2 AND inc.amount>0  AND inc.categoryID=icat.incomeCatID GROUP BY inc.categoryID ORDER BY SUM(inc.amount) DESC";
			
		$db = static::getDB();
		$stmt=$db->prepare($sql);
			
		$stmt->bindValue(':id', $ID, PDO::PARAM_STR);
		$stmt->bindValue(':data1', $Date1, PDO::PARAM_STR);
		$stmt->bindValue(':data2', $Date2, PDO::PARAM_STR);
      	$stmt->execute();
				
		return $stmt->fetchAll(PDO::FETCH_ASSOC); 
	}
	
	public static function getExpensesByDate($Date1, $Date2, $ID){
		
		$sql = "SELECT ecat.expenseCatName, SUM(exp.amount) AS sum FROM  expense_categories AS ecat, expenses AS exp WHERE userID=:id AND exp.data >=:data1 AND exp.data<=:data2 AND exp.amount>0  AND exp.categoryID=ecat.expenseCatID GROUP BY exp.categoryID ORDER BY SUM(exp.amount) DESC";
			
		$db = static::getDB();
		$stmt=$db->prepare($sql);
			
		$stmt->bindValue(':id', $ID, PDO::PARAM_STR);
		$stmt->bindValue(':data1', $Date1, PDO::PARAM_STR);
		$stmt->bindValue(':data2', $Date2, PDO::PARAM_STR);
      	$stmt->execute();
			//var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));
		return $stmt->fetchAll(PDO::FETCH_ASSOC); 
	}
	
	
	public static function getIncomesDetailsByDate($Date1, $Date2, $ID){
		$sql = "SELECT icat.incomeCatName, inc.data, inc.comment, inc.amount FROM  income_categories AS icat, incomes AS inc WHERE userID=:id AND inc.data >=:data1 AND inc.data<=:data2 AND inc.amount>0  AND inc.categoryID=icat.incomeCatID ORDER BY inc.categoryID, inc.data ASC";
			
		$db = static::getDB();
		$stmt=$db->prepare($sql);
			
		$stmt->bindValue(':id', $ID, PDO::PARAM_STR);
		$stmt->bindValue(':data1', $Date1, PDO::PARAM_STR);
		$stmt->bindValue(':data2', $Date2, PDO::PARAM_STR);
      	$stmt->execute();
				
		return $stmt->fetchAll(PDO::FETCH_ASSOC); 
	}
	
	public static function getExpensesDetailsByDate($Date1, $Date2, $ID){
		$sql = "SELECT ecat.expenseCatName, exp.data, exp.comment, exp.amount FROM  expense_categories AS ecat, expenses AS exp WHERE userID=:id AND exp.data >=:data1 AND exp.data<=:data2 AND exp.amount>0  AND exp.categoryID=ecat.expenseCatID ORDER BY exp.categoryID , exp.data ASC";
			
		$db = static::getDB();
		$stmt=$db->prepare($sql);
			
		$stmt->bindValue(':id', $ID, PDO::PARAM_STR);
		$stmt->bindValue(':data1', $Date1, PDO::PARAM_STR);
		$stmt->bindValue(':data2', $Date2, PDO::PARAM_STR);
      	$stmt->execute();
			//var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));
		return $stmt->fetchAll(PDO::FETCH_ASSOC); 
	}
	
	
}
