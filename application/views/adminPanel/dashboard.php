<body class="hold-transition sidebar-mini">



  <div class="wrapper">
    <!-- Navbar -->
    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0"><?= $data['pageTitle'] ?> </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><?= $data['pageTitle'] ?> </li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">

          <?php

          $this->load->model('CrudModel');

          if (isset($_POST['submitPreLoader'])) {

            $session_start_year = $_POST['session_start_year'];
            $session_start_month = $_POST['session_start_month'];
            $session_end_year = $_POST['session_end_year'];
            $session_end_month = $_POST['session_end_month'];

            $insertSession =  $this->db->query("INSERT INTO " . Table::schoolSessionTable . " (schoolUniqueCode,session_start_year,session_start_month,session_end_year,session_end_month) VALUES ('{$_SESSION['schoolUniqueCode']}','$session_start_year','$session_start_month','$session_end_year','$session_end_month')");

            $_SESSION['currentSession'] = $this->db->insert_id();

            $stateName = $_POST['stateName'];

            $stateInsert =  $this->db->query("INSERT INTO " . Table::stateTable . " (schoolUniqueCode,stateName) VALUES ('{$_SESSION['schoolUniqueCode']}','$stateName')");




            $cityName = $_POST['cityName'];
            $stateId = $this->db->insert_id();

            $insertCity = $this->db->query("INSERT INTO " . Table::cityTable . " (schoolUniqueCode,cityName,stateId) VALUES ('{$_SESSION['schoolUniqueCode']}','$cityName','$stateId')");


            $classes = $_POST['classes'];

            $totalClass = count($classes);
            for ($i = 0; $i < $totalClass; $i++) {

              $insertNewClass = $this->db->query("INSERT INTO " . Table::classTable . " (schoolUniqueCode,className) VALUES ('{$_SESSION['schoolUniqueCode']}','$classes[$i]')");
              if ($insertNewClass) {


                $insertArr = [
                  "schoolUniqueCode" => $this->CrudModel->sanitizeInput($_SESSION['schoolUniqueCode']),
                  "feeGroupName" => $this->CrudModel->sanitizeInput($classes[$i] . " Class"),
                  "session_table_id" => $this->CrudModel->sanitizeInput($_SESSION['currentSession'])
                ];


                $insertId = $this->CrudModel->insert(Table::newfeesgroupsTable, $insertArr);
              }
            }
            // sections
            $sections = $_POST['sections'];

            $totalSections = count($sections);
            for ($i = 0; $i < $totalSections; $i++) {

              $sectonsInsert = $this->db->query("INSERT INTO " . Table::sectionTable . " (schoolUniqueCode,sectionName) VALUES ('{$_SESSION['schoolUniqueCode']}','$sections[$i]')");
            }
            $updatePreLoader = $this->db->query("UPDATE " . Table::preLoader . " SET isRun = '2' WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ");

            $msgArr = [
              'class' => 'success',
              'msg' => 'PreLoader Added Successfully',
            ];
            $this->session->set_userdata($msgArr);
            header("Refresh:1 " . base_url() . "adminPanel");
          }














          $preLoader =  $this->db->query("SELECT * FROM  " . Table::preLoader . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND isRun = '1' LIMIT 1  ")->result_array();




          if (!empty($preLoader)) {


            $monthD =  $this->db->query("SELECT * FROM " . Table::monthTable . " WHERE Status = '1'")->result_array();


          ?>


            <!-- Button trigger modal -->
            <button type="button" class="btn mybtnColor btn-lg my-3" data-toggle="modal" data-target=".bd-example-modal-xl">
              Start PreLoader
            </button>

            <!-- Modal -->
            <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <form method="POST">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">School Default Settings</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <div class="row">
                        <div class="col-md-6">
                          <label>Select Start Month</label>
                          <select name="session_start_month" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                            <?php foreach ($monthD as $mon) {
                              $selected = '';
                              if ($mon['monthName'] == 'April') {
                                $selected = 'selected';
                              } else {
                                $selected = '';
                              }

                            ?>
                              <option <?= $selected ?> value="<?= $mon['monthName'] ?>"><?= $mon['monthName'] ?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label>Select Start Year</label>
                          <select name="session_start_year" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                            <?php foreach (HelperClass::sessionYears as $sec => $val) {
                              $selected = '';
                              if ($val == '2022') {
                                $selected = 'selected';
                              } else {
                                $selected = '';
                              }

                            ?>
                              <option <?= $selected ?> value="<?= $val ?>"><?= $val ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>Select End Month</label>
                          <select name="session_end_month" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                            <?php foreach ($monthD as $mon) {
                              $selected = '';
                              if ($mon['monthName'] == 'March') {
                                $selected = 'selected';
                              } else {
                                $selected = '';
                              }
                            ?>
                              <option <?= $selected ?> value="<?= $mon['monthName'] ?>"><?= $mon['monthName'] ?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label>Select End Year</label>
                          <select name="session_end_year" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                            <?php foreach (HelperClass::sessionYears as $sec => $val) {
                              $selected = '';
                              if ($val == '2023') {
                                $selected = 'selected';
                              } else {
                                $selected = '';
                              }

                            ?>
                              <option <?= $selected ?> value="<?= $val ?>"><?= $val ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>Select State Name</label>
                          <input type="text" name="stateName" value="Uttar Pradesh" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                          <label>Select City Name</label>
                          <input type="text" name="cityName" value="Baraut" class="form-control" required>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>Select Class Name</label>

                          <?php
                          $defaultClasses = ['Play', 'Nursery', 'LKG', 'UKG', '1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', '11th', '12th'];
                          ?>
                          <select name="classes[]" id="multiple-checkboxes-class" multiple class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                            <?php foreach ($defaultClasses as $dc) { ?>
                              <option value="<?= $dc ?>"><?= $dc ?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label>Select Section Name</label>
                          <?php

                          $defaultSection = ['A', 'B', 'C', 'D', 'Maths', 'Science', 'Commerce', 'Arts'];

                          ?>
                          <select name="sections[]" id="multiple-checkboxes-section" multiple class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                            <?php foreach ($defaultSection as $ds) { ?>
                              <option value="<?= $ds ?>"><?= $ds ?></option>
                            <?php } ?>
                          </select>

                        </div>
                      </div>

                    </div>
                    <div class="modal-footer">
                      <button type="submit" name="submitPreLoader" class="btn mybtnColor btn-block">Save</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>

          <?php }






          $this->CrudModel->checkPermission();

          $currentDate = date('Y-m-d');
          ?>

          <?php
          if (!empty($this->session->userdata('msg'))) {

            if ($this->session->userdata('class') == 'success') {
              HelperClass::swalSuccess($this->session->userdata('msg'));
            } else if ($this->session->userdata('class') == 'danger') {
              HelperClass::swalError($this->session->userdata('msg'));
            }

          ?>

            <div class="alert alert-<?= $this->session->userdata('class') ?> alert-dismissible fade show" role="alert">
              <strong>New Message!</strong> <?= $this->session->userdata('msg') ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
            $this->session->unset_userdata('class');
            $this->session->unset_userdata('msg');
          }
          ?>
          <div id="accordion">

            <div class="card card-primary card-outline">
              <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapsestudents" aria-expanded="false">
                <div class="card-header">
                  <h4 class="card-title w-100">
                    Students Section
                  </h4>
                </div>
              </a>
              <div id="collapsestudents" class="collapse show" data-parent="#accordion">
                <div class="card-body">
                  <?php include("dashinclude/student_section.php"); ?>
                </div>
              </div>
            </div>
            <div class="card card-primary card-outline">
              <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseteacher" aria-expanded="false">
                <div class="card-header">
                  <h4 class="card-title w-100">
                    Teachers Section
                  </h4>
                </div>
              </a>
              <div id="collapseteacher" class="collapse show" data-parent="#accordion">
                <div class="card-body">
                  <?php include("dashinclude/teacher_section.php"); ?>
                </div>
              </div>
            </div>

     

            <div class="card card-primary card-outline">
              <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false">
                <div class="card-header">
                  <h4 class="card-title w-100">
                    Fees Section
                  </h4>
                </div>
              </a>
              <div id="collapseThree" class="collapse show" data-parent="#accordion">
                <div class="card-body">
                  <?php include("dashinclude/fees_section.php"); ?>
                </div>
              </div>
            </div>

            <div class="card card-primary card-outline">
              <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false">
                <div class="card-header">
                  <h4 class="card-title w-100">
                    Academic Section
                  </h4>
                </div>
              </a>
              <div id="collapseTwo" class="collapse show" data-parent="#accordion">
                <div class="card-body">
                  <?php include("dashinclude/academic_section.php"); ?>
                </div>
              </div>
            </div>

          </div>













          <?php


          // $dd = $this->CrudModel->showStudentFeesViaIdClassAndSection(31,1, 1,$_SESSION['schoolUniqueCode'],$_SESSION['currentSession']);
          // echo "<pre>";
          // print_r($dd);







          $sheduleData = $this->db->query("
     SELECT cST.shedule_json, cST.id, ct.className,st.sectionName,cST.status FROM " . Table::classSheduleTable . " cST 
     JOIN " . Table::classTable . " ct ON ct.id = cST.class_id
     JOIN " . Table::sectionTable . " st ON st.id = cST.section_id 
     WHERE cST.status != '4' AND cST.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
     ORDER BY cST.id DESC
     ")->result_array();

          $totalCount = count($sheduleData);
          //  HelperClass::prePrintR($sheduleData);
          $sheduleD = [];
          $teacherArr = [];
          for ($i = 0; $i < $totalCount; $i++) {
            array_push($sheduleD, json_decode($sheduleData[$i]['shedule_json'], TRUE));
          }


          $totalY = count($sheduleD);
          for ($j = 0; $j < $totalY; $j++) {
            for ($k = 0; $k < count($sheduleD[$j]); $k++) {
              array_push($teacherArr, $sheduleD[$j][$k]);
            }
          }


          $totalT = count($teacherArr);
          $teachersArr = [];
          for ($l = 0; $l < $totalT; $l++) {
            // if()
          }
          // HelperClass::prePrintR($teacherArr);


          ?>
          <!--/.col (right) -->
        </div>

        <!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->

    <?php
    $this->load->view("adminPanel/pages/footer.php");
    $this->load->view("adminPanel/pages/footer-copyright.php");
    ?>
  </div>
  <!-- ./wrapper -->
  <script>

  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">


  <script>
    $(document).ready(function() {
      $('#multiple-checkboxes-class').multiselect({
        includeSelectAllOption: true,
      });
      $('#multiple-checkboxes-section').multiselect({
        includeSelectAllOption: true,
      });
    });
  </script>



  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <?php include("dashinclude/chartjs.php"); ?>