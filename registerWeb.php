<?php
	header("Access-Control-Allow-Origin: *"); //für get
	header('Access-Control-Allow-Headers: Content-Type'); //für axios.post Requests, schaltet CORS frei
	
	//insert file
	require "dbconnection.php";
	
	//https://stackoverflow.com/questions/41457181/axios-posting-params-not-read-by-post
	$_POST = json_decode(file_get_contents("php://input"),true); //JSON-Object von React muss umgewandelt werden
	
	//$user= $_POST["user"]; //get Parameter from axios-post-request
	$user_name = $_POST["userName"]; //"userName" and "userPassword" declaration in React LoginComponent from the Parameter user
	$user_password = $_POST["userPassword"];
	
	$mysql_require_qry = "SELECT * FROM Users WHERE username LIKE '$user_name';"; //fragt ab, ob es den username schon gibt
	$result_require = mysqli_query($conLink, $mysql_require_qry);
	
	//check if User is already in database
	if(mysqli_num_rows($result_require) > 0){
		echo "Username: " .$user_name ." existiert bereits mit " .mysqli_num_rows($result_require);
	}else{
		// insert data
		echo "Eintragen";
		$mysql_qry = "INSERT INTO Users(username, password) VALUES('$user_name', '$user_password');";
	
		//
		if($conLink->query($mysql_qry) === true){
			echo "Insert success: New Insert with username:  " .$user_name . ", " . $user_password;
		}else{
			echo "Error: " . $mysql_qry . "<br>" . $conLink->error ;
		}
		
	}
	
	
	
	//close connection to database
	$conLink-> close();
?>