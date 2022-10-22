<?php

error_reporting(0);
$schoolLogo = base_url() . HelperClass::schoolLogoImagePath;
if (isset($_GET['data'])) {


	$userId = explode('-', $_GET['data']);;
	$classId = $userId[0];
	$sectionId = $userId[1];
	$examNameId = $userId[2];
	$studentId = $userId[3];

	$condition = " AND s.id = '$studentId'  AND ss.id = '$sectionId'  AND sen.id = '$examNameId' AND c.id = '$classId' ";


	$marksheet = $this->db->query("SELECT 
r.id,r.marks,IF(r.result_status = '1', 'Pass', 'Fail') as resultStatus,
e.id as examId, sen.sem_exam_name, sen.exam_year, e.exam_date,e.max_marks,e.min_marks,
s.name,s.id as studentId, s.father_name,s.roll_no,s.mother_name,
c.className,ss.sectionName,
sub.subjectName,
r.created_at ,
sm.school_name, sm.mobile,sm.email,sm.address,CONCAT('$schoolLogo',sm.image) as logo,sm.pincode 
FROM " . Table::semExamResults . " r
JOIN " . Table::secExamTable . " e ON e.id = r.sec_exam_id
JOIN " . Table::semExamNameTable . " sen ON sen.id = r.sem_id
JOIN " . Table::studentTable . " s ON s.id = r.student_id
JOIN " . Table::classTable . " c ON c.id =  e.class_id
JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
JOIN " . Table::subjectTable . " sub ON sub.id =  e.subject_id
JOIN " . Table::schoolMasterTable . " sm ON sm.unique_id = r.schoolUniqueCode
WHERE r.status != 4 $condition ")->result_array();


	// if (empty($marksheet[0])) {
	// 	$msgArr = [
	// 		'class' => 'danger',
	// 		'msg' => 'Transfer Certificate Details Not Found. Please Generate Again.',
	// 	];
	// 	$this->session->set_userdata($msgArr);

	// 	header("Location: " . HelperClass::brandUrl);
	// }
}
// else{
// 	$msgArr = [
//         'class' => 'danger',
//         'msg' => 'Transfer Certificate Not Found. Please Generate Again.',
//     ];
//     $this->session->set_userdata($msgArr);

//     header("Location: ". HelperClass::brandUrl);
// }




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

		.resultTable {
			border: 1px solid black;
		}

		td,
		th {
			padding: 12px;
		}
	</style>

	<script src="https://code.jquery.com/jquery-1.8.2.js"></script>

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


<body>


	<p align="right"><button id="printbtn" onclick="window.print();">Print</button></p>


	<!--Generating Custom Title For Page-->
	<title>Marksheet : <?= $marksheet[0]['admission_no'] ?> | E-Marksheet Certificate - digitalfied.com</title>
	<!--Generating Custom Title For Page-->


	<div class="container">

		<table>
			<tr>
				<td><img src="<?= $marksheet[0]['logo'] ?>" width="180px" height="auto" /></td>
				<td class="text-center">
					<h2 style="font-size:26px;font-weight:bold"><?= strtoupper($marksheet[0]['school_name']) ?></h2>

					<p style="font-size:20px;font-weight:bold"><?= strtoupper($marksheet[0]['address'] . " " . $marksheet[0]['pincode']) ?></p>
					<p style="font-size:20px">Mobile: <?= $marksheet[0]['mobile'] ?> Email: <?= $marksheet[0]['email'] ?></p>
				</td>
				<td>

					<img class="qrcode" src="https://chart.googleapis.com/chart?chs=150x150&amp;cht=qr&amp;chl=<?= base_url('semResult?data=') . $classId . "-" . $sectionId ."-" . $examNameId . "-" . $studentId; ?>&amp;choe=UTF-8" alt="QR code" /> </br>
					<p>
						<center>Scan To Verify</center>
					</p>
				</td>
			</tr>
		</table>

		<h4 style="font-size: 25px; font-weight:bold">
			<center><?= $marksheet[0]['sem_exam_name'] ?> (Class <?=$marksheet[0]['className'] . " - " . $marksheet[0]['sectionName']?>) Result <?= $marksheet[0]['exam_year'] ?> </center>
		</h4>
		<br><br>
		<table border="0" width="100%">
			<tr class="my-2">
				<td>Roll No: </td>
				<th><?= $marksheet[0]['roll_no']; ?></th>
			</tr>
			<tr class="my-2">
				<td>Name: </td>
				<th><?= strtoupper($marksheet[0]['name']); ?> </th>
			</tr>
			<tr class="my-2">
				<td>Mother's Name: </td>
				<th><?= strtoupper($marksheet[0]['mother_name']); ?></th>
			</tr>
			<tr class="my-2">
				<td>Father's Name: </td>
				<th><?= strtoupper($marksheet[0]['father_name']); ?></th>
			</tr>
		</table>
		<br><br>
		<table class="table table-striped table-border mt-3 resultTable text-center" width="100%">
			<thead>
				<tr class="table-primary">
					<th class="text-center">S.No </th>
					<th>Subjects</th>
					<th class="text-center">Max Marks</th>
					<th class="text-center">Min Marks</th>
					<th class="text-center">Marks Obtained</th>
					<th class="text-center">Result Status</th>
				</tr>

			</thead>
			<tbody>
				<?php 
				$maxMarks = 0;
				$minMarks = 0;
				$totalObtainedMarks = 0;
				$j = 0;
				for($i=0; $total = count($marksheet), $i < $total; $i++)
				{ 
					$maxMarks += $marksheet[$i]['max_marks'];
					$minMarks += $marksheet[$i]['min_marks'];
					$totalObtainedMarks += $marksheet[$i]['marks'];
					
					?>
				<tr>
					<td><?= ++$j; ?>.</td>
					<td class="text-left"><?= $marksheet[$i]['subjectName']; ?></td>
					<td><?= $marksheet[$i]['max_marks']; ?></td>
					<td><?= $marksheet[$i]['min_marks']; ?></td>
					<td><?= $marksheet[$i]['marks']; ?></td>
					<td><?= $marksheet[$i]['resultStatus']; ?></td>
				</tr>
				<?php } ?>
				<tr style="border:1px;">
					<th style="border:1px;" colspan="2" class="text-center">Total</th>
					<th style="border:1px;" class="text-center"><?= $maxMarks; ?></th>
					<th style="border:1px;" class="text-center"><?= $minMarks; ?></th>
					<th style="border:1px;" class="text-center"><?= $totalObtainedMarks; ?></th>
					<th style="border:1px;" class="text-center"></th>
				</tr>
			</tbody>
		</table>
		<br><br>
		<table class="table table-borderless mt-2">
			<tr>
				<td>

					<!-- <hr> -->
					<center>Class Teacher Signature</center>
				</td>
				<td>

					<!-- <hr> -->
					<center>Checked By (Full Name & Designation )</center>
				</td>
				<td>

					<!-- <hr> -->
					<center>Principal Seal & Signature</center>
				</td>

				<td>


				</td>
			</tr>

		</table>

		<!-- <div style="clear:both"></div> -->
	</div>







	</div>


	</br>
	</br>
	<div class="container">
		<div class="row">
			<p style="text-align:justify">Disclaimer: This is a computer generated marksheet.</p>
		</div>
	</div>

</body>




</html>