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
	$recipe_ID = $_POST["recipeID"];
	//echo "API UserID: " . $user_ID;
	
	
	if($user_ID != '' && $recipe_ID != ''){
		
		//soll nur eine Klass für App und Web geben, hier muss differenziert werden, von wo die Anfrage kommt
		if($user_role == "Patienten"){
			
			//1)
			$mysql_qry = "SELECT * FROM ((Rezepte LEFT JOIN Patienten ON versichertennummer_fk = versichertennummer) LEFT JOIN Adressen ON id_adresse_fk = id_adresse)
									WHERE id_rezept LIKE '$recipe_ID';";
									
			/*//2)
			$mysql_qry = "SELECT * FROM (((Rezepte LEFT JOIN Aerzte ON LANR_fk = LANR) LEFT JOIN Betriebsstaetten ON betriebs_nummer_fk = betriebs_nummer) LEFT JOIN Adressen ON id_adresse_fk = id_adresse)
									WHERE id_rezept LIKE '$recipe_ID';";*/
				
			//$mysql_qry = "SELECT * FROM ( Rezepte LEFT JOIN Aerzte ON LANR_fk = LANR ) WHERE versichertennummer_fk LIKE '$user_ID';";
			/*$mysql_qry = "SELECT * FROM Rezepte 
									( (LEFT JOIN Patienten ON versichertennummer_fk = versichertennummer) LEFT JOIN Adressen ON id_adresse_fk = id_adresse )
									( ( (LEFT JOIN Aerzte ON LANR_fk = LANR) LEFT JOIN Betriebsstaetten ON betriebs_nummer_fk = betriebs_nummer) LEFT JOIN Adressen ON id_adresse_fk = id_adresse) 
									WHERE versichertennummer_fk LIKE '$user_ID';";*/
									
		}else if($user_role == "Aerzte"){
			//noch nicht in Gebrauch
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
					//1)
					$data[$i] = [ 'ver_nummer' => $datarow[$i][11], 'id_adresse_fk' => $datarow[$i][16], 'id_adresse' => $datarow[$i][18] ];
					
					//2)
					//$data[$i] = [ 'LANR' => $datarow[$i][11], 'betriebs_nummer' => $datarow[$i][18], 'id_adresse' => $datarow[$i][22] ];
				}else if($userRole == "Aerzte"){
					//$data[$i] = [ 'id' => $datarow[$i][0], 'med_name' => $datarow[$i][1], 'med_form' => $datarow[$i][2], 'med_menge' => $datarow[$i][3], 'ver_nummer' => $datarow[$i][9], 'LANR_fk' => $datarow[$i][10], 'pat_lastName' => $datarow[$i][12], 'pat_firstName' => $datarow[$i][13] ];
				}
					
			}
			
			return json_encode($data);
			
		}
	
	
	//close connection to database
	$conLink-> close();
?>
