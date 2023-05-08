<?php

function userExists($user_email){

	// Using provided email, this function returns true if an account
	// exists under the provided email address.

	$query="SELECT COUNT(*) FROM vs_users WHERE user_email='$user_email'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$result=$result['COUNT(*)'];

	return (($result==1) ? true : false);
}

function getUserEmailById($user_id){
	$query="SELECT user_email FROM vs_users WHERE user_id='$user_id'";
	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$user_email=$result['user_email'];

	return $user_email;
}

function getUserNameById($user_id){
	$query="SELECT user_first_name,user_last_name FROM vs_users WHERE user_id='$user_id'";
	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$user_name=$result['user_first_name'].' '.$result['user_last_name'];

	return $user_name;
}

function getUserIDbyEmail($user_email){
	$query="SELECT user_id FROM vs_users WHERE user_email='$user_email'";
	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$user_id=$result['user_id'];

	return $user_id;
}

function login($user_email,$user_password){

	// This function logs the user into the application

	$query="SELECT user_id,user_active FROM vs_users WHERE user_email='$user_email' AND user_password='$user_password'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);

	$user_id=$result['user_id'];
	$user_active=$result['user_active'];

	$result=NULL;

	if($user_id==NULL){
		$result="invalid";
	}

	else if($user_active=='0'){
		$result="inactive";
	}

	else {
		$_SESSION['user']=array();
		$_SESSION['user']['id']=$user_id;
		$_SESSION['user']['active']=true;

		$todays_date=get_todays_date();
		$query="UPDATE vs_users SET user_last_sign_in='$todays_date' WHERE user_id='$user_id'";
		execQuery($query);

		$result=true;
	}

	return $result;
}

function registerUser($user_first_name,$user_last_name,$user_email,$user_password,$user_join_date){

	// This function registers the user onto the application

	$query="INSERT INTO vs_users (user_first_name,user_last_name,user_email,user_password,user_join_date,user_subscription_type)
		VALUES ('$user_first_name','$user_last_name','$user_email','$user_password','$user_join_date','0')";

	$user_id=insertQuery($query);

	if($user_id==false){
		return false;
	}

	$token=generateToken();
	$token_id=add_activation_token($token,$user_id);

	if($token_id==false){
		$query="DELETE FROM vs_users WHERE user_id='$user_id'";
		$result=execQuery($query);

		return false;
	}
	else {

		$result=send_activation_email($token,$user_email,$user_first_name);		

		if($result==true){
			return true;
		}
		else {
			$query="DELETE FROM vs_users WHERE user_id='$user_id'";
			$result=execQUery($query);

			$query="DELETE FROM vs_user_tokens WHERE token_id='$token_id'";
			$result=execQuery($query);

			return false;
		}
	}
}

function encryptPassword($user_password){

	// This function uses the Encryption Key from connect.php to encrypt the password

	$iv_size=mcrypt_get_iv_size(MCRYPT_BLOWFISH,MCRYPT_MODE_ECB);	
    $iv=mcrypt_create_iv($iv_size,MCRYPT_RAND);
    $user_password=mcrypt_encrypt(MCRYPT_BLOWFISH,ENC_KEY,utf8_encode($user_password),MCRYPT_MODE_ECB,$iv);
    
    return $user_password;
}

function sessionActive(){

	// This function checks if the user's session is active

	return (isset($_SESSION['user']) ? $_SESSION['user']['id'] : false);
}

function logout(){

	// Logs the user out of the application 
	
	session_destroy();
}

function sendMail($subject,$message,$email_address){

	// This function is called when an email is to be sent

	$headers="MIME-Version: 1.0\r\n"."Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers.="From: VyvaSync <contact@vyvasync.com>\r\n";
	$headers.="Reply-To: contact@vyvasync.com\r\n";

	$message="<html><body>$message</body></html>";

	if(mail($email_address,$subject,$message,$headers)){
		return true;	
	}
	else {
		return false;
	}
}

