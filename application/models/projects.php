<?php

function addProject($project_name,$project_description,$project_start_date,$project_end_date,$user_role,$user_id){

	// Adds project into Database using parameters provided to function and creates folder
	// in the "Private" folder in which all storyboard images are stored.
	// Also stores the corresponding project member details into vs_project_members

	if($user_role=="Director"){
		$query="INSERT INTO vs_projects (project_name,project_description) 
			VALUES ('$project_name','$project_description')";
		
		$project_id=insertQuery($query);

		if($project_id){
			$query="INSERT INTO vs_notifications (meta_type,meta_value,project_id,referrer_id) 
				VALUES ('project_start_date','$project_start_date','$project_id','$user_id')";
			$result=execQuery($query);

			$query="INSERT INTO vs_notifications (meta_type,meta_value,project_id,referrer_id) 
				VALUES ('project_end_date','$project_end_date','$project_id','$user_id')";
			$result=execQuery($query);
		}
	}

	else if($user_role=="Producer"){
		$query="INSERT INTO vs_projects (project_name,project_description,project_start_date,project_end_date)
			VALUES ('$project_name','$project_description','$project_start_date','$project_end_date')";
	
		$project_id=insertQuery($query);
	}
	
	if($project_id){
		$query="SELECT user_email FROM vs_users WHERE user_id='$user_id'";

		$result=execQuery($query);
		$result=mysqli_fetch_assoc($result);
		$user_email=$result['user_email'];

		$query="INSERT INTO vs_project_members (user_email,user_id,user_role,user_status,project_id)
			VALUES ('$user_email','$user_id','$user_role','1','$project_id')";

		$result=execQuery($query);

		if($result==false){
			deleteProject($project_id);
			return false;
		}
		else {
			$project_folder_name=generateFolderName($project_id,$project_name);

			$query="UPDATE vs_projects SET project_folder_name='$project_folder_name' WHERE project_id='$project_id'";
			$result=execQuery($query);

			if($result){
				mkdir($project_folder_name);
				return $project_id;
			}
			else {
				deleteProject($project_id);
				return false;
			}
		}
	}
	else {
		return false;
	}
}

function deleteProject($project_id){

	// Deletes project using supplied Project ID

	$query="SELECT project_folder_name FROM vs_projects WHERE project_id='$project_id'";
	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$project_folder_name=$result['project_folder_name'];

	$query="DELETE FROM vs_projects WHERE project_id='$project_id'";
	$result=execQuery($query);

	$query="DELETE FROM vs_project_members WHERE project_id='$project_id'";
	$result=execQuery($query);

	$query="DELETE FROM vs_notifications WHERE project_id='$project_id'";
	$result=execQuery($query);

	$query="SELECT scene_id,scene_image FROM vs_project_scenes WHERE project_id='$project_id'";
	$result=execQuery($query);

	while($new_result=mysqli_fetch_array($result)){
		$scene_id=$new_result['scene_id'];
		$scene_image=$new_result['scene_image'];

		$new_query="DELETE FROM vs_project_meta WHERE scene_id='$scene_id'";
		execQuery($new_query);

		@unlink($scene_image);
	}

	$query="DELETE FROM vs_project_scenes WHERE project_id='$project_id'";
	$result=execQuery($query);

	@rmdir($project_folder_name);

	return true;
}

function updateProject($project_name,$project_description,$project_start_date,$project_end_date,$project_id){

	// Updates Project details

	$query="UPDATE vs_projects SET project_name='$project_name', project_description='$project_description',
		project_start_date='$project_start_date', project_end_date='$project_end_date' WHERE project_id='$project_id";

	$result=execQuery($query);
	return $result;
}

function getProjectNameById($project_id){

	// Retrieves Project name based on supplied Project ID

	$query="SELECT project_name FROM vs_projects WHERE project_id='$project_id'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$project_name=$result['project_name'];

	return (isset($project_name) ? $project_name : false);
}

function projectCount($user_id){

	// Returns total number of projects that the user is a member of

	$query="SELECT COUNT(*) FROM vs_project_meta WHERE user_id='$user_id'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$result=$result['COUNT(*)'];

	return $result;
}

