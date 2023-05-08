<?php

session_start();

require "../models/connect.php";
require "../models/users.php";
require "../models/projects.php";

$method=$_POST['request_method'];

$errors=0;

$response=array();

if($method=="sessionActive"){
	$user_id=sessionActive();

	if($user_id==false){
		$errors++;
		$response['main_message']="Please sign in to continue";
	}
	else {
		$response['user_id']=$user_id;
	}
}

if($method=="logout"){
	logout();
}

if($method=="user_role"){
	$project_id=$_POST['project_id'];

	$user_id=sessionActive();

	$user_role=isProjectMember($project_id,$user_id);

	if($user_role==false){
		$errors++;
		$response['main_message']="Not a member of this project";
	}
	else {
		$response['user_role']=$user_role;
	}
}

if($method=="notifications"){
	$user_id=sessionActive();

	$result=get_notifications($user_id);

	if($result){
		$response['notifications']=$result;
		$response['total_notifications']=$result['total'];
	}
	else {
		$response['main_message']="An error occurred";
	}
}

if($method=="total_notifications"){
	$user_id=sessionActive();

	$result=get_notifications($user_id);

	if($result){
		$response['total_notifications']=$result['total'];
	}
	else {
		$response['main_message']="An error occurred";
	}
}

if($method=="accept_notification"){
	$notification_id=escapeString($_POST['notification_id']);
	$result=accept_notification($notification_id);

	if($result==true){
		$response['main_message']="Request has been approved successfully";
	}
	else {
		$response['main_message']="Something went wrong. Please try again.";
	}
}

if($method=="reject_notification"){
	$notification_id=escapeString($_POST['notification_id']);
	$result=delete_notification($notification_id);

	if($result==true){
		$response['main_message']="Request has been rejected successfully";
	}
	else {
		$response['main_message']="Something went wrong. Please try again.";
	}
}

if($method=="change_password"){
	$user_id=sessionActive();

	$current_password=escapeString($_POST['current_password']);
	$password=escapeString($_POST['password']);
	$repeat_password=escapeString($_POST['repeat_password']);

	if($current_password==NULL || $password==NULL || $repeat_password==NULL){
		$errors++;
		$response['main_message']="Please complete all fields.";
	}
	else if($password!=$repeat_password){
		$errors++;
		$response['main_message']="Passwords do not match.";
	}
	else if(strlen($password)<6){
		$errors++;
		$response['main_message']="Password must contain at least 6 characters.";
	}
	else {
		$result=change_password($current_password,$password,$user_id);

		if($result==false){
			$errors++;
			$response['main_message']="The password you've entered is incorrect.";
		}
		else {
			$response['main_message']="Password has been changed successfully.";
		}
	}
}

if($method=="resend_activation_link"){
	$user_id=escapeString($_POST['user_id']);

	$result=resend_activation_link($user_id);

	if($result==false){
		$errors++;
		$response['main_message']="Something went wrong. The activation email could not be sent.";
	}
}

$response['success']=(($errors>0) ? false : true);

$jsonResponse=json_encode($response);

print_r($jsonResponse);

?>