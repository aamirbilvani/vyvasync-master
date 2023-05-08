function register(){

	$('#main_form').hide();
	$('#loading').show();

	var user_first_name = document.getElementById('user_first_name').value;
	var user_last_name = document.getElementById('user_last_name').value;
	var user_email = document.getElementById('user_email').value;
	var user_password_one = document.getElementById('user_password_one').value;
	var user_password_two = document.getElementById('user_password_two').value;

	$.post(
		"../controller/register.php",
		{
			'user_first_name' : user_first_name,
			'user_last_name' : user_last_name,
			'user_email' : user_email,
			'user_password_one' : user_password_one,
			'user_password_two' : user_password_two
		},
		function (data){
			var result = JSON.parse(data);

			if(result.success==true){
				window.location="/application/views/activate.php?register=true";
			}
			
			if(result.success==false){
				$('#loading').hide();
				$('#main_form').show();

				if(result.user_first_name){
					$('#user_first_name_div').removeClass('has-success');
					$('#user_first_name_div').addClass('has-error');
					$('#user_first_name_msg').css('display','block');
					$('#user_first_name_msg').text(result.user_first_name);
				}
				else {
					$('#user_first_name_div').removeClass('has-error');
					$('#user_first_name_div').addClass('has-success');
					$('#user_first_name_msg').css('display','none');
				}

				if(result.user_last_name){
					$('#user_last_name_div').removeClass('has-success');
					$('#user_last_name_div').addClass('has-error');
					$('#user_last_name_msg').css('display','block');
					$('#user_last_name_msg').text(result.user_last_name);
				}
				else {
					$('#user_last_name_div').removeClass('has-error');
					$('#user_last_name_div').addClass('has-success');
					$('#user_last_name_msg').css('display','none');
				}

				if(result.user_email){
					$('#user_email_div').removeClass('has-success');
					$('#user_email_div').addClass('has-error');
					$('#user_email_msg').css('display','block');
					$('#user_email_msg').text(result.user_email);
				}
				else {
					$('#user_email_div').removeClass('has-error');
					$('#user_email_div').addClass('has-success');
					$('#user_email_msg').css('display','none');
				}

				if(result.user_password){
					$('#user_password_one_div').removeClass('has-success');
					$('#user_password_one_div').addClass('has-error');
					$('#user_password_two_div').removeClass('has-success');
					$('#user_password_two_div').addClass('has-error');
					$('#user_password_msg').css('display','block');
					$('#user_password_msg').text(result.user_password);
				}
				else {
					$('#user_password_one_div').removeClass('has-error');
					$('#user_password_one_div').addClass('has-success');
					$('#user_password_two_div').removeClass('has-error');
					$('#user_password_two_div').addClass('has-success');
					$('#user_password_msg').css('display','none');
				}
			}
		}
	);
}

function login(){

	$('#main_form').hide();
	$('#loading').show();

	var user_email = document.getElementById('user_email').value;
	var user_password = document.getElementById('user_password').value;

	$.post(
		"../controller/login.php",
		{
			'user_email' : user_email,
			'user_password' : user_password
		},
		function (data){
			var result = JSON.parse(data);
			
			if(result.success==true){
				window.location="/application/views/dashboard.php";
			}
			
			if(result.success==false && result.redirect_activate==false){
				$('#loading').hide();
				$('#main_form').show();

				if(result.main_message){
					$('#user_email_div').removeClass('has-success');
					$('#user_email_div').addClass('has-error');
					$('#user_password_div').removeClass('has-success');
					$('#user_password_div').addClass('has-error');
					$('#login_response').css('display','block');
					$('#login_response').text(result.main_message);
				}
				else {
					$('#user_email_div').removeClass('has-error');
					$('#user_email_div').addClass('has-success');
					$('#user_password_div').removeClass('has-error');
					$('#user_password_div').addClass('has-success');
					$('#login_response').css('display','none');
				}
			}

			if(result.success==false && result.redirect_activate==true){
				window.location="/application/views/activate.php?register=true&user_id="+result.user_id;
			}
		}
	);
}


function selectrolehighlight()
{
	 $('#user_role').removeClass("select").addClass("select2");
}



function createProject(){
	
	$('#btn-enter-primary').hide();
	$('#main_form').hide();
	$('#loading').show();

	var project_name = document.getElementById('project_name').value;
	var project_description = document.getElementById('project_description').value;
	var project_start_date = document.getElementById('project_start_date').value;
	var project_end_date = document.getElementById('project_end_date').value;
	var user_role = document.getElementById('user_role').value;

	$.post(
		"../controller/project.php",
		{
			'project_name' : project_name,
			'project_description' : project_description,
			'project_start_date' : project_start_date,
			'project_end_date' : project_end_date,
			'user_role' : user_role
		},
		function (data){ 
			var result = JSON.parse(data);
			
			if(result.success==true){
				if(user_role=="Director"){
					window.location="/application/views/images.php?project_id="+result.project_id;
				}
				else if(user_role=="Producer"){
					window.location="/application/views/crew.php?project_id="+result.project_id;
				}
			}

			if(result.success==false){
				$('#loading').hide();
				$('#main_form').show();
				$('#btn-enter-primary').show();
				if(result.project_name){
					$('#project_name_div').removeClass('has-success');
					$('#project_name_div').addClass('has-error');
					$('#project_name_msg').css('display','block');
					$('#project_name_msg').text(result.project_name);
				}
				else {
					$('#project_name_div').removeClass('has-error');
					$('#project_name_msg').css('display','none');
				}

				if(result.project_start_date){
					$('#project_start_date_div').removeClass('has-success');
					$('#project_start_date_div').addClass('has-error');
					$('#project_start_date_msg').css('display','block');
					$('#project_start_date_msg').text(result.project_start_date);
				}
				else {
					$('#project_start_date_div').removeClass('has-error');
					$('#project_start_date_msg').css('display','none');
				}

				if(result.project_end_date){
					$('#project_end_date_div').removeClass('has-success');
					$('#project_end_date_div').addClass('has-error');
					$('#project_end_date_msg').css('display','block');
					$('#project_end_date_msg').text(result.project_end_date);
				}
				else {
					$('#project_end_date_div').removeClass('has-error');
					$('#project_end_date_msg').css('display','none');
				}

				if(result.project_description){
					$('#project_description_div').removeClass('has-success');
					$('#project_description_div').addClass('has-error');
					$('#project_description_msg').css('display','block');
					$('#project_description_msg').text(result.project_description);
				}
				else {
					$('#project_description_div').removeClass('has-error');
					$('#project_description_msg').css('display','none');
				}

				if(result.user_role){
					$('#user_role_div').removeClass('has-success');
					$('#user_role_div').addClass('has-error');
					$('#user_role_msg').css('display','block');
					$('#user_role_msg').text(result.user_role);
					$('#user_role').css("border","2px solid #E74C3C");
				}
				else {
					$('#user_role_div').removeClass('has-error');
					$('#user_role_msg').css('display','none');
				}
			}
		}
	);
}

