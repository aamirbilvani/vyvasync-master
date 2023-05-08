<?php

session_start();

require "../models/connect.php";
require "../models/users.php";
require "../models/projects.php";
require "../models/scenes.php";

$scene_id=escapeString($_POST['scene_id']);
$project_id=getSceneParent($scene_id);

$user_id=sessionActive();
$user_role=isProjectMember($project_id,$user_id);

$errors=0;

$response=array();

if($scene_id==NULL){
	$errors++;
	$response['main_message']="No scene supplied";
}

if($project_id==NULL){
	$errors++;
	$response['main_message']="No parent project found";
}

if($user_role==false){
	$errors++;
	$response['main_message']="Not a project member";
}

else if($user_role!="Director" && $user_role!="Assistant Director"){
	$errors++;
	$response['main_message']="Does not have authority to delete scene";
}

if($errors==0){
	$result=deleteScene($scene_id);

	if($result==false){
		$errors++;
		$response['main_message']="Could not delete scene";
	}
}

$response['success']=(($errors>0) ? false : true);

$jsonResponse=json_encode($response);

print_r($jsonResponse);

?>