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
	$user_role = $_POST["userRole"]; //muss übergeben werden ob Anfrage vom Patient/App oder Arzt/Web kommt
	$user_name = $_POST["userName"]; //"userName" and "userPassword" declaration in Android Studios BackgroundWorker-Class
	$user_password = $_POST["userPassword"];
	  
	
	//soll nur eine Klass für das Login in App und Web geben, hier muss differenziert werden, von wo die Anfrage kommt
	if($user_role == "Patienten"){
		
		//versichertennummer, nutzername und passwort are columns in the database, Collate beachtet GroßundKleinschreibung, muss auch in Datenbank gesetzt werden
		$mysql_qry = "SELECT versichertennummer FROM $user_role WHERE nutzername COLLATE Latin1_General_CS LIKE '$user_name' AND passwort COLLATE Latin1_General_CS LIKE '$user_password';";
	}else if($user_role == "Aerzte"){
		
		//versichertennummer, nutzername und passwort are columns in the database, Collate beachtet GroßundKleinschreibung, muss auch in Datenbank gesetzt werden
		$mysql_qry = "SELECT LANR FROM $user_role WHERE nutzername COLLATE Latin1_General_CS LIKE '$user_name' AND passwort COLLATE Latin1_General_CS LIKE '$user_password';";
	}
	
	$result = mysqli_query($conLink, $mysql_qry);
	
	
	//check if User is in database
	if(mysqli_num_rows($result) > 0 ){
		//Login success
		$row = mysqli_fetch_assoc($result);
		
		if($user_role == "Patienten"){
			$user_id =  $row['versichertennummer']; //versichertennummer ist spalte in database Patienten
		}else if($user_role == "Aerzte"){
			$user_id =  $row['LANR']; 
		}
		
		$data[0] = [ 'id' => $user_id, 'isUser' => true];
		
		echo json_encode($data);
		
	}else{
		//Login Failed
		echo " Angaben bitte überprüfen";
	}
	
	
	
	//close connection to database
	$conLink-> close();
?>