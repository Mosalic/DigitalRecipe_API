<?php
	
	//insert file
	require "dbconnection.php";
	
	$user_name = $_POST["user_name"]; //"user_name" and "user_password" declaration in Android Studios BackgroundWorker-Class
	$user_password = $_POST["user_password"];
	$mysql_qry = "SELECT * FROM Users WHERE username LIKE '$user_name' AND password LIKE '$user_password';";
	$result = mysqli_query($conLink, $mysql_qry);
	
	//check if User is in database
	if(mysqli_num_rows($result) > 0){
		echo "login success: Welcome  " .$user_name;
	}else{
		echo "login failed " ;
	}
	
	//close connection to database
	$conLink-> close();
?>