function acceptProject($project_id,$user_id){

	// This function is called when an invited user "Accepts" the invitation to the project

	$query="UDPATE vs_project_members SET user_status='1' WHERE project_id='$project_id' AND user_id='$user_id'";

	$result=execQuery($query);
	return $result;
}

function addMember($user_role,$user_email,$project_id,$requesting_user_id){

	// Adds a member to the project
	// This function requires the sendMail() function from the Users module to operate

	$response=array();

	if(isProjectMemberByEmail($project_id,$user_email)){
		$response['success']=false;
		$response['main_message']="Already a project member.";

		return $response;
	}

	$query="SELECT project_name FROM vs_projects WHERE project_id='$project_id'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$project_name=$result['project_name'];

	if(userExists($user_email)){
		$query="SELECT user_id FROM vs_users WHERE user_email='$user_email'";

		$result=execQuery($query);
		$result=mysqli_fetch_assoc($result);
		$user_id=$result['user_id'];

		$query="INSERT INTO vs_project_members (user_role,user_id,user_email,user_status,project_id)
			VALUES ('$user_role','$user_id','$user_email','0','$project_id')";
		$result=execQuery($query);
	}
	else {
		$query="INSERT INTO vs_project_members (user_role,user_email,project_id,user_status)
			VALUES ('$user_role','$user_email','$project_id','0')";
		$result=execQuery($query);
	}

	if($result==true){
		$query="INSERT INTO vs_notifications (meta_type,meta_value,project_id,referrer_id)
			VALUES ('project_invite','$user_email','$project_id','$requesting_user_id')";
		$result=execQuery($query);
	}

	if($result==false){
		removeMember($user_email,$project_id);
	}

	if($result==true){
		$response['success']=true;

		$subject="Project Invitation - VyvaSync";

		if(userExists($user_email)==true){
			$query="SELECT user_first_name FROM vs_users WHERE user_id='$user_id'";

			$result=execQuery($query);
			$result=mysqli_fetch_assoc($result);
			$user_first_name=$result['user_first_name'];

			$message="Dear $user_first_name<br><br>";
			$message.="You have been added to the project $project_name as $user_role on VyvaSync.<br><br>";
			$message.="To begin contributing to this project, please sign in to your VyvaSync account.<br><br>";
			$message.="<a href='http://beta.vyvasync.com/application/views/login.php'>Sign in</a><br><br>";
			$message.="Regards,<br>";
			$message.="The VyvaSync Team<br>";
		}

		if(userExists($user_email)==false){
			$message="Hi there,<br><br>";
			$message.="You have been added to the project $project_name as $user_role on VyvaSync.<br><br>";
			$message.="To begin contributing to this project, create an account on VyvaSync today and get started:<br><br>";
			$message.="<a href='http://beta.vyvasync.com/application/views/register.php'>Register with VyvaSync</a><br><br>";
			$message.="Regards,<br>";
			$message.="The VyvaSync Team<br>";
		}

		$result=sendMail($subject,$message,$user_email);

		if($result==false){
			removeMember($user_email,$project_id);
		}
	}

	return $response;
}

function removeMember($user_email,$project_id){

	// Removes a member from the project

	$query="DELETE FROM vs_project_members WHERE user_email='$user_email' AND project_id='$project_id'";
	$result=execQuery($query);

	$query="DELETE FROM vs_notifications WHERE meta_value='$user_email' AND project_id='$project_id'";
	$result=execQuery($query);

	return true;
}

function getCrew($project_id){
	$query="SELECT user_email,user_role,user_status FROM vs_project_members WHERE project_id='$project_id'";

	$result=execQuery($query);

	$users=array();
	$total_users=0;

	while($member=mysqli_fetch_array($result)){
		$user_email=$member['user_email'];
		$user_role=$member['user_role'];
		$user_status=$member['user_status'];

		if($user_status==0){
			$user_status="Invited";
		}
		else if($user_status==1){
			$user_status="Approved";
		}

		$new_query="SELECT user_id,user_first_name,user_last_name FROM vs_users WHERE user_email='$user_email'";

		$new_result=execQuery($new_query);
		$new_result=mysqli_fetch_assoc($new_result);

		if($new_result){
			$user_id=$new_result['user_id'];
			$user_first_name=$new_result['user_first_name'];
			$user_last_name=$new_result['user_last_name'];
			$user_name="$user_first_name $user_last_name";

			$users[$total_users]['meta']=$user_name;
			$users[$total_users]['user_id']=$user_id;
		}
		else {
			$users[$total_users]['meta']=$user_email;
		}

		$users[$total_users]['role']=$user_role;
		$users[$total_users]['user_email']=$user_email;
		$users[$total_users]['user_status']=$user_status;

		$total_users++;
	}

	if($total_users==0){
		return false;
	}
	else {
		return $users;
	}
}

