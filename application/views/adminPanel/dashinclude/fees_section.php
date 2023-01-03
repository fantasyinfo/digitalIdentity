<?php


$totalFeeSubmitToday = $this->db->query("SELECT SUM(depositAmount) as count FROM " . Table::newfeessubmitmasterTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND DATE(depositDate) = DATE(NOW())")->result_array()[0]['count'];

$totalOfferProvidedToday = $this->db->query("SELECT SUM(	discount) as count FROM " . Table::newfeessubmitmasterTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND DATE(depositDate) = DATE(NOW())")->result_array()[0]['count'];


$totalFeesSubmitThisMonth = $this->db->query("SELECT SUM(depositAmount) as count FROM " . Table::newfeessubmitmasterTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND MONTH(depositDate) = MONTH(NOW())")->result_array()[0]['count'];

$totalDiscountThisMonth = $this->db->query("SELECT SUM(discount) as count FROM " . Table::newfeessubmitmasterTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND MONTH(depositDate) = MONTH(NOW())")->result_array()[0]['count'];



?>



<div class="row">
    <div class="col-xl-6 col-md-6">
       
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Today Fees Deposits</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value text-success" data-toggle="tooltip" data-placement="top" title="Today Deposits"><i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($totalFeeSubmitToday, 2) ?></span> <span class="counter-value text-danger" data-toggle="tooltip" data-placement="top" title="Today Discounts"> <i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($totalOfferProvidedToday, 2) ?></span></h4>
                        <a href="<?= base_url('teacher/list'); ?>" class="text-decoration-underline">View all collections</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success rounded fs-3">
                            <i class="bx bx-dollar-circle text-success"></i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div>

    </div>

    <div class="col-xl-6 col-md-6">
       
       <div class="card card-animate">
           <div class="card-body">
               <div class="d-flex align-items-center">
                   <div class="flex-grow-1 overflow-hidden">
                       <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Monthly Fees Collection</p>
                   </div>
               </div>
               <div class="d-flex align-items-end justify-content-between mt-4">
                   <div>
                       <h3 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value text-success" data-toggle="tooltip" data-placement="top" title="Monthly Deposits"> <i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($totalFeesSubmitThisMonth, 2) ?></span> <span class="counter-value text-danger" data-toggle="tooltip" data-placement="top" title="Monthly Discounts"> <i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($totalDiscountThisMonth, 2) ?></span></h3>
                       <a href="<?= base_url('academic/allAttendance'); ?>" class="text-decoration-underline">View all collections</a>
                   </div>
                
               </div>
           </div><!-- end card body -->
       </div>
       
   </div>

</div>

