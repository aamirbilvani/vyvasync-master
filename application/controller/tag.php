<?php

session_start();

require "../models/connect.php";
require "../models/users.php";
require "../models/projects.php";

$user_id=sessionActive();

$project_id=escapeString($_POST['project_id']);
$scene_id=escapeString($_POST['scene_id']);

$user_role=isProjectMember($project_id,$user_id);

$method=$_POST['request_method'];

$errors=0;

$response=array();

if($project_id==NULL || $scene_id==NULL){
	$errors++;
	$response['main_message']="Insufficient data supplied.";
}

else if($user_role==false){
	$errors++;
	$response['main_message']="You are not a member of this project.";
}

else if($user_role!="Producer" && $user_role!="Director"){
	$errors++;
	$response['main_message']="You do not have authority to tag members to this scene.";
}

if($errors==0 && $method=="get_suggestions"){
	$user_input=escapeString($_POST['user_input']);

	$result=get_crew_suggestions($user_input,$user_id,$scene_id,$project_id);

	if($result){
		$response['members']=$result;
	}
	else {
		$response['members']=NULL;
	}
}

if($errors==0 && $method=="tag_member"){
	$tag_user_id=escapeString($_POST['user_id']);

	$result=tag_member($tag_user_id,$user_id,$scene_id,$project_id);
	
	if($result){
		$response['main_message']="Crew member has been tagged successfully.";
		$response['member']=$result;
	}
	else if($result==false){
		$errors++;
		$response['main_message']="Something went wrong. Crew member could not be tagged.";
	}
}

if($errors==0 && $method=="delete_tag"){
	$meta_id=escapeString($_POST['meta_id']);

	$result=delete_tag($meta_id);
	
	if($result){
		$response['main_message']="Tag removed successfully.";
	}
	else if($result==false){
		$errors++;
		$response['main_message']="Something went wrong. Tag could not be removed.";
	}
}

$response['success']=(($errors>0) ? false : true);

$jsonResponse=json_encode($response);

print_r($jsonResponse);

?>