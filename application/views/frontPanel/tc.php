
<?php

error_reporting(0);
$schoolLogo = base_url().HelperClass::schoolLogoImagePath;
if(isset($_GET['tc_id']))
{
	$userId = explode('-',$_GET['tc_id']);
	;
	$tcId = $userId[0];
	$userId = $userId[1];

	$tcDetails = $this->db->query("SELECT st.*, sm.school_name, sm.mobile,sm.email,sm.address,CONCAT('$schoolLogo',sm.image) as logo,sm.pincode FROM ".Table::studentTC." st 
	JOIN ".Table::schoolMasterTable." sm ON sm.unique_id = st.schoolUniqueCode 
	JOIN ".Table::studentTable." stu ON stu.id = st.student_id AND stu.schoolUniqueCode = st.schoolUniqueCode 
	WHERE st.id = '$tcId' AND stu.user_id = '$userId' LIMIT 1")->result_array()[0];

	if(empty($tcDetails))
	{
		$msgArr = [
			'class' => 'danger',
			'msg' => 'Transfer Certificate Details Not Found. Please Generate Again.',
		];
		$this->session->set_userdata($msgArr);
	
		header("Location: ". HelperClass::brandUrl);
	}
}else{
	$msgArr = [
        'class' => 'danger',
        'msg' => 'Transfer Certificate Not Found. Please Generate Again.',
    ];
    $this->session->set_userdata($msgArr);

    header("Location: ". HelperClass::brandUrl);
}




?>


<html>
<head>
	
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">

<!--Page Loader Style --><!--Page Loader Style --><!--Page Loader Style --><!--Page Loader Style --><!--Page Loader Style --><!--Page Loader Style --><!--Page Loader Style --><!--Page Loader Style -->
	<style> 
        #loader {  
    position: fixed;  
    left: 0px;  
    top: 0px;  
    width: 100%;  
    height: 100%;  
    z-index: 9999;  
    background: url('images/processing.gif') 50% 50% no-repeat rgb(249,249,249);  
}  
    </style> 
<!--Page Loader Style --><!--Page Loader Style --><!--Page Loader Style --><!--Page Loader Style --> 

<!--Page Loader Javascript --><!--Page Loader Javascript --><!--Page Loader Style -->   
<script src="https://code.jquery.com/jquery-1.8.2.js"></script> 

<!-- <script type="text/javascript">  
   $(window).load(function() {  
      $("#loader").fadeOut(1000);  
   });
