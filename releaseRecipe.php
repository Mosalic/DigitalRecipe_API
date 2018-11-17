<?php
	header("Access-Control-Allow-Origin: *"); //für get
	header('Access-Control-Allow-Headers: Content-Type'); //für axios.post Requests, schaltet CORS frei
	//insert file
	require "dbconnection.php";
	
	//when json is postet $_POST is null and must decoded (from web app), android app posts not in json
	if($_POST == null){
		
		$_POST = json_decode(file_get_contents("php://input"),true); //JSON-Object von React muss umgewandelt werden, muss für Android noch abgefangen werden (kein json)
	}
	
	
	$user_name = $_POST["userName"]; //"userName", "neededMedicine" declaration in Recipe.js
	$users_medicine = $_POST["neededMedicine"];
	
	
	$mysql_qry = "INSERT INTO Recipes(patientName, medicine) VALUES('$user_name', '$users_medicine');"; //patientName, medicine are columnnames in database
	
	
	if($conLink->query($mysql_qry) === true){
		echo "Rezept ausgestellt an: " . $user_name;
	}else{
		echo "Error: " . $mysql_qry . "<br>" . $conLink->error ;
	}
	
		
	
	//close connection to database
	$conLink-> close();
?>