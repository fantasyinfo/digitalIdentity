<?php

// id=9638521478-12326325-098Bn77OP-10-10-12-12-14

if(isset($_GET['id']))
{
    $e = explode("-",$_GET['id']);
    if($e[2] !== '098Bn77OP' || strlen($e[0]) != 4)
    {
         // redirect to homepage
    }
    $feesId = $e[3];
}else
{
    // redirect to homepage
}




				
	



$this->load->library('session');
$this->load->model('CrudModel');

$dir = base_url().HelperClass::uploadImgDir;
$schoolLogo = base_url().HelperClass::schoolLogoImagePath;
$studentImage = base_url().HelperClass::studentImagePath;

$sql = "SELECT ffst.invoice_id, ffst.offer_amt,ffst.deposit_amt,ffst.fee_deposit_date,IF(ffst.payment_mode = '2','Offline','Online') as payment_mode,ffst.depositer_name,ffst.depositer_mobile,ffst.depositer_address,
s.school_name,s.mobile as schoolMobile,s.email as schoolEmail,s.address as schoolAddress,s.pincode as schoolPincode,
CONCAT('$schoolLogo',s.image) as logo, st.name as studentName,st.email as studentEmail, st.mobile as studentMobile, st.address as studentAddress, st.pincode as studentPincode, state.stateName as studentState,city.cityName studentCity,CONCAT('$studentImage',st.image) as studentImage,ct.className as studentClass, sect.sectionName as studentSection
FROM ".Table::schoolMasterTable." s 
LEFT JOIN ".Table::feesForStudentTable." ffst ON ffst.schoolUniqueCode = s.unique_id
LEFT JOIN ".Table::studentTable." st ON st.id = ffst.student_id
LEFT JOIN ".Table::classTable." ct ON ct.id = ffst.class_id
LEFT JOIN ".Table::sectionTable." sect ON sect.id = ffst.section_id
LEFT JOIN ".Table::stateTable." state ON state.id = st.state_id
LEFT JOIN ".Table::cityTable." city ON city.id = st.city_id
WHERE ffst.id = '$feesId'
LIMIT 1";



$schoolData = $this->db->query($sql)->result_array()[0];

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
                    <h6>
                        <small class="float-right">Date: <?= date('d-m-y',strtotime($schoolData['fee_deposit_date'])); ?></small>
                    </h6>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    From
                    <address>
                        <strong><?= $schoolData['school_name']; ?></strong><br>
                        <?= $schoolData['schoolAddress'] ?><br>
                        Pincode <?= $schoolData['schoolPincode']; ?><br>
                        Phone: <?= $schoolData['schoolMobile']; ?><br>
                        Email: <?= $schoolData['schoolEmail']; ?>
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    To
                    <address>
                        <strong><?= $schoolData['studentName']; ?></strong><br>
                        <?= $schoolData['studentAddress']; ?><br>
                        <?= $schoolData['studentCity']  . ' '. $schoolData['studentState'] . ' '. $schoolData['studentPincode'];?><br>
                        Phone: <?= $schoolData['studentMobile']; ?><br>
                        Email: <?= $schoolData['studentEmail']; ?>
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <b># <?= $schoolData['invoice_id']; ?></b><br>
                    <img src="<?=$schoolData['logo']?>" alt="school_img" height="100px" width="100px">
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
                                <th>Student Name</th>
                                <th>Class - Section</th>
                                <th>Depositor Name</th>
                                <th>Depositor Mobile</th>
                                <th>Deposit Amount </th>
                                <th>Offer Amount </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $schoolData['studentName']; ?></td>
                                <td><?= $schoolData['studentClass'] . " - " . $schoolData['studentSection']?></td>
                                <td><?= $schoolData['depositer_name']; ?></td>
                                <td><?= $schoolData['depositer_mobile']; ?></td>
                                <td>₹ <?= number_format($schoolData['deposit_amt'],2);?>/-</td>
                                <td>₹ <?= number_format($schoolData['offer_amt'],2);?>/-</td>
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
               
                <img class="qrcode" src="https://chart.googleapis.com/chart?chs=150x150&amp;cht=qr&amp;chl=<?=base_url('feesInvoice') . "?id=" . $_GET['id'];?>&amp;choe=UTF-8" alt="QR code"><br> <b>Scan To Verify</b>
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
                                    <th style="width:50%">Total Deposit Amt: </th>
                                    <td>₹ <?= number_format($schoolData['deposit_amt'],2);?>/-</td>
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
                                    <th>Total Deposit & Offer:</th>
                                    <?php $totalAmt =  intval($schoolData['deposit_amt']) +  intval($schoolData['offer_amt']);  ?>
                                    <td>₹ <?= number_format($totalAmt,2) ;?>/-</td>
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