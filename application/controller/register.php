<?php

session_start();

require "../models/connect.php";
require "../models/users.php";

$user_first_name=escapeString($_POST['user_first_name']);
$user_last_name=escapeString($_POST['user_last_name']);
$user_email=escapeString($_POST['user_email']);
$user_password_one=escapeString($_POST['user_password_one']);
$user_password_two=escapeString($_POST['user_password_two']);

$user_join_date=get_todays_date();

$errors=0;

$response=array();

if($user_first_name==NULL){
	$errors++;
	$response['user_first_name']="Please enter your first name";
}

if($user_last_name==NULL){
	$errors++;
	$response['user_last_name']="Please enter your last name";
}

if($user_email==NULL){
	$errors++;
	$response['user_email']="Please enter an email address";
}

if(userExists($user_email)){
	$errors++;
	$response['user_email']="An account already exists under that email address";
}

if($user_password_one==NULL){
	$errors++;
	$response['user_password']="Please enter a password";
}

else if($user_password_two==NULL){
	$errors++;
	$response['user_password']="Please re-type your password";
}

else if($user_password_one!=$user_password_two){
	$errors++;
	$response['user_password']="Passwords do not match";
}

else if(strlen($user_password_one)<6){
	$errors++;
	$response['user_password']="Password must contain at least 6 characters";
}

if($errors>0){
	$response['success']=false;
	$response['main_message']="There are issues with your form";
}

else {
	$user_password=encryptPassword($user_password_one);

	$result=registerUser($user_first_name,$user_last_name,$user_email,$user_password,$user_join_date);

	if($result==true){
		$response['success']=true;
	}
	else {
		$response['success']=false;
		$response['main_message']="Something went wrong";
	}
}

$jsonResponse=json_encode($response);

print_r($jsonResponse);

?>