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
        $('#activation-message').html("Account Activated");
    });
</script>
<?php } ?>



<style type="text/css">

    @media (max-width:480px)
        {
            .resend
            {
                width:80% !important;
                
            }
            .mailimg{width:50% !important;}
        }
    
    .resend{width:50% ;}

</style>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed btn btn-info btn-embossed btn-lg" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="dashboard.php"><img src="../../public/images/vyvasynclogotype.png" width="150px" class="img-responsive"></a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right"></ul>
        </div>
    </div>
</nav>

<div class="container">

    <br>  <br> <br>     
     <div class="row center-block">
        <div class="col-xs-12 text-center">
           
          <span class="glyphicon glyphicon-ok" style="color:green;font-size:25vw;cursor:auto;"></span>
        
        </div>
    </div>
      <br>
	<div class="row center-block">
        <div class="col-xs-12">
            <h6 id="activation-message" class="text-center"></h6>
        </div>
    </div>
     <br> 
    <div class="row center-block">
        <div class="resend center-block " >
      <div class="form-group">
                    <button id="btn-enter-primary"  class="btn btn- btn-inverse btn-block"> Continue </button>
                </div>
        </div>
    </div>
   
</div>
<?php include 'footer.php'; ?>