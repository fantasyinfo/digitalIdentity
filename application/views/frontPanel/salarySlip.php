<?php

error_reporting(0);
$schoolLogo = base_url() . HelperClass::schoolLogoImagePath;

if (isset($_GET['tec_id'])) {
	$this->load->model('CrudModel');
	$userId = explode('-', $_GET['tec_id']);

	$employeeSalaryTableId = $userId[2];
	$monthId = $userId[5];
	$yearId = $userId[6];

	$salaryDetails = $this->CrudModel->checkEmployeeSalaryById($employeeSalaryTableId, $monthId, $yearId);








	$schoolDetails = $this->db->query("SELECT sm.school_name, sm.mobile,sm.email,sm.address,CONCAT('$schoolLogo',sm.image) as logo,sm.pincode FROM " .
		Table::schoolMasterTable . " sm WHERE sm.unique_id = '{$_SESSION['schoolUniqueCode']}' LIMIT 1")->result_array()[0];


	if (empty($salaryDetails)) {
		$msgArr = [
			'class' => 'danger',
			'msg' => 'Salary Slip Details Not Found. Please Generate Again.',
		];
		$this->session->set_userdata($msgArr);

		header("Location: " . HelperClass::brandUrl);
	}
} else {
	$msgArr = [
		'class' => 'danger',
		'msg' => 'Salary Slip Not Found. Please Generate Again.',
	];
	$this->session->set_userdata($msgArr);

	header("Location: " . HelperClass::brandUrl);
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
				font-size: 12px;
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

			p {

				font-size: 13px;

				color: #333;
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

			img {
				/* padding:8px; */
				/* border:1px solid #ccc; */
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
	<title>Digital Salary Slip - digitalfied.com</title>
	<!--Generating Custom Title For Page-->

	<!-- <?php echo '<pre>';
			print_r($salaryDetails); ?> -->
	<div class="container border" >

		<table>
			<tr>
				<td><img src="<?= $schoolDetails['logo'] ?>" width="180px" height="auto" /></td>
				<td class="text-center">
					<h2 style="font-size:26px;font-weight:bold"><?= strtoupper($schoolDetails['school_name']) ?></h2>

					<p style="font-size:20px;font-weight:bold">
						<?= strtoupper($schoolDetails['address'] . " " . $schoolDetails['pincode']) ?></p>
					<p style="font-size:20px">Mobile: <?= $schoolDetails['mobile'] ?> Email:
						<?= $schoolDetails['email'] ?></p>
				</td>
				<!-- <td>

					<img class="qrcode" src="https://chart.googleapis.com/chart?chs=150x150&amp;cht=qr&amp;chl=<?= base_url('salarySlip?tec_id=') . rand(1111, 9999) . '-' . rand(1111, 9999) . '-' . $employeeSalaryTableId . '-random_token-' . rand(111111111, 999999999) . '-' . $monthId . '-' . $yearId . '-salarySlip-98754-empICan-445'; ?>&amp;choe=UTF-8" alt="QR code" /> </br>
					<p>
						<center>Scan To Verify</center>
					</p>
				</td> -->
			</tr>
		</table>


		<h4 style="font-size: 32px; font-weight:bold; margin-bottom:20px;">
			<center><i>Salary Slip For <?= HelperClass::monthsForSchoolR[$monthId] . ' - ' . $yearId; ?></i></center>
		</h4>

		<!-- <table class="table table-borderless">
	
		
	</table>	 -->


		<table class="table table-bordered" width="100%">

			<tr>
				<td>Employee Name: </td>
				<td><b><?= $salaryDetails['employeeDetails']['employeeName']; ?></b></td>
				<td>Employee Id: </td>
				<td><b><?= $salaryDetails['employeeDetails']['empId'];  ?></b></td>
			<tr></tr>
			<td>Total Month Days: </td>
			<td><b><?= $salaryDetails['workingDays']['totalHolidaysIncludingSundays'] + $salaryDetails['workingDays']['totalWorkingDays']; ?>
					Days</b></td>
			<td>Total Holidays Including Sundays:</td>
			<td> <b><?= $salaryDetails['workingDays']['totalHolidaysIncludingSundays']; ?> Days</b></td>
			</tr>
			<tr>
				<td>Department: </td>
				<td><b><?= $salaryDetails['employeeDetails']['departmentName'] ?></b></td>
				<td>Designation: </td>
				<td><b><?= $salaryDetails['employeeDetails']['designationName'] ?></b></td>
			<tr></tr>
			<td>Total Working Days: </td>
			<td><b><?= $salaryDetails['workingDays']['totalWorkingDays']; ?> Days</b></td>
			<td>Paid Days (Present): </td>
			<td><b><?= $salaryDetails['attendanceData']['present']; ?> Days</b></td>
			</tr>
			<tr>
				<td>Date of Joining: </td>
				<td><b><?= date('d-M-Y', strtotime($salaryDetails['employeeDetails']['doj'])); ?></b></td>
				<td>Absents: </td>
				<td><b><?= $salaryDetails['attendanceData']['absent']; ?> Days</b></td>
			<tr></tr>
			<td>Gross Salary Per Month: </td>
			<td><b> ₹ <?= number_format($salaryDetails['employeeDetails']['basicSalaryMonth'], 2); ?></b></td>
			<td>Gross Salary Per Day: </td>
			<td><b> ₹ <?= number_format($salaryDetails['employeeDetails']['basicSalaryDay'], 2); ?></b></td>
			</tr>
			<tr>
				<td colspan="2" class="text-center"><u><b>Allowances</b></u></td>
				<td colspan="2" class="text-center"><u><b>Deductions</b></u></td>
			</tr>
			<tr>
				<td>Basic Salary:</td>
				<td><b> ₹ <?= number_format($salaryDetails['basicPay'], 2); ?></b></td>
				<td>Professinal Tax Deductions: <span style="font-weight:bold">
						<?= $salaryDetails['employeeDetails']['professionalTaxPerMonth']; ?> % </span></td>
				<td><b> ₹ <?= number_format($salaryDetails['deducations']['ptpm'], 2); ?></b></td>
			</tr>
			<tr>
				<td>House Rent Allowances: <span style="font-weight:bold"> <?= $salaryDetails['employeeDetails']['hra']; ?>
						% </span></td>
				<td><b> ₹ <?= number_format($salaryDetails['allowances']['hra'], 2); ?></b></td>
				<td>Provident Funds Deductions: <span style="font-weight:bold">
						<?= $salaryDetails['employeeDetails']['pfPerMonth']; ?> % </span></td>
				<td><b> ₹ <?= number_format($salaryDetails['deducations']['pfpm'], 2); ?></b></td>
			</tr>
			<tr>
				<td>Conveyance Allowances: <span style="font-weight:bold">
						<?= $salaryDetails['employeeDetails']['conAll']; ?> % </span></td>
				<td><b> ₹ <?= number_format($salaryDetails['allowances']['ca'], 2); ?></b></td>
				<td>TDS : <span style="font-weight:bold"> <?= $salaryDetails['employeeDetails']['tdsPerMonth']; ?> % </span>
				</td>
				<td><b> ₹ <?= number_format($salaryDetails['deducations']['tds'], 2); ?></b></td>
			</tr>
			<tr>
				<td>Medical Allowances: <span style="font-weight:bold">
						<?= $salaryDetails['employeeDetails']['medicalAll']; ?> % </span></td>
				<td><b> ₹ <?= number_format($salaryDetails['allowances']['ma'], 2); ?></b></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Special Allowances: <span style="font-weight:bold">
						<?= $salaryDetails['employeeDetails']['specialAll']; ?> % </span></td>
				<td><b> ₹ <?= number_format($salaryDetails['allowances']['sa'], 2); ?></b></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Dearness Allowances: <span style="font-weight:bold">
						<?= $salaryDetails['employeeDetails']['dearnessAll']; ?> % </span></td>
				<td><b> ₹ <?= number_format($salaryDetails['allowances']['da'], 2); ?></b></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Total Allowances:</td>
				<td><b> ₹ <?= number_format($salaryDetails['allowances']['total'], 2); ?></b></td>
				<td>Total Deductions:</td>
				<td><b> ₹ <?= number_format($salaryDetails['deducations']['total'], 2); ?></b></td>
			</tr>
			<tr>
				<td colspan="2" class="text-center"><b>Net Pay</b></td>
				<td colspan="2"><b> ₹ <?= number_format($salaryDetails['totalSalaryToPay'], 2); ?></b></td>
			</tr>
			<tr>
				<td colspan="2" class="text-center"><b>Amount in words</b></td>
				<td colspan="2"><b> <?= $this->CrudModel->numberToWordsCurrency($salaryDetails['totalSalaryToPay']); ?></b></td>
			</tr>

		</table>
		<br><br>
		<table class="table table-borderless mt-2">
			<tr>
				<td>
					<center>Employer Signature</center>
				</td>
				<td>
					<center>Employee Signature</center>
				</td>
			</tr>
		</table>
	</div>
	</div>


	</br>
	</br>


</body>




</html>