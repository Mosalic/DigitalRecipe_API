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
	//$user_ID = $_POST["userID"];  //userID wurde von js mitgeschickt
	//echo "API UserID: " . $user_ID;
	
	
	//if($user_ID != ''){
		
		//soll nur eine Klass für App und Web geben, hier muss differenziert werden, von wo die Anfrage kommt
		if($user_role == "Patienten"){
			$mysql_qry = "SELECT * FROM ( (Aerzte LEFT JOIN Betriebsstaetten ON betriebs_nummer_fk = betriebs_nummer) LEFT JOIN Adressen ON id_adresse_fk = id_adresse );";
		}else if($user_role == "Aerzte"){
			$mysql_qry = "SELECT * FROM Aerzte;"; //noch nicht in Gebrauch, vllt später
		}
		
		$result = mysqli_query($conLink, $mysql_qry);
		//print_r(mysqli_fetch_all($result));
		
		//prüft ob es Anforderungen gibt
		if(mysqli_num_rows($result) > 0 ){
			echo mysqli_result($result);
		}else{
			echo "Keine Angaben enthalten";
		}
		
		
	//}
	
	
	function mysqli_result($res) { 
			//echo 'Test funktion ';
			//$res->data_seek($row); 
			$datarow = mysqli_fetch_all($res); //alle Daten aus der Datenbank holen
			//print_r($datarow);
			
			//Werte aus der Datenbank im Array einen Schlüssel zuweisen
			for($i=0;$i<count($datarow);$i++){
				//echo '<br/>' . "Schleife " .$i .': ' ;
				//echo $i . ": " . count($datarow[$i]) . " "; //stand 11.12. werden 18 Parameter ausgegeben
				//echo $datarow[$i][1] ." ";
				
				$data[$i] = [ 'id_LANR' => $datarow[$i][0],'doc_lastName' => "musterNachname", /*'doc_lastName' => $datarow[$i][1],*/ 'doc_firstName' => $datarow[$i][2], 'doc_title' => $datarow[$i][3], 'doc_office_nr' => $datarow[$i][8], 'doc_office_name' => "musterPraxisname",/*'doc_office_name' => $datarow[$i][9],*/ 'office_phone' => $datarow[$i][10], 'id_adress' => $datarow[$i][12], 'adress_street' => "musterStrasse",/* 'adress_street' => $datarow[$i][13],*/ 'adress_street_nr' => $datarow[$i][14], 'adress_PLZ' => $datarow[$i][16], 'adress_city' => $datarow[$i][17] ];
					
			}
			
			
			return json_encode($data);
			
		}
	
	
	//close connection to database
	$conLink-> close();
?>
