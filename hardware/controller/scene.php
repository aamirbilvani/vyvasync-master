<?php

session_start();

require "../../application/models/connect.php";
require "../../application/models/users.php";
require "../../application/models/projects.php";
require "../../application/models/scenes.php";

$method=$_POST['request_method'];
$project_id=escapeString($_POST['project_id']);

$errors=0;

$response=array();

$user_id=4;

$user_role=isProjectMember($project_id,$user_id);

if($user_id==false){
	$errors++;
	$response['main_message']="User not signed in";
}

else if($user_role==false){
	$errors++;
	$response['main_message']="Not a member of this project";
}

else if($method=="get"){
	$date=escapeString($_POST['todays_date']);

	$result=getSceneForPi($project_id,$date);

	if($result==false){
		$errors++;
		$response['main_message']="No scene found for this project";
	}
	else {
		$response=$result;
	}
}

else if($method=="toggle"){
	$scene_id=escapeString($_POST['scene_id']);

	$temporary_project_id=getSceneParent($scene_id);

	if($user_role!="Director"){
		$errors++;
		$response['main_message']="Does not have authority to toggle scene status";
	}

	else if($temporary_project_id!=$project_id){
		$errors++;
		$response['main_message']="Invalid Scene ID supplied";
	}
	else {
		$result=toggleSceneStatus($scene_id);

		if($result===false){
			$errors++;
			$response['main_message']="Could not toggle scene status";
		}
		else {
			$response['scene_status']=$result;
		}
	}
}

$response['success']=(($errors>0) ? false : true);

$jsonResponse=json_encode($response);

print_r($jsonResponse);

?>