<?php
class Connect_Db
{
	var $conn = null;	
	var $servername = "localhost"; 
	var $username = "root";
	var $password = "";  
	var $dbname="dbwordpress"; 	
	
	public function __construct()
	{
		$this->conn = new mysqli($this->servername, $this->username, $this->password,$this->dbname);
		
	}
	
	function Connect()
	{		
		$connectionObj = $this->conn;
		if ($connectionObj->connect_error) 
		{
			die("Connection failed: " . $connectionObj->connect_error);
		} 
		$this->conn -> set_charset("utf8mb4_unicode_ci");		
		return $this->conn;
	}
	
	function Disconnect()
	{
		$connectionObj = $this->conn;
		$connectionObj->close();
	}
}
?>
