<?php
	header("Access-Control-Allow-Origin: *"); //für get
	header('Access-Control-Allow-Headers: Content-Type'); //für axios.post Requests, schaltet CORS frei
	//insert file
	require "dbconnection.php";
	
	//when json is postet $_POST is null and must decoded (from web app), android app posts not in json
	if($_POST == null){
		
		$_POST = json_decode(file_get_contents("php://input"),true); //JSON-Object von React muss umgewandelt werden, muss für Android noch abgefangen werden (kein json)
	}
	
	$user_role = $_POST["userRole"];
	$ver_number = $_POST["userID"]; //"userID" declaration in App-Class
	$user_complaint = $_POST["usersComplaint"];
	$user_Medicine = $_POST["usersMedicine"];
	$doctor_id = $_POST["usersDoctor"];
	
	$mysql_qry = "INSERT INTO Anforderungen(beschwerden, medikament, versichertennummer_fk, LANR_fk) 
					VALUES('$user_complaint', '$user_Medicine', '$ver_number', '$doctor_id');"; //patientName, medicine are columnnames in database
	
	
	
	if($conLink->query($mysql_qry) === true){
		//echo "Anforderung erstellt von Patient: " . $ver_number;
		$data[0] = ['info' => "createNewRequire",'doc_lastName' => "musterNachname"];
		//echo $data[0]['info'];
		return json_encode($data);
		
	}else{
		echo "Error: " . $mysql_qry . "<br>" . $conLink->error ;
	}
	
	
		
	//close connection to database
	$conLink-> close();
?>