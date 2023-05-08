<?php 

$page_title="Notifications - VyvaSync";

include 'header.php';
include 'nav.php'; 

?>

<script type="text/javascript">
$(document).ready(function(){ getNotifications(); });
</script>

<div class="container">
	<h3 class="text-center">Notifications</h3>
	<div id="error-box" class="breadcrumb message-box error"></div>
	<div id="success-box" class="breadcrumb message-box success"></div>
	<div id="loading" class="col-xs-12 text-center">
		<img class="preloader" src="../../public/images/vyvasynclogo.png" alt="Loading.." width="75" height="75">
	</div>
</div>

<div id="notifications" class="container notifications">
	<div id="project_invites"></div>
	<div id="project_dates"></div>
</div>

<?php include 'footer.php' ?>