<?php

session_start();

require "../models/connect.php";
require "../models/users.php";
require "../models/projects.php";
require "../models/scenes.php";

$method=$_POST['request_method'];

if($method=="toggle" || $method=="delete" || $method=="detail"){
	$scene_id=escapeString($_POST['scene_id']);
	$project_id=getSceneParent($scene_id);
}
else if($method=='project_detail'){
	$project_id=escapeString($_POST['project_id']);
}
else {
	$project_id=escapeString($_POST['project_id']);
	$date=escapeString($_POST['todays_date']);
}

$errors=0;

$response=array();

$user_id=sessionActive();

$user_role=isProjectMember($project_id,$user_id);

if($user_id==false){
	$errors++;
	$response['main_message']="User not signed in";
}

else if($user_role==false){
	$errors++;
	$response['main_message']="Not a member of this project";
}

else if($method=="toggle"){
	if($user_role!="Director"){
		$errors++;
		$response['main_message']="Does not have authority to toggle scene status";
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

else if($method=="delete"){
	if($user_role!="Director"){
		$errors++;
		$response['main_message']="Does not have authority to toggle scene status";
	}
	else {
		$result=deleteScene($scene_id);

		if($result===false){
			$errors++;
			$response['main_message']="Could not delete scene";
		}
	}
}

else if($method=="all"){
	$result=getScenes($project_id,$user_role,'all',$date);

	if($result==false){
		$errors++;
		$response['main_message']="No scenes found";
	}
	else {
		$response=$result;
	}
}

else if($method=="completed"){
	$result=getScenes($project_id,$user_role,'completed',$date);
	
	if($result==false){
		$errors++;
		$response['main_message']="There are currently no completed scenes";
	}
	else {
		$response=$result;
	}
}

else if($method=="todays"){
	$result=getScenes($project_id,$user_role,'todays',$date);
	
	if($result==false){
		$errors++;
		$response['main_message']="No scenes scheduled for today";
	}
	else {
		$response=$result;
	}
}

else if($method=="detail"){
	$result=getSceneDetail($scene_id,$user_id);

	if($result==false){
		$errors++;
		$response['main_message']="Something went wrong";
	}
	else {
		$response=$result;
	}
}

else if($method=="project_detail"){
	$result=getProjectDetails($project_id);

	if($result==false){
		$errors++;
		$response['main_message']="Something went wrong";
	}
	else {
		$response=$result;
	}
}

$response['success']=(($errors>0) ? false : true);

$jsonResponse=json_encode($response);

print_r($jsonResponse);

?>