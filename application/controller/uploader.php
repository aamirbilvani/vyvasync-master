<?php

session_start();

require "../models/connect.php";
require "../models/projects.php";
require "../models/scenes.php";

$errors=0;

$response=array();

$imageCheck=getimagesize($_FILES['storyboard']['tmp_name']);
$imageSize=$_FILES['storyboard']['size'];
$imageName=basename($_FILES['storyboard']['name']);
$imageExtension=pathinfo($imageName,PATHINFO_EXTENSION);

$user_id=$_SESSION['user']['id'];

$project_id=$_POST['project_id'];
$project_name=getProjectNameById($project_id);

$user_role=isProjectMember($project_id,$user_id);

if($user_role==false){
	$errors++;
	$response['main_message']="You are not a member of this project";
}

else if($user_role!="Director" && $user_role!="Assistant Director"){
	$errors++;
	$response['main_message']="You do not have authority to upload images";
}

if($project_name==false){
	$errors++;
	$response['main_message']="Project not found";
}

if($imageExtension!="jpg" && $imageExtension!="jpeg" && $imageExtension!="png" && $imageExtension!="gif" &&
	$imageExtension!="JPG" && $imageExtension!="JPEG" && $imageExtension!="PNG" && $imageExtension!="GIF"){
	$errors++;
	$response['main_message']="<b>$imageName</b> is not a valid image file";
}

else if($imageCheck===false){
	$errors++;
	$response['main_message']="<b>$imageName</b> is not a valid image file";
}

else if($imageSize>IMG_SIZE){
	$errors++;
	$response['main_message']="<b>$imageName</b> exceeds upload limit of <b>5MB</b>";
}

if($errors>0){
	$response['success']=false;
}

else {
	$targetFolder=getFolderName($project_id);

	if($targetFolder==false){
		$errors++;
		$response['success']=false;
		$response['error_message']="Could not retrieve project folder";
	}

	$targetFile="$targetFolder/$imageName";

	if($errors==0){
		if(file_exists($targetFile) && $imageName!='image.jpg' && $imageName!='image.png' && $imageName!='image.jpeg'){
			$errors++;
			$response['success']=false;
			$response['main_message']="An image by that name already exists";
		}
	}
	
	if($errors==0){
		if($imageName=='image.jpg' || $imageName=='image.jpeg' || $imageName=='image.png'){
			$characters="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$temp=NULL;

			for($k=0;$k<8;$k++){
				$temp=$temp.$characters[rand(0,strlen($characters)-1)];
			}

			$imageName=$temp.'.'.$imageExtension;
		}

		$targetFile="$targetFolder/$imageName";

		if(move_uploaded_file($_FILES['storyboard']['tmp_name'],$targetFile)){

			$scene_id=addToStoryboard($targetFile,$project_id);

			if($scene_id){
				$response['success']=true;
				$response['image_url']=$targetFile;
				$response['scene_id']=$scene_id;
			}
			else {
				$response['success']=false;
				$response['main_message']="Something went wrong";

				unlink($targetFile);
			}
		}
		else {
			$response['success']=false;
			$response['main_message']="An error occurred";
		}
	}
}

$jsonResponse=json_encode($response);

print_r($jsonResponse);

?>