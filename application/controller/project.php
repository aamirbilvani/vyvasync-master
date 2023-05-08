<?php

session_start();

require "../models/connect.php";
require "../models/projects.php";

$user_id=$_SESSION['user']['id'];

$project_name=escapeString($_POST['project_name']);
$project_description=escapeString($_POST['project_description']);
$project_start_date=escapeString($_POST['project_start_date']);
$project_end_date=escapeString($_POST['project_end_date']);
$user_role=escapeString($_POST['user_role']);

$errors=0;

$response=array();

if($project_name==NULL){
	$errors++;
	$response['project_name']="Please enter a project name";
}

else if(preg_match('#^[a-z0-9\x20]+$#i',$project_name)==false){
	$errors++;
	$response['project_name']="Project name can contain alphanumeric letters and spaces only";
}

if($project_description==NULL){
	$errors++;
	$response['project_description']="Please enter a project description";
}

if($project_start_date==NULL){
	$errors++;
	$response['project_start_date']="Please select a start date";
}

if($project_end_date==NULL){
	$errors++;
	$response['project_end_date']="Please select an end date";
}

if($user_role!="Producer" && $user_role!="Director"){
	$errors++;
	$response['user_role']="Please select your role";
}

if($errors>0){
	$response['success']=false;
	$response['main_message']="There are issues with your form";
}

else {
	$temp=explode("/",$project_start_date);
	$project_start_date=$temp[2].'-'.$temp[0].'-'.$temp[1];

	$temp=explode("/",$project_end_date);
	$project_end_date=$temp[2].'-'.$temp[0].'-'.$temp[1];

	if(strtotime($project_start_date)>strtotime($project_end_date)){
		$errors++;
		$response['success']=false;
		$response['project_end_date']="Project end date must be greater than or equal to project start date";
	}

	if(strtotime($project_end_date)>=strtotime($project_start_date)){
		$project_id=addProject($project_name,$project_description,$project_start_date,$project_end_date,$user_role,$user_id);

		if($project_id){
			$response['success']=true;
			$response['project_id']=$project_id;
		}
		else {
			$response['success']=false;
			$response['main_message']="Error creating project";
		}
	}
}

$jsonResponse=json_encode($response);

print_r($jsonResponse);

?>