function generateToken(){
	$token=NULL;

	$characters="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

	for($k=0;$k<30;$k++){
		$token=$token.$characters[rand(0,strlen($characters)-1)];
	}

	$query="SELECT COUNT(*) FROM vs_user_tokens WHERE token_value='$token'";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$count=$result['COUNT(*)'];

	if($count==1){
		generateToken();
	}
	else {
		return $token;
	}
}

function activateAccount($token){
	$query="SELECT token_id,user_id FROM vs_user_tokens WHERE token_value='$token'";
	$result=execQuery($query);

	$count=mysqli_num_rows($result);

	if($count==1){
		$result=mysqli_fetch_assoc($result);

		$token_id=$result['token_id'];
		$user_id=$result['user_id'];

		$query="UPDATE vs_users SET user_active='1' WHERE user_id='$user_id'";
		$result=execQuery($query);

		$query="SELECT user_email FROM vs_users WHERE user_id='$user_id'";
		$result=execQuery($query);
		$result=mysqli_fetch_assoc($result);
		$user_email=$result['user_email'];

		$query="UPDATE vs_project_members SET user_id='$user_id' WHERE user_email='$user_email'";
		$result=execQuery($query);

		$query="DELETE FROM vs_user_tokens WHERE token_id='$token_id'";
		$result=execQuery($query);

		return true;
	}
	else {
		return false;
	}
}

function add_activation_token($token,$user_id){
	$token_type="account_activation";

	$query="INSERT INTO vs_user_tokens (token_type,token_value,user_id) VALUES ('$token_type','$token','$user_id')";
	$result=insertQuery($query);

	return $result;
}

function add_password_reset_token($token,$user_id){
	$token_type="password_reset";

	$query="INSERT INTO vs_user_tokens (token_type,token_value,user_id) VALUES ('$token_type','$token','$user_id')";
	$result=execQuery($query);

	return $result;
}

function send_activation_email($token,$user_email,$user_first_name){
	$subject="Registration successful";

	$message="Dear $user_first_name,<br><br>";
	$message.="Thank you for registering with VyvaSync.<br><br>";
	$message.="To activate your account, please click on the link below:<br><br>";
	$message.="<a href='http://beta.vyvasync.com/application/views/activate.php?token=$token' target='_blank'>Click here to activate your account</a><br><br>";
	$message.="Regards,<br>";
	$message.="The VyvaSync Team<br><br>";

	$result=sendMail($subject,$message,$user_email);

	return $result;
}

function resend_activation_link($user_id){
	$query="SELECT user_first_name,user_email,user_active FROM vs_users WHERE user_id=$user_id";

	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$user_active=$result['user_active'];
	$user_email=$result['user_email'];
	$user_first_name=$result['user_first_name'];

	if($user_active==1){
		return false;
	}

	else {
		$query="SELECT token_value FROM vs_user_tokens WHERE token_type='account_activation' AND user_id='$user_id'";

		$result=execQuery($query);
		$result=mysqli_fetch_assoc($result);
		$token_value=$result['token_value'];

		$result=send_activation_email($token_value,$user_email,$user_first_name);

		return $result;
	}
}

