<?php

// This is the Schema function. This function creates the table into the
// database and should never be altered.

require "connect.php";

function db_schema(){
	$query="CREATE TABLE IF NOT EXISTS vs_users (
		user_id int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(user_id),
		user_first_name varchar(20) NOT NULL,
		user_last_name varchar(40),
		user_email varchar(200) NOT NULL,
		user_password varchar(300) NOT NULL,
		user_join_date date NOT NULL,
		user_join_medium varchar(50),
		user_active varchar(1) DEFAULT '0',
		user_profile_picture text,
		user_description text,
		user_subscription_type varchar(1) NOT NULL,
		user_last_payment_date date,
		user_last_payment_amount varchar(4),
		user_total_paid_for int		
		)";
	execQuery($query);

	$query="CREATE TABLE IF NOT EXISTS vs_user_tokens (
		token_id int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(token_id),
		token_type varchar(200) NOT NULL,
		token_value varchar(300),
		user_id int NOT NULL,
		FOREIGN KEY(user_id) REFERENCES vs_users(user_id)
		)";
	execQuery($query);

	$query="CREATE TABLE IF NOT EXISTS vs_paid_for_members (
		id int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(id),
		paid_for_user_id int,
		paid_for_user_email varchar(300) NOT NULL,
		user_id int NOT NULL,
		FOREIGN KEY(user_id) REFERENCES vs_users(user_id)
		)";
	execQuery($query);

	$query="CREATE TABLE IF NOT EXISTS vs_projects (
		project_id int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(project_id),
		project_name varchar(200) NOT NULL,
		project_description text,
		project_start_date date,
		project_end_date date,
		project_category varchar(20),
		project_folder_name varchar(400)
		)";
	execQuery($query);

	$query="CREATE TABLE IF NOT EXISTS vs_project_members (
		id int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(id),
		user_email varchar(200),
		user_id int,
		FOREIGN KEY(user_id) REFERENCES vs_users(user_id),
		user_role varchar(60) NOT NULL,
		user_status varchar(1) DEFAULT '0',
		project_id int NOT NULL,
		FOREIGN KEY(project_id) REFERENCES vs_projects(project_id)		
		)";
	execQuery($query);

	$query="CREATE TABLE IF NOT EXISTS vs_project_meta (
		meta_id int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(meta_id),
		meta_type varchar(20) NOT NULL,
		meta_value text NOT NULL,
		user_id int NOT NULL,
		FOREIGN KEY(user_id) REFERENCES vs_users(user_id),
		scene_id int NOT NULL,
		FOREIGN KEY(scene_id) REFERENCES vs_project_scenes(scene_id)
		)";
	execQuery($query);

	$query="CREATE TABLE IF NOT EXISTS vs_project_scenes (
		scene_id int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(scene_id),
		scene_name varchar(100),
		scene_date date,
		scene_image text,
		scene_status varchar(1) DEFAULT 0,
		project_id int NOT NULL,
		FOREIGN KEY(project_id) REFERENCES vs_projects(project_id)
		)";
	execQuery($query);

	$query="CREATE TABLE IF NOT EXISTS vs_notifications (
		id int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(id),
		meta_type varchar(30),
		meta_value varchar(50),
		scene_id int,
		FOREIGN KEY(scene_id) REFERENCES vs_scenes(scene_id),
		project_id int,
		FOREIGN KEY(project_id) REFERENCES vs_projects(project_id),
		user_id int,
		FOREIGN KEY(user_id) REFERENCES vs_users(user_id),
		referrer_id int NOT NULL,
		FOREIGN KEY(referrer_id) REFERENCES vs_users(user_id)
		)";
	execQuery($query);

	$query="CREATE TABLE IF NOT EXISTS vs_app_settings (
		settings_id int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(settings_id),
		settings_type varchar(30),
		settings_value varchar(30)
		)";
	execQuery($query);

	$query="CREATE TABLE IF NOT EXISTS vs_app_log (
		log_id int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(log_id),
		log_type varchar(30) NOT NULL,
		log_value varchar(300) NOT NULL,
		log_date date NOT NULL,
		user_id int NOT NULL,
		FOREIGN KEY(user_id) REFERENCES vs_users(user_id)
		)";
	execQuery($query);
}

db_schema();

?>