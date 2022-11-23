<?php


$this->load->library('session');
$this->load->model('CrudModel');

$dir = base_url() . HelperClass::uploadImgDir;
$schoolLogo = base_url() . HelperClass::schoolLogoImagePath;
$studentImage = base_url() . HelperClass::studentImagePath;

if (isset($_GET['fees_id'])) {
    $this->load->model('CrudModel');
    $tokenFiter = $this->db->query("SELECT * FROM " . Table::tokenFilterTable . " WHERE token = '{$_GET['fees_id']}' AND status = '1' LIMIT 1")->result_array()[0];

    if (!empty($tokenFiter)) {
        $feesInvoiceData = $this->db->query("SELECT nfsm.*, nftt.feeTypeName, nfgt.feeGroupName,nfmt.amount FROM " . Table::newfeessubmitmasterTable . " nfsm
        INNER JOIN " . Table::newfeesgroupsTable . " nfgt ON nfgt.id = nfsm.nfgId
        INNER JOIN " . Table::newfeestypesTable . " nftt ON nftt.id = nfsm.nftId
        INNER JOIN " . Table::newfeemasterTable . " nfmt ON nfmt.id = nfsm.fmtId
        WHERE nfsm.randomToken = '{$tokenFiter['token']}' AND nfsm.status = '1' AND nfsm.schoolUniqueCode = '{$tokenFiter['schoolUniqueCode']}' AND nfsm.id ='{$tokenFiter['insertId']}' LIMIT 1")->result_array()[0];

        // print_r($feesInvoiceData);die();
        if (empty($feesInvoiceData)) {
            header("Location: " . HelperClass::brandUrl);
        }
    }
} else {
    // redirect to homepage
}


$schoolDetails = $this->db->query("SELECT sm.school_name, sm.mobile,sm.email,sm.address,CONCAT('$schoolLogo',sm.image) as logo,sm.pincode FROM " . Table::schoolMasterTable . " sm WHERE sm.unique_id = '{$feesInvoiceData['schoolUniqueCode']}' LIMIT 1")->result_array()[0];







$sql = "SELECT st.*,CONCAT('$studentImage',st.image) as studentImage,ct.className, sect.sectionName,state.stateName,city.cityName
FROM " . Table::studentTable . " st 
LEFT JOIN " . Table::classTable . " ct ON ct.id = st.class_id
LEFT JOIN " . Table::sectionTable . " sect ON sect.id = st.section_id
LEFT JOIN " . Table::stateTable . " state ON state.id = st.state_id
LEFT JOIN " . Table::cityTable . " city ON city.id = st.city_id
WHERE st.id = '{$feesInvoiceData['stuId']}' AND st.schoolUniqueCode = '{$feesInvoiceData['schoolUniqueCode']}'
LIMIT 1";



$studentData = $this->db->query($sql)->result_array()[0];
// print_r($studentData);

?>

<!-- Content Wrapper. Contains page content -->



<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

    <style>
           #printArea{
                height: 12cm;
                width:18cm;
                border: 1px solid #000;
            }

        @media print {
            #printbtn {
                display: none;
            }

            @page {
                size: A4;
                /* margin: 10px; */

            }

            #printArea{
                height: 12cm;
                width:18cm;
                border: 1px solid #000;
            }



        }
    </style>
</head>

<body>

<p align="right"><button id="printbtn" onclick="window.print();">Print</button></p>
    <div class="container">
        <div class="row">
            <div class="col-md-6" id="printArea">
                <div class="container">
                    <div class="row">
                       
                        <h2 class="text-center" style="font-size: 16px; font-weight:bold; margin-bottom:10px; border-bottom:1px solid #000;"><i>Fees Deposit Receipt</i></h2>
                       
                        <table>
                            <tr>
                                <td><img src="<?= $schoolDetails['logo'] ?>" width="70px" height="auto" /></td>
                                <td class="text-center">
                                    <h2 style="font-size:14px;font-weight:bold"><?= strtoupper($schoolDetails['school_name']) ?></h2>

                                    <h4 style="font-size:12px;font-weight:bold">
                                        <?= strtoupper($schoolDetails['address'] . " " . $schoolDetails['pincode']) ?></h4>
                                    <!-- <h4 style="font-size:17px">Mobile: <?= $schoolDetails['mobile'] ?> Email:
                            <?= $schoolDetails['email'] ?></h4> -->
                                </td>
                                <td>
                                <img class="qrcode" src="https://chart.googleapis.com/chart?chs=100x100&amp;cht=qr&amp;chl=<?= base_url('feesInvoice') . "?fees_id=" . $_GET['fees_id']; ?>&amp;choe=UTF-8" alt="QR code">
                                </td>
                            </tr>

                        </table>

                        <table class="table table-borderless my-2" style="font-size:14px;">
                            <tr>
                                <td>Date: <b><?= date('d-F-Y', strtotime($feesInvoiceData['depositDate'])); ?></b></td>
                                <td>
                                    Fees Invoice No: <b># <?= $feesInvoiceData['invoiceId']; ?></b>
                                </td>
                            </tr>
                            <tr>
                                <td>Student Name: <b><?= $studentData['name']; ?></b></td>
                                <td>Class : <b><?= $studentData['className'] . " ( " . $studentData['sectionName'] . " ) "; ?></b></td>
                            </tr>
                            <tr>
                                <td>Father's Name: <b><?= $studentData['father_name']; ?></b></td>
                                <td>Payment Mode : <b><?php if ($feesInvoiceData['paymentMode'] == '2') {
                                                            echo 'Online';
                                                        } else {
                                                            echo 'Cash';
                                                        } ?></b></td>
                            </tr>
                        </table>
                        <table class="table mb-0 align-middle bg-white my-2" style="font-size:14px;border:1px solid #000">
                            <thead class="bg-light">
                                <tr>
                                    <th>Group</th>
                                    <th>Type</th>
                                    <th>Deposit</th>
                                    <th>Discounts</th>
                                    <th>Fine</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $feesInvoiceData['feeGroupName']; ?></td>
                                    <td><?= $feesInvoiceData['feeTypeName']; ?></td>
                                    <td>₹ <?= number_format($feesInvoiceData['depositAmount'], 2); ?></td>
                                    <td>₹ <?= number_format($feesInvoiceData['discount'], 2); ?></td>
                                    <td>₹ <?= number_format($feesInvoiceData['fine'], 2); ?></td>

                                </tr>
                            </tbody>
                        </table>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <table class="table table-borderless" style="font-size:14px; text-align:right;">
                                        <tbody>
                                            <tr>
                                                <th style="width:50%">Total Deposit Amt </th>
                                                <td>₹ <?= number_format($feesInvoiceData['depositAmount'], 2); ?>/-</td>
                                            </tr>
                                            <tr>
                                                <th>Total Amount ( Deposit + Fine )</th>
                                                <td>₹ <?php
                                                        $tTotal = $feesInvoiceData['depositAmount'] + $feesInvoiceData['fine'];
                                                        echo number_format($tTotal, 2); ?>/-</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>



</body>

</html>