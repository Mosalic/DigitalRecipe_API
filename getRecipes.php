<?php
	header("Access-Control-Allow-Origin: *");  //für axios.get Requests, schaltet CORS frei
	header('Access-Control-Allow-Headers: Content-Type'); //für axios.post Requests, schaltet CORS frei
	//header('Content-type: application/json');
	//insert file
	require "dbconnection.php";
	
	if($_POST == null){
		
		$_POST = json_decode(file_get_contents("php://input"),true); //JSON-Object von React muss umgewandelt werden, muss für Android noch abgefangen werden (kein json)
	}
	
	$user_ID = $_POST["userID"];  //userID wurde von js mitgeschickt
	//echo "API UserID: " . $user_ID;
	
	if($user_ID != ''){
		$mysql_qry = "SELECT * FROM Rezepte WHERE LANR_fk LIKE '$user_ID';";
		$result = mysqli_query($conLink, $mysql_qry);
		
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
				
				$data[$i] = [ 'id' => $datarow[$i][0], 'med_name' => $datarow[$i][1], 'med_form' => $datarow[$i][2], 'med_menge' => $datarow[$i][3], 'ver_nummer' => $datarow[$i][9], 'LANR_fk' => $datarow[$i][10] ];
					
			}
			
			
			return json_encode($data);
			
		}
	
	
	
	
	
	//close connection to database
	$conLink-> close();
?>