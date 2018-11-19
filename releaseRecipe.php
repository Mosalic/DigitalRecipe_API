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
	
	if($ver_number != '' && $users_medicine != ''){
		$mysql_qry = "INSERT INTO Rezepte(medikament_name, medikament_form, medikament_menge, versichertennummer_fk, LANR_fk) 
							VALUES('$users_medicine', 'Tabletten', '30 Stück', '$ver_number', '0000000001');"; //patientName, medicine are columnnames in database
	}else{
		echo "Angaben: " . $ver_number . ", " . $users_medicine;
	}
	
	
	if($conLink->query($mysql_qry) === true){
		echo "Rezept ausgestellt an Patient mit Vers.Nummer: " . $ver_number;
	}else{
		echo "Error: " . $mysql_qry . "<br>" . $conLink->error ;
	}
	
		
	
	//close connection to database
	$conLink-> close();
?>