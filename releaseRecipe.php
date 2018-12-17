<?php
	header("Access-Control-Allow-Origin: *"); //für get
	header('Access-Control-Allow-Headers: Content-Type'); //für axios.post Requests, schaltet CORS frei
	//insert file
	require "dbconnection.php";
	
	//when json is postet $_POST is null and must decoded (from web app), android app posts not in json
	if($_POST == null){
		
		$_POST = json_decode(file_get_contents("php://input"),true); //JSON-Object von React muss umgewandelt werden, muss für Android noch abgefangen werden (kein json)
	}
	
	
	$ver_number = $_POST["verNumber"]; //"verNumber", "neededMedicine" declaration in Recipe.js
	$users_medicine = $_POST["neededMedicine"];
	$require_ID = $_POST["requireID"];
	$user_ID = $_POST["userID"];  //userID wurde von js mitgeschickt
	
	if($ver_number != '' && $users_medicine != ''){
		$mysql_qry = "INSERT INTO Rezepte(id_rezept, medikament_name, medikament_form, medikament_menge, versichertennummer_fk, LANR_fk) 
							VALUES('$require_ID', '$users_medicine', 'Tabletten', '30 Stück', '$ver_number', '$user_ID');"; //patientName, medicine are columnnames in database
	}else{
		echo "Angaben: " . $ver_number . ", " . $users_medicine;
	}
	
	//prüfen ob Eintrag in Tabelle Rezept ausgeführt wurde
	if($conLink->query($mysql_qry) === true){
		echo "Rezept ausgestellt an Patient mit Vers.Nummer: " . $ver_number;
		$mysql_qry = "UPDATE Anforderungen SET id_rezept_fk = '$require_ID', zugelassen = 'true' WHERE id_anforderung = '$require_ID' ";
		
		//prüfen ob Tabelle Anforderungen anschließend geupdated wurde
		if($conLink->query($mysql_qry) === true){
			echo "Anforderung update mit id: " . $require_ID ;
			//return true;
		
		}else{
			echo "Error: " . $mysql_qry . "<br>" . $conLink->error ;
		}
		
	}else{
		echo "Error: " . $mysql_qry . "<br>" . $conLink->error ;
	}
	
		
	
	//close connection to database
	$conLink-> close();
?>