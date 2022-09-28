<?php 




$totalExamsAdded = $this->db->query("SELECT count(1) as count FROM ".Table::examTable." WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1'")->result_array()[0]['count'];

$totalResutlsPublised = $this->db->query("SELECT count(1) as count FROM ".Table::resultTable." WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1'")->result_array()[0]['count'];


$todayExams = $this->db->query("SELECT count(1) as count FROM ".Table::examTable." WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND DATE(date_of_exam) = DATE(NOW())")->result_array()[0]['count'];

$todayResultPublished = $this->db->query("SELECT count(1) as count FROM ".Table::resultTable." WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND DATE(result_date) = DATE(NOW())")->result_array()[0]['count'];

?>


<div class="row">
            <div class="col-lg-3 col-6">

              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?= $totalExamsAdded?></h3>
                  <p>Total Exams Added</p>
                </div>
                <div class="icon">
                <i class="fa-solid fa-bars"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-6">

              <div class="small-box bg-primary">
                <div class="inner">
                  <h3><?= $totalResutlsPublised?></h3>
                  <p>Total Results Published</p>
                </div>
                <div class="icon">
                <i class="fa-solid fa-list"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-6">

              <div class="small-box bg-danger">
                <div class="inner">
                  <h3><?= $todayExams?></h3>
                  <p>Today Exams</p>
                </div>
                <div class="icon">
                <i class="fa-solid fa-bars"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            

            <div class="col-lg-3 col-6">

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
            </div>

          </div>