function isProjectMember($project_id,$user_id){

	// Returns true if user is in fact the member of the given project

	$query="SELECT user_role FROM vs_project_members WHERE project_id='$project_id' AND user_id='$user_id'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$user_role=$result['user_role'];

	return (isset($user_role) ? $user_role : false);
}

function isProjectMemberByEmail($project_id,$user_email){
	// Returns true if user is in fact the member of the given project

	$query="SELECT user_role FROM vs_project_members WHERE project_id='$project_id' AND user_email='$user_email'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$user_role=$result['user_role'];

	return (isset($user_role) ? $user_role : false);
}

function getAllProjects($user_id){
	$query="SELECT COUNT(*) FROM vs_project_members WHERE user_status='1' AND user_id='$user_id'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$total=$result['COUNT(*)'];

	if($total==0){
		return false;
	}

	$projects=array();

	$projects['total']=$total;

	$total=0;

	$query="SELECT project_id,user_role FROM vs_project_members WHERE user_status='1' AND user_id='$user_id' ORDER BY project_id DESC";

	$result=execQuery($query);

	while($project=mysqli_fetch_array($result)){
		$project_id=$project['project_id'];

		$query="SELECT project_name,project_start_date,project_end_date,project_description FROM vs_projects WHERE project_id='$project_id'";

		$new_result=execQuery($query);
		$new_result=mysqli_fetch_assoc($new_result);

		$project_name=$new_result['project_name'];
		$project_description=$new_result['project_description'];

		$project_start_date=$new_result['project_start_date'];

		if($project_start_date){
			$date_array=explode("-",$project_start_date);
			$project_start_date=$date_array[1].'/'.$date_array[2].'/'.$date_array[0];
		}
		else {
			$project_start_date="Pending approval";
		}

		$project_end_date=$new_result['project_end_date'];

		if($project_end_date){
			$date_array=explode("-",$project_end_date);
			$project_end_date=$date_array[1].'/'.$date_array[2].'/'.$date_array[0];
		}
		else {
			$project_end_date="Pending approval";
		}

		$projects[$total]=array();
		$projects[$total]['project_id']=$project_id;
		$projects[$total]['project_name']="<a href='scenes.php?project_id=$project_id'>".$project_name."</a>";
		$projects[$total]['project_start_date']=$project_start_date;
		$projects[$total]['project_end_date']=$project_end_date;
		$projects[$total]['project_description']=$project_description;
		$projects[$total]['user_role']=$project['user_role'];


		$query="SELECT COUNT(*) FROM vs_project_scenes WHERE project_id='$project_id' AND scene_status='1'";

		$new_result=execQuery($query);
		$new_result=mysqli_fetch_assoc($new_result);

		$projects[$total]['project_shots_completed']=$new_result['COUNT(*)'];


		$query="SELECT COUNT(*) FROM vs_project_scenes WHERE project_id='$project_id' AND scene_status='0'";

		$new_result=execQuery($query);
		$new_result=mysqli_fetch_assoc($new_result);

		$projects[$total]['project_shots_remaining']=$new_result['COUNT(*)'];

		$total++;
	}

	return $projects;
}

