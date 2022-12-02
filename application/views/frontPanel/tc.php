<?php

error_reporting(0);
$this->load->model('CrudModel');
$schoolLogo = base_url() . HelperClass::schoolLogoImagePath;
if (isset($_GET['tc_id'])) {
	$userId = explode('-', $_GET['tc_id']);;
	$tcId = $userId[0];
	$userId = $userId[1];

	$tcDetails = $this->db->query("SELECT st.*, sm.school_name, sm.mobile,sm.email,sm.address,CONCAT('$schoolLogo',sm.image) as logo,sm.pincode FROM " . Table::studentTC . " st 
	JOIN " . Table::schoolMasterTable . " sm ON sm.unique_id = st.schoolUniqueCode 
	JOIN " . Table::studentTable . " stu ON stu.id = st.student_id AND stu.schoolUniqueCode = st.schoolUniqueCode 
	WHERE st.id = '$tcId' AND stu.user_id = '$userId' LIMIT 1")->result_array()[0];

	if (empty($tcDetails)) {
		$msgArr = [
			'class' => 'danger',
			'msg' => 'Transfer Certificate Details Not Found. Please Generate Again.',
		];
		$this->session->set_userdata($msgArr);

		header("Location: " . HelperClass::brandUrl);
	}
} else {
	$msgArr = [
		'class' => 'danger',
		'msg' => 'Transfer Certificate Not Found. Please Generate Again.',
	];
	$this->session->set_userdata($msgArr);

	header("Location: " . HelperClass::brandUrl);
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
		@page {
			size: A4;
			margin: 1px;
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

		
		}
	</style>
</head>


<body>

	<p align="right"><button id="printbtn" onclick="window.print();">Print</button></p>

	<title>Admission No : <?= $tcDetails['admission_no'] ?> | E-Transfer Certificate - digitalfied.com</title>
	


	<div class="container">

		<table style="font-size:10px;" width="100%">
			<tr>
				<td><img src="<?= $tcDetails['logo'] ?>" width="70px" height="auto" /></td>
				<td class="text-center">
					<p style="font-size:33px;font-weight:bold;line-height:10px;"><?= strtoupper($tcDetails['school_name']) ?></p>
					<p style="font-size:16px;font-weight:bold;line-height:16px;"><?= strtoupper($tcDetails['address'] . " " . $tcDetails['pincode']) ?></br>
					(Affiliated to Central Board of Secondary Education, New Delhi)</br>
					Mobile: <?= $tcDetails['mobile'] ?> Email: <?= $tcDetails['email'] ?>
				</p>
				</td>
				<td>
					<img class="qrcode" src="https://chart.googleapis.com/chart?chs=100x100&amp;cht=qr&amp;chl=<?= base_url('tc?tc_id=') . $tcDetails['id'] . "-" . $tcDetails['user_id']; ?>&amp;choe=UTF-8" width="70px" height="auto"/>
				</td>
			</tr>
		</table>
		<h4 style="font-size: 15px; font-weight:bold">
			<center><i>स्थानांतरण प्रमाण पत्र / TRANSFER CERTIFICATE</i></center>
		</h4>

		<table class="table table-bordered table-striped" style="font-size:10px;" width="100%">
			<tr>
				<td>
					विद्यालय सं0 / School No : <b><?= $tcDetails['book_register_no'] ?></b>
				</td>
				<td>
					पुस्तक सं0 / Book No : <b><?= $tcDetails['book_register_no'] ?></b>
				</td>
				<td>
					क्र0 सं0 / SI No: <b><?= $tcDetails['s_i_s_r_no'] ?></b>
				</td>
				<td>
					प्रवेश सं0 / Admission No: <b><?= $tcDetails['admission_no'] ?></b>
				</td>
			</tr>
			<tr>
				<td>
					Affiliation No : <b><?= $tcDetails['book_register_no'] ?></b>
				</td>
				<td>
					Renewed upto : <b><?= $tcDetails['book_register_no'] ?></b>
				</td>
				<td colspan="2">
					Status of the school : <b><?= $tcDetails['s_i_s_r_no'] ?></b>
				</td>
			</tr>
			<tr>
				<td colspan="4">
					Registration Number of the candidate (in case Class IX to XII) : <b><?= $tcDetails['s_i_s_r_no'] ?></b>
				</td>
			</tr>

			<tr>
				<td colspan="4">विद्यार्थी का नाम / Name Of Pupil: <b><?= strtoupper($tcDetails['student_name']) ?></b></td>
			</tr>
			<tr>
				<td colspan="4">पिता/अभिभावक का नाम / Father's/Guardian's Name: <b><?= strtoupper($tcDetails['father_name']) ?></b></td>
			</tr>
			<tr>
				<td colspan="4">माता का नाम / Mother's Name: <b><?= strtoupper($tcDetails['mother_name']) ?></b></td>
			</tr>

			<tr>
				<td colspan="4">प्रवेश पुस्तिका के अनुसार जन्म तिथि / Date of birth according to the Admission Register (अंकों में /in figure): <b><?= date('d-m-Y', strtotime($tcDetails['date_of_birth'])); ?> <?php echo $this->CrudModel->dateToWords(date('d', strtotime(@$tcDetails['date_of_birth'])));
																																																					echo  ' ' . date('F', strtotime(@$tcDetails['date_of_birth']));
																																																					echo ' ' .  $this->CrudModel->dateToWords(date('Y', strtotime(@$tcDetails['date_of_birth']))); ?></b></td>
			</tr>
			<tr>
				<td colspan="4">राष्ट्रीयता / Nationality : <b><?= $tcDetails['nationality']; ?></b></td>
			</tr>
			<tr>
				<td colspan="4">विद्यालय में प्रथम प्रवेश की तिथि व कक्षा / Date of the first admission in the school with class : <b><?= date('d-m-Y', strtotime($tcDetails['date_of_admission'])); ?></b></td>
			</tr>
			<tr>
				<td colspan="4">क्या छात्र अनुसूचित जाति / अनुसूचित जनजाति / अन्य पिछड़ा वर्ग से संबंधित है / Whether the pupil belongs to SC/ST/OBC category: <b>No</b></td>
			</tr>
			<tr>
				<td colspan="4">क्या छात्र का परिणाम अनुत्तीर्ण है ? / Whether the student is failed ? <b>No</b></td>
			</tr>
			<tr>
				<td colspan="4">पिछली कक्षा जिसमें विद्यार्थी अध्ययनरत था / Class in which the pupil last studied :
					<b><?= $tcDetails['last_class_studies']; ?></b>
				</td>
			</tr>
			<tr>
				<td colspan="4">पिछले विद्यालय / बोर्ड परीक्षा एवं परिणाम /School / Board Annual Examination Last Taken With Result :
					<b><?= strtoupper($tcDetails['board_exam_last_taken']); ?> EXAM- PASSED</b>
				</td>

			</tr>
			<tr>
				<td colspan="4">क्या विद्यार्थी का परीक्षा परिणाम अनुत्तीर्ण है /Whether the student is failed :
					<b><?= $tcDetails['failed_in_class']; ?> No</b>
				</td>

			</tr>
			<tr>
				<td colspan="4">प्रस्तावित विषय /Subject Studied : <b><?= $tcDetails['subjects_studies']; ?></b></td>
			</tr>
			<tr>
				<td colspan="4">क्या उच्च कक्षा में पदोन्नति के लिए योग्य हैं / Whether Qualified For Promotion To The Higher Class :
					<b><?= $tcDetails['qualify_for_permotion']; ?></b>
				</td>
			</tr>
			<tr>
				<td colspan="4">क्या विद्यार्थी ने विद्यालय की सभी देय राशि का भुगतान कर दिया है / Whether The Pupil Has Paid All Dues To The School :<b><?= $tcDetails['fees_due']; ?></b></td>
			</tr>
			<tr>
				<td colspan="4">क्या विद्यार्थी को कोई शुल्क रियायत प्रदान की गई है यदि हां तो उसकी प्रति/ Whether The Pupil Was In Reciept Any Concession , If So The Nature Of Such Concession :<b>No</b></td>
			</tr>
			<tr>
				<td colspan="4">अंतिम तिथि तक उपस्थितियो की कुल संख्या/ Number of meetings up to date :<b><?= $tcDetails['total_working_days']; ?></b></td>
			</tr>
			<tr>
				<td colspan="4">विद्यार्थी के विद्यालय में दिवसों की कुल उपस्थितिय/ Number of the school days pupil attended :<b><?= $tcDetails['total_present_days']; ?></b></td>
			</tr>
			<tr>
				<td colspan="4">क्या विद्यार्थी एन0सी0सी0 कैडेट है/ स्काउट है/ विवरण दें/ Whether the pupil in NCC Cadet/ Boy Scout/ Girl Guide (give details) :<b><?= $tcDetails['ncc_cadet']; ?></b></td>
			</tr>
			<tr>
				<td colspan="4">विद्यार्थी ने किन किन खेलों में अथवा पाठ्यक्रमों में क्रियाकलापों में भाग लिया है /Games Played For Extra Curricular Activities In Which The People Usually Took Part :<b><?= $tcDetails['game_played']; ?></b></td>
			</tr>
			<tr>
				<td colspan="4">सामान्य आचरण /General Conduct:<b>Good</b></td>
			</tr>
			<tr>
				<td colspan="4">विद्यालय छोड़ने का कारण/Reason For Leaving School:<b><?= $tcDetails['reason_for_leaving']; ?></b></td>
			</tr>
			<tr>
				<td colspan="4">कोई अन्य टिप्पणी /Any other Remark:<b><?= $tcDetails['remark']; ?></b></td>
			</tr>
			<tr>
				<td colspan="4">प्रमाण पत्र के लिए आवेदन की तिथि /Date Of Application For Certificate : <b><?= date('d-m-Y', strtotime($tcDetails['date_of_application'])); ?></b></td>
			</tr>
			<tr>
				<td colspan="4">प्रमाण पत्र जारी करने की तिथि /Date Of Issue Of Certificate : <b><?= date('d-m-Y', strtotime($tcDetails['date_of_issue'])); ?></b></td>
			</tr>

		</table>
		<br>
		<table class="table table-borderless mt-2" style="font-size: 10px;">
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


</body>




</html>