<?php

define("DB_NAME","vyvasy5_beta"); // Database Name
define("DB_HOST","localhost"); // Database Host
define("DB_USER","vyvasy5_nabeel"); // Database Username
define("DB_PASSWORD","vyvaS!_91nabeel"); // Database Password

define("ENC_KEY","vs!@*2015"); // Password Encryption Key

define("IMG_SIZE","5000000"); // Storyboard Image Size Limit

function connectdb(){

	// This function connects to the database and returns the
	// connection variable.

	$con=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);

	if($con==true){
		if(mysqli_select_db($con,DB_NAME)){
			return $con;
		}
		else {
			die("Database connection failed");
		}
	}
	else {
		die("Database connection failed");	
	}
}

function execQuery($query){

	// Execute query and return result

	$con=connectdb();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	return $result;
}

function insertQuery($query){

	// Execute "INSERT" query and return insert ID

	$con=connectdb();
	$result=mysqli_query($con,$query);

	if($result==true){
		$result=mysqli_insert_id($con);
	}

	mysqli_close($con);
	return $result;
}

function escapeString($string){

	// Escape String to avoid SQL injection

	$con=connectdb();
	$result=mysqli_real_escape_string($con,$string);
	return $result;
}

function get_todays_date(){
	$todays_date=gmdate("Y-m-d");

	return $todays_date;
}

?>