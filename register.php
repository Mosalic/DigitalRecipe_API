<?php
	
	//insert file
	require "dbconnection.php";
	
	//$user_id = $_POST["user_id"]; //increase automatic, see database settings
	$user_name = $_POST["user_name"]; //"user_name" and "user_password" declaration in Android Studios BackgroundWorker-Class
	$user_password = $_POST["user_password"];
	
	// insert data
	$mysql_qry = "INSERT INTO Users(username, password) VALUES('$user_name', '$user_password');";
	
	//
	if($conLink->query($mysql_qry) === true){
		echo "Insert success: New Insert with username:  " .$user_name;
	}else{
		echo "Error: " . $mysql_qry . "<br>" . $conLink->error ;
	}
	
	//close connection to database
	$conLink-> close();
?>