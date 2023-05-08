<?php 

$page_title="Login";

include 'header.php'; 

?>
<div id="bg"><img src="../../public/images/heroimg.jpg" alt=""></div>

<div class="container">
	<div class="row" style="padding-top: 20px">
    	<div class="col-xs-11 col-sm-11 col-md-6 col-lg-6 profile-box">
        	<img src="../../public/images/vyvasynclogotype.png" class="img-responsive">
            <p class="text-center">Log in to your VyvaSync account</p>
            <hr>
            <div id="loading" class="col-xs-12 text-center">
                <img class="preloader" src="../../public/images/vyvasynclogo.png" alt="" width="75" height="75">
            </div>
            <div id="main_form">
                <div class="form-group" id="user_email_div">
                    <input id="user_email" class="input-lg form-control" type="email" placeholder="Email address" />
                </div>
                <div class="form-group" id="user_password_div">
               	    <input id="user_password" class="input-lg form-control" type="password" placeholder="Password" />
                </div>
                <p class="errortext" id="login_response"></p>
                <div class="form-group">
                    <button id="btn-enter-primary" onclick="login()" class="btn btn-lg btn-primary btn-block">Login <i class="fa fa-sign-in"> </i></button>
                </div>
                <div class="form-group">
                    <a href="register.php"><button class="btn btn-lg btn-primary btn-block">Register <i class="fa fa-sticky-note"> </i></button></a>
                </div>
                <p>Can't sign in? <a href="#">Reset your password</a></p>
            </div>
		</div> 
	</div>
</div>

<?php include 'footer.php' ?>                  