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
			$mysql_qry = "SELECT * FROM ( Rezepte LEFT JOIN Aerzte ON LANR_fk = LANR ) WHERE versichertennummer_fk LIKE '$user_ID';";
			
		}else if($user_role == "Aerzte"){
			
			$mysql_qry = "SELECT * FROM ( Rezepte LEFT JOIN Patienten ON versichertennummer_fk = versichertennummer ) WHERE LANR_fk LIKE '$user_ID';";
		}
		
		$result = mysqli_query($conLink, $mysql_qry);
		
		//check requires
		if(mysqli_num_rows($result) > 0 ){
			echo mysqli_result($result, $user_role);
		}else{
			echo "Keine Angaben enthalten";
		}
		
	}
	
	
	function mysqli_result($res, $userRole) { 
			
			$datarow = mysqli_fetch_all($res); //database response into array
			
			for($i=0;$i<count($datarow);$i++){
				
				if($userRole == "Patienten"){
					$data[$i] = [ 'id' => $datarow[$i][0], 'med_name' => $datarow[$i][1], 'med_form' => $datarow[$i][2], 'med_menge' => $datarow[$i][3], 'med_datum' => $datarow[$i][5], 'noctu' => $datarow[$i][6], 'aut_idem' => $datarow[$i][8], 'ver_nummer' => $datarow[$i][9], 'LANR_fk' => $datarow[$i][10], 'doc_lastName' => $datarow[$i][12], 'doc_firstName' => $datarow[$i][13], 'doc_title' => $datarow[$i][14] ];
					$data[$i] = array_map('htmlentities', $data[$i]); //solution for problem with öäü
				}else if($userRole == "Aerzte"){
					$data[$i] = [ 'id' => $datarow[$i][0], 'med_name' => $datarow[$i][1], 'med_form' => $datarow[$i][2], 'med_menge' => $datarow[$i][3], 'ver_nummer' => $datarow[$i][9], 'LANR_fk' => $datarow[$i][10], 'pat_lastName' => $datarow[$i][12], 'pat_firstName' => $datarow[$i][13] ];
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
