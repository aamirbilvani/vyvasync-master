<?php

if(!isset($_GET['token']) && !isset($_GET['register'])){
	header('Location: dashboard.php');
}

if(isset($_GET['register'])){
	$register=$_GET['register'];

	if($register!=true){
		header('Location: dashboard.php');
	}
}

if(isset($_GET['token'])){
	$token=$_GET['token'];
}

$page_title="Account Activation";

include 'header.php';

?>

<?php if(isset($_GET['token'])){ ?>
<script type="text/javascript">$(document).ready(activateUser('<?php echo $token; ?>'));</script>
<?php } ?>

<?php if(isset($_GET['register'])){ ?>
<script type="text/javascript">
$(document).ready(function(){
	$('#activation_image').html("<img class='img-responsive' src='../../public/images/mailicon2.png'>");
	$('#activation_text').text("We've sent you an email. Please visit the link in the email to activate your account.");
	$('#activation_button').html("<button onclick='resend_activation_link()' class='btn btn-block btn-inverse'>Resend activation link</button>");
});
</script>
<?php } ?>

<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button id="navmenu" type="button" class="navbar-toggle collapsed btn btn-info btn-embossed btn-lg" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="dashboard.php"><img src="../../public/images/vyvasynclogotype.png" width="150px" class="img-responsive"></a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
        </div>
    </div>
</nav>

<div class="container">
	<h3 class="text-center">Account activation</h3>
	<div id="loading" class="col-xs-12 text-center">
		<img class="preloader" src="../../public/images/vyvasynclogo.png" alt="" width="75" height="75">
	</div>
</div>

<div class="container">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div id="activation_image" class="col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4 col-xs-12 activation-image"></div>
		<div id="activation_text" class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12 activation-text"></div>
		<div id="activation_button" class="col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4 col-xs-12 activation-button"></div>
	</div>
</div>

<?php include 'footer.php'; ?>