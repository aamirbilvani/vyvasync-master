<?php

function addToStoryboard($scene_image,$project_id){

	// Adds image to Storyboard

	$query="INSERT INTO vs_project_scenes (scene_image,project_id) VALUES ('$scene_image','$project_id')";

	$result=insertQuery($query);
	return $result;
}

function addScene($scene_name,$scene_date,$scene_image,$project_id,$user_id){

	// Add scene to project

	$query="INSERT INTO vs_project_scenes (scene_name,scene_image,project_id) VALUES ('$scene_name','$scene_image','$project_id')";
	$scene_id=insertQuery($query);

	if($scene_id){
		$query="INSERT INTO vs_notifications (meta_type,meta_value,scene_id,project_id,user_id) 
			VALUES ('scene_date','$scene_date','$scene_id','$project_id','$user_id')";
		$result=execQuery($query);

		return $result;
	}
	else {
		return false;
	}
}

function deleteScene($scene_id){

	// Deletes scenes from Project

	$query="SELECT scene_image FROM vs_project_scenes WHERE scene_id='$scene_id'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$scene_image=$result['scene_image'];

	if(unlink($scene_image)){
		$query="DELETE FROM vs_project_scenes WHERE scene_id='$scene_id'";
		$result=execQuery($query);

		$query="DELETE FROM vs_project_meta WHERE scene_id='$scene_id'";
		$result=execQuery($query);

		return $result;
	}
	else {
		return false;
	}
}

function getSceneParent($scene_id){
	$query="SELECT project_id FROM vs_project_scenes WHERE scene_id='$scene_id'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$project_id=$result['project_id'];

	return ((isset($project_id) ? $project_id : false));
}

function toggleSceneStatus($scene_id){

	// This function is called when Director "OKs" a scene

	$query="SELECT scene_status FROM vs_project_scenes WHERE scene_id='$scene_id'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);

	$scene_status=($result['scene_status']=='0' ? '1' : '0');

	$query="UPDATE vs_project_scenes SET scene_status='$scene_status' WHERE scene_id='$scene_id'";

	$result=execQuery($query);

	return (($result==false) ? false : $scene_status);
}

function addNote($meta_value,$scene_id,$user_id){

	// Used to add notes by a member onto a particular scene
	// This function requires the Project model to operate

	$query="SELECT meta_id FROM vs_project_meta WHERE meta_type='note' AND scene_id='$scene_id' AND user_id='$user_id'";
	
	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$meta_id=$result['meta_id'];

	if($meta_id){
		$query="UPDATE vs_project_meta SET meta_value='$meta_value' WHERE meta_id='$meta_id'";
	}
	else {
		$query="INSERT INTO vs_project_meta (meta_type,meta_value,user_id,scene_id)
			VALUES ('note','$meta_value','$user_id','$scene_id')";
	}

	$result=execQuery($query);

	return $result;
}

function addSceneMeta($scene_name,$scene_date,$meta_value,$scene_id,$user_id){
	$project_id=getSceneParent($scene_id);
	$user_role=isProjectMember($project_id,$user_id);

	if($user_role=="Director" || $user_role=="Assistant Director"){
		$query="UPDATE vs_project_scenes SET scene_name='$scene_name' WHERE scene_id='$scene_id'";
		$result=execQuery($query);

		$result=addNote($meta_value,$scene_id,$user_id);

		if($scene_date!=NULL){
			$temp=explode("/",$scene_date);
			$scene_date=$temp[2].'-'.$temp[0].'-'.$temp[1];
		}

		$query="SELECT scene_date FROM vs_project_scenes WHERE scene_id='$scene_id'";
		$result=execQuery($query);
		$result=mysqli_fetch_assoc($result);
		$actual_scene_date=$result['scene_date'];

		$query="SELECT meta_value FROM vs_notifications WHERE scene_id='$scene_id' AND meta_type='scene_date' AND project_id='$project_id'";
		$result=execQuery($query);
		$result=mysqli_fetch_assoc($result);
		$pending_scene_date=$result['meta_value'];

		if($pending_scene_date==NULL){
			if($actual_scene_date!=$scene_date){
				$query="INSERT INTO vs_notifications (meta_type,meta_value,scene_id,project_id,referrer_id)
						VALUES ('scene_date','$scene_date','$scene_id','$project_id','$user_id')";
				$result=execQuery($query);

				$query="UPDATE vs_project_scenes SET scene_date=NULL WHERE scene_id='$scene_id'";
				$result=execQuery($query);

				return '4';		
			}
			else {
				return '1';
			}
		}
		else {
			if($scene_date!=$pending_scene_date){
				return '3';
			}
			else {
				return '1';
			}
		}
	}
	else {
		return '2';
	}
}