function addScenes(project_id){

	var images = document.getElementById('images').files;
	var limit = images.length;
	$('#imagepreviews').css("width","90%"); 
	$('#error-box').html('');
	$('#error-box').hide();

	for(var i=0; i<limit; i++){
		var imageName = document.getElementById('images').files[i].name;
		var fileSize=document.getElementById('images').files[i].size;

		if(fileSize>5000000){
			$('#error-box').show();
			$('#error-box').append("<p><b>"+imageName+"</b> exceeds upload limit of <b>5MB</b></p>");
		}
		else {
			addScenes_supp(project_id,i,limit);
		}
	}
}

function addScenes_supp(project_id,i,limit){
	var images = document.getElementById('images').files;
	var imageName = document.getElementById('images').files[i].name;

	console.log(imageName.length);
	if(imageName.length>18){
		imageName = imageName.substring(0,18);
		imageName = imageName+'...';
	}
	console.log(imageName);

	var storyboard = new FormData();
	storyboard.append('project_id',project_id);
	storyboard.append('storyboard',images[i]);

	$.ajax({
		url: "../controller/uploader.php",
		type: "POST",
		data: storyboard,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$('#progress-boxes').append("\
				<div id='progress-box-"+i+"' class='container progress-container'>\
					<p class='text-center progress-text'>Uploading <b>"+imageName+"</b></p>\
					<div class='progress'>\
						<div id='progress-status-"+i+"' class='progress-bar' role='progressbar' aria-valuemin='0' aria-valuemax='100'>\
							<span id='progress-span-"+i+"' class='sr-only'></span>\
						</div>\
					</div>\
				</div>\
			");
			
			if(i==0){
				$('#images').prop('disabled',true);
				$('#nextstep').prop('disabled',true);
			}
		},
		xhr: function(){
			var xhr = new window.XMLHttpRequest();
			xhr.upload.addEventListener("progress",function(e){
				if(e.lengthComputable){
					var percentComplete = (e.loaded / e.total) * 100;
					$('#progress-status-'+i).width(percentComplete+'%');
					$('#progress-span-'+i).html(percentComplete+'%');
				}
			},false);

			return xhr;
		},
		success: function(data){
			var result = JSON.parse(data);
			if(result.success==true){
				$('#images_holder').append("\
					<div id='scene-"+result.scene_id+"' class='col-md-4 col-sm-4 col-xs-12'>\
						<div class='col-md-12 col-sm-12 col-xs-12 scene-box'>\
							<img src='"+result.image_url+"' class='img-responsive scene-height-custom'>\
							<button onclick='removeScene("+result.scene_id+")' class='close'></button>\
						</div>\
					</div>\
				");
			}
			if(result.success==false){
				$('#error-box').show();
				$('#error-box').append("<p>"+result.main_message+"</p>");
			}
		},
		complete: function(){
			$('#progress-box-'+i).remove();
			
			if(i==(limit-1)){
				$('#images').prop('disabled',false);
				$('#nextstep').prop('disabled',false);
			}
		}
	});
}

function addScene(project_id){

	var image = document.getElementById('image').files;
	var imageName = document.getElementById('image').files[0].name;

	var storyboard = new FormData();
	storyboard.append('project_id',project_id);
	storyboard.append('storyboard',image[0]);

	$('#error-box').html('');
	$('#error-box').hide();

	$.ajax({
		url: "../controller/uploader.php",
		type: "POST",
		data: storyboard,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$('#progress-boxes').append("\
				<div id='progress-box' class='container progress-container'>\
					<p class='text-center progress-text'>Uploading <b>"+imageName+"</b></p>\
					<div class='progress'>\
						<div id='progress-status' class='progress-bar' role='progressbar' aria-valuemin='0' aria-valuemax='100'>\
							<span id='progress-span' class='sr-only'></span>\
						</div>\
					</div>\
				</div>\
			");
		},
		xhr: function(){
			var xhr = new window.XMLHttpRequest();
			xhr.upload.addEventListener("progress",function(e){
				if(e.lengthComputable){
					var percentComplete = (e.loaded / e.total) * 100;
					$('#progress-status').width(percentComplete+'%');
					$('#progress-span').html(percentComplete+'%');
				}
			},false);

			return xhr;
		},
		success: function(data){
			var result = JSON.parse(data);

			if(result.success==true){
				$('#error-box').hide();
				$('#image_holder').append("<img src='"+result.image_url+"' width='100%' height='100%'>");
				$('#scene_button').html("\
					<button id='btn_remove' class='btn btn-default btn-file' onclick='removeSceneCustom("+project_id+","+result.scene_id+")'>Remove</button>\
					<input type='hidden' id='scene_id' value='"+result.scene_id+"'>\
				");
			}
			if(result.success==false){
				$('#error-box').show();
				$('#error-box').append("<p>"+result.main_message+"</p>");
			}
		},
		complete: function(){
			$('#progress-box').remove();
		}
	});
}

