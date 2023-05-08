<?php

if(!isset($_GET['project_id'])){
	header('Location: dashboard.php');
}
else {
	$project_id=$_GET['project_id'];
}

if(isset($_GET['scene_id'])){
	$scene_id=$_GET['scene_id'];
}

$page_title="Storyboard";

include 'header.php';
include 'nav.php';

?>
<script type="text/javascript">
$(document).ready(function(){
	getProjectDetails('<?php echo $project_id; ?>');
	storyboard_gur('<?php echo $project_id; ?>');
	
<?php if(isset($_GET['scene_id'])){ ?>
	storyboard_details('<?php echo $scene_id; ?>');
<?php } ?>

	$('#crew_members').on('mousedown',function(event){
		event.preventDefault();
	});

	$('#crew_members').on('click',function(){
		$(this).hide();
	});
});
</script>

<div class="container">
	<ul class="breadcrumb bread-custom">
		<li><a href="dashboard.php">Dashboard</a></li>
		<li id="pr_name_link"></li>
		<?php if(!isset($_GET['scene_id'])) { ?>
		<li class="active">Add Storyboard</li>
		<?php } ?>
		<?php if(isset($_GET['scene_id'])){ ?>
		<li id="sr_name" class="active"></li>
		<?php } ?>
	</ul>
</div>

<div class="container">
	<h3 class="text-center">Storyboard</h3>
	<div id="progress-boxes"></div>
	<div id="error-box" class="breadcrumb message-box error"></div>
	<div id="success-box" class="breadcrumb message-box success"></div>
	<div id="loading" class="col-xs-12 text-center">
		<img class="preloader" src="../../public/images/vyvasynclogo.png" alt="" width="75" height="75">
	</div>
</div>

<div id="storyboard" class="container">
	<div class="col-md-7 col-sm-7 col-xs-12 storyboard">
		<div id="image_holder" class="col-md-12 col-sm-12 col-xs-12 storyboard-image"></div>
		<div id="scene_button">
			<span class="btn btn-default btn-file">
				<span class="fileinput-new">Add image</span>
				<input type="file" id="image" onchange="addScene('<?php echo $project_id; ?>')" disabled>
				<input type='hidden' id='scene_id' value='<?php if(isset($_GET['scene_id'])){ echo $scene_id; } ?>'>
			</span>
		</div>
	</div>
	<div class="col-md-5 col-sm-5 col-xs-12">
		<div id="storyboard_meta"></div>

		<div id="tagged_crew_holder" class="storyboard-crew storyboard-field-hide">
			<input id="tagged_crew" type="text" class="form-control storyboard-field storyboard-field-hide" placeholder="Tag crew members" onfocusout="hideCrewMembers()" onkeyup="viewCrewMembers(this.value,'<?php if(isset($_GET['scene_id'])){ echo $scene_id; } ?>','<?php echo $project_id; ?>')">
			<ul id="crew_members" class="storyboard-field-hide"></ul>
		</div>

		<input id="scene_date" type="text" class="form-control storyboard-field storyboard-field-hide" placeholder="Shoot date">
		<input id="scene_name" type="text" class="form-control storyboard-field storyboard-field-hide" placeholder="Scene title">
		<textarea id="scene_note" type="text" class="form-control storyboard-field storyboard-custom-margin" rows="4" placeholder="Notes"></textarea>

		<div id="commentbuttons" class="storyboard-custom-margin">
			<button onclick='saveScene()' class="btn btn-primary"> Save</button>
			<button onclick='cancelScene()' class="btn"> Delete</button>
		</div>
	</div>
</div>

<?php include 'footer.php' ?>