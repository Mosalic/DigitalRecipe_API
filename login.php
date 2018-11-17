<?php
	header("Access-Control-Allow-Origin: *"); //für get
	header('Access-Control-Allow-Headers: Content-Type'); //für axios.post Requests, schaltet CORS frei
	//insert file
	require "dbconnection.php";
	
	//when json is postet $_POST is null and must decoded (from web app), android app posts not in json
	if($_POST == null){
		$_POST = json_decode(file_get_contents("php://input"),true); //JSON-Object von React muss umgewandelt werden, muss für Android noch abgefangen werden (kein json)
	}
	
	
	$user_id;
	$user_name = $_POST["userName"]; //"userName" and "userPassword" declaration in Android Studios BackgroundWorker-Class
	$user_password = $_POST["userPassword"];
	$mysql_qry = "SELECT id FROM Users WHERE username COLLATE Latin1_General_CS LIKE '$user_name' AND password COLLATE Latin1_General_CS LIKE '$user_password';";  //id is a column in the database, Collate beachtet GroßundKleinschreibung, muss auch in Datenbank gesetzt werden
	$result = mysqli_query($conLink, $mysql_qry);
	
	
	//check if User is in database
	if(mysqli_num_rows($result) > 0 ){
		//Login success
		$row = mysqli_fetch_assoc($result);
		$user_id =  $row['id'];
		echo "ID: " . $user_id;
	}else{
		//Login Failed
		echo "Angaben bitte überprüfen";
	}
	
	
	
	//close connection to database
	$conLink-> close();
?>