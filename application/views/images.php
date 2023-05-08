<style>.btn-default{color:#fff !important;}</style>
<?php

if(isset($_GET['project_id'])){
    $project_id=$_GET['project_id'];
}
else {
    header('Location: dashboard.php');
}

$page_title="Add Scenes";

include 'header.php';
include 'nav.php';

?>
<div class="container">
    <div class="col-xs-12">
        <h3 class="text-center">Add Scenes</h3>
    </div>
</div>

<div class="container">
	<div id="progress-boxes" class="col-md-12 col-sm-12 col-xs-12 text-center" style="overflow: hide"></div>
	<div id="error-box" class="breadcrumb message-box error"></div>
</div>

<div class="container" id="imagepreviews">
    <div id="images_holder" class="col-md-12 col-sm-12 col-xs-12 text-center image-upload-holder"></div>
</div>


<div class="col-md-12 col-sm-12 col-xs-12 text-center" style="margin-bottom: 10px;">
    <span class="btn btn-default btn-file"><span class="fileinput-new">Add images</span>
    <input type="file" id="images" onchange="addScenes('<?php echo $project_id; ?>')" multiple></span>
    <a href="crew.php?project_id=<?php echo $project_id; ?>"><button id="nextstep" name="nextstep" class="btn btn-primary">Done</button></a>
</div>
          
<?php include 'footer.php'; ?>