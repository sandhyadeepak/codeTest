<?php
class Connect_Db
{
	var $conn = null;
	//var $servername = "142.4.25.60:3306";
	var $servername = "localhost:3306"; 
	var $username = "sarwasin_admin";
	var $password = "IDpiQ%&7V9n"; //newsportal db
	var $dbname="sarwasin_web"; 	
	
	public function __construct()
	{
		$this->conn = new mysqli($this->servername, $this->username, $this->password,$this->dbname);
		// $this->conn -> set_charset("utf8mb4_unicode_ci");
	}
	/*public dbconnect()
	{
		$this->conn = new mysqli($this->servername, $this->username, $this->password,$this->dbname);		
	}*/
	function Connect()
	{		
		$connectionObj = $this->conn;
		if ($connectionObj->connect_error) 
		{
			die("Connection failed: " . $connectionObj->connect_error);
		} 
		 $this->conn -> set_charset("utf8mb4_unicode_ci");
		// echo "Current character set is: " . $mysqli -> character_set_name();
		return $this->conn;
	}
	
	function Disconnect()
	{
		$connectionObj = $this->conn;
		$connectionObj->close();
	}
}
?>
