<?php
	header("Access-Control-Allow-Origin: *");
	//insert file
	require "dbconnection.php";
	
	$mysql_qry = "SELECT * FROM Users;";
	$result = mysqli_query($conLink, $mysql_qry);
	
	echo mysqli_result($result, 1);
	
	function mysqli_result($res, $row, $field=1) { 
		echo 'Test funktion ';
		//$res->data_seek($row); 
		$datarow = mysqli_fetch_all($res); 
		
		for($i=0;$i<5;$i++){
			echo '<br/>' . "Schleife " .$i .': ' ;
			for($j=0;$j<3;$j++){
				
				echo $datarow[$i][$j] . ', ';
			}
		}
		echo '<br/>';
		
		/*foreach($datarow as $data) {
			echo $data;
		}*/
		
		/*for($i=0; $i<$datarow.sizeOf(); $i++){
			$res->data_seek($i);
			$datarow = $res->fetch_array();
			
			foreach($datarow as $data){
				//return $datarow[$field]; 
				echo json_encode($data);
			}
		}*/
		//echo "return JSON: " .json_encode($datarow);
		return json_encode($datarow);
	}
	
	
	
	//close connection to database
	$conLink-> close();
?>