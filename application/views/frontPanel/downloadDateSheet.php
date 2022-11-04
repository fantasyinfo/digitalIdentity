<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <title>DateSheet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    
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

			#container {
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

            #cTable td{
                border:1px solid black;
            }
			p3 {

				font-size: 14px;
				color: #FD0000;

			}

			p4 {

				font-size: 15px;
				color: #FF0004;

			}

            #myTR{
                color:white;background-color:black;
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
<?php 
error_reporting(0);
$schoolLogo = base_url() . HelperClass::schoolLogoImagePath;
// print_r($_GET);
$condition = '';
if(!empty($_GET['data']) && !empty($_GET['data']))
{
    $ddata = explode('-',$_GET['data']);

    $condition .= " AND sen.id = '{$ddata['2']}' ";
    $condition .= " AND c.id = '{$ddata['0']}' ";
    $condition .= " AND sec.id = '{$ddata['1']}' ";

    $d = $this->db->query("SELECT se.id as semId, se.schoolUniqueCode, se.exam_date,se.exam_day,se.exam_start_time,se.exam_end_time, se.min_marks, se.max_marks, se.status,sen.id as semNameId, sen.sem_exam_name, sen.exam_year,c.className,sec.sectionName,sub.subjectName
    FROM " .Table::secExamTable." se 
    LEFT JOIN ".Table::semExamNameTable." sen ON sen.id =  se.sem_exam_id
    LEFT JOIN ".Table::classTable." c ON c.id =  se.class_id
    LEFT JOIN ".Table::sectionTable." sec ON sec.id =  se.section_id
    LEFT JOIN ".Table::subjectTable." sub ON sub.id =  se.subject_id
    WHERE se.status != 4 $condition ")->result_array();


    if(!empty($d))
    {
        $data = $d;

        $schoolName = $this->db->query("SELECT sm.school_name,sm.mobile,sm.email,sm.address,CONCAT('$schoolLogo',sm.image) as logo,sm.pincode  FROM ".Table::schoolMasterTable ." sm WHERE sm.unique_id = '{$d[0]['schoolUniqueCode']}' LIMIT 1")->result_array()[0];

      
    }

}else
{
    // redirect to home
}




?>
    <div id="container">
            <table width="100%">  
			<tr>
				<td><img src="<?= $schoolName['logo'] ?>" width="180px" height="auto" /></td>
				<td class="text-center">
					<h2 style="font-size:26px;font-weight:bold"><?= strtoupper($schoolName['school_name']) ?></h2>

					<p style="font-size:20px;font-weight:bold"><?= strtoupper($schoolName['address'] . " " . $schoolName['pincode']) ?></p>
					<p style="font-size:16px">Mobile: <?= $schoolName['mobile'] ?>, Email: <?= $schoolName['email'] ?></p>
				</td>
				<td>

					<img class="qrcode" src="https://chart.googleapis.com/chart?chs=150x150&amp;cht=qr&amp;chl=<?= base_url('downloadDateSheet?data=') . $ddata['0'] . "-" . $ddata['1'] ."-" . $ddata['2'] ?>&amp;choe=UTF-8" alt="QR code" /> </br>
					<p>
						<center>Scan To Verify</center>
					</p>
				</td>
			</tr>
		</table>
           
        <h4 style="font-size: 25px; font-weight:bold">
			<center>Date Sheet For <?=  $data[0]['sem_exam_name'] . " " . $data[0]['exam_year']; ?></center>
			<center>Class <?=  $data[0]['className'] . " " . $data[0]['sectionName']; ?></center>
		</h4>
		<br><br>
           
                <table class="table table-border mt-3 table-striped text-center" width="100%" style="border:1px solid black;" id="cTable">
                    <thead>
                      <tr>
                        <th scope="col"><b>Date</b></th>
                        <th scope="col"><b>Day</b></th>
                        <th scope="col"><b>Subject Name</b></th>
                       
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach($data as $dd)
                        { ?>
                         <tr>
                            <td><?= date('d-m-Y', strtotime($dd['exam_date']));?></td>
                            <td><?=$dd['exam_day'];?></td>
                            <td scope="row"><?=$dd['subjectName'];?></td>
                         </tr>

                      <?php  }
                        
                        
                        ?>
                    </tbody>
                  </table>
    </div>

  </body>
</html>