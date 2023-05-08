
<?php

if(isset($_GET['project_id'])){
	$project_id=$_GET['project_id'];
}
else {
	header('Location: dashboard.php');
}

$page_title="Project Scenes";

include 'header.php';
include 'nav.php';

?>

<script type="text/javascript">
$(document).ready(function(){

	getProjectDetails('<?php echo $project_id; ?>');

	var date = new Date();

	var day = date.getDate();
	if(day<10){
		day="0"+day;
	}

	var month=date.getMonth()+1;
	if(month<10){
		month="0"+month;
	}

	var year = date.getFullYear();

	date=month+'/'+day+'/'+year;

	$('#date').html(date);
	$('#date_small').html(date);

	$('#btn_all_scenes').trigger('click');
});
</script>

<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1 hidden-xs hidden-sm scene-details"><!-- For Large Screen Buttons -->
			<h5 class="text-center"><span id="project_name"></span></h5>
			<h6 class="text-center"><i class="fa fa-calendar"></i> <span id="date"></span></h6>
			<h6 class="text-center project-description"><span id="project_description"></span></h6>
			<h6 class="text-center">
				<a href="storyboard.php?project_id=<?php echo $project_id; ?>"><button id="sceneadd" class="btn btn-primary"><i class="fa fa-plus"></i> Add a scene</button></a>
				<button id="btn_completed_scenes" onclick="getScenes('<?php echo $project_id; ?>','completed')" class="btn btn-inverse"><i class="fa fa-check"></i> Completed scenes</button>
				<button id="btn_todays_scenes" onclick="getScenes('<?php echo $project_id; ?>','todays')" class="btn btn-inverse"><i class="fa fa-calendar"></i> Today's scenes</button>
				<button id="btn_all_scenes" onclick="getScenes('<?php echo $project_id; ?>','all')" class="btn btn-inverse"><i class="fa fa-th-list"></i> All scenes</button>
				<a href="crew.php?project_id=<?php echo $project_id; ?>"><button class="btn btn-inverse"><i class="fa fa-group"></i> Project members</button></a>
				<button onclick="generate_call_sheet('<?php echo $project_id; ?>')" class="btn btn-inverse"><i class="fa fa-phone"></i> Call Sheet</button>
			</h6>
			<hr>
		</div>

		<div class="col-xs-12 col-sm-12 hidden-md hidden-lg scene-details"><!-- For Small Screen Buttons -->
			<h5 class="text-center"><span id="project_name_small"></span></h5>
			<h6 class="text-center"><i class="fa fa-calendar"></i> <span id="date_small"></span></h6>
			<h6 class="text-center project-description-small"><span id="project_description_small"></span></h6>
			<h6 class="text-center">
				<a href="storyboard.php?project_id=<?php echo $project_id; ?>"><button class="btn btn-primary btn-circle btn-lg" data-toggle="tooltip" data-placement="top" data-original-title="Add a scene"><i class="fa fa-plus"></i></button></a>
				<button id="btn_completed_scenes" onclick="getScenes('<?php echo $project_id; ?>','completed')" class="btn btn-inverse btn-circle btn-lg" data-toggle="tooltip" data-placement="top" data-original-title="Completed scenes"><i class="fa fa-check"></i></button>
				<button id="btn_todays_scenes" onclick="getScenes('<?php echo $project_id; ?>','todays')" class="btn btn-inverse btn-circle btn-lg" data-toggle="tooltip" data-placement="top" data-original-title=" Today's scenes"><i class="fa fa-calendar"></i></button>
				<button id="btn_all_scenes" onclick="getScenes('<?php echo $project_id; ?>','all')" class="btn btn-inverse btn-circle btn-lg" data-toggle="tooltip" data-placement="top" data-original-title="All scenes"><i class="fa fa-th-list"></i></button>
				<a href="crew.php?project_id=<?php echo $project_id; ?>"><button class="btn btn-inverse btn-circle btn-lg" data-toggle="tooltip" data-placement="top" data-original-title="Project members"><i class="fa fa-group"></i></button></a>
				<button onclick="generate_call_sheet('<?php echo $project_id; ?>')" class="btn btn-inverse btn-circle btn-lg" data-toggle="tooltip" data-placement="top" data-original-title="Send Call Sheet"><i class="fa fa-phone"></i></button>
			</h6>
			<hr>
		</div>
	</div>

	<div id="error-box" class="breadcrumb message-box error"></div>
	<div id="success-box" class="breadcrumb message-box success"></div>

	<div id="loading" class="col-xs-12 text-center">
		<img class="preloader" src="../../public/images/vyvasynclogo.png" alt="" width="75" height="75">
	</div>

	<div class="scenes" id="scenes"></div>
</div>

<?php include 'footer.php' ?>