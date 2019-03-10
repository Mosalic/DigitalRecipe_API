<?php
	//CORS setting
	header("Access-Control-Allow-Origin: *");  
	header('Access-Control-Allow-Headers: Content-Type'); 
	//header('Content-type: application/json;charset=utf-8');
	
	//insert file
	require "dbconnection.php";
	
	
	if($_POST == null){
		
		$_POST = json_decode(file_get_contents("php://input"),true); 
	}
	

	//get data from JSON
	$user_role = $_POST["userRole"]; 
	$user_ID = $_POST["userID"];  
	$recipe_ID = $_POST["recipeID"];
	$data = "";
	
	
	if($user_ID != '' && $recipe_ID != ''){
		
		if($user_role == "Patienten"){
			
			$mysql_qry = "SELECT * FROM Rezepte 
									LEFT JOIN Patienten ON versichertennummer_fk = versichertennummer LEFT JOIN Adressen AS patAdresse ON Patienten.id_adresse_fk = patAdresse.id_adresse 
									LEFT JOIN Aerzte ON LANR_fk = LANR LEFT JOIN Betriebsstaetten ON betriebs_nummer_fk = betriebs_nummer LEFT JOIN Adressen AS docAdresse ON Betriebsstaetten.id_adresse_fk = docAdresse.id_adresse
									WHERE id_rezept LIKE '$recipe_ID';";
									
		}else if($user_role == "Aerzte"){
			//not used in the moment
		}
		
		$result = mysqli_query($conLink, $mysql_qry);
		
		//check result
		if(mysqli_num_rows($result) > 0 ){
			echo mysqli_result($result, $user_role);
		}else{
			echo "Keine Angaben enthalten";
		}
		
	}
	
	
	function mysqli_result($res, $userRole) { 
			 
			$datarow = mysqli_fetch_all($res); //set data in array
			
			for($i=0;$i<count($datarow);$i++){
				
				if($userRole == "Patienten"){
					
					$data[$i] = [ 'ver_nummer' => $datarow[$i][11], 'pat_lastName' => $datarow[$i][12], 'pat_firstName' => $datarow[$i][13], 'pat_insurance' => $datarow[$i][15], 
								'pat_adresse_street' => $datarow[$i][19], 'pat_adresse_street_nr' => $datarow[$i][20], 'pat_adresse_PLZ' => $datarow[$i][22], 'pat_adresse_city' => $datarow[$i][23],
								'LANR' => $datarow[$i][24],'doc_lastName' => $datarow[$i][25], 'doc_firstName' => $datarow[$i][26], 'doc_title' => $datarow[$i][27], 'doc_signature' => $datarow[$i][28], 
								'betriebs_nummer' => $datarow[$i][31], 'betriebs_name' => $datarow[$i][32], 'betriebs_phone' => $datarow[$i][33],
								'doc_adresse_street' => $datarow[$i][36], 'doc_adresse_street_nr' => $datarow[$i][37], 'doc_adresse_PLZ' => $datarow[$i][39], 'doc_adresse_city' => $datarow[$i][40]];
					
					$data[$i] = array_map('htmlentities', $data[$i]); //solution for problem with öäü
				}else if($userRole == "Aerzte"){
					// not implemented yet
					//$data[$i] = [ 'id' => $datarow[$i][0], 'med_name' => $datarow[$i][1], 'med_form' => $datarow[$i][2], 'med_menge' => $datarow[$i][3], 'ver_nummer' => $datarow[$i][9], 'LANR_fk' => $datarow[$i][10], 'pat_lastName' => $datarow[$i][12], 'pat_firstName' => $datarow[$i][13] ];
				}
					
			}
			
			//http://www.php.net/manual/en/function.json-encode.php
			$json_data = html_entity_decode(json_encode($data));
			
			return $json_data; //json_encode($data);
			
		}
	
	
	//close connection to database
	$conLink-> close();
?>