function get_notifications($user_id){
	$user_email=getUserEmailById($user_id);

	$notifications=array();
	$notifications['invites']=array();
	$notifications['projects']=array();
	$notifications['total']=0;

	$total=0;

	// Get Project Invitations
	$query="SELECT id,project_id,referrer_id FROM vs_notifications WHERE meta_type='project_invite' AND meta_value='$user_email'";
	$query_result=execQuery($query);

	$k=0;

	while($result=mysqli_fetch_array($query_result)){
		$notification_id=$result['id'];
		$project_id=$result['project_id'];
		$requesting_user_id=$result['referrer_id'];

		$user_role=isProjectMemberByEmail($project_id,$user_email);
		$requesting_user_name=getUserNameById($requesting_user_id);
		$project_name=getProjectNameById($project_id);

		$notifications['invites'][$k]=array();
		$notifications['invites'][$k]['notification_id']=$notification_id;
		$notifications['invites'][$k]['notification']="You have been added to the project <span class='notification-bold'>$project_name</span> as <span class='notification-bold'>$user_role</span> by <span class='notification-bold'>$requesting_user_name</span>.";

		$k++; $total++;
	}
	// End

	// Get Project Dates and Scene Dates
	$query="SELECT project_id FROM vs_project_members WHERE user_role='Producer' AND user_id='$user_id' AND user_status='1'";
	$query_result=execQuery($query);

	$k=0;

	while($result=mysqli_fetch_array($query_result)){
		$project_id=$result['project_id'];
		$project_name=getProjectNameById($project_id);

		$query="SELECT id,meta_type,meta_value,scene_id,referrer_id FROM vs_notifications WHERE project_id='$project_id' AND meta_type!='project_invite'";
		$new_query_result=execQuery($query);

		while($new_result=mysqli_fetch_array($new_query_result)){
			$notification_id=$new_result['id'];
			$meta_type=$new_result['meta_type'];
			$meta_value=$new_result['meta_value'];
			$scene_id=$new_result['scene_id'];

			$requesting_user_id=$new_result['referrer_id'];
			$requesting_user_name=getUserNameById($requesting_user_id);

			if($scene_id){
				$query="SELECT scene_name FROM vs_project_scenes WHERE scene_id='$scene_id'";
				$temp_result=execQuery($query);
				$temp_result=mysqli_fetch_assoc($temp_result);
				$scene_name=$temp_result['scene_name'];
			}

			$notifications['projects'][$k]=array();
			$notifications['projects'][$k]['notification_id']=$notification_id;
			
			if($meta_type=="project_start_date"){
				$notifications['projects'][$k]['notification']="Project start date for project <span class='notification-bold'>$project_name</span> has been set to <span class='notification-bold'>$meta_value</span> by <span class='notification-bold'>$requesting_user_name</span>.";
			}
			else if($meta_type=="project_end_date"){
				$notifications['projects'][$k]['notification']="Project end date for project <span class='notification-bold'>$project_name</span> has been set to <span class='notification-bold'>$meta_value</span> by <span class='notification-bold'>$requesting_user_name</span>.";
			}
			else if($meta_type=="scene_date"){
				$notifications['projects'][$k]['notification']="Shoot date for scene <span class='notification-bold'>$scene_name</span> of project <span class='notification-bold'>$project_name</span> has been set to <span class='notification-bold'>$meta_value</span> by <span class='notification-bold'>$requesting_user_name</span>.";
			}

			$k++; $total++;
		}
	}

	$notifications['total']=$total;

	return $notifications;
}

function accept_notification($notification_id){
	$query="SELECT meta_type,meta_value,scene_id,project_id FROM vs_notifications WHERE id='$notification_id'";
	
	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);

	$meta_type=$result['meta_type'];
	$meta_value=$result['meta_value'];
	$scene_id=$result['scene_id'];
	$project_id=$result['project_id'];

	if($meta_type=="project_start_date"){
		$query="UPDATE vs_projects SET project_start_date='$meta_value' WHERE project_id='$project_id'";
		$result=execQuery($query);

		delete_notification($notification_id);

		return $result;
	}
	else if($meta_type=="project_end_date"){
		$query="UPDATE vs_projects SET project_end_date='$meta_value' WHERE project_id='$project_id'";
		$result=execQuery($query);

		delete_notification($notification_id);

		return $result;
	}
	else if($meta_type=="scene_date"){
		$query="UPDATE vs_project_scenes SET scene_date='$meta_value' WHERE scene_id='$scene_id' AND project_id='$project_id'";
		$result=execQuery($query);

		delete_notification($notification_id);
		
		return $result;
	}
	else if($meta_type=="project_invite"){
		$query="UPDATE vs_project_members SET user_status='1' WHERE user_email='$meta_value'";
		$result=execQuery($query);

		$query="DELETE FROM vs_notifications WHERE id='$notification_id'";
		$result=execQuery($query);
		
		return $result;
	}
}

