<?php

error_reporting(0);
$schoolLogo = base_url() . HelperClass::schoolLogoImagePath;
$gatePassImage = base_url() . HelperClass::gatePassImagePath;

if (isset($_GET['gatePass'])) {
    $id = $_GET['gatePass'];

    $gatePass = $this->db->query("SELECT g.*, CONCAT('$gatePassImage',g.image) as gImage, sm.school_name, sm.mobile as schoolMobile,sm.email,sm.address as schoolAddress,CONCAT('$schoolLogo',sm.image) as logo,sm.pincode , stu.name as studentName, CONCAT(cl.className , ' ( ' , sec.sectionName, ' ) ') as class FROM " . Table::gatePassTable . " g
	JOIN " . Table::schoolMasterTable . " sm ON sm.unique_id = g.schoolUniqueCode 
	JOIN " . Table::studentTable . " stu ON stu.id = g.student_id
	JOIN " . Table::classTable . " cl ON cl.id = g.class_id
	JOIN " . Table::sectionTable . " sec ON sec.id = g.section_id
	WHERE g.id = '$id' LIMIT 1")->result_array()[0];

    // if(empty($gatePass)){
    //     echo $this->db->last_query();
    // }

    // if (empty($gatePass)) {
    //     $msgArr = [
    //         'class' => 'danger',
    //         'msg' => 'Transfer Certificate Details Not Found. Please Generate Again.',
    //     ];
    //     $this->session->set_userdata($msgArr);

    //     header("Location: " . HelperClass::brandUrl);
    //
}
// } else {
//     $msgArr = [
//         'class' => 'danger',
//         'msg' => 'Transfer Certificate Not Found. Please Generate Again.',
//     ];
//     $this->session->set_userdata($msgArr);

//     header("Location: " . HelperClass::brandUrl);
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
    <title>Gate Pass | E-Gate Pass - digitalfied.com</title>
</head>

<body>
    <p align="right"><button id="printbtn" onclick="window.print();">Print</button></p>
    
    <div class="container" style="border: 1px solide #000;">

        <table>
            <tr>
                <td><img src="<?= $gatePass['logo'] ?>" width="120px" height="auto" /></td>
                <td class="text-center">
                    <h2 style="font-size:20px;font-weight:bold"><?= strtoupper($gatePass['school_name']) ?></h2>

                    <p style="font-size:16px;font-weight:bold"><?= strtoupper($gatePass['schoolAddress'] . " " . $gatePass['pincode']) ?></p>
                    <p style="font-size:16px">Mobile: <?= $gatePass['schoolMobile'] ?> Email: <?= $gatePass['email'] ?></p>
                </td>
                <td class="float-right">
                    <img class="qrcode" src="https://chart.googleapis.com/chart?chs=120x120&amp;cht=qr&amp;chl=<?= base_url('gatePass?gatePass=') . $_GET['gatePass'] ; ?>&amp;choe=UTF-8" alt="QR code" /> </br>
                    <p>
                        <center>Scan To Verify</center>
                    </p>
                </td>
            </tr>
        </table>


        <h4 style="font-size: 30px; font-weight:bold">
            <center>GATE PASS</center>
        </h4>


        <?php
        // echo '<pre>';
        // print_r($gatePass);
        ?>



                <table class="table">
                    <tbody>
                        <tr>
                            <td> 
                                <img src="<?= $gatePass['gImage']; ?>" width="150px" height="150px" />
                            </td>
                            <td style="line-height:30px;font-size:18px;">
                                Date & Time: <b><?= date('d F Y h:i A' , strtotime($gatePass['date'] . $gatePass['time'])) ; ?></b> </br>
                                Student Name: <b><?= $gatePass['studentName']; ?></b> </br>
                                Class: <b><?= $gatePass['class']; ?></b> </br>
                                Guardians Name: <b><?= $gatePass['guardian_name']; ?></b> </br>
                                Mobile: <b><?= $gatePass['mobile']; ?></b>  </br>
                                Address: <b><?= $gatePass['address']; ?></b>  </br>
                            </td>
                        </tr>
                    </tbody>
                </table>
         
    </div>
    <hr style="border:1px solid dotted;">


</body>

</html>