function getTodaysProjects($user_id,$date){
	$query="SELECT COUNT(*) FROM vs_project_members WHERE user_status='1' AND user_id='$user_id'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$total=$result['COUNT(*)'];

	if($total==0){
		return false;
	}

	$projects=array();

	$total=0;

	$query="SELECT project_id,user_role FROM vs_project_members WHERE user_status='1' AND user_id='$user_id' ORDER BY project_id DESC";

	$result=execQuery($query);

	while($project=mysqli_fetch_array($result)){
		$project_id=$project['project_id'];

		$query="SELECT COUNT(*) FROM vs_project_scenes WHERE scene_status='0' AND scene_date='$date' AND project_id='$project_id'";
		
		$new_result=execQuery($query);
		$new_result=mysqli_fetch_assoc($new_result);
		$count=$new_result['COUNT(*)'];

		if($count>0){
			$query="SELECT project_name,project_start_date,project_end_date,project_description FROM vs_projects WHERE project_id='$project_id'";

			$new_result=execQuery($query);
			$new_result=mysqli_fetch_assoc($new_result);

			$project_name=$new_result['project_name'];
			$project_description=$new_result['project_description'];

			if($project_start_date){
				$date_array=explode("-",$project_start_date);
				$project_start_date=$date_array[1].'/'.$date_array[2].'/'.$date_array[0].' (mm/dd/yyyy)';
			}
			else {
				$project_start_date="Pending approval";
			}

			$project_end_date=$new_result['project_end_date'];

			if($project_end_date){
				$date_array=explode("-",$project_end_date);
				$project_end_date=$date_array[1].'/'.$date_array[2].'/'.$date_array[0].' (mm/dd/yyyy)';
			}
			else {
				$project_end_date="Pending approval";
			}

			$projects[$total]=array();
			$projects[$total]['project_id']=$project_id;
			$projects[$total]['project_name']="<a href='scenes.php?project_id=$project_id'>".$project_name."</a>";
			$projects[$total]['project_start_date']=$project_start_date;
			$projects[$total]['project_end_date']=$project_end_date;
			$projects[$total]['project_description']=$project_description;
			$projects[$total]['user_role']=$project['user_role'];


			$query="SELECT COUNT(*) FROM vs_project_scenes WHERE project_id='$project_id' AND scene_status='1'";

			$new_result=execQuery($query);
			$new_result=mysqli_fetch_assoc($new_result);

			$projects[$total]['project_shots_completed']=$new_result['COUNT(*)'];


			$query="SELECT COUNT(*) FROM vs_project_scenes WHERE project_id='$project_id' AND scene_status='0'";

			$new_result=execQuery($query);
			$new_result=mysqli_fetch_assoc($new_result);

			$projects[$total]['project_shots_remaining']=$new_result['COUNT(*)'];

			$total++;
		}
	}

	$projects['total']=$total;

	return (($total>0) ? $projects : false);
}

function getProjectDetails($project_id){
	$response=array();

	$query="SELECT project_name,project_description,project_start_date,project_end_date FROM vs_projects WHERE project_id='$project_id'";

	$result=execQuery($query);

	if($result==false){
		return false;
	}

	$result=mysqli_fetch_assoc($result);

	$response['project_name']=$result['project_name'];
	$response['project_description']=$result['project_description'];

	$start_date=$result['project_start_date'];
	$end_date=$result['project_end_date'];

	if($start_date){
		$date_array=explode("-",$start_date);
		$start_date=$date_array[1].'/'.$date_array[2].'/'.$date_array[0];
	}

	if($end_date){
		$date_array=explode("-",$end_date);
		$end_date=$date_array[1].'/'.$date_array[2].'/'.$date_array[0];
	}

	$response['project_start_date']=$start_date;
	$response['project_end_date']=$end_date;

	return $response;
}

function generateFolderName($project_id,$project_name){
	$guid=strtolower($project_name);

	$title_length=strlen($project_name);

	for($k=0;$k<$title_length;$k++){
		if($guid[$k]==" "){
			$guid[$k]="-";
		}
	}

	$guid='../../private/'.$guid.'-'.$project_id;

	return $guid;
}

function getFolderName($project_id){
	$query="SELECT project_folder_name FROM vs_projects WHERE project_id='$project_id'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$project_folder_name=$result['project_folder_name'];

	if($project_folder_name){
		return $project_folder_name;
	}
	else {
		return false;
	}
}

