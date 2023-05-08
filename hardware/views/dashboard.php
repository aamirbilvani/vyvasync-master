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

    getProjectsForPi();
});
</script>
<div class="container">
    <ul class="breadcrumb">
        <li class="active"><a href="#">Dashboard</a></li>
    </ul>
	<div class="row">
        <div class="col-xs-12 col-sm-12">
            <h5 class="text-center">My Projects</h5>
            <h6 class="text-center"><i class="fa fa-calendar"></i> <span id="date"></span></h6>
            <hr>
        </div>
        <div id="loading" class="col-xs-12 text-center">
            <img class="preloader" src="../../public/images/vyvasynclogo.png" alt="Loading" width="75" height="75">
        </div>
        <div id="projects"></div>
    </div>
</div>
<?php include 'footer.php'; ?>