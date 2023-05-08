<?php 

$page_title="Register";

include 'header.php';

?>

<div id="bg">
	<img src="../../public/images/heroimg.jpg" alt="">
</div>

<div class="container">
	<div class="row" style="padding-top: 20px">
		<div class="col-xs-11 col-sm-11 col-md-6 col-lg-6 profile-box">
			<img src="../../public/images/vyvasynclogotype.png" class="img-responsive">
			<p class="text-center">Create your VyvaSync account</p>
			<div id="loading" class="col-xs-12 text-center">
				<img class="preloader" src="../../public/images/vyvasynclogo.png" alt="" width="75" height="75">
			</div>
			<div id="main_form">
				<div class="form-group" id="user_first_name_div">
					<input class="input-lg form-control" type="text" placeholder="First name" id="user_first_name" />
					<p id="user_first_name_msg" class="errortext"></p>
				</div>
				<div class="form-group" id="user_last_name_div">
					<input class="input-lg form-control" type="text" placeholder="Last name" id="user_last_name" />
					<p id="user_last_name_msg" class="errortext"></p>
				</div>
				<div class="form-group" id="user_email_div">
					<input class="input-lg form-control" type="email" placeholder="Email address" id="user_email" />
					<p id="user_email_msg" class="errortext"></p>
				</div>
				<div class="form-group" id="user_password_one_div">
					<input class="input-lg form-control" type="password" placeholder="Password" id="user_password_one" />
				</div>
				<div class="form-group" id="user_password_two_div">
					<input class="input-lg form-control" type="password" placeholder="Re-type password" id="user_password_two" />
					<p id="user_password_msg" class="errortext"></p>
				</div>
				<div class="form-group">
                    <button id="btn-enter-primary" onclick="register()" class="btn btn-lg btn-primary btn-block">Register <i class="fa fa-sticky-note"> </i></button>
                </div>
                <div class="form-group">   
                    <a href="login.php"><button class="btn btn-lg btn-primary btn-block">Login <i class="fa fa-sign-in"> </i></button></a>
                </div>
                <p>Can't sign in? <a href="#">Reset your password</a>
			</div>
		</div> 
	</div>
</div>

<?php include 'footer.php' ?>     