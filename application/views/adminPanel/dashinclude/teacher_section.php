<?php

$totalStudents = $this->db->query("SELECT count(1) as count FROM " . Table::teacherTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1'")->result_array()[0]['count'];

$totalboys = $this->db->query("SELECT count(1) as count FROM " . Table::teacherTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND gender = '1'")->result_array()[0]['count'];

$totalgirls = $this->db->query("SELECT count(1) as count FROM " . Table::teacherTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND gender = '2' ")->result_array()[0]['count'];

$totalPresentStudents = $this->db->query("SELECT count(1) as count FROM " . Table::attendenceTeachersTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND attendenceStatus = '1' AND DATE(dateTime) = DATE(NOW())")->result_array()[0]['count'];


$totalAbsentStudents = $this->db->query("SELECT count(1) as count FROM " . Table::attendenceTeachersTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND attendenceStatus = '0' AND DATE(dateTime) = DATE(NOW())")->result_array()[0]['count'];

?>


<div class="row">
    <div class="col-xl-6 col-md-6">
       
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Today Attendance</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value text-success" data-toggle="tooltip" data-placement="top" title="Presents">P <?= $totalPresentStudents ?></span> <span class="counter-value text-danger" data-toggle="tooltip" data-placement="top" title="Absents"> A <?= $totalAbsentStudents ?></span></h4>
                        <a href="<?= base_url('teacher/list'); ?>" class="text-decoration-underline">View all attendance</a>
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
                       <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Teachers</p>
                   </div>
               </div>
               <div class="d-flex align-items-end justify-content-between mt-4">
                   <div>
                       <h3 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value text-success" data-toggle="tooltip" data-placement="top" title="Ladies"> <i class="fa-solid fa-child-dress"></i> <?= $totalgirls ?></span> <span class="counter-value text-danger" data-toggle="tooltip" data-placement="top" title="Gents"> <i class="fa-solid fa-child"></i> <?= $totalboys ?></span></h3>
                       <a href="<?= base_url('academic/allAttendance'); ?>" class="text-decoration-underline">View all teachers</a>
                   </div>
                
               </div>
           </div><!-- end card body -->
       </div>
       
   </div>

</div>


