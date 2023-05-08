<?php

session_start();

require "../models/connect.php";
require "../models/users.php";

$user_email=escapeString($_POST['user_email']);

$user_password=escapeString($_POST['user_password']);
$user_password=encryptPassword($user_password);

$errors=0;

$response=array();
$response['redirect_activate']=false;

if($user_email==NULL || $user_password==NULL){
	$errors++;
	$response['main_message']="Please complete all fields";
}

if($errors==0){
	$result=login($user_email,$user_password);

	if($result==='invalid'){
		$errors++;
		$response['main_message']="Invalid email or password";
	}

	if($result==='inactive'){
		$errors++;
		$response['main_message']="This account has not yet been activated";
		$response['user_id']=getUserIDbyEmail($user_email);
		$response['redirect_activate']=true;
	}
}

$response['success']=($errors>0 ? false : true);

$jsonResponse=json_encode($response);

print_r($jsonResponse);

?>