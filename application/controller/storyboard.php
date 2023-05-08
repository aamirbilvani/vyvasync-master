<?php

session_start();

require "../models/connect.php";
require "../models/users.php";
require "../models/projects.php";
require "../models/scenes.php";

$errors=0;

$response=array();

$user_id=sessionActive();

$scene_id=escapeString($_POST['scene_id']);
$project_id=getSceneParent($scene_id);

$user_role=isProjectMember($project_id,$user_id);

if($user_id==false){
	$errors++;
	$response['main_message']="Please sign in";
}

if($user_role==false){
	$errors++;
	$response['main_message']="Not a member of this project";
}

else if($user_role=="Director" || $user_role=="Assistant Director"){

	$scene_name=escapeString($_POST['scene_name']);
	$scene_date=$_POST['scene_date'];
	$meta_value=escapeString($_POST['scene_note']);

	$result=addSceneMeta($scene_name,$scene_date,$meta_value,$scene_id,$user_id);

	if($result=='1') {
		$response['main_message']="Changes saved successfully.";
	}

	else if($result=='2'){
		$errors++;
		$response['main_message']="Something went wrong. Your changes could not be saved.";
	}
	
	else if($result=='3'){
		$errors++;
		$response['main_message']="A request to change shoot date already exists.";
	}

	else if($result=='4'){
		$response['main_message']="Shoot date has been forwarded to Producer for approval.";
	}
}

else {
	$meta_value=escapeString($_POST['scene_note']);

	$result=addNote($meta_value,$scene_id,$user_id);

	if($result==true){
		$response['main_message']="Changes saved successfully.";
	}

	else if($result==false){
		$errors++;
		$response['main_message']="Something went wrong. Your changes could not be saved.";
	}
}

$response['success']=(($errors>0) ? false : true);

$jsonResponse=json_encode($response);

print_r($jsonResponse);

?>