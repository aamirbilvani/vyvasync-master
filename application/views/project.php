<?php 

$page_title="Create Project";

include 'header.php';
include 'nav.php'; 

?>
<style type="text/css">
.createpjnt { display:none; }
.createpj { display:inline; }

@media(max-width:480px){
	.createpj { display:none; }
	.createpjnt { display:inline; }
	.popover { display:none !important; }
}

.select {color:#B2BCC5;}
.select2 {color:#34495E;}
</style>

<div class="container">
	<div class="col-xs-12">
		<h3 class="text-center">Create your project</h3>
	</div>

	<div id="loading" class="col-xs-12 text-center">
		<img class="preloader" src="../../public/images/vyvasynclogo.png" alt="" width="75" height="75">
	</div>

	<div id="main_form">
		<!-- Name of the Project -->
		<div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
		   <div class="form-group" id="project_name_div">
				<input id="project_name" type="text" class="form-control" placeholder="Name your project" />
				<span class="form-control-feedback fa fa-video-camera"></span>
				<p class="errortext" id="project_name_msg"></p>
			</div>
		</div>

		<!-- Date picker -->
		<div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
			<div class="form-group" id="project_start_date_div">
				<input id="project_start_date" type="text" class="form-control" placeholder="When do you start?" />
				<span class="form-control-feedback fa fa-calendar-check-o"></span>
				<p class="errortext" id="project_start_date_msg"></p>
			</div>
		</div>

		<!-- Date picker -->
		<div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
			<div class="form-group" id="project_end_date_div">
				<input id="project_end_date" type="text" class="form-control" placeholder="What's your deadline?" />
				<span class="form-control-feedback fa fa-calendar-check-o"></span>
				<p class="errortext" id="project_end_date_msg"></p>
			</div>
		</div>

		<div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
			<div class="form-group" id="user_role_div">
				<select id="user_role" onclick="selectrolehighlight()"  class="form-control select  select-block">
					
						<option disabled="disabled" selected="selected" >What's your role in this project?</option>
						<option value="Producer">Producer</option>
						<option value="Director">Director</option>
					
				</select>
				<p class="errortext" id="user_role_msg"></p>
			</div>
		</div>

		<!-- Description -->
		<div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
			<div class="form-group" id="project_description_div">
				<textarea id="project_description" row="4" class="form-control" placeholder="Enter a description of your project"></textarea>
				<p class="errortext" id="project_description_msg"></p>
			</div>
		</div>
	</div>

	<!--Buttons --> 
	<div class="col-xs-12 text-center">
		<button id="btn-enter-primary" class="btn btn-primary" onclick="createProject()">Create project</button>
	</div>
	
</div>
		  
<?php include 'footer.php'; ?>