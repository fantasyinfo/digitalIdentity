<?php

error_reporting(0);
$schoolLogo = base_url() . HelperClass::schoolLogoImagePath;

$this->load->model('CrudModel');

$filterToken = "token=" . $randomToken . "-m-" . $monthId . "-y-" . $yearId . "-i-" . $salaryEmpId . "-iId-" . $c;





if(isset($_GET['stu_id'])){
	$studentId = $_GET['stu_id'];

	@$schoolUniqueCode = @$this->db->query("SELECT schoolUniqueCode FROM " . Table::studentTable . " WHERE id = '$studentId' LIMIT 1")->result_array()[0]['schoolUniqueCode'];

	if(!empty($schoolUniqueCode)){
		$schoolDetails = $this->db->query("SELECT sm.school_name, sm.mobile,sm.email,sm.address,CONCAT('$schoolLogo',sm.image) as logo,sm.pincode FROM " . Table::schoolMasterTable . " sm WHERE sm.unique_id = '$schoolUniqueCode' LIMIT 1")->result_array()[0];
	}


}

?>


<html>

<head>

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">


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

	<script src="https://code.jquery.com/jquery-1.8.2.js"></script>


	<style>
		#innerTable th {
			border: 1px solid #000;

		}

		#innerTable tr {
			border: 1px solid #000;

		}

		#innerTable td {
			border: 1px solid #000;

		}


		@page {
			size: Legal;
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

			#innerTable th {
				border: 1px solid #000;

			}

			#innerTable tr {
				border: 1px solid #000;

			}

			#innerTable td {
				border: 1px solid #000;

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




	<!--Generating Custom Title For Page-->
	<title>Digital Scholar Register - digitalfied.com</title>
	<!--Generating Custom Title For Page-->

	<!-- <?php echo '<pre>';
			print_r($salaryDetails); ?> -->
	<div class="container border">

		<table>
			<tr>
				<td><img src="<?= $schoolDetails['logo'] ?>" width="100px" height="auto" /></td>
				<td class="text-center">
					<h2 style="font-size:24px;font-weight:bold"><?= strtoupper($schoolDetails['school_name']) ?></h2>

					<p style="font-size:16px;font-weight:bold">
						<?= strtoupper($schoolDetails['address'] . " " . $schoolDetails['pincode']) ?></p>
					<p style="font-size:16px">Mobile: <?= $schoolDetails['mobile'] ?> Email:
						<?= $schoolDetails['email'] ?></p>
				</td>
				<td>
					<img class="qrcode" src="https://chart.googleapis.com/chart?chs=150x150&amp;cht=qr&amp;chl=<?= base_url('scholarRegisterCertificate?stu_id=') . $_GET['stu_id'] ?>&amp;choe=UTF-8" alt="QR code" width="150px" style="padding-left:20px;" />
				</td>
			</tr>
		</table>


		<h4 style="font-size: 24px; font-weight:bold; margin-bottom:20px;">
			<center>SCHOLAR REGISTER</center>
		</h4>

		<?php