function getScenes($project_id,$user_role,$scene_status,$date){
	$query="SELECT COUNT(*) FROM vs_project_scenes WHERE project_id='$project_id'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$count=$result['COUNT(*)'];

	if($count==0){
		return false;
	}

	$total=0;

	$scenes=array();

	if($scene_status=="all"){
		$query="SELECT scene_id,scene_name,scene_date,scene_image,scene_status FROM vs_project_scenes WHERE project_id='$project_id'";
	}
	else if($scene_status=="todays"){
		$query="SELECT scene_id,scene_name,scene_date,scene_image,scene_status FROM vs_project_scenes WHERE project_id='$project_id' AND scene_date='$date' AND scene_status='0'";
	}
	else if($scene_status=="completed"){
		$query="SELECT scene_id,scene_name,scene_date,scene_image,scene_status FROM vs_project_scenes WHERE project_id='$project_id' AND scene_status='1'";
	}
	

	$result=execQuery($query);

	while($scene=mysqli_fetch_array($result)){
		$scene_id=$scene['scene_id'];

		$scenes[$total]['scene_id']=$scene['scene_id'];
		$scenes[$total]['scene_image']=$scene['scene_image'];
		$scenes[$total]['scene_status']=$scene['scene_status'];

		if($scene['scene_name']==NULL){
			$scenes[$total]['scene_name']="Untitled scene";
		}
		else {
			$scenes[$total]['scene_name']=$scene['scene_name'];
		}

		$scene_date=$scene['scene_date'];

		if($scene_date==NULL){
			$scenes[$total]['scene_date']="--";
		}
		else {
			$scene_date=explode("-",$scene_date);
			$scene_date=$scene_date[1].'-'.$scene_date[2].'-'.$scene_date[0];
			$scenes[$total]['scene_date']=$scene_date;
		}

		$new_query="SELECT COUNT(*) FROM vs_project_meta WHERE scene_id='$scene_id' AND meta_type='note'";
		$new_result=execQuery($new_query);
		$new_result=mysqli_fetch_assoc($new_result);
		$total_notes=$new_result['COUNT(*)'];

		$scenes[$total]['total_scene_notes']=$total_notes;

		$total++;
	}

	$scenes['total']=$total;
	$scenes['user_role']=$user_role;

	if($total==0){
		return false;
	}
	else {
		return $scenes;
	}
}

function getSceneDetail($scene_id,$requesting_user_id){
	$project_id=getSceneParent($scene_id);

	if($project_id==false){
		return false;
	}

	$response=array();

	$query="SELECT scene_name,scene_date,scene_image FROM vs_project_scenes WHERE scene_id='$scene_id'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);

	$response['scene_name']=$result['scene_name'];
	$response['scene_image']=$result['scene_image'];

	$scene_date=$result['scene_date'];

	if($scene_date){
		$temp=explode("-",$scene_date);
		$scene_date=$temp[1].'/'.$temp[2].'/'.$temp[0];
	}

	$response['scene_date']=$scene_date;

	$query="SELECT user_role,user_id FROM vs_project_members WHERE project_id='$project_id'";
	$result=execQuery($query);

	$k=0;

	$response['roles']=array();

	while($member=mysqli_fetch_array($result)){
		$user_id=$member['user_id'];
		$user_role=$member['user_role'];

		$user_name=getUserNameById($user_id);

		$query="SELECT meta_value FROM vs_project_meta WHERE meta_type='note' AND scene_id='$scene_id' AND user_id='$user_id'";

		$new_result=execQuery($query);
		$new_result=mysqli_fetch_assoc($new_result);

		$response['roles'][$k]=array();
		$response['roles'][$k]['role']=$user_role;
		$response['roles'][$k]['user_name']=$user_name;
		$response['roles'][$k]['note']=$new_result['meta_value'];

		if($requesting_user_id==$user_id){
			$response['my_note']=$new_result['meta_value'];
			$response['roles'][$k]['user_name']="You";
		}

		$k++;
	}

	$response['tagged_members']=NULL;

	$query="SELECT vs_users.user_id,user_first_name,user_last_name,meta_id
				FROM vs_users, vs_project_meta
				WHERE vs_users.user_id=vs_project_meta.meta_value
				AND vs_project_meta.meta_type='crew_tag'
				AND vs_project_meta.scene_id='$scene_id'
				ORDER BY meta_id ASC
	";

	$query=execQuery($query);

	while($result=mysqli_fetch_array($query)){
		$user_id=$result['user_id'];
		$user_first_name=$result['user_first_name'];
		$user_last_name=$result['user_last_name'];
		$meta_id=$result['meta_id'];

		$requesting_user_role=isProjectMember($project_id,$requesting_user_id);

		if($requesting_user_role=="Director" || $requesting_user_role=="Producer"){
			$response['tagged_members'].="<span id='$meta_id' class='storyboard-tagged-crew'>$user_first_name $user_last_name <span class='storyboard-crew-delete' onclick='deleteCrewMemberTag($meta_id,$project_id,$scene_id)'>X</span></span>";
		}
		else {
			$response['tagged_members'].="<span class='storyboard-tagged-crew'>$user_first_name $user_last_name</span>";
		}
	}

	return $response;
}

function getSceneForPi($project_id,$date){
	$query="SELECT scene_id,scene_name,scene_image FROM vs_project_scenes WHERE project_id='$project_id' AND scene_status='0' AND scene_date='$date' ORDER BY scene_id ASC";

	$result=execQuery($query);
	$rows=mysqli_num_rows($result);

	$response=array();

	if($rows>0){
		$result=mysqli_fetch_assoc($result);

		$response['scene_id']=$result['scene_id'];
		$response['scene_image']=$result['scene_image'];

		if($result['scene_name']==NULL){
			$response['scene_name']="Untitled Scene";
		}
		else {
			$response['scene_name']=$result['scene_name'];
		}

		return $response;
	}

	else {
		return false;
	}
}

?>