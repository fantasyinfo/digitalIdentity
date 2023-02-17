<?php

require  HelperClass::barCodeFilePath;

$schoolUniqueCode = $_SESSION['schoolUniqueCode'];

$schoolLogo = base_url() . HelperClass::schoolLogoImagePath;
$studentImage = base_url() . HelperClass::teacherImagePath;

$studentDetails = $this->db->query($sql = "SELECT qr.qrcodeUrl,qr.uniqueValue as qrName, 
CONCAT(cl.className, ' - ', se.sectionName) as classNames, 
st.name,
st.mobile as studentMobile,
st.address as studentAddress, 
ct.cityName  as studentCity,
st.pincode as studentPincode, 
sm.school_name, 
sm.address,
sm.mobile,
sm.pincode,
sm.email as schoolEmail , 
CONCAT('$studentImage',st.image) as studentImage, 
CONCAT('$schoolLogo',sm.image) as schoolImage, 
st.user_id
FROM " . Table::qrcodeTeachersTable . " qr 
LEFT JOIN " . Table::teacherTable . " st ON st.user_id = qr.uniqueValue
LEFT JOIN " . Table::classTable . " cl ON cl.id = st.class_id
LEFT JOIN " . Table::sectionTable . " se ON se.id = st.section_id
LEFT JOIN " . Table::cityTable . " ct ON ct.id = st.city_id
JOIN " . Table::schoolMasterTable . " sm ON sm.unique_id = st.schoolUniqueCode
WHERE qr.status != 0 
AND st.status NOT IN ('3','4')
AND st.schoolUniqueCode = '$schoolUniqueCode'
ORDER BY qr.id DESC LIMIT 1")->result_array();
// echo $sql;


  //print_r($studentDetails);
$color = 'blue';
if(isset($_GET['color'])){
    $color = $_GET['color'];
}



?>
<html>

<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />


    <style>
        * {
            overflow-x: visible;
            margin: 0;
            padding: 0;
        }

        .td {
            width: 5.4cm !important;
            height: 8.6cm !important;
            /* width: 6.21cm !important;
            height: 9.2cm !important; */
            background-image: url('<?= base_url("assets/uploads/try_1.png") ?>');
            background-repeat: no-repeat;
            background-position: center;

        }

        body {
            font-family: sans-serif;
        }


        p{
          margin-bottom: none !important;
        }
        @media print {

            @page {
                size: A4;
                margin: 5px;

            }


            body {
                font-family: sans-serif;
            }

            .td {
                width: 5.4cm !important;
                height: 8.6cm !important;
                background-image: url('<?= base_url("assets/uploads/try_1.png") ?>');
                background-repeat: no-repeat;
                background-position: center;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                 border: 1px dotted #000; 
            }

            tr,
            th,
            td {
                page-break-inside: avoid !important;
            }

            #printbtn {
                display: none;
            }

        }
    </style>



</head>


<body>


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
                            <tr style="text-align:center;padding-left:5px;padding-right:5px;">
                                <td>
                                    <img src="<?= $s['schoolImage']; ?>" alt="" height="40px" width="40px" style="border-radius:50%;backgroun-color:#fff;">
                                </td>
                                <td>

                                    <p style="font-size:10px; font-weight:bold;margin-bottom:0px;"><?= strtoupper($s['school_name']); ?></p>
                                    <p style="font-size:7px;margin-bottom:0px;"><?= $s['address']; ?></p>
                                    <p style="font-size:7px;margin-bottom:0px;">Mo: <?= $s['mobile']; ?> Email: <?= $s['schoolEmail']; ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="cardBody">
                        <div style="text-align:center;">
                            <img src="<?= $s['studentImage']; ?>" height="100px" width="100px" style="border-radius:50%;">
                        </div>
                       
                        <div style="text-align:center;">
                        <p style="font-size:10px; font-weight:bold;"><?= $s['user_id'] ?></p>
                        </div>

                        <div style="text-align:center;">
                            <img src="https://chart.googleapis.com/chart?chs=200x200&amp;cht=qr&amp;chl=<?= $s['qrcodeUrl']; ?>&amp;choe=UTF-8" alt="<?= $s['qrcodeUrl']; ?>" height="70px" width="70px">
                        </div>

                        <table style="font-size:10px;padding-left:5px;padding-right: 5px;">

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
                                <td><?=  $s['studentCity'] ?></td>
                            </tr>
                        </table>
                    </div>
                </td>

            <?php $i++;
            }  ?>


        </tr>
    </table>
    <p align="right"><button id="printbtn" onclick="window.print();">Print</button></p>
</body>


</html>