<?php



// print_r($_GET);
// die();

$nftid = $_GET['feesTypeId'];
$nfgid = $_GET['feesGroupId'];
$nfmid = $_GET['feesMasterId'];
$studentId = $_GET['studentId'];
$classId = $_GET['classId'];
$sectionId = $_GET['sectionId'];


$this->load->library('session');
$this->load->model('CrudModel');

$dir = base_url() . HelperClass::uploadImgDir;
$schoolLogo = base_url() . HelperClass::schoolLogoImagePath;
$studentImage = base_url() . HelperClass::studentImagePath;

if (isset($_GET['feesTypeId'])) {
    $this->load->model('CrudModel');

    $feesInvoiceData = $this->db->query($sql = "SELECT nfsm.*, nftt.feeTypeName, nfgt.feeGroupName,nfmt.amount FROM " . Table::newfeessubmitmasterTable . " nfsm
        INNER JOIN " . Table::newfeesgroupsTable . " nfgt ON nfgt.id = nfsm.nfgId
        INNER JOIN " . Table::newfeestypesTable . " nftt ON nftt.id = nfsm.nftId
        INNER JOIN " . Table::newfeemasterTable . " nfmt ON nfmt.id = nfsm.fmtId
        WHERE nfsm.fmtId IN ($nfmid) 
        AND nfsm.nftId IN ($nftid) 
        AND nfsm.nfgId IN ($nfgid) 
        AND nfsm.stuId = '$studentId'
        AND nfsm.classId = '$classId'
        AND nfsm.sectionId = '$sectionId'
        AND nfsm.session_table_id = '{$_SESSION['currentSession']}'
        AND nfsm.status = '1' 
        AND nfsm.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ")->result_array();

    // print_r($feesInvoiceData);die();
    if (empty($feesInvoiceData)) {
        header("Location: " . HelperClass::brandUrl);
    }
    // echo $sql;
}



$schoolDetails = $this->db->query("SELECT sm.school_name, sm.mobile,sm.email,sm.address,CONCAT('$schoolLogo',sm.image) as logo,sm.pincode FROM " . Table::schoolMasterTable . " sm WHERE sm.unique_id = '{$_SESSION['schoolUniqueCode']}' LIMIT 1")->result_array()[0];







$sql = "SELECT st.*,CONCAT('$studentImage',st.image) as studentImage,ct.className, sect.sectionName,state.stateName,city.cityName
FROM " . Table::studentTable . " st 
LEFT JOIN " . Table::classTable . " ct ON ct.id = st.class_id
LEFT JOIN " . Table::sectionTable . " sect ON sect.id = st.section_id
LEFT JOIN " . Table::stateTable . " state ON state.id = st.state_id
LEFT JOIN " . Table::cityTable . " city ON city.id = st.city_id
WHERE st.id = '$studentId' AND st.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
LIMIT 1";



$studentData = $this->db->query($sql)->result_array()[0];
// print_r($studentData);

// HelperClass::prePrintR($feesInvoiceData);






?>

<!-- Content Wrapper. Contains page content -->



 <html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

    <style>
        #printArea {
            height: auto;
            width: 18cm;
            border: 1px solid #000;
        }

        @media print {
            #printbtn {
                display: none;
            }

            @page {
                size: A4;
              

            }

            #printArea {
                height: auto;
                width: 18cm;
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
                                </td>
                            </tr>

                        </table>

                        <table class="table table-borderless my-2" style="font-size:14px;">
                            <tr>
                                <td>Date: <b><?= date('d-F-Y'); ?></b></td>
                                <!-- <td>
                                    Fees Invoice No: <b># <?= $feesInvoiceData['invoiceId']; ?></b>
                                </td> -->
                            </tr>
                            <tr>
                                <td>Student Name: <b><?= $studentData['name']; ?></b></td>
                                <td>Class : <b><?= $studentData['className'] . " ( " . $studentData['sectionName'] . " ) "; ?></b></td>
                            </tr>
                            <tr>
                                <td>Father's Name: <b><?= $studentData['father_name']; ?></b></td>
                                <td>Payment Mode : <b><?php if ($feesInvoiceData[0]['paymentMode'] == '2') {
                                                            echo 'Online';
                                                        } else {
                                                            echo 'Cash';
                                                        } ?></b></td>
                            </tr>
                        </table>
                        <table class="table mb-0 align-middle bg-white my-2" style="font-size:14px;border:1px solid #000">
                            <thead class="bg-light">
                                <tr>
                                    <!-- <th>Group</th> -->
                                    <th>Fees Type</th>
                                    <th>Deposit</th>
                                    <th>Discounts</th>
                                    <th>Fine</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php 
                                
                                $depositAmt = 0;
                                $discountAmt = 0;
                                $fintAmt = 0;
                                if(isset($feesInvoiceData)){
                                    foreach($feesInvoiceData as $f){ ?>

                                <tr>
                                    <!-- <td><?= $f['feeGroupName']; ?></td> -->
                                    <td><?= $f['feeTypeName']; ?></td>
                                    <td>₹ <?= number_format($f['depositAmount'], 2); ?></td>
                                    <td>₹ <?= number_format($f['discount'], 2); ?></td>
                                    <td>₹ <?= number_format($f['fine'], 2); ?></td>

                                </tr>

                                  <?php 

                                    $depositAmt = $depositAmt + $f['depositAmount'];
                                    $discountAmt = $discountAmt + $f['discount'];
                                    $fintAmt = $fintAmt + $f['fine'];
                                  
                                }

                                }?>
                               

                            </tbody>
                        </table>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <table class="table table-borderless" style="font-size:14px; text-align:right;">
                                        <tbody>
                                            <tr>
                                                <th style="width:50%">Total Deposit Amt </th>
                                                <td>₹ <?= number_format($depositAmt, 2); ?>/-</td>
                                            </tr>
                                            <tr>
                                                <th>Total Amount ( Deposit + Fine )</th>
                                                <td>₹ <?php
                                                        $tTotal = $depositAmt + $fintAmt;
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