function delete_notification($notification_id){
	$query="SELECT meta_type,meta_value,project_id FROM vs_notifications WHERE id='$notification_id'";
	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);

	$meta_type=$result['meta_type'];
	$meta_value=$result['meta_value'];
	$project_id=$result['project_id'];

	if($meta_type=="project_invite"){
		$query="DELETE FROM vs_project_members WHERE user_email='$meta_value' AND project_id='$project_id'";
		$result=execQuery($query);
	}

	$query="DELETE FROM vs_notifications WHERE id='$notification_id'";
	$result=execQuery($query);

	return $result;
}

function change_password($current_password,$new_password,$user_id){
	$current_password=encryptPassword($current_password);
	$new_password=encryptPassword($new_password);

	$query="SELECT user_password FROM vs_users WHERE user_id='$user_id'";
	$result=execQuery($query);
	$result=mysqli_fetch_assoc($result);
	$actual_current_password=$result['user_password'];

	if($actual_current_password!=$current_password){
		return false;
	}
	else {
		$query="UPDATE vs_users SET user_password='$new_password' WHERE user_id='$user_id'";
		$result=execQuery($query);
		return $result;
	}
}

function get_crew_suggestions($user_input,$requesting_user_id,$scene_id,$project_id){
	$suggestions=NULL;

	$query="SELECT vs_users.user_id,user_first_name,user_last_name,user_role
				FROM vs_users, vs_project_members
				WHERE
					vs_users.user_id=vs_project_members.user_id
					AND vs_project_members.project_id='$project_id'
					AND vs_project_members.user_id!='$requesting_user_id'
					AND vs_project_members.user_status='1'
					AND (vs_users.user_first_name LIKE '$user_input%' OR vs_users.user_last_name LIKE '$user_input%')
	";

	$query=execQuery($query);

	while($result=mysqli_fetch_array($query)){
		$user_id=$result['user_id'];

		$new_query="SELECT meta_id FROM vs_project_meta WHERE meta_type='crew_tag' AND meta_value='$user_id' AND scene_id='$scene_id'";
		$new_result=execQuery($new_query);
		$count=mysqli_num_rows($new_result);

		if($count==0){
			$user_first_name=$result['user_first_name'];
			$user_last_name=$result['user_last_name'];
			$user_role=$result['user_role'];

			$suggestions.="<li onclick='tagCrewMember($user_id,$scene_id,$project_id)'>$user_first_name $user_last_name [$user_role]</li>";
		}
	}

	return $suggestions;
}

function tag_member($tag_user_id,$user_id,$scene_id,$project_id){
	$query="SELECT meta_id FROM vs_project_meta WHERE meta_type='crew_tag' AND meta_value='$tag_user_id' AND scene_id='$scene_id'";
	$result=execQuery($query);
	$result=mysqli_num_rows($result);

	if($result>0){
		return false;
	}
	else {
		$query="INSERT INTO vs_project_meta (meta_type,meta_value,user_id,scene_id) 
			VALUES ('crew_tag','$tag_user_id','$user_id','$scene_id')";

		$meta_id=insertQuery($query);

		if($meta_id){
			$query="SELECT user_first_name,user_last_name FROM vs_users WHERE user_id='$tag_user_id'";
			$result=execQuery($query);
			$result=mysqli_fetch_assoc($result);
			$user_first_name=$result['user_first_name'];
			$user_last_name=$result['user_last_name'];

			$result="<span id='$meta_id' class='storyboard-tagged-crew'>$user_first_name $user_last_name <span class='storyboard-crew-delete' onclick='deleteCrewMemberTag($meta_id,$project_id,$scene_id)'>X</span></span>";
			
			return $result;
		}
		else {
			return false;
		}
	}
}

function delete_tag($meta_id){
	$query="DELETE FROM vs_project_meta WHERE meta_id='$meta_id'";
	$result=execQuery($query);

	return $result;
}

?>