</script>  -->
<!--Page Loader Javascript --><!--Page Loader Javascript --><!--Page Loader Javascript -->
		
		<style>
			
			@page {
        size: A4;
        margin: 10px;
    }
    @media print {
	#printbtn {
    display :  none;
    }
        html, body {	
                   
        }
		body
		{
			margin:0;
			padding:0;
			font-size:12px;
			font-family:Segoe, Segoe UI, DejaVu Sans, Trebuchet MS, Verdana," sans-serif";
		}
		u {    
    border-bottom: 1px dashed #515151;
    text-decoration: none;
           }
		.container
		{
			margin:0 auto;
			padding:7px;
			/* background-color:#f1f1f1; */
			margin-top:0px;
			border:1px solid #ccc;
			width:100%;
			
		}
		h1
		{
			font-size:22px;
			color:#0086b3;
			line-height:30px;
		}
		p
		{
			
			font-size:13px;
			
			color:#333;
		}
		p2
		{
			
			font-size:10px;
			color: #001B9E;
		}
		p3
		{
			
			font-size:14px;
			color:#FD0000;
			
		}
		 p4
		{
			
			font-size:15px;
			color:#FF0004;
			
		}	
		img
		{
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


		
                 <p align="right"><button id ="printbtn" onclick="window.print();">Print</button></p> 
			
		 
			
			
				 <!-- Array ( [id] => 2 [schoolUniqueCode] => 683611 [book_register_no] => 01 [s_i_s_r_no] => 11 [admission_no] => 00112255 [student_id] => 26 [student_name] => Tonny [father_name] => a [mother_name] => a [gender] => Male [category] => Common [nationality] => Indian [date_of_admission] => 2022-10-21 [date_of_birth] => 2022-10-21 [last_class_studies] => 2nd - B [board_exam_last_taken] => CBSE Board [failed_in_class] => NO [subjects_studies] => Hindi,English,Maths,Science [qualify_for_permotion] => Yes [fees_due] => Yes [total_working_days] => 196 [total_present_days] => 140 [ncc_cadet] => NO [game_played] => Cricket [general_conduct] => Good [date_of_application] => 2022-10-21 [date_of_issue] => 2022-10-21 [reason_for_leaving] => Transfer of Father [remark] => NA [shedule_tribe] => No [session_table_id] => 2 [status] => 1 [created_at] => 2022-10-21 08:21:07 [school_name] => Dummy Public School [mobile] => 9638521470 [email] => growell@gmail.com [address] => abc building baraut [image] => img-SCHOOL-1666145548istockphoto-1171617683-612x612.jpg [pincode] => 250611 )	 -->

				
								
<!--Generating Custom Title For Page-->
<title>Admission No : <?= $tcDetails['admission_no'] ?> | E-Transfer Certificate - digitalfied.com</title>
<!--Generating Custom Title For Page-->


<div class="container">
			
<table >
	<tr>
		<td><img src="<?=$tcDetails['logo']?>" width="180px" height="auto"/></td>
		<td class="text-center">
			<h2 style="font-size:26px;font-weight:bold"><?= strtoupper($tcDetails['school_name'])?></h2>
		
			<p style="font-size:20px;font-weight:bold"><?= strtoupper($tcDetails['address'] . " " .$tcDetails['pincode'])?></p>
			<p style="font-size:20px">Mobile: <?=$tcDetails['mobile']?> Email:  <?=$tcDetails['email']?></p>
		</td>
		<td >
		<img class="qrcode" src="https://chart.googleapis.com/chart?chs=150x150&amp;cht=qr&amp;chl=<?=base_url('tc?tc_id=') . $tcDetails['id'] . "-" . $tcDetails['user_id'];?>&amp;choe=UTF-8" alt="QR code" /> </br><p><center>Scan To Verify</center></p>
		</td>
	</tr>
</table>
			
			
			<h4 style="font-size: 40px; font-weight:bold"><center><i>TRANSFER CERTIFICATE</i></center> </h4>
			
	<!-- <table class="table table-borderless">
	
		
	</table>	 -->
	
		
<table class="table table-bordered table-striped" width="100%">				
<tr>
	<th scope="col"><h4>Book No : <u><b><?= $tcDetails['book_register_no'] ?></b></u></h4></th>
    <th scope="col"><h4>SI No: <u><b><?= $tcDetails['s_i_s_r_no'] ?></b></u></h4></th>
    <th colspan="2"><h4>Admission No: <u><b><?= $tcDetails['admission_no'] ?></b></u></h4></th>
    <!-- <th scope="col"><h4>TC No: <u><b><?= $tcDetails['id'] ?></b></u></h4></th>		 -->
	</tr>	
	
	<tr>
	<td colspan="4">Full Name Of Pupil: <u><b><?= strtoupper($tcDetails['student_name']) ?></b></u></td>		
	</tr>
	<tr>
	<td colspan="4">Father's/Guardian's Name: <u><b><?= strtoupper($tcDetails['father_name']) ?></b></u></td>	
	</tr>
	<tr>
	<td colspan="4">Mother's Name: <u><b><?= strtoupper($tcDetails['mother_name']) ?></b></u></td>	
	</tr>
	 <tr>
      <td >Gender : <u><b><?= $tcDetails['gender'] ?></b></u></td>
      <td>Category : <u><b><?= $tcDetails['category'] ?></b></u></td>
	  <td>Nationality : <u><b><?= $tcDetails['nationality']; ?></b></u></td>
	  
    </tr>
 <tr>
 		<!-- <td>General Conduct : <u><b><?= $tcDetails['general_conduct']; ?></b></u></td> -->
     
	  <td>Whether Failed : <u><b><?= $tcDetails['failed_in_class']; ?></b></u></td> 
	  <td>Admission Date : <u><b><?= date('d-m-Y', strtotime($tcDetails['date_of_admission'])); ?></b></u></td>
	  <td>Date Of Birth : <u><b><?= date('d-m-Y', strtotime($tcDetails['date_of_birth'])); ?></b></u></td>
    </tr>


		
<tr>
 <td colspan="4">Weather Ncc Cadet / Boy Scout / Girl Guide Details Be Given : 
 <b><u><?= $tcDetails['ncc_cadet']; ?></b></u>
 </td>
 </tr>
 <tr>
 <td colspan="4">Games Played For Extra Curricular Activities In Which The People Usually Took Part : 
 <u><b><?= $tcDetails['game_played']; ?></b></u>
 </td>		
</tr>

<tr>
 <td colspan="4">School Board Annual Examination Last Taken With Result : 
 <u><b><?= strtoupper($tcDetails['board_exam_last_taken']); ?> EXAM- PASSED</b></u>
 </td>
 		
</tr>
<tr>
 <td colspan="4">Standard In Which The Pupil Was Studying At The Time Of Leaving The School : 
 <b><u><?= $tcDetails['last_class_studies']; ?></b></u>
 </td>		
</tr>
<tr>
 <td colspan="4">Subject Studied : <u><b><?= $tcDetails['subjects_studies']; ?></b></u></td>		
</tr>
<tr>
 <td colspan="4">Whether Candidate Belongs To Scheduled Caste Or Schedule Tribe : 
 <b><u><?= $tcDetails['shedule_tribe']; ?></b></u></td>
 </tr>
<tr>
 <td colspan="2">Whether Qualified For Promotion To The Higher Class If Yes, Specify : 
 <u><b><?= $tcDetails['qualify_for_permotion']; ?></b></u>
 </td>
 
 <td colspan="2">Whether The Pupil Has Paid All The Fees Due To The School : 
 <u><b><?= $tcDetails['fees_due']; ?></b></u>
 </td>		
</tr>	
<tr>
      
 
	  <td colspan="2">Date Of Application For Certificate : <u><b><?= date('d-m-Y', strtotime($tcDetails['date_of_application'])); ?></b></u></td>
	 <td colspan="2">Date Of Issue Of Certificate : <u><b><?= date('d-m-Y', strtotime($tcDetails['date_of_issue'])); ?></b></u></td>
		 
		 </tr>		
<tr>
 <td colspan="2">Total Number Of Working Days Upto The Date Of Leaving : 
 <u><b><?= $tcDetails['total_working_days']; ?></b></u>
 </td>
 
 <td colspan="2">Total Number Of School Days Attended : 
 <u><b><?= $tcDetails['total_present_days']; ?></b></u>
 </td>		
</tr>



 <tr>
 <td colspan="2">Reason For Leaving School : <u><b><?= $tcDetails['reason_for_leaving']; ?></b></u></td>
 <td colspan="2">Other Remakrks : <u><b><?= $tcDetails['remark']; ?></b></u></td>		
</tr>	
  

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

				
</body>	




</html>


			




			
