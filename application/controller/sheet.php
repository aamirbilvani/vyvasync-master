<?php

session_start();

require "../models/connect.php";
require "../models/users.php";
require "../models/projects.php";
require "../models/scenes.php";
require "fpdf/fpdf.php";
require "mailer/class.phpmailer.php";

class PDF extends FPDF {
	function Header(){
    	$this->Image('../../public/images/pdflogo.png',170,7,30);
    	$this->SetFont('Arial','B',15);
    	$this->Cell(0,0,'Call Sheet',0,0);
    	$this->Line(10,18,200,18);
    	$this->Ln(20);
	}

	function Footer(){
		$this->SetY(-15);
    	$this->SetFont('Arial','B',8);
    	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

$method=$_POST['request_method'];
$project_id=escapeString($_POST['project_id']);

$errors=0;

$response=array();

$user_id=sessionActive();

$user_role=isProjectMember($project_id,$user_id);

if($user_id==false){
	$errors++;
	$response['main_message']="User not signed in";
}

else if($user_role!="Producer"){
	$errors++;
	$response['main_message']="You do not have authority to generate call sheets.";
}

else if($method=="generate_send"){
	$call_sheet_data=generate_call_sheet($project_id);

	if($call_sheet_data==1){
		$errors++;
		$response['main_message']="Project does not contain any incomplete scenes to generate call sheet for.";
	}
	else if($call_sheet_data==2){
		$errors++;
		$response['main_message']="Cannot generate call sheet until all project scenes have been assigned a shoot date.";
	}
	else {
		$project_name=$call_sheet_data['project_name'];
		$project_description=$call_sheet_data['project_description'];
		$director=$call_sheet_data['director'];
		$producer=$call_sheet_data['producer'];

		$total_scenes=$call_sheet_data['total_scenes'];
		$project_scenes=$call_sheet_data['scenes'];

		$call_sheet=new PDF();
		$call_sheet->AliasNbPages();
		
		$call_sheet->AddPage();
		$call_sheet->SetFont('Arial','',11);
		$call_sheet->Cell(0,8,"Project name: $project_name",0,1);
		$call_sheet->Ln();
		$call_sheet->MultiCell(0,5,"Project description: $project_description");
		$call_sheet->Ln();
		$call_sheet->Cell(0,8,"Director: $director",0,1);
		$call_sheet->Ln();
		$call_sheet->Cell(0,8,"Producer: $producer",0,1);

		$k=0; $index=1;
		
		while($k<$total_scenes-1){
			$scene_name=$project_scenes[$k]['scene_name'];
			$scene_date=$project_scenes[$k]['scene_date'];
			$scene_image=$project_scenes[$k]['scene_image'];
			$tagged_members=$project_scenes[$k]['tagged_members'];
			
			$call_sheet->AddPage();		
			$call_sheet->SetFont('Arial','',16);

			$call_sheet->Cell(0,8,"Scene #$index",0,1,'C');
			$call_sheet->Image($scene_image,50,45,110);
			$call_sheet->SetFont('Arial','B',14);
			$call_sheet->Ln(91);
			$call_sheet->Cell(0,4,"$scene_name",0,1,'C');
			$call_sheet->SetFont('Arial','',10);
			$call_sheet->Cell(0,8,"Shoot date: $scene_date",0,1,'C');
			$call_sheet->Ln(7);
			$call_sheet->SetFont('Arial','',12);
			$call_sheet->MultiCell(0,5,"Tagged members: $tagged_members");

			$k++; $index++;
		}

		$attachment=$call_sheet->Output('call-sheet.pdf','S');
	
		send_call_sheet($attachment,$project_id);
	}
}

$response['success']=(($errors>0) ? false : true);

$jsonResponse=json_encode($response);

print_r($jsonResponse);

?>