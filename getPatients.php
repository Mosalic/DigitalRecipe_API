<?php
	//CORS settings
	header("Access-Control-Allow-Origin: *");  
	header('Access-Control-Allow-Headers: Content-Type'); 
	//header('Content-type: application/json');
	
	//insert file
	require "dbconnection.php";
	
	
	if($_POST == null){
		
		$_POST = json_decode(file_get_contents("php://input"),true);
	}
	

	//get data from JSON
	$user_role = $_POST["userRole"]; 
	$user_ID = $_POST["userID"]; 
	

	if($user_ID != ''){
		
		if($user_role == "Patienten"){
			$mysql_qry = "SELECT * FROM Patienten LEFT JOIN Adressen ON id_adresse_fk = id_adresse LEFT JOIN Login ON id_login_fk = id_login WHERE versichertennummer COLLATE Latin1_General_CS LIKE '$user_ID';"; 
		}else if($user_role == "Aerzte"){
			//not used in the moment
			$mysql_qry = "SELECT * FROM ( Patienten LEFT JOIN Adressen ON id_adresse_fk = id_adresse ) WHERE versichertennummer COLLATE Latin1_General_CS LIKE '$user_ID';";
		}
		
		
	}else{
		
		if($user_role == "Patienten"){
			//not used in the moment
			$mysql_qry = "SELECT * FROM (Patienten LEFT JOIN Adressen ON id_adresse_fk = id_adresse);"; 
		}else if($user_role == "Aerzte"){
			//not used in the moment
			$mysql_qry = "SELECT * FROM ( Patienten LEFT JOIN Adressen ON id_adresse_fk = id_adresse );";
		}
	}
	
	$result = mysqli_query($conLink, $mysql_qry);
		//check result
		if(mysqli_num_rows($result) > 0 ){
			echo mysqli_result($result);
		}else{
			echo "Keine Angaben enthalten";
		}
	
	
	function mysqli_result($res) { 
			 
			$datarow = mysqli_fetch_all($res); //set data in array
			
			for($i=0;$i<count($datarow);$i++){
				
				$data[$i] = [ 'id_verNr' => $datarow[$i][0], 'user_lastName' => $datarow[$i][1], 'user_firstName' => $datarow[$i][2], 'user_geb' => $datarow[$i][3], 'user_ver' => $datarow[$i][4],'id_adress' => $datarow[$i][7], 'adress_street' => $datarow[$i][8], 'adress_street_nr' => $datarow[$i][9], 'adress_PLZ' => $datarow[$i][11], 'adress_city' => $datarow[$i][12], 'user_username' => $datarow[$i][14] ];
					
			}
			
			return json_encode($data);
		}
	
	
	//close connection to database
	$conLink-> close();
?>
