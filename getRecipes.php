<?php
	header("Access-Control-Allow-Origin: *");  //für axios.get Requests, schaltet CORS frei
	header('Access-Control-Allow-Headers: Content-Type'); //für axios.post Requests, schaltet CORS frei
	//header('Content-type: application/json');
	//insert file
	require "dbconnection.php";
	
	
	if($_POST == null){
		
		$_POST = json_decode(file_get_contents("php://input"),true); //JSON-Object von React muss umgewandelt werden, muss für Android noch abgefangen werden (kein json)
	}
	

	$user_role = $_POST["userRole"]; //muss übergeben werden ob Anfrage vom Patient/App oder Arzt/Web kommt
	$user_ID = $_POST["userID"];  //userID wurde von js mitgeschickt
	//echo "API UserID: " . $user_ID;
	
	
	if($user_ID != ''){
		
		//soll nur eine Klass für App und Web geben, hier muss differenziert werden, von wo die Anfrage kommt
		if($user_role == "Patienten"){
			$mysql_qry = "SELECT * FROM ( Rezepte LEFT JOIN Aerzte ON LANR_fk = LANR ) WHERE versichertennummer_fk LIKE '$user_ID';";
			/*$mysql_qry = "SELECT * FROM ( (Rezepte LEFT JOIN Patienten ON versichertennummer_fk = versichertennummer) LEFT JOIN Adressen ON id_adresse_fk = id_adresse )
									WHERE versichertennummer_fk LIKE '$user_ID';";*/
			/*$mysql_qry = "SELECT * FROM Rezepte 
									( (LEFT JOIN Patienten ON versichertennummer_fk = versichertennummer) LEFT JOIN Adressen ON id_adresse_fk = id_adresse )
									( ( (LEFT JOIN Aerzte ON LANR_fk = LANR) LEFT JOIN Betriebsstaetten ON betriebs_nummer_fk = betriebs_nummer) LEFT JOIN Adressen ON id_adresse_fk = id_adresse) 
									WHERE versichertennummer_fk LIKE '$user_ID';";*/
		}else if($user_role == "Aerzte"){
			//$mysql_qry = "SELECT * FROM Rezepte WHERE LANR_fk LIKE '$user_ID';";
			$mysql_qry = "SELECT * FROM ( Rezepte LEFT JOIN Patienten ON versichertennummer_fk = versichertennummer ) WHERE LANR_fk LIKE '$user_ID';";
		}
		
		$result = mysqli_query($conLink, $mysql_qry);
		
		//prüft ob es Anforderungen gibt
		if(mysqli_num_rows($result) > 0 ){
			echo mysqli_result($result, $user_role);
		}else{
			echo "Keine Angaben enthalten";
		}
		
		
	}
	
	
	function mysqli_result($res, $userRole) { 
			//echo 'Test funktion ';
			//$res->data_seek($row); 
			$datarow = mysqli_fetch_all($res); //alle Daten aus der Datenbank holen
			
			
			//Werte aus der Datenbank im Array einen Schlüssel zuweisen
			for($i=0;$i<count($datarow);$i++){
				//echo '<br/>' . "Schleife " .$i .': ' ;
				if($userRole == "Patienten"){
					$data[$i] = [ 'id' => $datarow[$i][0], 'med_name' => $datarow[$i][1], 'med_form' => $datarow[$i][2], 'med_menge' => $datarow[$i][3], 'med_datum' => $datarow[$i][5], 'noctu' => $datarow[$i][6], 'aut_idem' => $datarow[$i][8], 'ver_nummer' => $datarow[$i][9], 'LANR_fk' => $datarow[$i][10], 'doc_lastName' => $datarow[$i][12], 'doc_firstName' => $datarow[$i][13], 'doc_title' => $datarow[$i][14] ];
					/*$data[$i] = [ 'id_recipe' => $datarow[$i][0], 'med_name' => $datarow[$i][1], 'med_form' => $datarow[$i][2], 'med_menge' => $datarow[$i][3], 'med_datum' => $datarow[$i][5], 'noctu' => $datarow[$i][6],
								'ver_nummer' => $datarow[$i][9], 'LANR_fk' => $datarow[$i][10], 'pat_lastName' => $datarow[$i][12], 'pat_firstName' => $datarow[$i][13], 'pat_geb' => $datarow[$i][14],
								'pat_insurance' => $datarow[$i][15], 'adress_id' => $datarow[$i][18], 'adress_street' => $datarow[$i][19], 'adress_street_nr' => $datarow[$i][20], 'adress_PLZ' => $datarow[$i][22], 'adress_city' => $datarow[$i][23] ];*/
					$data[$i] = array_map('htmlentities', $data[$i]); //solution for problem with öäü
				}else if($userRole == "Aerzte"){
					$data[$i] = [ 'id' => $datarow[$i][0], 'med_name' => $datarow[$i][1], 'med_form' => $datarow[$i][2], 'med_menge' => $datarow[$i][3], 'ver_nummer' => $datarow[$i][9], 'LANR_fk' => $datarow[$i][10], 'pat_lastName' => $datarow[$i][12], 'pat_firstName' => $datarow[$i][13] ];
				}
				
					
			}
			
			
			if($userRole == "Patienten"){
				//test UTF8, wegen Porbleme mit öäü http://www.php.net/manual/en/function.json-encode.php
				$json_data = html_entity_decode(json_encode($data));
				return $json_data; //json_encode($data);
			}else if($userRole == "Aerzte"){
				return json_encode($data);
			}
			
		}
	
	
	//close connection to database
	$conLink-> close();
?>
