<?php
$host = "127.0.0.1";
$dbname = "your-database-name";
$username = "your-username";
$password = "your-password";
try{
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
}catch(Exception $e){
    die("Fatal error: ".$e->getMessage());
}
?>
