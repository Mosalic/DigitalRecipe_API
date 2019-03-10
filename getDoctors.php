<?php
	header("Access-Control-Allow-Origin: *");  
	header('Access-Control-Allow-Headers: Content-Type'); 
	//header('Content-type: application/json');
	
	//insert file
	require "dbconnection.php";
	
	//when data is not in JSON, must decode
	if($_POST == null){
		
		$_POST = json_decode(file_get_contents("php://input"),true);
	}
	
	//get data from JSON
	$user_role = $_POST["userRole"]; 
	$user_ID = $_POST["userID"]; 
	
	if($user_ID != '' && $user_ID != "x"){
		
		if($user_role == "Patienten"){
			
			$mysql_qry = "SELECT * FROM (Pat_Besucht_Arzt LEFT JOIN Aerzte ON LANR_fk = LANR) WHERE versichertennummer_fk COLLATE Latin1_General_CS LIKE '$user_ID' ;";
			
		}else if($user_role == "Aerzte"){
			
			$mysql_qry = "SELECT * FROM ( (Aerzte LEFT JOIN Betriebsstaetten ON betriebs_nummer_fk = betriebs_nummer) LEFT JOIN Adressen ON id_adresse_fk = id_adresse ) WHERE LANR COLLATE Latin1_General_CS LIKE '$user_ID';";
		}
	
	}else if($user_ID == "x"){
		//get data without specific userID
		if($user_role == "Patienten"){
			$mysql_qry = "SELECT * FROM ( (Aerzte LEFT JOIN Betriebsstaetten ON betriebs_nummer_fk = betriebs_nummer) LEFT JOIN Adressen ON id_adresse_fk = id_adresse );";
		}else if($user_role == "Aerzte"){
			$mysql_qry = "SELECT * FROM ( Aerzte LEFT JOIN Betriebsstaetten ON betriebs_nummer_fk = betriebs_nummer);";
		}
	}else{
		//not implemented yet, get data with specific userID
	}
	
	$result = mysqli_query($conLink, $mysql_qry);
		
		//check requires
		if(mysqli_num_rows($result) > 0 ){
			echo mysqli_result($result, $user_ID, $user_role);
		}else{
			echo "Keine Angaben enthalten";
		}
	
	
	function mysqli_result($res, $userID, $userRole) { 
			 
			$datarow = mysqli_fetch_all($res); //set data in array
			
			for($i=0;$i<count($datarow);$i++){
				
				if($userID != '' && $userID != "x"){
		
					if($userRole == "Patienten"){
						$data[$i] = ['LANR_fk' => $datarow[$i][2], 'doc_lastName' => $datarow[$i][4], 'doc_firstName' => $datarow[$i][5], 'doc_title' => $datarow[$i][6]];
						$data[$i] = array_map('htmlentities', $data[$i]); //solution for problem with öäü
					}else if($userRole == "Aerzte"){
						$data[$i] = [ 'id_LANR' => $datarow[$i][0], 'doc_lastName' => $datarow[$i][1],'doc_firstName' => $datarow[$i][2], 'doc_title' => $datarow[$i][3], 'doc_office_nr' => $datarow[$i][7], 'doc_office_name' => $datarow[$i][8], 'office_phone' => $datarow[$i][9], 'id_adress' => $datarow[$i][11], 'adress_street' => $datarow[$i][12], 'adress_street_nr' => $datarow[$i][13], 'adress_PLZ' => $datarow[$i][15], 'adress_city' => $datarow[$i][16] ];
					}
	
				}else{
					//no userID, return all
					if($userRole == "Patienten"){
						$data[$i] = [ 'id_LANR' => $datarow[$i][0],'doc_lastName' => $datarow[$i][1], 'doc_firstName' => $datarow[$i][2], 'doc_title' => $datarow[$i][3], 'doc_office_nr' => $datarow[$i][7],'doc_office_name' => $datarow[$i][8], 'office_phone' => $datarow[$i][9], 'id_adress' => $datarow[$i][11], 'adress_street' => $datarow[$i][12], 'adress_street_nr' => $datarow[$i][13], 'adress_PLZ' => $datarow[$i][15], 'adress_city' => $datarow[$i][16] ];
						$data[$i] = array_map('htmlentities', $data[$i]); //solution for problem with öäü
					}else if($userRole == "Aerzte"){
						//not implemented yet
					}
				}
					
			}
			
			
			if($userRole == "Patienten"){
				//http://www.php.net/manual/en/function.json-encode.php
				$json_data = html_entity_decode(json_encode($data));
				return $json_data; //json_encode($data);
			}else if($userRole == "Aerzte"){
				return json_encode($data);
			}
			
		}
	
	
	//close connection to database
	$conLink-> close();
?>
