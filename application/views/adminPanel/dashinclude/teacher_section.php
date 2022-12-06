<?php

$totalStudents = $this->db->query("SELECT count(1) as count FROM " . Table::teacherTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1'")->result_array()[0]['count'];

$totalboys = $this->db->query("SELECT count(1) as count FROM " . Table::teacherTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND gender = '1'")->result_array()[0]['count'];

$totalgirls = $this->db->query("SELECT count(1) as count FROM " . Table::teacherTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND gender = '2' ")->result_array()[0]['count'];

$totalPresentStudents = $this->db->query("SELECT count(1) as count FROM " . Table::attendenceTeachersTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND attendenceStatus = '1' AND DATE(dateTime) = DATE(NOW())")->result_array()[0]['count'];


$totalAbsentStudents = $this->db->query("SELECT count(1) as count FROM " . Table::attendenceTeachersTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND attendenceStatus = '0' AND DATE(dateTime) = DATE(NOW())")->result_array()[0]['count'];

?>


<div class="row">


            <div class="col-md-6">
    <div class="card border-top-3">
      <div class="card-body">
        <div class="row">
          <div class="col-md-9">
        <p class="card-text  font-size-22">Today Presents : <b><?= $totalPresentStudents ?></b>   <i class="fa-solid fa-circle-check text-success"></i></p>
        <p class="card-text font-size-22">Today Absents : <b><?= $totalAbsentStudents ?></b>   <i class="fa-sharp fa-solid fa-circle-xmark text-danger"></i></p>
          </div>
         
        </div>
        

      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card border-top-3 ">
      <div class="card-body">
         <p class="card-text font-size-22">Total Teachers : <i class="fa-solid fa-user"></i> <b ><?= $totalStudents ?></b></p>
        <p class="card-text font-size-22">Total Boys : <i class="fa-solid fa-child"></i>  <b><?= $totalboys ?> </b></p>
        <p class="card-text font-size-22">Total Girls : <i class="fa-solid fa-child-dress"></i> <b><?= $totalgirls ?></b></p>
      
    
         
      </div>
    </div>
  </div>


</div>