if(!empty($schoolUniqueCode)){

	$studentDetails = $this->db->query("SELECT s.*, c.className FROM " . Table::studentTable . " s
	JOIN " . Table::classTable . " c ON c.id = s.class_id
	JOIN " . Table::sectionTable . " sec ON sec.id = s.section_id
	WHERE s.id = '$studentId' 
	AND s.schoolUniqueCode = '$schoolUniqueCode'")->result_array()[0];
}


		?>
		<div id="innerTable">
			<table class="table text-center" width="100%" style="font-size:14px;border:1px solid #000;">
				<thead>
					<tr>
						<th class="text-center">SR Number</th>
						<th class="text-center">Register Number</th>
						<th class="text-center">Aadhar Card Number</th>
						<th class="text-center">Admission Number</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?= @$studentDetails['sr_number']; ?></td>
						<td><?= @$studentDetails['register_no']; ?></td>
						<td><?= @$studentDetails['aadhar_no']; ?></td>
						<td><?= @$studentDetails['admission_no']; ?></td>
					</tr>

				</tbody>

			</table>
			<table class="table text-center" width="100%" style="font-size:14px;border:1px solid #000;">
				<thead>
					<tr>
						<th class="text-center">Name of the Student</th>
						<th class="text-center">Father's Name or Guardian</th>
						<th class="text-center">Mother's Name</th>
						<th class="text-center">Occupation and Address</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?= @$studentDetails['name']; ?></td>
						<td><?= @$studentDetails['father_name']; ?></td>
						<td><?= @$studentDetails['mother_name']; ?></td>
						<td><?= @$studentDetails['occupation'] . ' and ' . $studentDetails['address'] . ' ' . $studentDetails['cityName']; ?></td>
					</tr>

				</tbody>

			</table>
			<table class="table text-center" width="100%" style="font-size:14px;border:1px solid #000;">
				<thead>
					<tr>
						<th class="text-center">Date of Birth</th>
						<th class="text-center">Date of Birth in Words</th>
						<th class="text-center">The last School of student attended before joining this school</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?= date('jS F Y', strtotime(@$studentDetails['dob'])); ?></td>
						<td><?php echo $this->CrudModel->dateToWords(date('d',strtotime(@$studentDetails['dob'])));
							echo  ' ' . date('F',strtotime(@$studentDetails['dob']));
							echo ' ' .  $this->CrudModel->dateToWords(date('Y',strtotime(@$studentDetails['dob']))); ?></td>
						<td><?= @$studentDetails['last_schoool_name']; ?></td>
					</tr>

				</tbody>

			</table>
			<table class="table  text-center" width="100%" style="font-size:14px;border:1px solid #000;">
				<thead>
					<tr>
						<th class="text-center">Class</th>
						<th class="text-center">Date of Admission</th>
						<th class="text-center">Date of Promotion</th>
						<th class="text-center">Date of Removal</th>
						<th class="text-center">Cause of Removal</th>
						<th class="text-center">Session Year</th>
						<th class="text-center">Conduct</th>
						<th class="text-center">Work</th>
					</tr>
				</thead>
				<tbody>
					<?php

					$srDetails = [];

					if(!empty($schoolUniqueCode)){
					$alreadySRCheck =  $this->db->query("SELECT * FROM " . Table::srRegisterHistory . " WHERE student_id = '$studentId' AND status = '1' AND schoolUniqueCode = '$schoolUniqueCode' LIMIT 1")->result_array();

					if (!empty($alreadySRCheck)) {
						$srDetails = json_decode($alreadySRCheck[0]['srData'], true);
					}
				}
					$i = 0;
					foreach (HelperClass::srClass as $classes => $values) { ?>
						<tr>
							<td>
								<?= $values; ?>
							</td>
							<td>
								<?= !empty($srDetails[$i]['doa']) ? date('d / m / Y', strtotime($srDetails[$i]['doa'])) : '' ?>
							</td>
							<td>
								<?= !empty($srDetails[$i]['dop']) ? date('d / m / Y', strtotime($srDetails[$i]['dop'])) : '' ?>
							</td>
							<td>
								<?= !empty($srDetails[$i]['dor']) ? date('d / m / Y', strtotime($srDetails[$i]['dor'])) : '' ?>
							</td>
							<td>
								<?= !empty($srDetails[$i]['causeOfRemoval']) ? $srDetails[$i]['causeOfRemoval'] : '' ?>
							</td>
							<td>
								<?= !empty($srDetails[$i]['sessionYears']) ? $srDetails[$i]['sessionYears'] : '' ?>
							</td>
							<td>
								<?= !empty($srDetails[$i]['conduct']) ? $srDetails[$i]['conduct'] : '' ?>
							</td>
							<td>
								<?= !empty($srDetails[$i]['work']) ? $srDetails[$i]['work'] : '' ?>
							</td>
						</tr> <?php $i++;
							}  ?>
				</tbody>
			</table>

		</div>
		<h4 class="text-center">Certified that the above Student's Register has been upto date of the Student's leaving as required by the Departmental Rules.</h4>


		<table class="table" style="margin-top:50px;">
			<tr>
				<td>
					Prepared By
				</td>
				<td>
					Date
				</td>
				<td>
					Principal
				</td>
			</tr>
		</table>
	</div>
	</div>


	</br>
	</br>


</body>




</html>