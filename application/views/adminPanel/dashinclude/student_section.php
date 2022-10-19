<?php 

$totalStudents = $this->db->query("SELECT count(1) as count FROM ".Table::studentTable." WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1'")->result_array()[0]['count'];

$totalPresentStudents = $this->db->query("SELECT count(1) as count FROM ".Table::attendenceTable." WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND attendenceStatus = '1' AND DATE(dateTime) = DATE(NOW())")->result_array()[0]['count'];


$totalAbsentStudents = $this->db->query("SELECT count(1) as count FROM ".Table::attendenceTable." WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND attendenceStatus = '0' AND DATE(dateTime) = DATE(NOW())")->result_array()[0]['count'];

?>


<div class="row">
            <div class="col-lg-3 col-6">

              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?= $totalStudents?></h3>
                  <p>Total Sudents Register</p>
                </div>
                <div class="icon">
                <i class="fa-solid fa-user"></i>
                </div>
                <a href="<?= base_url('student/list');?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-6">

              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?= $totalPresentStudents?></h3>
                  <p>Total Present Students Today</p>
                </div>
                <div class="icon">
                <i class="fa-solid fa-user-plus"></i>
                </div>
                <a href="<?= base_url('academic/allAttendance');?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-6">

              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><?= $totalAbsentStudents?></h3>
                  <p>Total Absent Students Today</p>
                </div>
                <div class="icon">
                <i class="fa-solid fa-user-minus"></i>
                </div>
                <a href="<?= base_url('academic/allAttendance');?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            

            <!-- <div class="col-lg-3 col-6">

              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>65</h3>
                  <p>Unique Visitors</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div> -->

          </div>