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
			//versichertennummer, nutzername und passwort are columns in the database, Collate beachtet GroßundKleinschreibung, muss auch in Datenbank gesetzt werden
			$mysql_qry = "SELECT * FROM Anforderungen WHERE versichertennummer_fk LIKE COLLATE Latin1_General_CS '$user_ID';";
		}else if($user_role == "Aerzte"){
			//versichertennummer, nutzername und passwort are columns in the database, Collate beachtet GroßundKleinschreibung, muss auch in Datenbank gesetzt werden
			$mysql_qry = "SELECT * FROM Anforderungen WHERE LANR_fk LIKE '$user_ID';";
		}
		
		$result = mysqli_query($conLink, $mysql_qry);
		
		//prüft ob es Anforderungen gibt
		if(mysqli_num_rows($result) > 0 ){
			echo mysqli_result($result, 1);
		}else{
			echo "Keine Angaben enthalten";
		}
		
		
	}
	
	function mysqli_result($res, $row, $field=1) { 
			//echo 'Test funktion ';
			//$res->data_seek($row); 
			$datarow = mysqli_fetch_all($res); //alle Daten aus der Datenbank holen
			
			
			//Werte aus der Datenbank im Array einen Schlüssel zuweisen
			for($i=0;$i<count($datarow);$i++){
				//echo '<br/>' . "Schleife " .$i .': ' ;
				
				$data[$i] = [ 'id' => $datarow[$i][0], 'beschwerden' => $datarow[$i][1], 'med_name' => $datarow[$i][2], 'ver_nummer' => $datarow[$i][3], 'LANR_fk' => $datarow[$i][4] ];
					// will encode to JSON object: {"name":"God","age":-1}  
					// accessed as example in JavaScript like: result.name or result['name'] (returns "God")
					
				/*for($j=0;$j<3;$j++){
					//echo $datarow[$i][$j] . ', ';
				}*/
			}
			
			/*echo "Alle id's: ";
			foreach($data as $da){
				echo $da['id'];
			}*/
			
			return json_encode($data);
			
		}
	
	
	
	
	
	//close connection to database
	$conLink-> close();
?>