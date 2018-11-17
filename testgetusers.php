<?php
	header("Access-Control-Allow-Origin: *");  //für axios.get Requests, schaltet CORS frei
	//header('Content-type: application/json');
	//insert file
	require "dbconnection.php";
	
	$mysql_qry = "SELECT * FROM Users;";
	$result = mysqli_query($conLink, $mysql_qry);
	
	echo mysqli_result($result, 1);
	
	function mysqli_result($res, $row, $field=1) { 
		//echo 'Test funktion ';
		//$res->data_seek($row); 
		$datarow = mysqli_fetch_all($res); //alle Daten aus der Datenbank holen
		
		
		//Werte aus der Datenbank im Array einen Schlüssel zuweisen
		for($i=0;$i<count($datarow);$i++){
			//echo '<br/>' . "Schleife " .$i .': ' ;
			
			$data[$i] = [ 'id' => $datarow[$i][0], 'name' => $datarow[$i][1], 'password' => $datarow[$i][2] ];
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