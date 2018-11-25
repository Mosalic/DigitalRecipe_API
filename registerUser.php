<?php
	header("Access-Control-Allow-Origin: *"); //für get
	header('Access-Control-Allow-Headers: Content-Type'); //für axios.post Requests, schaltet CORS frei
	
	//insert file
	require "dbconnection.php";
	
	//https://stackoverflow.com/questions/41457181/axios-posting-params-not-read-by-post
	//when json is postet $_POST is null and must decoded (from web app), android app posts not in json
	if($_POST == null){
		$_POST = json_decode(file_get_contents("php://input"),true); //JSON-Object von React muss umgewandelt werden, muss für Android noch abgefangen werden (kein json)
	}
	
		
	$user_id;
	$user_role = $_POST["userRole"]; //muss übergeben werden ob Anfrage vom Patient/App oder Arzt/Web kommt
	$user_name = $_POST["userName"]; //"userName" and "userPassword" declaration in React LoginComponent from the Parameter user
	$user_password = $_POST["userPassword"];
	

	//soll nur eine Klass für die Registrierung in App und Web geben, userRole entscheidet in welcher Tabelle geprüft wird
	// nutzername is a column in the database, Collate beachtet GroßundKleinschreibung, muss auch in Datenbank gesetzt werden
	//fragt ab, ob es den username schon gibt
	$mysql_require_qry = "SELECT * FROM $user_role WHERE nutzername COLLATE Latin1_General_CS LIKE '$user_name';";
	
	$result_require = mysqli_query($conLink, $mysql_require_qry);
	
	
	//check if User is already in database
	if(mysqli_num_rows($result_require) > 0){
		echo "Username: " .$user_name ." existiert bereits mit ";
	}else{
		// set query
		if($user_name != '' && $user_password != ''){
			echo "Query setzen, ";
			
			if($user_role == "Patienten"){
				echo "Rolle: " . $user_role;
				//Datenformat für Tabelle Patienten
				$mysql_qry = "INSERT INTO $user_role VALUES('000000patient', 'patientnachname', 'patientvorname', CURDATE() ,'$user_name', '$user_password', 'patientkrankenkasse', 1);";
			}else if($user_role == "Aerzte"){
				echo "Rolle: " . $user_role;
				//Datenformat für Tabelle Aerzte
				$mysql_qry = "INSERT INTO $user_role VALUES('000000arzt', 'arztnachname', 'arztvorname', 'Dr.' , 'A.ArztSig', '$user_name', '$user_password', '00005');";
			}
			
		}
	
		//isert data
		if($conLink->query($mysql_qry) === true){
			echo " Insert success: New Insert with username:  " .$user_name . ", " . $user_password;
		}else{
			echo "Error: " . $mysql_qry . "<br>" . $conLink->error ;
		}
		
	}
	
	
	
	//close connection to database
	$conLink-> close();
?>