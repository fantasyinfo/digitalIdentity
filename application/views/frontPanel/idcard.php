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
ORDER BY qr.id DESC")->result_array();



//  print_r($studentDetails);



?>
<html>

<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <script src="https://code.jquery.com/jquery-1.8.2.js"></script>

    <style>
        * {
            overflow-x: visible
        }

        #card {
            /* margin-top: 10px; */
            /* margin-right: 10px;
            margin-left: 10px; */
            background-color: #800000;
            -webkit-print-color-adjust: exact;
            color: #fff;
            padding:0.25cm;
        }

        #cardBody {
            background-color: #f2f2f2;
            /* margin-bottom: 10px; */
            /* margin-right: 10px;
            margin-left: 10px; */
            /* border: .1 px solid #000; */
            -webkit-print-color-adjust: exact;
            padding:0.25cm;
            border-bottom: 1px solid #800000;
        }

        .td{
                width: 5.5cm !important;
                height: 8.5cm !important;
                border: 1px dotted #000;
                padding:5px;
            }

        @media print {

            @page {
            size: A4,
            margin:5px;
        }
        

        body{
            font-family: sans-serif;
        }

            .p1 {
                font-size: 14px;
                font-weight: bold;
                line-height: 14px;
                margin: 0;
            }

            .p2 {
                font-size: 8px;
                line-height: 10px;
                margin: 0;
                margin-top: 3px;
            }

            .td{
                width: 5.5cm !important;
                border: 1px dotted #000;
                height: 8.5cm !important;
                margin:auto;
                padding:5px;
            }

            #card {
                background-color: #800000;
                -webkit-print-color-adjust: exact;
                color: #fff;
                /* margin-top: 10px; */
                /* margin-right: 10px;
                margin-left: 10px; */
                padding-top:0.25cm;
            }

            #cardBody {
                background-color: #f2f2f2;
                /* margin-bottom: 10px; */
                /* margin-right: 10px;
                margin-left: 10px; */
                 border-bottom: 1px solid #800000; 
                -webkit-print-color-adjust: exact;
                padding:0.25cm;
                
            }


            tr,
            th,
            td {
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

    <table>
        <tr>

   
    <?php

    $i = 0;
    foreach ($studentDetails as $s) {

        if ($i % 8 == 0) {
            echo '<div id="pageBeak"></div>';
        }

        if ($i % 3 == 0) {
            echo '</tr><tr>';
        }

    ?>
    <td class="td" >
    <div id="card">
            <table>
                <tr style="padding-left:5px;color:#fff;text-align:center;"> 
                    <td>
                    <img src="<?= $s['schoolImage']; ?>" alt="" height="50px" width="50px">
                    </td>
                    <td>
                
                        <p style="font-size:14px; font-weight:bold; line-height:18px;"><?= strtoupper($s['school_name']); ?></p>
                    </td>
                </tr>
            </table>
        </div>
        <div id="cardBody">
            <table>
                <tr>
                    
                    <td>
                        <img src="<?= $s['studentImage']; ?>" alt="" height="90px" width="80px" style="border:1px solid #800000 !important;">
                    </td>
                    <td>
                        <!-- <h6><?= $s['user_id'] ?></h6> -->
                        <img src="https://chart.googleapis.com/chart?chs=200x200&amp;cht=qr&amp;chl=<?= $s['qrcodeUrl']; ?>&amp;choe=UTF-8" alt="https://qverify.in?stuid=dvm-stu0000151" height="90px" width="90px">
                    </td>
                </tr>
            </table>
            <table style="font-size:12px;">
                <tr>
                    <td><b>Name : </b></td>
                    <td><?= $s['name']; ?></td>
                </tr>
                <tr>
                    <td><b>Class : </b></td>
                    <td><?= $s['classNames']; ?></td>
                </tr>
                <tr>
                    <td><b>Phone : </b></td>
                    <td><?= $s['studentMobile']; ?></td>
                </tr>
                <tr>
                    <td><b>Address : </b></td>
                    <td><?= $s['studentAddress'] . " " . $s['studentCity'] . " " . $s['studentPincode']; ?></td>
                </tr>
            </table>

            <?php
            echo '<img style="width:110px; padding:10px;" class="" src="data:image/png;base64,' . base64_encode($generator->getBarcode($s['user_id'], $generator::TYPE_CODE_128)) . '">';
            ?>
        </div>
    </td>
    
    <?php $i++;
    }  ?>


</tr>
    </table>

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