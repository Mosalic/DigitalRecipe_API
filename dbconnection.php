<?php
	
	//https://draeger-it.blog/android-app-mit-einer-mysql-datenbank-verbinden-16-01-2016/
	//https://www.youtube.com/watch?v=HK515-8-Q_w
	
	//setting up database connection
	
		//Database Settings
		$dbServer = "localhost";
		$dbUsername = "root";	//create account for other permissions and setting, root is default
		$dbPassword = "";
		$dbName = "digitalrecipe";
		
		//Create connection
		$conLink = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbName); 
		
		/* //Status Meldung zum test über localhost/android_connect/login.php
		if(!$conLink){
			//die("Connection failed: " . mysqli_error());
			echo "Connections failed";
		}else{
			echo "Connection success ";
		}*/
		
		//Check connection
		if(mysqli_connect_errno()){
			//die("Error while setting up the database connection");
			echo "Error while setting up the database connection: " . mysqli_connect_errno();
		}
		
		return $conLink;  //connected to database
	
	
?>