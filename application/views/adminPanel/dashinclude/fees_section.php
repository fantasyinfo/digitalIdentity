<?php 


$totalFeeSubmitToday = $this->db->query("SELECT SUM(deposit_amt) as count FROM ".Table::feesForStudentTable." WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND DATE(fee_deposit_date) = DATE(NOW())")->result_array()[0]['count'];

$totalOfferProvidedToday = $this->db->query("SELECT SUM(offer_amt) as count FROM ".Table::feesForStudentTable." WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND DATE(fee_deposit_date) = DATE(NOW())")->result_array()[0]['count'];


$totalFeesSubmitThisMonth = $this->db->query("SELECT SUM(deposit_amt) as count FROM ".Table::feesForStudentTable." WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND MONTH(fee_deposit_date) = MONTH(NOW())")->result_array()[0]['count'];

$todayResultPublished = $this->db->query("SELECT count(1) as count FROM ".Table::resultTable." WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND DATE(result_date) = DATE(NOW())")->result_array()[0]['count'];

?>


<div class="row">
            <div class="col-lg-3 col-6">

              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?php if($totalFeeSubmitToday == NULL || $totalFeeSubmitToday == 0) {echo 0;}else{ echo $totalFeeSubmitToday; }?></h3>
                  <p>Total Fees Submit</p>
                </div>
                <div class="icon">
                <i class="fa-solid fa-money-bill"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-6">

              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?php if($totalOfferProvidedToday == NULL || $totalOfferProvidedToday == 0) {echo 0;}else{ echo $totalOfferProvidedToday; }?></h3>
                  <p>Total Offer Provide For Fees Today</p>
                </div>
                <div class="icon">
                <i class="fa-solid fa-money-bill"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-6">

              <div class="small-box bg-primary">
                <div class="inner">
                  <h3><?php if($totalFeesSubmitThisMonth == NULL || $totalFeesSubmitThisMonth == 0) {echo 0;}else{ echo $totalFeesSubmitThisMonth; }?></h3>
                  <p>Total Fees Submit This Month</p>
                </div>
                <div class="icon">
                <i class="fa-solid fa-money-bill"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            

            <!-- <div class="col-lg-3 col-6">

              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?= $todayResultPublished?></h3>
                  <p>Today Result Published</p>
                </div>
                <div class="icon">
                <i class="fa-solid fa-list"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div> -->

          </div>