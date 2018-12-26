<?php
error_reporting(E_ERROR);

$login = new Login();

switch($_GET["action"]){
    case "registerUser":
        $r = $login->registerUser($_GET["userName"], $_GET["password"], $_GET["repassword"], $_GET["registerKey"]);
        break;
    case "accessAccount":
        $r = $login->accessAccount($_GET["userName"], $_GET["password"]);
        break;
    case "generateRegisterKey":
        $r = $login->generateRegisterKey($_GET["adminPassword"]);
        break;
    case "isPremium":
        $r = $login->isPremium($_GET["userName"]);
        break;
    default:
        $r = "ERROR:NO_ACTION";
}

echo $r;

class Login{
////LOCAL FUNCTION [->]
    private function query($sql, $arg, $fetch = false){
        require "connection.php";
        $q = $db->prepare($sql);
        $q->execute($arg);
        return $fetch ? $q->fetch(2) : $q;
    }

    private function bcrypt($password){
        return password_hash($password, PASSWORD_BCRYPT, ["cost" => 10]);
    }

    private function userExist($username){
        return $this->query("SELECT accountID FROM accounts WHERE userName COLLATE latin1_bin LIKE ?", array($username), true)["accountID"];
    }

    private function isBanned($username){
        return $this->query("SELECT isBanned FROM accounts WHERE accountID = ?", array($this->getAccountID($username)), true)["isBanned"];
    }

    private function getAccountID($username){
        return $this->query("SELECT accountID FROM accounts WHERE userName COLLATE latin1_bin LIKE ?", array($username), true)["accountID"];
    }
////LOCAL FUNCTION [<-]

////USER FUNCTION [->]
    public function registerUser($username, $password, $repassword, $registerKey){
        if(empty($username) ||empty($password) || empty($registerKey) || empty($repassword)) return "ERROR:MISSING_PARAMETERS";
        if(strlen($username)>20 || strlen($username) < 3) return "ERROR:USERNAME_TOO_SHORT";
        if(strlen($password) < 3) return "ERROR:PASSWORD_TOO_SHORT";
        if(!$this->AssignKey($username, $registerKey)) return "ERROR:INVALID_KEY"; //FUUUUCK
        if($this->userExist($username)) return "ERROR:USERNAME_TAKEN";        
        if($password != $repassword) return "ERROR:PASSWORDS_NOT_MATCH";        
        $this->query("INSERT INTO accounts(userName, password) VALUES (?, ?)", array($username, $this->bcrypt($password)));
        return "OK:DONE";
    }

    public function accessAccount($username, $password){ //=login
        if(empty($username) || empty($password)) return "ERROR:MISSING_PARAMETERS";
        if(!$this->userExist($username)) return "ERROR:INVALID_CREDENTIALS";
        if($this->isBanned($username)) return "ERROR:USER_BANNED";
        $pass = $this->query("SELECT password FROM accounts WHERE userName COLLATE latin1_bin LIKE ?", array($username), true);
        return password_verify($password, $pass["password"]) ? "OK:LOGGED_IN" : "ERROR:INVALID_CREDENTIALS";
    }

    public function isPremium($username){
        if(empty($username)) return "ERROR:MISSING_PARAMETERS";
        return $this->query("SELECT isPremium FROM accounts WHERE accountID  = ?", array($this->getAccountID($username)), true)["isPremium"];
    }
////USER FUNCTION [<-]

////REGISTER KEY FUNCTION [->]
    public function generateRegisterKey($adminpassword, $size = 10){
        if($adminpassword != "test") return "ERROR:NOT_ENOUGH_PRIVILEGES";
        $exist=false;
        do{
            $alpha = "abcdefhijklmnopqrstuvwxyzABCDEFHIJKLMNOPQRSTUVWXYZ0123456789";
            $key = "";
            for($i = 0; $i<$size; $i++){
                $key .= $alpha[mt_rand(0, strlen($alpha) - 1)];
            }
            if($this->keyExist($key)) $exist = true;
        }while($exist);
        $this->query("INSERT INTO registrationKeys(registerKey) VALUES(?)", array($key));
        return $key;
    }

    private function keyExist($key){
        return $this->query("SELECT registerKey FROM registrationKeys WHERE registerKey COLLATE latin1_bin LIKE ? AND userName IS NULL", array($key), true)["registerKey"];
    }
    
    private function AssignKey($username, $key){
        if(!$this->keyExist($key)) return false;
        $this->query("UPDATE registrationKeys SET userName = ? WHERE registerKey COLLATE latin1_bin LIKE ?", array($username, $key));
        return true;
    }
////REGISTER KEY FUNCTION [<-]
}
?>
