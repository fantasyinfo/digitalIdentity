<?php

require  HelperClass::barCodeFilePath;

$generator = new Picqer\Barcode\BarcodeGeneratorPNG();







$classId = '20';
$sectionId = '2';
$schoolUniqueCode = '683611';

$schoolLogo = base_url() . HelperClass::schoolLogoImagePath;
$studentImage = base_url() . HelperClass::studentImagePath;

$studentDetails = $this->db->query("SELECT qr.qrcodeUrl,qr.uniqueValue as qrName, CONCAT(cl.className, ' - ', se.sectionName) as classNames, st.roll_no, st.name,st.mobile as studentMobile,st.address as studentAddress, ct.cityName  as studentCity,st.pincode as studentPincode, sm.school_name, sm.address,sm.mobile,sm.pincode,CONCAT('$studentImage',st.image) as studentImage, CONCAT('$schoolLogo',sm.image) as schoolImage, st.user_id
FROM " . Table::qrcodeTable . " qr 
JOIN " . Table::studentTable . " st ON st.user_id = qr.uniqueValue
JOIN " . Table::classTable . " cl ON cl.id = st.class_id
JOIN " . Table::sectionTable . " se ON se.id = st.section_id
LEFT JOIN " . Table::cityTable . " ct ON ct.id = st.city_id
JOIN " . Table::schoolMasterTable . " sm ON sm.unique_id = st.schoolUniqueCode
WHERE qr.status != 0 
AND st.class_id = '$classId'
AND st.section_id = '$sectionId'
AND st.schoolUniqueCode = '$schoolUniqueCode'
ORDER BY qr.id DESC LIMIT 4")->result_array();



//  print_r($studentDetails);



?>
<html>

<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-1.8.2.js"></script>

    <style>
    * { overflow-x: visible }
    
        #card {
            width: 10.5cm  !important;
            height: 17cm !important;
            margin: 20px;
        }

        .card-header {
            background-color: #800000;
            -webkit-print-color-adjust: exact;
        }
        
        @media print {
       
            .p1{
                font-size:14px; font-weight:bold; line-height:14px;margin:0;
            }
            .p2{
                font-size:8px; line-height:10px;margin:0;margin-top:3px;
            }
            
            .rrow{
                display:flex;
                
            }
            col5{
                width:50%;
            }
            col4{
                width:40%;
            }
            col3{
                width: 25%;
            }
            col9{
                75%;
            }
    #card {
            width: 10.5cm  !important;
            height: 17cm !important;
            margin: 20px;
        }
        
        tr, th, td {
        page-break-inside: avoid !important;
    }

    #printbtn {
				display: none;
			}


      /*#pageBeak*/
      /*{*/
      /*  page-break-after: always;*/
      /*  page-break-inside: avoid;*/
      /*}*/
}


    </style>

    
    
</head>


<body>
<p align="right"><button id="printbtn" onclick="window.print();">Print</button></p>
    <div class="container">
        <div class="row" id="printAble">
            <?php

$i=1;
            foreach ($studentDetails as $s) { 
             
                if($i % 9 == 0)
                {
                    echo '<div id="pageBeak"></div>';
                }
                
                
                ?>
    
                <!--<div class="col-md-4 my-3">-->
                    <div class="card border" id="card" style="border:1px solid #800000 !important;">
                        <div class="card-header text-white" style="background-color: #800000;">
                            <div class="row rrow">
                                <div class="col-md-3 col3">
                                    <img src="<?= $s['schoolImage']; ?>" alt="" height="100px" width="100px">
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-8 col9">
                                    <p class="p1" style="font-size:20px; font-weight:bold; line-height:22px;margin:0;"><?= strtoupper($s['school_name']); ?></p>
                                    <p  class="p2"style="font-size:16px; line-height:11px;margin:0;margin-top:8px;"><?= strtoupper($s['address']) . ' ' . $s['pincode']; ?></p>
                                     <p  class="p2"style="font-size:16px; line-height:11px;margin:0;margin-top:8px;"><?= $s['mobile']; ?></p> 
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row rrow">
                                <div class="col-md-6 col5">
                                    <img src="<?= $s['studentImage']; ?>" alt="" height="220px" width="180px" style="border:1px solid #800000 !important;">
                                </div>


                                <div class="col-md-6 col4">
                                <h6 style="padding:5px; border-bottom:1px solid #800000;font-weight:bold;" class="text-center"><?=$s['user_id']?></h6>
                                    <img src="https://chart.googleapis.com/chart?chs=200x200&amp;cht=qr&amp;chl=<?= $s['qrcodeUrl']; ?>&amp;choe=UTF-8" alt="https://qverify.in?stuid=dvm-stu0000151" height="180px" width="180px">
                                        
                                 
                                </div>
                            </div>
                            <!-- <hr> -->
                            <table style="font-size:15px;" class="table mt-2">
                                <tr>
                                    <td><b>Name</b></td>
                                    <td><?= $s['name']; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Class</b></td>
                                    <td><?= $s['classNames']; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Phone</td>
                                    <td><?= $s['studentMobile']; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Address</b></td>
                                    <td><?= $s['studentAddress'] . " " . $s['studentCity'] . " " . $s['studentPincode']; ?></td>
                                </tr>
                            </table>

                            <?php
                            echo'<img style="width:360px;" class="" src="data:image/png;base64,' . base64_encode($generator->getBarcode($s['user_id'], $generator::TYPE_CODE_128)) . '">';
                            ?>
                        </div>
                    </div>
                <!--</div>-->
            <?php  $i++; }  ?>

        </div>


    </div>
    </div>

</body>






</html>



    <!-- <div class="card border" id="card" style="border:1px solid #800000 !important;">
        <div class="card-header text-white" style="background-color: #800000;">
            <div class="row">
                <div class="col-md-3">
                    <img src="https://dvm.digitalfied.in/assets/uploads/schoollogo/img-SCHOOL-1666144720istockphoto-1171617683-612x612.jpg" alt="" height="40px" width="40px">
                </div>
                <div class="col-md-9">
                    <p style="font-size:9px; font-weight:bold; line-height:8px;margin:0;">DIVINE GLOBAL ACADEMY</p>
                    <p style="font-size:7px; line-height:10px;margin:0;margin-top:3px;">Patti Chaudhran Baraut 250611</p>
                    <p style="font-size:7px; line-height:10px;margin:0;margin-top:3px;">+91 6396369663 +91 9632585214</p>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <img src="https://dvm.digitalfied.in/assets/uploads/student/img-stu0000151-1665989398630-01873773en_Masterfile.jpg" alt="" height="90px" width="70px" style="border:1px solid #800000 !important;">
                </div>


                <div class="col-md-4">
                    <img src="https://chart.googleapis.com/chart?chs=50x50&amp;cht=qr&amp;chl=https://qverify.in?stuid=dvm-stu0000151&amp;choe=UTF-8" alt="https://qverify.in?stuid=dvm-stu0000151" height="100px" width="90px">
                </div>
            </div>
            <hr>
            <table style="font-size:10px;">
                <tr>
                    <td><b>Name</b></td>
                    <td>Gaurav Sharma</td>
                </tr>
                <tr>
                    <td><b>Class</b></td>
                    <td>10th B</td>
                </tr>
                <tr>
                    <td><b>Phone</td>
                    <td>+91 6397520223</td>
                </tr>
                <tr>
                    <td><b>Address</b></td>
                    <td>12/190 Gali Shivpuri Patti Chaudhran Baraut Baghpat Uttar Pradesh 250611 India.</td>
                </tr>
            </table>

        </div>
    </div> -->
