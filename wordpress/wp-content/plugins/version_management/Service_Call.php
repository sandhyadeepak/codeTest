<?php
	require_once("Common.php"); 
	require_once("Connect_Db.php");
	if(isset($_POST['action']) && !empty($_POST['action'])) {
		$action = $_POST['action'];
		switch($action) {			
			case 'getVersionData' : getVersionData();break;
			case 'createVersionData' : createVersionData();break;
			case 'getFilteredData' : getFilteredData();break;
			
		}
	}
	
	/******************************************************************/
	function getFilteredData(){
		$fromversiontime = $_POST['fromversiontime'];
		$toversiontime = $_POST['toversiontime'];

		$connectDb = new Connect_Db();
		$Conn = $connectDb->Connect(); 
		$responce=array();
		$sql="SELECT * FROM wp_version WHERE date_time BETWEEN '".$fromversiontime."' AND '".$toversiontime."'";
		$result = $Conn->query($sql);
		if ($result->num_rows >0)  {
			while($row = $result->fetch_assoc())    
			{
				$responce[]=$row;							
			}
		}
		$result = json_encode($responce);
	  	echo $result; 
    }
	/******************************************************************/
	function getVersionData(){
		
		$connectDb = new Connect_Db();
		$Conn = $connectDb->Connect(); 
		$responce=array();
		$sql="SELECT * FROM wp_version";
		$result = $Conn->query($sql);
		if ($result->num_rows >0)  {
			while($row = $result->fetch_assoc())    
			{
				$responce[]=$row;							
			}
		}
		$result = json_encode($responce);
	  	echo $result; 
    }
	/*************************************************** */
	function createVersionData(){
		$date_time = $_POST['datetime'];
		$p_version = $_POST['p_version'];
		$n_version = $_POST['n_version'];
		
		$connectDb = new Connect_Db();
		$Conn = $connectDb->Connect(); 
		$responce=array();
		$sql="INSERT INTO wp_version(`date_time`,`p_version`,`n_version`)values('".$date_time."','".$p_version."','".$n_version."')";
		$result = $Conn->query($sql);
		
		
	  	echo 1; 
    }
	/*************************************************** */
?>
