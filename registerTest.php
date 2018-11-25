<?php
	
	//insert file
	require "dbconnection.php";
	
	//$user_id = $_POST["user_id"]; //increase automatic, see database settings
	$user_name = $_POST["userName"]; //"userName" and "userPassword" declaration in Android Studios BackgroundWorker-Class
	$user_password = $_POST["userPassword"];
	
	// insert data
	$mysql_qry = "INSERT INTO Patienten(nutzername, passwort) VALUES('$user_name', '$user_password');";
	
	//
	if($conLink->query($mysql_qry) === true){
		echo "Insert success: New Insert with username:  " .$user_name;
	}else{
		echo "Error: " . $mysql_qry . "<br>" . $conLink->error ;
	}
	
	//close connection to database
	$conLink-> close();
?>