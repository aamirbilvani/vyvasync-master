<?php 

$page_title="Change Password";

include 'header.php';
include 'nav.php'; 

?>

<div class="container">
	<h3 class="text-center">Change Password</h3>
	<div id="progress-boxes"></div>
	<div id="error-box" class="breadcrumb message-box error"></div>
	<div id="success-box" class="breadcrumb message-box success"></div>
	<div id="loading" class="col-xs-12 text-center">
		<img class="preloader" src="../../public/images/vyvasynclogo.png" alt="" width="75" height="75">
	</div>
</div>

<div class="container">
	<div id="main_form" class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12">
		<div class="form-group">
			<input class="input-md form-control" type="password" placeholder="Current password" id="current_password" required/>
		</div>
		<div class="form-group">
			<input class="input-md form-control" type="password" placeholder="Password" id="password" required/>
		</div>
		<div class="form-group">
			<input class="input-md form-control" type="password" placeholder="Repeat password" id="repeat_password" required/>
		</div>
		<div class="form-group">
            <button id="btn-enter-primary" onclick="changePassword()" class="btn btn-md btn-primary btn-block">Change password</button>
        </div>
    </div>     
</div>

<?php include 'footer.php'; ?>