function removeScene(scene_id){
	$.post(
		"../controller/remover.php",
		{
			'scene_id' : scene_id
		},
		function (data){
			var result = JSON.parse(data);

			if(result.success==true){
				$("#scene-"+scene_id).remove();
			}

			if(result.success==false){
				console.log(result.main_message);
			}
		}
	);
}

function removeSceneCustom(project_id,scene_id){
	$.post(
		"../controller/remover.php",
		{
			'scene_id' : scene_id
		},
		function (data){
			var result = JSON.parse(data);

			if(result.success==true){
				$('#error_holder').hide();
				$('#image_holder').html('');
				$('#scene_button').html("\
					<span class='btn btn-default btn-file'>\
						<span class='fileinput-new'>Add image</span>\
						<input type='file' id='image' onchange='addScene("+project_id+")'>\
						<input type='hidden' id='scene_id' value=''>\
					</span>\
				");
			}

			if(result.success==false){
				console.log(result.main_message);
			}
		}
	);
}

function saveScene(){

	$('#error-box').hide();
	$('#success-box').hide();
	$('#storyboard').hide();
	$('#loading').show();
	
	var scene_id = document.getElementById('scene_id').value;

	if(scene_id==""){
		$('#loading').hide();
		$('#error-box').show();
		$('#error-box').html('<p>Please upload storyboard image</p>');
		$('#storyboard').show();
	}
	else {

		$('#error-box').hide();

		var scene_name = document.getElementById('scene_name').value;
		var scene_date = document.getElementById('scene_date').value;
		var scene_note = document.getElementById('scene_note').value;

		$.post(
			"../controller/storyboard.php",
			{
				'scene_id' : scene_id,
				'scene_name' : scene_name,
				'scene_date' : scene_date,
				'scene_note' : scene_note
			},
			function (data){

				$('#loading').hide();
				$('#storyboard').show();

				var result = JSON.parse(data);
			
				if(result.success==true){
					$('#error-box').html('');
					$('#error-box').hide();
					$('#success-box').show();
					$('#success-box').html(result.main_message);
				}

				if(result.success==false){
					$('#error-box').show();
					$('#error-box').html(result.main_message);
				}
			}
		);
	}
}

function cancelScene(){

	var scene_id = $('#scene_id').val();

	if(scene_id===undefined){
		window.location="dashboard.php";
	}
	else {
		$.post(
			"../controller/remover.php",
			{
				'scene_id' : scene_id
			},
			function (data){
				var result = JSON.parse(data);

				if(result.success==true){
					window.location="dashboard.php";
				}

				if(result.success==false){
					console.log(result.main_message);
				}
			}
		);	
	}
}

function addCrew(project_id){
	var user_role = document.getElementById('roletype').value;
	var user_email=document.getElementById('user_email').value;
	
	if(user_role=="Custom"){
		user_role = document.getElementById('custom_role').value;
	}

	$('#crew_holder').hide();
	$('#loading').show();
	
	$.post(
		"../controller/crew.php",
		{
			'user_email' : user_email,
			'user_role' : user_role,
			'project_id' : project_id,
			'request_method' : 'post'
		},
		function (data){

			var result = JSON.parse(data);

			if(result.success==true){
				$('#loading').hide();
				$('#crew_holder').show();

				$('#user_email').val('');
				$('#custom_role').val('');
				$('#error-box').hide();
				getCrew(project_id);
			}

			if(result.success==false){
				$('#loading').hide();
				$('#crew_holder').show();
				$('#error-box').show();
				$('#error-box').text(result.main_message);
			}
		}
	);
}

function removeCrew(tr_id,user_email,project_id){
	$.post(
		"../controller/crew.php",
		{
			'user_email' : user_email,
			'project_id' : project_id,
			'request_method' : 'remove'
		},
		function (data){
			var result = JSON.parse(data);
			
			if(result.success==true){
				$('#'+tr_id).remove();
			}

			if(result.success==false){
				console.log(result.main_message);
			}
		}
	);
}

function getCrew(project_id){

	$('#crew').html('');
	$('#crew_holder').hide();
	$('#loading').show();

	$.post(
		"../controller/crew.php",
		{
			'project_id' : project_id,
			'request_method': 'get'
		},
		function (data){

			var result = JSON.parse(data);

			if(result.success==true){

				$('#loading').hide();
				$('#crew_holder').show();

				var count;
				for(var k=0;k<result.users.length;k++){
					count=k+1;

					var role = result.users[k].role;
					var role_class;

					if(role=="Producer"){
						role_class="label-producer"; 
					}
					else if(role=="Assistant Producer"){
						role_class="label-ast-pro"; 
					}
					else if(role=="Director"){
						role_class="label-director";
					}
					else if(role=="Assistant Director"){
						role_class="label-ast-dir";
					}
					else if(role=="Cameraman"){
						role_class="label-camera";
					}
					else if(role=="Art Department"){
						role_class="label-art";
					}
					else if(role=="Light Department"){
						role_class="label-light";
					}
					else if(role=="Cast"){
						role_class="label-cast";
					}
					else {
						role_class="label-custom";
					}

					var user_role=getUserRole(project_id);

					if((user_role=="Producer" || user_role=="Director") && user_role!=role && role!="Producer"){
						var row_id='crew-'+count;

						$('#crew').append("\
							<tr id='"+row_id+"'>\
								<td>"+result.users[k].meta+" <button onclick=\"removeCrew('"+row_id+"','"+result.users[k].user_email+"','"+project_id+"')\" type='button' class='btn btn-xs btn-danger btn-embossed'><i class='fa fa-times'></i> Remove</button></td>\
								<td><span class='label "+role_class+"'>"+result.users[k].role+"</span></td>\
								<td>"+result.users[k].user_status+"</td>\
								</tr>\
							");
					}
					else {
						$('#crew').append("\
							<tr>\
								<td>"+result.users[k].meta+"</td>\
								<td><span class='label "+role_class+"'>"+result.users[k].role+"</span></td>\
								<td>"+result.users[k].user_status+"</td>\
							</tr>\
						");
					}
				}
			}

			if(result.success==false){
				$('#loading').hide();
				$('#crew_holder').show();

				console.log(result.main_message);
			}
		}
	);
}

