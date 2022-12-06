<?php


$totalFeeSubmitToday = $this->db->query("SELECT SUM(depositAmount) as count FROM " . Table::newfeessubmitmasterTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND DATE(depositDate) = DATE(NOW())")->result_array()[0]['count'];

$totalOfferProvidedToday = $this->db->query("SELECT SUM(	discount) as count FROM " . Table::newfeessubmitmasterTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND DATE(depositDate) = DATE(NOW())")->result_array()[0]['count'];


$totalFeesSubmitThisMonth = $this->db->query("SELECT SUM(depositAmount) as count FROM " . Table::newfeessubmitmasterTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND MONTH(depositDate) = MONTH(NOW())")->result_array()[0]['count'];

$totalDiscountThisMonth = $this->db->query("SELECT SUM(discount) as count FROM " . Table::newfeessubmitmasterTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND MONTH(depositDate) = MONTH(NOW())")->result_array()[0]['count'];



?>


<div class="row">
  <div class="col-md-6">
    <div class="card border-top-3">
      <div class="card-body">
        <div class="row">
          <div class="col-md-9">
            <p class="card-text  font-size-22">Today Fees Deposits: <b><i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($totalFeeSubmitToday, 2) ?></b> </p>
            <p class="card-text font-size-22">Today Discount Provided : <b><i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($totalOfferProvidedToday, 2) ?></b> </p>
          </div>

        </div>


      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card border-top-3">
      <div class="card-body">
        <div class="row">
          <div class="col-md-9">
            <p class="card-text  font-size-22">This Month Fees Deposits: <b><i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($totalFeesSubmitThisMonth, 2) ?></b> </p>
            <p class="card-text font-size-22">This Month Discount Provided : <b><i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($totalDiscountThisMonth, 2) ?></b> </p>
          </div>

        </div>


      </div>
    </div>
  </div>

</div>