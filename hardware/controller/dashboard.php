<?php

session_start();

require "../../application/models/connect.php";
require "../../application/models/projects.php";

$errors=0;

$response=array();

$user_id=4;

$date=escapeString($_POST['todays_date']);

$result=getTodaysProjects($user_id,$date);
	
if($result==false){
	$errors++;
	$response['main_message']="No projects scheduled for today";
}
else {
	$response=$result;
}

$response['success']=(($errors>0) ? false : true);

$jsonResponse=json_encode($response);

print_r($jsonResponse);

?>