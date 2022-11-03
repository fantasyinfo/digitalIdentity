<?php

error_reporting(0);
$schoolLogo = base_url() . HelperClass::schoolLogoImagePath;



if (isset($_GET['tec_id'])) {
	$this->load->model('CrudModel');
	$tokenFiter = $this->db->query("SELECT * FROM ".Table::tokenFilterTable." WHERE token = '{$_GET['tec_id']}' AND status = '1' LIMIT 1")->result_array()[0];

	
	if(!empty($tokenFiter))
	{
		$experienceLetter = $this->db->query("SELECT * FROM ".Table::experienceLetterTable." WHERE id = '{$tokenFiter['insertId']}' AND status = '1' AND schoolUniqueCode = '{$tokenFiter['schoolUniqueCode']}' LIMIT 1")->result_array()[0];


		if(empty($experienceLetter))
		{
			//header("Location: " . HelperClass::brandUrl);
		}
	}


	$schoolDetails = $this->db->query("SELECT sm.school_name, sm.mobile,sm.email,sm.address,CONCAT('$schoolLogo',sm.image) as logo,sm.pincode FROM " .
		Table::schoolMasterTable . " sm WHERE sm.unique_id = '{$experienceLetter['schoolUniqueCode']}' LIMIT 1")->result_array()[0];


	if (empty($salaryDetails)) {
		$msgArr = [
			'class' => 'danger',
			'msg' => 'Experience Letter Details Not Found.',
		];
		$this->session->set_userdata($msgArr);

		//header("Location: " . HelperClass::brandUrl);
	}
} else {
	$msgArr = [
		'class' => 'danger',
		'msg' => 'Experience Letter Not Found.',
	];
	$this->session->set_userdata($msgArr);

	//header("Location: " . HelperClass::brandUrl);
}




?>


<html>

<head>

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">

	<!--Page Loader Style -->
	<!--Page Loader Style -->
	<!--Page Loader Style -->
	<!--Page Loader Style -->
	<!--Page Loader Style -->
	<!--Page Loader Style -->
	<!--Page Loader Style -->
	<!--Page Loader Style -->
	<style>
		#loader {
			position: fixed;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			z-index: 9999;
			background: url('images/processing.gif') 50% 50% no-repeat rgb(249, 249, 249);
		}
	</style>
	<!--Page Loader Style -->
	<!--Page Loader Style -->
	<!--Page Loader Style -->
	<!--Page Loader Style -->

	<!--Page Loader Javascript -->
	<!--Page Loader Javascript -->
	<!--Page Loader Style -->
	<script src="https://code.jquery.com/jquery-1.8.2.js"></script>

	<!-- <script type="text/javascript">  
   $(window).load(function() {  
      $("#loader").fadeOut(1000);  
   });
</script>  -->
	<!--Page Loader Javascript -->
	<!--Page Loader Javascript -->
	<!--Page Loader Javascript -->

	<style>

		@page {
			size: A4;
			margin: 10px;
		}

		@media print {
			#printbtn {
				display: none;
			}

			html,
			body {}

			body {
				margin: 0;
				padding: 0;
				font-size: 16px;
				font-family: Segoe, Segoe UI, DejaVu Sans, Trebuchet MS, Verdana, " sans-serif";
			}

			u {
				border-bottom: 1px dashed #515151;
				text-decoration: none;
			}

			.container {
				margin: 0 auto;
				padding: 7px;
				/* background-color:#f1f1f1; */
				margin-top: 0px;
				border: 1px solid #ccc;
				width: 100%;

			}

			h1 {
				font-size: 22px;
				color: #0086b3;
				line-height: 30px;
			}

			#contentData p {

				text-align:justify;font-size:18px;margin:40px;line-height:40px;
			}

			p2 {

				font-size: 10px;
				color: #001B9E;
			}

			p3 {

				font-size: 14px;
				color: #FD0000;

			}

			p4 {

				font-size: 15px;
				color: #FF0004;

			}

		}
	</style>
</head>
<!-- <body>	  -->
<!-- <div id="loader"></div>  -->

<!-- <body onload="window.print()">  -->

<body>
	<!--<center> <h2>Student Result</h2> </center>-->



	<p align="right"><button id="printbtn" onclick="window.print();">Print</button></p>




	<!-- Array ( [id] => 2 [schoolUniqueCode] => 683611 [book_register_no] => 01 [s_i_s_r_no] => 11 [admission_no] => 00112255 [student_id] => 26 [student_name] => Tonny [father_name] => a [mother_name] => a [gender] => Male [category] => Common [nationality] => Indian [date_of_admission] => 2022-10-21 [date_of_birth] => 2022-10-21 [last_class_studies] => 2nd - B [board_exam_last_taken] => CBSE Board [failed_in_class] => NO [subjects_studies] => Hindi,English,Maths,Science [qualify_for_permotion] => Yes [fees_due] => Yes [total_working_days] => 196 [total_present_days] => 140 [ncc_cadet] => NO [game_played] => Cricket [general_conduct] => Good [date_of_application] => 2022-10-21 [date_of_issue] => 2022-10-21 [reason_for_leaving] => Transfer of Father [remark] => NA [shedule_tribe] => No [session_table_id] => 2 [status] => 1 [created_at] => 2022-10-21 08:21:07 [school_name] => Dummy Public School [mobile] => 9638521470 [email] => growell@gmail.com [address] => abc building baraut [image] => img-SCHOOL-1666145548istockphoto-1171617683-612x612.jpg [pincode] => 250611 )	 -->



	<!--Generating Custom Title For Page-->
	<title>Digital Experience Letter - digitalfied.com</title>
	<!--Generating Custom Title For Page-->

	<div class="container border" >

		<table>
			<tr>
				<td><img src="<?= $schoolDetails['logo'] ?>" width="130px" height="auto" /></td>
				<td class="text-center">
					<h2 style="font-size:30px;font-weight:bold"><?= strtoupper($schoolDetails['school_name']) ?></h2>

					<h4 style="font-size:20px;font-weight:bold">
						<?= strtoupper($schoolDetails['address'] . " " . $schoolDetails['pincode']) ?></h4>
					<h4 style="font-size:20px">Mobile: <?= $schoolDetails['mobile'] ?> Email:
						<?= $schoolDetails['email'] ?></h4>
				</td>
				<td>

					<img class="qrcode" src="https://chart.googleapis.com/chart?chs=150x150&amp;cht=qr&amp;chl=<?= base_url('experienceLetter?tec_id=') . $_GET['tec_id'] ?>&amp;choe=UTF-8" alt="QR code" width="130px" style="padding-left:20px;"/> </br>
					<h4>
						<center>Scan To Verify</center>
					</h4>
				</td>
			</tr>
		</table>


		<h4 style="font-size: 36px; font-weight:bold; margin-bottom:20px;">
			<center><i>Experience Letter</i></center>
		</h4>


	<div id="contentData"><?= $experienceLetter['content'];?>
	<br><br>
	<p>Sincerely,</p>

	<p>Issue Date: <u><?=$experienceLetter['issueDate']?></u></p>
	<p>Name : __________________________</p>
	<p>Designation : __________________________</p>




</div>
<table class="table-borderless mt-2">
	<tr>
   	<td><center>Principal Seal & Signature</center></td>
	</tr>	
		
	</table>

	</div>
	</div>


	</br>
	</br>


</body>




</html>