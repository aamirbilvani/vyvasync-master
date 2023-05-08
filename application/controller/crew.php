<?php

session_start();

require "../models/connect.php";
require "../models/users.php";
require "../models/projects.php";

$user_id=sessionActive();

$project_id=escapeString($_POST['project_id']);

$user_role=isProjectMember($project_id,$user_id);

$method=$_POST['request_method'];

$errors=0;

$response=array();

if($user_role==false){
	$errors++;
	$response['main_message']="You are not a member of this project.";
}

else if($method=="get"){
	$response['users']=getCrew($project_id);
}

else if($user_role!="Producer" && $user_role!="Director"){
	$errors++;
	$response['main_message']="You do not have authority to add members to this project.";
}

if($errors==0 && $method=="remove"){
	$user_email=escapeString($_POST['user_email']);

	$result=removeMember($user_email,$project_id);

	if($result==false){
		$errors++;
		$response['main_message']="Something went wrong. Project member could not be removed.";
	}
}

if($errors==0 && $method=="post"){
	$target_role=escapeString($_POST['user_role']);
	$target_email=escapeString($_POST['user_email']);

	if($target_email==NULL){
		$errors++;
		$response['main_message']="Please enter an email address.";
	}

	else if($target_role==NULL){
		$errors++;
		$response['main_message']="Please select a role.";
	}

	if($errors==0){
		$result=addMember($target_role,$target_email,$project_id,$user_id);

		if($result['success']==false){
			$errors++;
			$response['main_message']=$result['main_message'];
		}
	}
}

$response['success']=(($errors>0) ? false : true);

$jsonResponse=json_encode($response);

print_r($jsonResponse);

?>