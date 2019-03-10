<?php
	//CORS settings
	header("Access-Control-Allow-Origin: *"); 
	header('Access-Control-Allow-Headers: Content-Type');
	
	//insert file
	require "dbconnection.php";
	
	//when json is postet $_POST is null and must decoded
	if($_POST == null){
		
		$_POST = json_decode(file_get_contents("php://input"),true); 
	}
	
	//get data from JSON
	$ver_number = $_POST["verNumber"];
	$users_medicine = $_POST["neededMedicine"];
	$require_ID = $_POST["requireID"];
	$user_ID = $_POST["userID"];  
	
	if($ver_number != '' && $users_medicine != ''){
		
		//insert data
		$mysql_qry = 	"INSERT INTO Rezepte(id_rezept, medikament_name, medikament_form, medikament_menge, versichertennummer_fk, LANR_fk) 
						VALUES('$require_ID', '$users_medicine', 'Tabletten', '30 Stk.', '$ver_number', '$user_ID');"; 
							
	}else{
		echo "Angaben: " . $ver_number . ", " . $users_medicine;
	}
	
	//insert worked
	if($conLink->query($mysql_qry) === true){
		echo "Rezept ausgestellt an Patient mit Vers.Nummer: " . $ver_number;
		
		$mysql_qry = "UPDATE Anforderungen SET id_rezept_fk = '$require_ID', zugelassen = 'true' WHERE id_anforderung = '$require_ID' ";
		
		//update worked
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