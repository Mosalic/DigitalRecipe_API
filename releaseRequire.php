<?php
	//CORS settings
	header("Access-Control-Allow-Origin: *"); 
	header('Access-Control-Allow-Headers: Content-Type'); 
	
	//insert file
	require "dbconnection.php";
	
	//when json is postet $_POST is null and must decoded (from web app), android app posts not in json
	if($_POST == null){
		
		$_POST = json_decode(file_get_contents("php://input"),true); 
	}
	
	//get data from JSON, "userRole" etc. define in App or Web
	$user_role = $_POST["userRole"];
	$ver_number = $_POST["userID"]; 
	$user_complaint = $_POST["usersComplaint"];
	$user_Medicine = $_POST["usersMedicine"];
	$doctor_id = $_POST["usersDoctor"];
	
	
	$mysql_qry = 	"INSERT INTO Anforderungen(beschwerden, medikament, versichertennummer_fk, LANR_fk) 
					VALUES('$user_complaint', '$user_Medicine', '$ver_number', '$doctor_id');"; 
	
	
	
	if($conLink->query($mysql_qry) === true){
		$data[0] = ['info' => "createNewRequire",'doc_lastName' => "musterNachname"];
		
		return json_encode($data);
		
	}else{
		echo "Error: " . $mysql_qry . "<br>" . $conLink->error ;
	}
	
	
		
	//close connection to database
	$conLink-> close();
?>