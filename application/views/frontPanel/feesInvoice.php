<?php


$this->load->library('session');
$this->load->model('CrudModel');

$dir = base_url().HelperClass::uploadImgDir;
$schoolLogo = base_url().HelperClass::schoolLogoImagePath;
$studentImage = base_url().HelperClass::studentImagePath;

if(isset($_GET['fees_id']))
{
    $this->load->model('CrudModel');
	$tokenFiter = $this->db->query("SELECT * FROM ".Table::tokenFilterTable." WHERE token = '{$_GET['fees_id']}' AND status = '1' LIMIT 1")->result_array()[0];

	if(!empty($tokenFiter))
	{
		$feesInvoiceData = $this->db->query("SELECT nfsm.*, nftt.feeTypeName, nfgt.feeGroupName,nfmt.amount FROM ".Table::newfeessubmitmasterTable." nfsm
        INNER JOIN ".Table::newfeesgroupsTable." nfgt ON nfgt.id = nfsm.nfgId
        INNER JOIN ".Table::newfeestypesTable." nftt ON nftt.id = nfsm.nftId
        INNER JOIN ".Table::newfeemasterTable." nfmt ON nfmt.id = nfsm.fmtId
        WHERE nfsm.randomToken = '{$tokenFiter['token']}' AND nfsm.status = '1' AND nfsm.schoolUniqueCode = '{$tokenFiter['schoolUniqueCode']}' AND nfsm.id ='{$tokenFiter['insertId']}' LIMIT 1")->result_array()[0];

        // print_r($feesInvoiceData);die();
		if(empty($feesInvoiceData))
		{
			header("Location: " . HelperClass::brandUrl);
		}
	}
}else
{
    // redirect to homepage
}


$schoolDetails = $this->db->query("SELECT sm.school_name, sm.mobile,sm.email,sm.address,CONCAT('$schoolLogo',sm.image) as logo,sm.pincode FROM " . Table::schoolMasterTable . " sm WHERE sm.unique_id = '{$feesInvoiceData['schoolUniqueCode']}' LIMIT 1")->result_array()[0];

				
	




$sql = "SELECT st.*,CONCAT('$studentImage',st.image) as studentImage,ct.className as studentClass, sect.sectionName as studentSection,state.stateName,city.cityName
FROM ".Table::studentTable." st 
LEFT JOIN ".Table::classTable." ct ON ct.id = st.class_id
LEFT JOIN ".Table::sectionTable." sect ON sect.id = st.section_id
LEFT JOIN ".Table::stateTable." state ON state.id = st.state_id
LEFT JOIN ".Table::cityTable." city ON city.id = st.city_id
WHERE st.id = '{$feesInvoiceData['stuId']}' AND st.schoolUniqueCode = '{$feesInvoiceData['schoolUniqueCode']}'
LIMIT 1";



$studentData = $this->db->query($sql)->result_array()[0];
// print_r($studentData);

?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
<!-- Content Wrapper. Contains page content -->


<style>
    @page {
    size: auto;
    margin: 0;
}

@print {
    @page :footer {
        display: none
    }
 
    @page :header {
        display: none
    }
}


@media print {
    @page {
        margin-top:5px;
        margin-bottom: 5px;
    }
    body {
        padding: 72px;
       
    }
}
</style>




<div class="container shadow my-2" >
    <div class="row">
    <div class="invoice p-3 mb-3">
        <div id="printableArea">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                    <center><h2 style="font-size: 24px; font-weight:bold; margin-bottom:20px;"><i>Fees Deposit Receipt</i></h2></center>
                    <h6>
                        <small class="float-right">Date: <?= date('d-m-y',strtotime($feesInvoiceData['depositDate'])); ?></small>
                    </h6>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    From
                    <address>
                        <strong><?= $schoolDetails['school_name']; ?></strong><br>
                        <?= $schoolDetails['address'] ?><br>
                        Pincode <?= $schoolDetails['pincode']; ?><br>
                        Phone: <?= $schoolDetails['mobile']; ?><br>
                        Email: <?= $schoolDetails['email']; ?>
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    To
                    <address>
                        <strong><?= $studentData['name']; ?></strong><br>
                        <?= $studentData['address']; ?><br>
                        <?= $studentData['cityName']  . ' '. $studentData['stateName'] . ' '. $studentData['pincode'];?><br>
                        Phone: <?= $studentData['mobile']; ?><br>
                        Email: <?= $studentData['email']; ?>
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <b># <?= $feesInvoiceData['invoiceId']; ?></b><br>
                    <img src="<?=$schoolDetails['logo']?>" alt="school_img" height="100px" width="100px">
                </div>
           
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fees Group</th>
                                <th>Fees Type</th>
                                <!-- <th>Fees Amount</th> -->
                                <th>Deposit Amount</th>
                                <th>Discounts Amount</th>
                                <th>Fine Amount</th>
                                <!-- <th>Total</th> -->
                                <!-- <th>Depositor Mobile</th>
                                <th>Deposit Amount </th>
                                <th>Offer Amount </th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $feesInvoiceData['feeGroupName']; ?></td>
                                <td><?= $feesInvoiceData['feeTypeName'] ;?></td>
                                <!-- <td>₹ <?= number_format($feesInvoiceData['amount'],2); ?></td> -->
                                <td>₹ <?= number_format($feesInvoiceData['depositAmount'],2); ?></td>
                                <td>₹ <?= number_format($feesInvoiceData['discount'],2); ?></td>
                                <td>₹ <?= number_format($feesInvoiceData['fine'],2); ?></td>
                               
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
               
                <img class="qrcode" src="https://chart.googleapis.com/chart?chs=150x150&amp;cht=qr&amp;chl=<?=base_url('feesInvoice') . "?fees_id=" . $_GET['fees_id'];?>&amp;choe=UTF-8" alt="QR code"><br> <b>Scan To Verify</b>
                    <!-- <p class="lead">Payment Methods:</p>
                    <img src="../../dist/img/credit/visa.png" alt="Visa">
                    <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
                    <img src="../../dist/img/credit/american-express.png" alt="American Express">
                    <img src="../../dist/img/credit/paypal2.png" alt="Paypal"> -->

                    <!-- <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        This Invoice is only for view it is not a valid prof of deposit the fees.
                    </p> -->
                </div>
                <!-- /.col -->
                <div class="col-6">
                    <!-- <p class="lead">Amount Due 2/22/2014</p> -->

                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th style="width:50%">Total Deposit Amt </th>
                                    <td>₹ <?= number_format($feesInvoiceData['depositAmount'],2);?>/-</td>
                                </tr>
                                <!-- <tr>
                                    <th>Tax </th>
                                   <td>₹ 00</td> 
                                </tr>
                                <tr>
                                    <th>GST</th>
                                   <td>₹ 00</td>
                                </tr> -->
                                <tr>
                                    <th>Total Amount ( Deposit + Fine )</th>
                                    <td>₹ <?php
                                    $tTotal = $feesInvoiceData['depositAmount'] + $feesInvoiceData['fine'];
                                    echo number_format($tTotal,2)  ;?>/-</td>
                                </tr>
                              
                                   
                                 
                               
                            </tbody>
                        </table>
                        
                    </div>
                    <img src="<?=$dir?>cerfified.png" alt="certified" class="float-end" height="100px" width="100px">
        
                </div>
         
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
            <!-- this row will not appear when printing -->
            <div class="row no-print">
                <div class="col-12">
                    <button  class="btn btn-default" onclick="printDiv('printableArea')"><i class="fas fa-print"></i> Print</button>
                    <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;" onclick="printDiv('printableArea')" >
                        <i class="fas fa-download"></i> Generate PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Back button -->
<div class="container">
    <div class="row">
        <a href="<?=base_url('master/feesListingMaster')?>" class="link">Back To Fees Submit</a>
    </div>
</div>

<script>
    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>