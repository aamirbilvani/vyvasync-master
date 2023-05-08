
<?php

if(isset($_GET['project_id'])){
	$project_id=$_GET['project_id'];
}
else {
	header('Location: dashboard.php');
}

$page_title="Project Members";

include 'header.php';
include 'nav.php';

?>

<script type="text/javascript">
$(document).ready(function(){
	getProjectDetails('<?php echo $project_id; ?>');
	getCrew('<?php echo $project_id; ?>');
});
</script>

<style type="text/css">
#memberstype li { transition: all 0.3s; padding: 3%; }
#memberstype li:hover { background-color:#1d1d1d; color:#fff; cursor:pointer; }
.others_div { display:none; }	
</style>

<div class="container">
	<ul class="breadcrumb bread-custom">
		<li><a href="dashboard.php">Dashboard</a></li>
		<li id="pr_name_link"></li>
		<li class="active">Project Members</li>
	</ul>
</div>

<div class="container">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<h3 class="text-center">Project Members</h3>
	</div>
</div>

<div class="container">
	<div id="error-box" class="breadcrumb message-box error"></div>
	<div id="success-box" class="breadcrumb message-box success"></div>
</div>

<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3 col-sm-offset-3">
			<div id="user_email_div" class="crew-field-holder">
				<input type="email" id="user_email" class="form-control crew-field" placeholder="Email address">
				<input id="custom_role" type="text" class="form-control crew-field crew-field-hide" placeholder="Custom role">
			</div>
				  
			<div class="dropdown">
				<button type="button" id="rolebutton" class="btn dropdown-toggle" data-toggle="dropdown">Select role<span class="caret"></span></button>
				<button type="button" id="invitemembers" onclick="addCrew('<?php echo $project_id; ?>')" class="btn btn-primary">Invite</button>
				<input type="hidden" id="roletype" value="">
			   
				<ul id="memberstype" class="dropdown-menu">
					<li id="Producer" onclick="selectedvalue(this.id)">Producer</li>
					<li id="Director" onclick="selectedvalue(this.id)">Director</li>
					<li id="Assistant Producer" onclick="selectedvalue(this.id)">Assistant Producer</li>
					<li id="Assistant Director" onclick="selectedvalue(this.id)">Assistant Director</li>
					<li id="Cameraman" onclick="selectedvalue(this.id)">Cameraman</li>
					<li id="Art Department" onclick="selectedvalue(this.id)">Art Department</li>
					<li id="Light Department" onclick="selectedvalue(this.id)">Light Department</li>
					<li id="Cast" onclick="selectedvalue(this.id)">Cast</li>
					<li id="Custom" onclick="selectedvalue(this.id)">Custom</li>
				</ul>
			</div>
		</div>
	</div>
	<hr>
</div>

<div class="container">
	<div id="loading" class="col-md-12 col-sm-12 col-xs-12 text-center">
		<img class="preloader" src="../../public/images/vyvasynclogo.png" alt="" width="75" height="75">
	</div>
</div>

<div id="crew_holder" class="container">
	<div id="main_holder" class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Name</th>
					<th>Role</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody id="crew"></tbody>
		</table>
	</div>
</div>

<?php include 'footer.php'; ?>