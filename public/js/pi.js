function getProjectsForPi(){

	$('#projects').hide();
	$('#loading').show();

	var date = new Date();

	date=date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();

	$.post(
		"../controller/dashboard.php",
		{
			'todays_date' : date
		},
		function (data){
			var result = JSON.parse(data);

			$('#loading').hide();
			$('#projects').show();
			
			if(result.success==true){
				$('#projects').html('');

				var rel_image;
				var user_role;
				var shots_completed;
				var shots_remaining;
				var percentage;

				for(var k=0;k<result.total;k++){

					user_role=result[k].user_role;

					if(user_role=="Director"){
						rel_image="../../public/images/director.png";

						shots_completed=result[k].project_shots_completed;

						if(shots_completed===undefined){
							shots_completed=0;
						}

						shots_remaining=result[k].project_shots_remaining;

						if(shots_remaining===undefined){
							shots_remaining=0;
						}

						percentage=((shots_completed)/(shots_completed+shots_remaining))*100;

						$('#projects').append("\
							<div class='container'>\
								<div class='col-xs-12 project-box'>\
									<div class='col-xs-8'>\
										<h4>"+result[k].project_name+"</h4>\
										<div class='progress'>\
											<div class='progress-bar' style='width: "+percentage+"%'></div>\
										</div>\
										<p class='bold'>Completed: <span class='shots-completed'>"+shots_completed+"</span> Remaining: <span class='shots-remaining'>"+shots_remaining+"</span></p>\
									</div>\
								</div>\
							</div>\
						");
					
					}
				}
			}

			if(result.success==false){
				$('#projects').html('');
				$('#projects').append("\
					<div class='col-xs-12'>\
            			<h6 class='text-center'>"+result.main_message+"</h6>\
        			</div>\
				");
			}
		}
	);
}

function getScenesForPi(project_id){

	$('#image-main').hide();
	$('#buttons').hide();
	$('#loading').show();

	var date = new Date();

	date=date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();

	$.post(
		"../controller/scene.php",
		{
			'project_id' : project_id,
			'todays_date' : date,
			'request_method' : 'get'
		},
		function (data){
			var result = JSON.parse(data);
			
			if(result.success==true){
				$('#loading').hide();
				$('#image-main').show();
				$('#buttons').show();

				$('#scene-title-link').text(result.scene_name);
				$('#scene-title').text(result.scene_name);

				$('#scene_id').val(result.scene_id);

				$('#image_holder').html("<img src='"+result.scene_image+"' alt='"+result.scene_name+"' width='100%' height='100%'>");
			}

			if(result.success==false){
				$('#loading').hide();
				$('#error-box').show();

				$('#error-box').append("\
					<div class='col-xs-12'>\
            			<h6 class='text-center'>"+result.main_message+"</h4>\
        			</div>\
				");
			}
		}
	);
}

function updateSceneStatusForPi(project_id){

	$('#image-main').hide();
	$('#buttons').hide();
	$('#loading').show();

	var scene_id = document.getElementById('scene_id').value;

	$.post(
		"../controller/scene.php",
		{
			'scene_id' : scene_id,
			'project_id' : project_id,
			'request_method' : 'toggle'
		},
		function (data){
			var result = JSON.parse(data);
			
			if(result.success==true){
				location.reload();
			}

			if(result.success==false){
				$('#loading').hide();
				$('#error-box').show();

				$('#error-box').append("\
					<div class='col-xs-12'>\
            			<h6 class='text-center'>"+result.main_message+"</h4>\
        			</div>\
				");
			}
		}
	);
}