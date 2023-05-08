<?php

session_start();

require "../models/connect.php";
require "../models/users.php";
require "../models/projects.php";

$method=$_POST['request_method'];

$errors=0;

$response=array();

$user_id=sessionActive();

if($user_id==false){
	$errors++;
	$response['main_message']="Please <a href='login.php'>sign in</a> to continue";
}

else if($method=="all"){
	$result=getAllProjects($user_id);

	if($result==false){
		$errors++;
		$response['main_message']="No projects available, would you like to <a href='project.php'>add one</a>?";
	}
	else {
		$response=$result;
	}
}

else if($method=="todays"){
	$date=escapeString($_POST['todays_date']);

	$result=getTodaysProjects($user_id,$date);
	
	if($result==false){
		$errors++;
		$response['main_message']="No projects scheduled for today, would you like to <a href='project.php'>add one</a>?";
	}
	else {
		$response=$result;
	}
}

else if($method=="delete"){
	$project_id=escapeString($_POST['project_id']);

	$user_role=isProjectMember($project_id,$user_id);

	if($user_role=="Director" || $user_role=="Producer"){
		$result=deleteProject($project_id);

		if($result==false){
			$errors++;
			$response['main_message']="Could not delete project";
		}
	}
	else {
		$errors++;
		$response['main_message']="Do not have permission to delete project";
	}
}

$response['success']=(($errors>0) ? false : true);

$jsonResponse=json_encode($response);

print_r($jsonResponse);

?>