function getUserRole(project_id){
	var user_role;

	$.ajaxSetup({async:false});

	$.post(
		"../controller/users.php",
		{
			'project_id' : project_id,
			'request_method' : 'user_role'
		},
		function (data){
			var result = JSON.parse(data);
			
			if(result.success==true){
				user_role=result.user_role;
			}

			if(result.success==false){
				user_role=false;
			}
		}
	);

	$.ajaxSetup({async:true});

	return user_role;
}

function getProjects(request_method){

	$('#projects').hide();
	$('#loading').show();

	var date = new Date();

	date=date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();

	$.post(
		"../controller/dashboard.php",
		{
			'todays_date' : date,
			'request_method' : request_method,
			dataType:"text"
		},
		function (data){
			
			var result = JSON.parse(data);
			console.log(result);
			$('#loading').hide();
			$('#projects').show();
			
			if(result.success==true){
				$('#projects').html('');

				var rel_image;
				var user_role;
				var user_role_tag;
				var shots_completed;
				var shots_remaining;
				var percentage;

				for(var k=0;k<result.total;k++){

					user_role=result[k].user_role;

					if(user_role=="Producer"){
						rel_image="../../public/images/producer.png";
						user_role_tag="background-color: #c0392b";
					}
					else if(user_role=="Director"){
						rel_image="../../public/images/director.png";
						user_role_tag="background-color: #8e44ad";
					}
					else if(user_role=="Assistant Producer"){
						rel_image="../../public/images/asst-prod.png";
						user_role_tag="background-color: #27ae60";
					}
					else if(user_role=="Assistant Director"){
						rel_image="../../public/images/asst-dir.png";
						user_role_tag="background-color: #16a085";
					}
					else if(user_role=="Art Department"){
						rel_image="../../public/images/art-director.png";
						user_role_tag="background-color: #f39c12";
					}
					else if(user_role=="Light Department"){
						rel_image="../../public/images/light.png";
						user_role_tag="background-color: #ef07cd";
					}
					else if(user_role=="Cameraman"){
						rel_image="../../public/images/camera.png";
						user_role_tag="background-color: #e67e22";
					}
					else if(user_role=="Cast"){
						rel_image="../../public/images/cast.png";
						user_role_tag="background-color: #012bba";
					}
					else {
						rel_image="../../public/images/custom.png";
						user_role_tag="background-color: #aaa";
					}

					shots_completed=result[k].project_shots_completed;

					if(shots_completed===undefined){
						shots_completed=0;
					}

					shots_completed=parseInt(shots_completed);

					shots_remaining=result[k].project_shots_remaining;

					if(shots_remaining===undefined){
						shots_remaining=0;
					}

					shots_remaining=parseInt(shots_remaining);

					percentage=((shots_completed)/(shots_completed+shots_remaining))*100;

					$('#projects').append("\
						<div class='col-md-12 col-sm-12 col-xs-12 project-box'>\
							<div class='col-md-4 col-sm-4 hidden-xs'>\
								<img src='"+rel_image+"' class='img-responsive'>\
							</div>\
							<div class='col-xs-12 hidden-lg hidden-md hidden-sm project-role' style='"+user_role_tag+"'>"+user_role+"</div>\
							<div class='col-md-8 col-sm-8 col-xs-12'>\
								<h4>"+result[k].project_name+"</h4>\
								<h5 class='project-description'>"+result[k].project_description+"</h5>\
								<p class='project-date'>Start date: <span class='shots-bold'>"+result[k].project_start_date+"</span></p>\
								<p class='project-date project-date-custom'>End date: <span class='shots-bold'>"+result[k].project_end_date+"</span></p>\
								<div class='progress'>\
									<div class='progress-bar' style='width: "+percentage+"%'></div>\
								</div>\
								<p class='shots-meta'>Scenes completed: <span class='shots-bold'>"+shots_completed+"</span></p>\
								<p class='shots-meta shots-meta-custom'>Scenes remaining: <span class='shots-bold'>"+shots_remaining+"</span></p>\
								<div class='hidden-xs'>\
									<a href='scenes.php?project_id="+result[k].project_id+"'><button class='btn btn-inverse btn-sm'><i class='fa fa-th-list'></i> Project Scenes</button></a>\
									<a href='crew.php?project_id="+result[k].project_id+"'><button class='btn btn-inverse btn-sm'><i class='fa fa-group'></i> Project Members</button></a>\
									<span id='delete-large-"+result[k].project_id+"'></span>\
								</div>\
								<div class='col-xs-12 hidden-sm hidden-md hidden-lg'>\
									<h6 class='text-center'>\
										<a href='scenes.php?project_id="+result[k].project_id+"'><button class='btn btn-inverse btn-circle btn-lg'><i class='fa fa-th-list'></i></button></a>\
										<a href='crew.php?project_id="+result[k].project_id+"'><button class='btn btn-inverse btn-circle btn-lg'><i class='fa fa-group'></i></button></a>\
										<span id='delete-small-"+result[k].project_id+"'></span>\
									</h6>\
								</div>\
							</div>\
						</div>\
					");

					if(user_role=="Director" || user_role=="Producer"){
						$('#delete-large-'+result[k].project_id).append("<button onclick=\"deleteProject('"+result[k].project_id+"')\" class='btn btn-sm'><i class='fa fa-remove'></i> Delete</button>");
						$('#delete-small-'+result[k].project_id).append("<button onclick=\"deleteProject('"+result[k].project_id+"')\" class='btn btn-circle btn-lg'><i class='fa fa-remove'></i></button>");
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

function deleteProject(project_id){
	$.post(
		"../controller/dashboard.php",
		{
			'project_id' : project_id,
			'request_method' : 'delete'
		},
		function (data){
			var result = JSON.parse(data);
			
			if(result.success==true){
				getProjects('all');
			}

			if(result.success==false){
				console.log(result.main_message);
			}
		}
	);
}

function getScenes(project_id,request_method){

	$('#scenes').hide();
	$('#loading').show();

	var date = new Date();

	date=date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();

	$.post(
		"../controller/scene.php",
		{
			'project_id' : project_id,
			'todays_date' : date,
			'request_method' : request_method
		},
		function (data){
			var result = JSON.parse(data);
			
			if(result.success==true){
				$('#scenes').html('');

				$('#loading').hide();
				$('#scenes').show();

				for(var k=0;k<result.total;k++){
					if(result.user_role=='Director' && result[k].scene_status=='0'){
						$('#scenes').append("\
							<div class='col-md-4 col-sm-4 col-xs-12'>\
								<div class='col-md-12 col-sm-12 col-xs-12 scene-box'>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<img src='"+result[k].scene_image+"' class='img-responsive scene-height-custom'>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<h4>"+result[k].scene_name+"</h4>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<h3>Shoot date: <span class='shots-bold'>"+result[k].scene_date+"</span></h3>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<h3>Total notes: <span class='shots-bold'>"+result[k].total_scene_notes+"</span></h3>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<button id='completescene' onclick='updateSceneStatus("+result[k].scene_id+")' class='btn btn-success btn-block hidden-sm hidden-xs completescene'><i class='fa fa-check'></i> Complete</button>\
										<button onclick='viewStoryboard("+project_id+","+result[k].scene_id+")' class='btn btn-primary btn-block hidden-sm hidden-xs'><i class='fa fa-edit'></i> Notes</button>\
										<button onclick='deleteScene("+result[k].scene_id+")' class='btn btn-disabled btn-block hidden-sm hidden-xs'><i class='fa fa-times'></i> Delete</button>\
										<h6 class='text-center'>\
											<button onclick='updateSceneStatus("+result[k].scene_id+")' class='btn btn-success btn-circle btn-lg hidden-md hidden-lg'><i class='fa fa-check'></i></button>\
											<button onclick='viewStoryboard("+project_id+","+result[k].scene_id+")' class='btn btn-primary btn-circle btn-lg hidden-md hidden-lg'><i class='fa fa-edit'></i></button>\
											<button onclick='deleteScene("+result[k].scene_id+")' class='btn btn-disabled btn-circle btn-lg hidden-md hidden-lg'><i class='fa fa-times'></i></button>\
										</h6>\
									</div>\
								</div>\
							</div>\
						");
					}
					else if(result.user_role=='Director' && result[k].scene_status=='1'){
						$('#scenes').append("\
							<div class='col-md-4 col-sm-4 col-xs-12'>\
								<div class='col-md-12 col-sm-12 col-xs-12 scene-box'>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<img src='"+result[k].scene_image+"' class='img-responsive scene-height-custom'>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<h4>"+result[k].scene_name+"</h4>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<h3>Completed on: "+result[k].scene_date+"</h3>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<h3>Total notes: <span class='shots-bold'>"+result[k].total_scene_notes+"</span></h3>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<button onclick='updateSceneStatus("+result[k].scene_id+")' class='btn btn-success btn-block hidden-sm hidden-xs'><i class='fa fa-check'></i> Redo</button>\
										<button onclick='viewStoryboard("+project_id+","+result[k].scene_id+")' class='btn btn-primary btn-block hidden-sm hidden-xs'><i class='fa fa-edit'></i> Notes</button>\
										<button onclick='deleteScene("+result[k].scene_id+")' class='btn btn-disabled btn-block hidden-sm hidden-xs'><i class='fa fa-times'></i> Delete</button>\
										<h6 class='text-center'>\
											<button onclick='updateSceneStatus("+result[k].scene_id+")' class='btn btn-success btn-circle btn-lg hidden-md hidden-lg'><i class='fa fa-check'></i></button>\
											<button onclick='viewStoryboard("+project_id+","+result[k].scene_id+")' class='btn btn-primary btn-circle btn-lg hidden-md hidden-lg'><i class='fa fa-edit'></i></button>\
											<button onclick='deleteScene("+result[k].scene_id+")' class='btn btn-disabled btn-circle btn-lg hidden-md hidden-lg'><i class='fa fa-times'></i></button>\
										</h6>\
									</div>\
								</div>\
							</div>\
						");
					}
					else if(result.user_role!='Director' && result[k].scene_status=='0'){
						$('#scenes').append("\
							<div class='col-md-4 col-sm-4 col-xs-12'>\
								<div class='col-md-12 col-sm-12 col-xs-12 scene-box'>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<img src='"+result[k].scene_image+"' class='img-responsive scene-height-custom'>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<h4>"+result[k].scene_name+"</h4>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<h3>Shoot date: "+result[k].scene_date+"</h3>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<h3>Total notes: <span class='shots-bold'>"+result[k].total_scene_notes+"</span></h3>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<button onclick='viewStoryboard("+project_id+","+result[k].scene_id+")' class='btn btn-primary btn-block hidden-sm hidden-xs'><i class='fa fa-edit'></i> Notes</button>\
										<h6 class='text-center'>\
											<button onclick='viewStoryboard("+project_id+","+result[k].scene_id+")' class='btn btn-primary btn-circle btn-lg hidden-md hidden-lg'><i class='fa fa-edit'></i></button>\
										</h6>\
									</div>\
								</div>\
							</div>\
						");
					}
					else if(result.user_role!='Director' && result[k].scene_status=='1'){
						$('#scenes').append("\
							<div class='col-md-4 col-sm-4 col-xs-12'>\
								<div class='col-md-12 col-sm-12 col-xs-12 scene-box'>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<img src='"+result[k].scene_image+"' class='img-responsive scene-height-custom'>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<h4>"+result[k].scene_name+"</h4>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<h3>Completed on: "+result[k].scene_date+"</h3>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<h3>Total notes: <span class='shots-bold'>"+result[k].total_scene_notes+"</span></h3>\
									</div>\
									<div class='col-md-12 col-sm-12 col-xs-12'>\
										<button onclick='viewStoryboard("+project_id+","+result[k].scene_id+")' class='btn btn-primary btn-block hidden-sm hidden-xs'><i class='fa fa-edit'></i> Notes</button>\
										<h6 class='text-center'>\
											<button onclick='viewStoryboard("+project_id+","+result[k].scene_id+")' class='btn btn-primary btn-circle btn-lg hidden-md hidden-lg'><i class='fa fa-edit'></i></button>\
										</h6>\
									</div>\
								</div>\
							</div>\
						");
					}
				}
			}

			if(result.success==false){
				$('#scenes').html('');

				$('#loading').hide();
				$('#scenes').show();

				$('#scenes').append("\
					<div class='col-xs-12'>\
						<h6 class='text-center'>"+result.main_message+"</h4>\
					</div>\
				");
			}
		}
	);
}

function updateSceneStatus(scene_id){
	$.post(
		"../controller/scene.php",
		{
			'scene_id' : scene_id,
			'request_method' : 'toggle'
		},
		function (data){
			var result = JSON.parse(data);
			
			if(result.success==true){
				if(result.scene_status=='0'){
					$('#btn_all_scenes').trigger('click');
				}
				else if(result.scene_status=='1'){
					$('#btn_completed_scenes').trigger('click');
				}
			}

			if(result.success==false){
				console.log(result.main_message);
			}
		}
	);
}

function deleteScene(scene_id){
	$.post(
		"../controller/scene.php",
		{
			'scene_id' : scene_id,
			'request_method' : 'delete'
		},
		function (data){
			var result = JSON.parse(data);
			
			if(result.success==true){
				$('#btn_all_scenes').trigger('click');
			}

			if(result.success==false){
				console.log(result.main_message);
			}
		}
	);
}

function viewStoryboard(project_id,scene_id){
	window.location='storyboard.php?project_id='+project_id+'&scene_id='+scene_id;
}

function storyboard_gur(project_id){
	$.post(
		"../controller/users.php",
		{
			'project_id' : project_id,
			'request_method' : 'user_role'
		},
		function (data){
			var result = JSON.parse(data);
			
			if(result.success==true){
				user_role=result.user_role;

				if(user_role=="Director" || user_role=="Assistant Director"){
					$('#image').prop("disabled",false);
					$('#scene_name').css("display","block");
					$('#scene_date').css("display","block");
					$('#tagged_crew_holder').css("display","block");
					$('#tagged_crew').css("display","block");
				}
			}

			if(result.success==false){
				console.log(result.main_message);
			}
		}
	);
}

function storyboard_details(scene_id){
	$.post(
		"../controller/scene.php",
		{
			'scene_id' : scene_id,
			'request_method' : 'detail'
		},
		function (data){
			var result = JSON.parse(data);
			
			if(result.success==true){
				
				$('#image_holder').append("<img src='"+result.scene_image+"' class='img-responsive'>");
				$('#scene_button').html("<input type='hidden' id='scene_id' value='"+scene_id+"'>");

				if(result.scene_name){
					$('#sr_name').html(result.scene_name);
					$('#storyboard_meta').append("<div class='storyboard-meta'><h3>Scene name</h3>"+result.scene_name+"</div>");
					$('#scene_name').val(result.scene_name);
				}
				else {
					$('#sr_name').html("Untitled scene");
					$('#storyboard_meta').append("<div class='storyboard-meta'><h3>Scene name</h3>Untitled scene</div>");
				}

				if(result.scene_date){
					$('#storyboard_meta').append("<div class='storyboard-meta'><h3>Shoot date</h3>"+result.scene_date+"</div>");
					$('#scene_date').val(result.scene_date);
				}
				else {
					$('#storyboard_meta').append("<div class='storyboard-meta'><h3>Shoot date</h3>No date assigned</div>");
				}

				if(result.tagged_members){
					$('#storyboard_meta').append("<div class='storyboard-meta'><h3>Tagged crew</h3><div id='tagged-crew-message' class='storyboard-field-hide'>No one has been tagged yet</div><div id='tagged-crew-members'>"+result.tagged_members+"</div></div>");
				}
				else {
					$('#storyboard_meta').append("<div class='storyboard-meta'><h3>Tagged crew</h3><div id='tagged-crew-message'>No one has been tagged yet</div><div id='tagged-crew-members'></div></div>");
				}

				for(var k=0;k<result.roles.length;k++){
					if(result.roles[k].note){
						$('#storyboard_meta').append("<div class='storyboard-meta'><h3>"+result.roles[k].user_name+" ("+result.roles[k].role+")</h3>"+result.roles[k].note+"</div>");
					}
				}

				if(result.my_note){
					$('#scene_note').val(result.my_note);
				}
			}

			if(result.success==false){
				console.log(result.main_message);
			}
		}
	);	
}

function logout(){
	$.post(
		"../controller/users.php",
		{
			'request_method' : 'logout'
		},
		function (data){
			var result = JSON.parse(data);
			
			if(result.success==true){
				window.location="login.php";
			}

			if(result.success==false){
				console.log(result.main_message);
			}
		}
	);	
}

function getProjectDetails(project_id){
	$.post(
		"../controller/scene.php",
		{
			'project_id' : project_id,
			'request_method' : 'project_detail'
		},
		function (data){
			var result = JSON.parse(data);

			if(result.success==true){
				$('#project_name').html(result.project_name);
				$('#project_name_small').html(result.project_name);

				$('#project_description').html(result.project_description);
				$('#project_description_small').html(result.project_description);

				if(result.project_start_date==undefined){
					$('#project_start').html('Pending approval');
					$('#project_start_small').html('Pending approval');
				}
				else {
					$('#project_start').html(result.project_start_date);
					$('#project_start_small').html(result.project_start_date);
				}

				if(result.project_end_date==undefined){
					$('#project_end').html('Pending approval');
					$('#project_end_small').html('Pending approval');
				}
				else {
					$('#project_end').html(result.project_end_date);
					$('#project_end_small').html(result.project_end_date);
				}

				$('#pr_name').html(result.project_name);
				$('#pr_name_link').html("<a href='scenes.php?project_id="+project_id+"'>"+result.project_name+"</a>");
			}
			else {
				console.log(result.main_message);
			}
		}
	);	
}

function checkSession(page_title){
	$.post(
		"../controller/users.php",
		{
			'request_method' : 'sessionActive'
		},
		function (data){
			var result = JSON.parse(data);

			if(result.success==true){
				if(page_title=="Register" || page_title=="Login"){
					window.location="dashboard.php";
				}
			}

			else if(result.success==false){
				if(page_title!="Register" && page_title!="Login" && page_title!="Account Activation"){
					window.location="login.php";
				}
			}
		}
	);	
}

function activateUser(token){
	$('#loading').show();

	$.post(
		"../controller/activate.php",
		{
			'token' : token
		},
		function (data){
			var result = JSON.parse(data);

			$('#loading').hide();
			$('#activation_text').html(result.main_message);
		}
	);	
}

function selectedvalue(id){
	if(id=="Custom"){
		$('#custom_role').css("display","block");
	}

	else {
		$('#custom_role').css("display","none");
	}

	$('#roletype').val(id);
	$('#rolebutton').text(id);
}

function resend_activation_link(user_id){
	$('#main').hide();
	$('#loading').show();

	$.post(
		"../controller/users.php",
		{
			'request_method' : 'resend_activation_link',
			'user_id' : user_id
		},
		function (data){
			var result = JSON.parse(data);

			if(result.success==true){
				window.location="activate.php?register=true&user_id="+user_id+"&resend=true";
			}
			else {
				$('#loading').hide();
				$('#main').show();
				console.log(result.main_message);
			}
		}
	);	
}

function getNotifications(){
	$('#notifications').hide();
	$('#loading').show();

	$('#project_invites').html('');
	$('#project_dates').html('');

	$.post(
		"../controller/users.php",
		{
			'request_method' : 'notifications'
		},
		function (data){
			$('#loading').hide();
			$('#notifications').show();

			var result = JSON.parse(data);
			
			if(result.success==true){
				if(result.notifications.total>0){
					$('#header_notifications_total').text(' ('+result.notifications.total+')');

					for(var k=0;k<result.notifications.invites.length;k++){
						$('#project_invites').append("\
							<div class='col-md-12 col-sm-12 col-xs-12 notification-holder'>\
								<div class='col-md-7 col-sm-7 col-xs-12 notification-text'>"+result.notifications.invites[k].notification+"</div>\
								<div class='col-md-3 col-md-offset-2 col-sm-3 col-sm-offset-1 hidden-xs text-right'>\
									<button onclick='acceptNotification("+result.notifications.invites[k].notification_id+")' class='btn btn-primary'>Accept</button>\
									<button onclick='declineNotification("+result.notifications.invites[k].notification_id+")' class='btn'>Decline</button>\
								</div>\
								<div class='col-xs-12 hidden-md hidden-sm hidden-lg'>\
									<h6 class='text-center'>\
										<button onclick='acceptNotification("+result.notifications.invites[k].notification_id+")' class='btn btn-success btn-circle btn-lg hidden-md hidden-sm hidden-lg'><i class='fa fa-check'></i></button>\
										<button onclick='declineNotification("+result.notifications.invites[k].notification_id+")' class='btn btn-disabled btn-circle btn-lg hidden-md hidden-sm hidden-lg'><i class='fa fa-times'></i></button>\
									</h6>\
								</div>\
							</div>\
						");
					}

					for(var k=0;k<result.notifications.projects.length;k++){
						$('#project_dates').append("\
							<div class='col-md-12 col-sm-12 col-xs-12 notification-holder'>\
								<div class='col-md-7 col-sm-7 col-xs-12 notification-text'>"+result.notifications.projects[k].notification+"</div>\
								<div class='col-md-3 col-md-offset-2 col-sm-3 col-sm-offset-1 hidden-xs text-right'>\
									<button onclick='acceptNotification("+result.notifications.projects[k].notification_id+")' class='btn btn-primary'>Accept</button>\
									<button onclick='declineNotification("+result.notifications.projects[k].notification_id+")' class='btn'>Decline</button>\
								</div>\
								<div class='col-xs-12 hidden-md hidden-sm hidden-lg'>\
									<h6 class='text-center'>\
										<button onclick='acceptNotification("+result.notifications.projects[k].notification_id+")' class='btn btn-success btn-circle btn-lg hidden-md hidden-sm hidden-lg'><i class='fa fa-check'></i></button>\
										<button onclick='declineNotification("+result.notifications.projects[k].notification_id+")' class='btn btn-disabled btn-circle btn-lg hidden-md hidden-sm hidden-lg'><i class='fa fa-times'></i></button>\
									</h6>\
								</div>\
							</div>\
						");
					}
				}
				else {
					$('#notifications').append("\
						<div class='col-md-12 col-sm-12 col-xs-12'>\
							<h6 class='text-center'>You have no new notifications</h6>\
						</div>\
					");

					$('#header_notifications_total').text('');
				}
			}

			if(result.success==false){
				console.log(result.main_message);
			}
		}
	);	
}

function getTotalNotifications(){
	$.post(
		"../controller/users.php",
		{
			'request_method' : 'total_notifications'
		},
		function (data){
			var result = JSON.parse(data);
			
			if(result.success==true){
				if(result.total_notifications>0){
					$('#header_notifications_total').text(' ('+result.total_notifications+')');
				}
				else {
					$('#header_notifications_total').text('');
				}
			}

			if(result.success==false){
				console.log(result.main_message);
			}
		}
	);	
}

function acceptNotification(notification_id){
	$('#error-box').hide();
	$('#success-box').hide();
	$('#notifications').hide();
	$('#loading').show();

	$.post(
		"../controller/users.php",
		{
			'request_method' : 'accept_notification',
			'notification_id' : notification_id
		},
		function (data){
			var result = JSON.parse(data);

			$('#loading').hide();

			getNotifications();
			
			if(result.success==true){
				$('#success-box').show();
				$('#success-box').text(result.main_message);
			}

			if(result.success==false){
				$('#error-box').show();
				$('#error-box').text(result.main_message);
			}
		}
	);	
}

function declineNotification(notification_id){
	$('#error-box').hide();
	$('#success-box').hide();
	$('#notifications').hide();
	$('#loading').show();

	$.post(
		"../controller/users.php",
		{
			'request_method' : 'reject_notification',
			'notification_id' : notification_id
		},
		function (data){
			var result = JSON.parse(data);

			$('#loading').hide();

			getNotifications();
			
			if(result.success==true){
				$('#success-box').show();
				$('#success-box').text(result.main_message);
			}

			if(result.success==false){
				$('#error-box').show();
				$('#error-box').text(result.main_message);			
			}
		}
	);	
}

function changePassword(){
	var current_password = document.getElementById('current_password').value;
	var password = document.getElementById('password').value;
	var repeat_password = document.getElementById('repeat_password').value;

	if(current_password=='' || password=='' || repeat_password==''){
		$('#error-box').show();
		$('#error-box').text('Please complete all fields.');
	}
	else if(password!=repeat_password){
		$('#error-box').show();
		$('#error-box').text('Passwords do not match.');
	}
	else if(password.length<6){
		$('#error-box').show();
		$('#error-box').text('Password must have at least 6 characters.');
	}
	else {
		$('#error-box').hide();
		$('#error-box').text('');
		$('#success-box').hide();
		$('#success-box').text('');
		$('#main_form').hide();
		$('#loading').show();

		$.post(
			"../controller/users.php",
			{
				'request_method' : 'change_password',
				'current_password' : current_password,
				'password' : password,
				'repeat_password' : repeat_password
			},
			function (data){
				var result = JSON.parse(data);

				$('#loading').hide();
				$('#main_form').show();
				
				if(result.success==true){
					$('#success-box').show();
					$('#success-box').text(result.main_message);
				}

				if(result.success==false){
					$('#error-box').show();
					$('#error-box').text(result.main_message);			
				}
			}
		);		
	}
}

function viewCrewMembers(user_input,scene_id,project_id){
	$.post(
		"../controller/tag.php",
		{
			'request_method' : 'get_suggestions',
			'project_id' : project_id,
			'scene_id' : scene_id,
			'user_input' : user_input
		},
		function (data){
			var result = JSON.parse(data);

			if(result.success==true){
				if(result.members){
					$('#crew_members').html(result.members);
					$('#crew_members').show();
				}
				else {
					$('#crew_members').hide();
					$('#crew_members').html('');
				}
			}

			if(result.success==false){
				console.log(result.main_message);			
			}
		}
	);	
}

function hideCrewMembers(){
	$('#crew_members').hide();
}

function tagCrewMember(user_id,scene_id,project_id){
	$.post(
		"../controller/tag.php",
		{
			'request_method' : 'tag_member',
			'user_id' : user_id,
			'scene_id' : scene_id,
			'project_id' : project_id
		},
		function (data){
			var result = JSON.parse(data);

			if(result.success==true){
				if(result.member){
					$('#tagged-crew-message').hide();
					$('#tagged-crew-members').append(result.member);
					$('#tagged_crew').val('');
				}
			}

			if(result.success==false){
				console.log(result.main_message);			
			}
		}
	);	
}

function deleteCrewMemberTag(meta_id,project_id,scene_id){
	$.post(
		"../controller/tag.php",
		{
			'request_method' : 'delete_tag',
			'meta_id' : meta_id,
			'project_id' : project_id,
			'scene_id' : scene_id
		},
		function (data){
			var result = JSON.parse(data);

			if(result.success==true){
				$('#'+meta_id).remove();
				if($('#tagged-crew-members').is(':empty')){
					$('#tagged-crew-message').show();
				}
			}

			if(result.success==false){
				console.log(result.main_message);			
			}
		}
	);	
}

function generate_call_sheet(project_id){
	$('#error-box').hide();
	$('#error-box').text('');
	$('#success-box').hide();
	$('#success-box').text('');
	$('#scenes').hide();
	$('#loading').show();
	
	$.post(
		"../controller/sheet.php",
		{
			'request_method' : 'generate_send',
			'project_id' : project_id
		},
		function (data){
			var result = JSON.parse(data);

			$('#loading').hide();
			$('#scenes').show();
				
			if(result.success==true){
				$('#success-box').show();
				$('#success-box').text("Call sheet has been emailed to all project members successfully.");
			}

			if(result.success==false){
				$('#error-box').show();
				$('#error-box').text(result.main_message);			
			}
		}
	);	
}