function generate_call_sheet($project_id){
	$query="SELECT COUNT(*) FROM vs_project_scenes WHERE project_id='$project_id' AND scene_status='0'";
	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$count=$result['COUNT(*)'];

	if($count==0){
		return 1;
	}

	$query="SELECT COUNT(*) FROM vs_project_scenes WHERE project_id='$project_id' AND scene_date IS NULL";
	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$count=$result['COUNT(*)'];

	if($count>0){
		return 2;
	}

	$call_sheet=array();

	$query="SELECT project_name,project_description FROM vs_projects WHERE project_id='$project_id'";
	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);

	$call_sheet['project_name']=$result['project_name'];
	$call_sheet['project_description']=$result['project_description'];

	$query="SELECT user_first_name,user_last_name
				FROM vs_users, vs_project_members
				WHERE vs_users.user_id=vs_project_members.user_id
				AND vs_project_members.project_id='$project_id'
				AND vs_project_members.user_role='Director'";
	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$call_sheet['director']=$result['user_first_name'].' '.$result['user_last_name'];

	$query="SELECT user_first_name,user_last_name
				FROM vs_users, vs_project_members
				WHERE vs_users.user_id=vs_project_members.user_id
				AND vs_project_members.project_id='$project_id'
				AND vs_project_members.user_role='Producer'";
	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$call_sheet['producer']=$result['user_first_name'].' '.$result['user_last_name'];

	$query="SELECT scene_id,scene_name,scene_date,scene_image FROM vs_project_scenes WHERE project_id='$project_id' AND scene_status='0'";
	$query=execQuery($query);

	$call_sheet['scenes']=array(); $k=0;

	while($result=mysqli_fetch_array($query)){
		$scene_id=$result['scene_id'];
		$scene_name=$result['scene_name'];
		$scene_date=$result['scene_date'];
		$scene_image=$result['scene_image'];

		if($scene_name==NULL){ $scene_name="Untitled scene"; }

		$tagged_members=NULL;
		
		$new_query="SELECT user_first_name,user_last_name
						FROM vs_users,vs_project_meta
						WHERE vs_project_meta.scene_id='$scene_id'
						AND vs_project_meta.meta_type='crew_tag'
						AND vs_project_meta.meta_value=vs_users.user_id";
		$new_query=execQuery($new_query);

		while($new_result=mysqli_fetch_array($new_query)){
			$tagged_members.=$new_result['user_first_name'].' '.$new_result['user_last_name'].', ';
		}


		if($tagged_members==NULL){
			$tagged_members="No crew tagged for this scene.";
		}
		else {
			$temp=strlen($tagged_members);
			$temp--;

			if($tagged_members[$temp]==" "){
				$temp--;
				$tagged_members[$temp]=" ";
			}
		}

		$call_sheet['scenes'][$k]=array();

		$call_sheet['scenes'][$k]['scene_name']=$scene_name;
		$call_sheet['scenes'][$k]['scene_date']=$scene_date;
		$call_sheet['scenes'][$k]['scene_image']=$scene_image;
		$call_sheet['scenes'][$k]['tagged_members']=$tagged_members;

		$k++;
	}

	$call_sheet['total_scenes']=$k+1;

	return $call_sheet;
}

function send_call_sheet($attachment,$project_id){
	$query="SELECT project_name FROM vs_projects WHERE project_id='$project_id'";
	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$project_name=$result['project_name'];

	$query="SELECT user_email FROM vs_project_members WHERE user_status='1' AND project_id='$project_id'";
	$query=execQuery($query);

	while($result=mysqli_fetch_array($query)){
		$user_email=$result['user_email'];

		$new_query="SELECT user_first_name,user_last_name FROM vs_users WHERE user_email='$user_email'";
		$new_result=execQuery($new_query);
		$new_result=mysqli_fetch_assoc($new_result);
		$user_name=$new_result['user_first_name'].' '.$new_result['user_last_name'];

		$mail=new PHPMailer();
		$mail->AddReplyTo("contact@vyvasync.com","VyvaSync");
		$mail->SetFrom('contact@vyvasync.com','VyvaSync');
		
		$address=$user_email;
		$mail->AddAddress($address,$user_name);

		$mail->Subject="Call Sheet - VyvaSync";

		$message="Dear $user_name</br></br>";
		$message.="Please find attached along with this email the Call Sheet for project: $project_name</br></br>";
		$message.="Regards,</br>";
		$message.="The VyvaSync Team";

		$mail->MsgHTML($message);

		$mail->AddStringAttachment($attachment,'call-sheet.pdf');
		$mail->Send();
	}
}

?>