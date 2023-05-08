<?php

require "../models/connect.php";
require "../models/users.php";

$errors=0;

$response=array();

$token=escapeString($_POST['token']);

if($token==NULL){
	$errors++;
	$response['main_message']="No token supplied for activation";
}
else {
	$result=activateAccount($token);

	if($result==false){
		$errors++;
		$response['main_message']="There seems to be a problem with that activation token.";
	}

	if($result==true){
		$response['main_message']="Your account has been activated. Please <a href='login.php'>sign in</a> to continue.";
	}
}

$response['success']=(($errors>0) ? false : true);

$jsonResponse=json_encode($response);

print_r($jsonResponse);

?>