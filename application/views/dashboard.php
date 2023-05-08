<?php 

$page_title="Dashboard";

include 'header.php';
include 'nav.php'; 

?>

<script type="text/javascript">
$(document).ready(function(){

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
 	$('#btn_all_projects').trigger('click');
});	
	
</script>

<div class="container">
	<div class="row"> 

		<div class="col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3 hidden-xs hidden-sm">
			<h5 class="text-center">My Projects</h5>
			<h6 class="text-center"><i class="fa fa-calendar"></i> <span id="date"></span></h6>
			<h6 class="text-center">
				<a href="project.php"><button id="createproject" class="btn btn-primary"><i class="fa fa-plus"></i> Create project</button></a>
				<button id="todaysprojt" id="btn_todays_projects" onclick="getProjects('todays')" class="btn btn-inverse"><i class="fa fa-calendar"></i> Today's projects</button>
				<button id="displayallt" id="btn_all_projects" onclick="getProjects('all')" class="btn btn-inverse"><i class="fa fa-th-list"></i> All projects</button>
			</h6>
			<hr>
		</div>

		<div class="col-xs-12 col-sm-12 hidden-md hidden-lg">
			<h5 class="text-center">My Projects</h5>
			<h6 class="text-center"><i class="fa fa-calendar"></i> <span id="date"></span></h6>
			<h6 class="text-center">
				<a href="project.php"><button id="createpj" class="btn btn-primary btn-circle btn-lg createpj" data-toggle="tooltip" data-placement="top" title="" data-original-title="Create a project"><i class="fa fa-plus"></i></button></a>
				<button id="btn_todays_projects" onclick="getProjects('todays')" class="btn btn-inverse btn-circle btn-lg" data-toggle="tooltip" data-placement="top" title="" data-original-title="View today's projects"><i class="fa fa-calendar"></i></button>
				<button id="btn_all_projects" onclick="getProjects('all')" class="btn btn-inverse btn-circle btn-lg" data-toggle="tooltip" data-placement="top" title="" data-original-title="View all projects"><i class="fa fa-th-list"></i></button>
			</h6>
			<hr>
		</div>

		<div id="loading" class="col-xs-12 text-center">
			<img class="preloader" src="../../public/images/vyvasynclogo.png" alt="Loading" width="75" height="75">
		</div>

	</div>
</div>

<div class="container">
	<div id="projects"></div>
</div>

<?php include 'footer.php'; ?>