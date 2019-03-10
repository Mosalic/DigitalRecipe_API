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
	
	
	$user_id;
	//get data from JSON
	$user_role = $_POST["userRole"]; 
	$user_name = $_POST["userName"]; 
	$user_password = $_POST["userPassword"];
	  
	
	
	if($user_role == "Patienten"){
		
		$mysql_qry = "SELECT versichertennummer FROM ($user_role LEFT JOIN Login ON id_login_fk = id_login) WHERE username COLLATE Latin1_General_CS LIKE '$user_name' AND password COLLATE Latin1_General_CS LIKE '$user_password'  AND userrole COLLATE Latin1_General_CS LIKE '$user_role';";
			
	}else if($user_role == "Aerzte"){
		
		$mysql_qry = "SELECT LANR FROM ($user_role LEFT JOIN Login ON id_login_fk = id_login) WHERE username COLLATE Latin1_General_CS LIKE '$user_name' AND password COLLATE Latin1_General_CS LIKE '$user_password'  AND userrole COLLATE Latin1_General_CS LIKE '$user_role';";
	}
	
	$result = mysqli_query($conLink, $mysql_qry);
	
	
	//check if User is in database
	if(mysqli_num_rows($result) > 0 ){
		//Login success
		$row = mysqli_fetch_assoc($result);
		
		if($user_role == "Patienten"){
			$user_id =  $row['versichertennummer']; 
		}else if($user_role == "Aerzte"){
			$user_id =  $row['LANR']; 
		}
		
		$data[0] = [ 'id' => $user_id, 'isUser' => true];
		
		
		
	}else{
		//Login Failed, wrong inputs
		$data[0] = ['id' => null, 'isUser' => false];
		
	}
	
	echo json_encode($data); //return data to the app or webapp
	
	//close connection to database
	$conLink-> close();
?>