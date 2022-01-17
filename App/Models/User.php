<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;

/**
 * User model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
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

    /**
     * Save the user model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
			
			$token = new Token();
			$hashed_token = $token->getHash();
			$this->activation_token = $token->getValue();
			

            $sql = 'INSERT INTO users (userID, userName, email, password_hash, activation_hash, is_active)
                    VALUES (NULL, :name, :email, :password_hash, :activation_hash, 0)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);
			$stmt->execute();
			
			//adds default values for the user
			//$thisUserName = $this->email; - chyba niepotrzebne
				
			$sql="SELECT userID FROM users WHERE email=:email";
			$stmt=$db->prepare($sql);
			$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
			$stmt->execute();
			$IDs = $stmt->fetch();   ;
			$ID=$IDs[0];
			
			$this->addDefaultSettings($ID);
			
            return true;
        }

        return false;
    }

protected function addDefaultSettings($ID){
	//adding default expenses to settings
	$db = static::getDB();
	
	$sql='SELECT expenseCatID FROM expense_categories WHERE IfDefault=1';
	$stmt = $db->prepare($sql);
	$stmt->execute();
	
	$expNumber=$stmt->rowCount();
	for($i=1; $i<=$expNumber; $i++){
		$numbers= $stmt->fetch();   ;
		$number=$numbers['expenseCatID'];
		
		$insert='INSERT INTO expense_settings VALUES (NULL, :id, :number)';		
		$stmt2 = $db->prepare($insert);
		$stmt2->bindValue(':id', $ID, PDO::PARAM_STR);
		$stmt2->bindValue(':number', $number, PDO::PARAM_STR);
		$stmt2->execute();
	}

		
	// adding default income settings
	$sqlInc='SELECT incomeCatID FROM income_categories WHERE IfDefault=1';
	$stmtInc = $db->prepare($sqlInc);
	$stmtInc->execute();
	
	$incNumber=$stmtInc->rowCount();
	for($i=1; $i<=$incNumber; $i++){
		$numbers= $stmtInc->fetch();   ;
		$number=$numbers['incomeCatID'];
		
		$insertInc='INSERT INTO income_settings VALUES (NULL, :id, :number)';		
		$stmt3 = $db->prepare($insertInc);
		$stmt3->bindValue(':id', $ID, PDO::PARAM_STR);
		$stmt3->bindValue(':number', $number, PDO::PARAM_STR);
		$stmt3->execute();
	}	
	
	// adding default payment methods 
	$sqlPay='SELECT payMethCatID FROM pay_method_categories WHERE IfDefault=1';
	$stmtPay = $db->prepare($sqlPay);
	$stmtPay->execute();
	
	$payNumber=$stmtPay->rowCount();
	for($i=1; $i<=$payNumber; $i++){
		$numbers= $stmtPay->fetch();   ;
		$number=$numbers['payMethCatID'];
		
		$insertPay='INSERT INTO pay_method_settings VALUES (NULL, :id, :number)';		
		$stmt4 = $db->prepare($insertPay);		
		$stmt4->bindValue(':id', $ID, PDO::PARAM_STR);
		$stmt4->bindValue(':number', $number, PDO::PARAM_STR);
		$stmt4->execute();
	}	
	
	
}


    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
        // Name
        if ($this->name == '') {
            $this->errors[] = 'Name is required';
        }

        // email address
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid email';
        }
        if (static::emailExists($this->email, $this->id ?? null)) {
            $this->errors[] = 'email already taken';
        }

        // Password
        if (isset($this->password)) {

            if (strlen($this->password) < 6) {
                $this->errors[] = 'Please enter at least 6 characters for the password';
            }

            if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
                $this->errors[] = 'Password needs at least one letter';
            }

            if (preg_match('/.*\d+.*/i', $this->password) == 0) {
                $this->errors[] = 'Password needs at least one number';
            }
        }
    }

    /**
     * See if a user record already exists with the specified email
     *
     * @param string $email email address to search for
     *
     * @return boolean  True if a record already exists with the specified email, false otherwise
     */
    public static function emailExists($email, $ignore_id=null)
    {
        $user = static::findByEmail($email);
		
		if($user){
			if ($user->userID != $ignore_id){
				return true;
			}
		}
		return false;
    }

    /**
     * Find a user model by email address
     *
     * @param string $email email address to search for
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Authenticate a user by email and password.
     *
     * @param string $email email address
     * @param string $password password
     *
     * @return mixed  The user object or false if authentication fails
     */
    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);

        if ($user &&  $user->is_active) {
            if (password_verify($password, $user->password_hash)) {
                return $user;
            }
        }

        return false;
    }

    /**
     * Find a user model by ID
     *
     * @param string $id The user ID
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE userID = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Remember the login by inserting a new unique token into the remembered_logins table
     * for this user record
     *
     * @return boolean  True if the login was remembered successfully, false otherwise
     */
    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();

        //$expiry_timestamp = time() + 60 * 60 * 24 * 30;  // 30 days from now
        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;  // 30 days from now

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
                VALUES (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        //$stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }
	
	public static function sendPasswordReset ($email){
		$user = static::findByEmail($email);
		
		if($user){
			// reset password
			if($user -> startPasswordReset()){
				 //send mail to the user
				 $user ->sendPasswordResetEmail();
				
			}
		}
		
		
	}
	
	protected function startPasswordReset(){
		$token = new Token();
		$hashed_token= $token->getHash();
		$this -> password_reset_token=$token->getValue();
		
		$expiry_timestamp= time()+ 60*60*2; //valid for 2 h
		
		$sql = 'UPDATE users 
					SET password_reset_hash = :token_hash, 
							password_reset_exp=:expires_at
					WHERE id=:id';
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
	}
	
	protected function sendPasswordResetEmail(){
		$url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;
		
		$text = View::getTemplate('Password/reset_email.txt', ['url' => $url]);
        $html = View::getTemplate('Password/reset_email.html', ['url' => $url]);

        Mail::send($this->email, 'Password reset', $text, $html);
		
	}
	
	
	public static function findByPasswordReset($token){
		$token = new Token($token);
		$hashed_token = $token->getHash();
		
		$sql = 'SELECT * FROM users
                WHERE password_reset_hash = :token_hash';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
		
		$user = $stmt->fetch();
		
		if ($user) {

            // Check password reset token hasn't expired
            if (strtotime($user->password_reset_exp) > time()) {
                return $user;
			}
		}
	}
	
	public function resetPassword($password){
		$this->password = $password;
		$this->validate();
		
		if (empty($this->errors)){
			$password_hash = password_hash($this->password, PASSWORD_DEFAULT);
			$sql = 'UPDATE users
						SET password_hash = :password_hash,
						password_reset_hash = NULL,
                        password_reset_exp = NULL
						WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
		
	}
	
	  public function sendActivationEmail()   {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/signup/activate/' . $this->activation_token;

        $text = View::getTemplate('Signup/activation_email.txt', ['url' => $url]);
        $html = View::getTemplate('Signup/activation_email.html', ['url' => $url]);
		$mailTitle =  'Aktywacja konta w aplikacji do planowania wydatkow';
		
        Mail::send($this->email, $this->name, $mailTitle, $text, $html);
    }
	
	public static function activate($value){
		$token = new Token($value);
		$hashed_token = $token->getHash();
		
		$registration_date= time();
		
		$sql = 'UPDATE users
                SET is_active = 1,
                    activation_hash = null,
					registration_date = :registration_date 
                WHERE activation_hash = :hashed_token';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
		$stmt->bindValue(':registration_date', date('Y-m-d H:i:s', $registration_date), PDO::PARAM_STR);
		$stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);
		
        $stmt->execute();    
	}
	
	public function updateProfile($data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];

        // Only validate and update the password if a value provided
        if ($data['password'] != '') {
            $this->password = $data['password'];
        }

        $this->validate();

        if (empty($this->errors)) {

            $sql = 'UPDATE users
                    SET userName = :name,
                        email = :email';

            // Add password if it's set
            if (isset($this->password)) {
                $sql .= ', password_hash = :password_hash';
            }

            $sql .= "\nWHERE userID = :id";

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            // Add password if it's set
            if (isset($this->password)) {
                $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
                $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            }

            return $stmt->execute();
        }

        return false;
    }
	
	
	
	
}
