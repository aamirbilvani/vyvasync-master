<?php

if(!isset($_GET['project_id'])){
	header('Location: dashboard.php');
}
else {
	$project_id=$_GET['project_id'];
}

$page_title="Scene";

include 'header.php';
include 'nav.php';

?>
<script type="text/javascript">
$(document).ready(function(){
	getScenesForPi('<?php echo $project_id; ?>');
});
</script>

<div class="container">
	 <ul class="breadcrumb">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li class="active"><a id="scene-title-link" href="#"></a></li>
    </ul>

	<div class="container">
		<h3 id="scene-title" class="text-center"></h3>
	</div>

	<div id="error-box" class="container message-box error"></div>

	<div id="loading" class="col-xs-12 text-center">
		<img class="preloader" src="../../public/images/vyvasynclogo.png" alt="" width="75" height="75">
	</div>

	<div id="image-main" class="container" style='margin-bottom: 20px'>
		<div id="image_holder" style="padding:10px; border: 2px dashed #bdc3c7;" class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3"></div>	
	</div>

	<!-- Buttons --> 
	<div id="buttons" class="col-xs-12 text-center" style="margin-bottom:20px;">
		<input id="scene_id" type="hidden" name="scene_id" value="">
		<button onclick="updateSceneStatusForPi('<?php echo $project_id; ?>')" class='btn btn-success btn-block' style='margin-bottom:5px;'><i class='fa fa-check'></i> Complete</button>
	</div>

</div>

<?php include 'footer.php' ?>