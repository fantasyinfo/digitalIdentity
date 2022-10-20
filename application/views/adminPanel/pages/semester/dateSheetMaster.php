<style>
  .big-checkbox {
    width: 1.5rem;
    height: 1.5rem;
    top: 0.5rem
  }
</style>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');
    $this->load->model('CrudModel');

    // fetching city data
    $examNameSem = $this->db->query("SELECT * FROM " . Table::semExamNameTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND  status = '1' ORDER BY id DESC")->result_array();

    $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND  status = '1' ORDER BY id DESC")->result_array();

    $sectionData = $this->db->query("SELECT * FROM " . Table::sectionTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND  status = '1' ORDER BY id DESC")->result_array();

    $subjectData = $this->db->query("SELECT * FROM " . Table::subjectTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND  status = '1' ORDER BY id DESC")->result_array();


    if (isset($_POST['submit'])) {

      $sem_exam_id = $_POST['sem_exam_id'];
      $class_id = $_POST['class_id'];
      $section_id = $_POST['section_id'];
      $subject_id = $_POST['subject_id'];
      $exam_date = $_POST['exam_date'];
      $exam_day = date('l', strtotime($_POST['exam_date']));
      // $exam_start_time = ($_POST['exam_start_time']) ? $_POST['exam_start_time'] : "";
      // $exam_end_time = (@$_POST['exam_end_time']) ?$_POST['exam_end_time'] : "";
      $min_marks = $_POST['min_marks'];
      $max_marks = $_POST['max_marks'];
      $schoolUniqueCode = $_SESSION['schoolUniqueCode'];


      // first check if this class & section has already any exam added for same exam date


      $checkAlreadyToday = $this->db->query("SELECT * FROM " . Table::secExamTable . " WHERE schoolUniqueCode = '$schoolUniqueCode' AND  status = '1' AND class_id = '$class_id' AND section_id = '$section_id' AND exam_date = '$exam_date' AND sem_exam_id = '$sem_exam_id' ORDER BY id DESC")->result_array();

      if(!empty($checkAlreadyToday))
      {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'This Class & Section Has Already Exam Added Today, Please Edit That.',
          ];
          $this->session->set_userdata($msgArr);
          header("Refresh:1 " . base_url() . "semester/dateSheetMaster");
          exit(0);
      }






      $addExamSemester = $this->db->query("INSERT INTO " . Table::secExamTable . " 
      (schoolUniqueCode,sem_exam_id,class_id,section_id,subject_id,exam_date,exam_day,min_marks,max_marks,session_table_id) 
      VALUES ('$schoolUniqueCode','$sem_exam_id','$class_id','$section_id', '$subject_id','$exam_date','$exam_day','$min_marks','$max_marks','{$_SESSION['currentSession']}')");

      if ($addExamSemester) {

        $msgArr = [
          'class' => 'success',
          'msg' => 'New Semester Exam Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Semester Not Added Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "semester/dateSheetMaster");
    }





    // edit and delete action
    if (isset($_GET['action'])) {

      if ($_GET['action'] == 'edit') {
        $editId = $_GET['edit_id'];
        $editExamSem = $this->db->query("SELECT * FROM " . Table::secExamTable . " WHERE id='$editId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
      }


      if ($_GET['action'] == 'delete') {
        $deleteId = $_GET['delete_id'];
        $deletesectionData = $this->db->query("UPDATE " . Table::secExamTable . " SET status = '4' WHERE id='$deleteId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
        if ($deletesectionData) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Semester Exam Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Semester Exam Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "semester/dateSheetList");
      }

      if ($_GET['action'] == 'status') {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];
        $updateStatus = $this->db->query("UPDATE " . Table::secExamTable . " SET status = '$status' WHERE id = '$updateId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if ($updateStatus) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Semester Exam Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Semester Exam Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "semester/dateSheetList");
      }
    }




    // update exiting section
    if (isset($_POST['update'])) {
      $sem_exam_id = $_POST['sem_exam_id'];
      $class_id = $_POST['class_id'];
      $section_id = $_POST['section_id'];
      $subject_id = $_POST['subject_id'];
      $exam_date = $_POST['exam_date'];
      $exam_day = date('l', strtotime($_POST['exam_date']));
      // $exam_start_time = ($_POST['exam_start_time']) ? $_POST['exam_start_time'] : "";
      // $exam_end_time = ($_POST['exam_end_time']) ?$_POST['exam_end_time'] : "";
      $min_marks = $_POST['min_marks'];
      $max_marks = $_POST['max_marks'];
      $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
      $updateSemExamId = $_POST['updateSemExamId'];


        $checkAlreadyToday = $this->db->query("SELECT * FROM " . Table::secExamTable . " WHERE schoolUniqueCode = '$schoolUniqueCode' AND  status = '1' AND class_id = '$class_id' AND section_id = '$section_id' AND exam_date = '$exam_date' AND sem_exam_id = '$sem_exam_id' ORDER BY id DESC")->result_array();

      if(!empty($checkAlreadyToday))
      {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'This Class & Section Has Already Exam Added Today, Please Edit That.',
          ];
          $this->session->set_userdata($msgArr);
          header("Refresh:1 " . base_url() . "semester/dateSheetMaster");
          exit(0);
      }

      $updateSemExam = $this->db->query("UPDATE " . Table::secExamTable . " SET sem_exam_id = '$sem_exam_id',class_id = '$class_id', section_id = '$section_id', subject_id = '$subject_id', exam_date = '$exam_date', exam_day = '$exam_day', exam_start_time = '$exam_start_time',  exam_end_time = '$exam_end_time', min_marks = '$min_marks', max_marks = '$max_marks' WHERE id = '$updateSemExamId'   AND schoolUniqueCode = '$schoolUniqueCode'");

      if ($updateSemExam) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Semester Exam Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Semester Exam Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "semester/dateSheetList");
    }




    ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
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
          <div class="row">
            <!-- left column -->
            <?php //print_r($data['class']);
            ?>
            <div class="col-md-12 mx-auto">
              <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Add New Semester Exam</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="row">
                  <div class="card-body">
                    <form method="post" action="">
                      <?php
                      if (isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
                        <input type="hidden" name="updateSemExamId" value="<?= $editId ?>">
                      <?php }

                      ?>
                      <div class="row">
                        <div class="col-md-8 ">
                          <table class="table ">
                            <tr>
                              <td>
                                <label>Select Semester Exam Name</label>
                              </td>
                              <td>
                                <select id="sem_exam_id" class="form-control  select2 select2-danger" name="sem_exam_id" data-dropdown-css-class="select2-danger" style="width: 100%;" required>
                                  <option></option>
                                  <?php
                                  $selected = '';
                                  if (isset($examNameSem) && !empty($examNameSem)) {


                                    foreach ($examNameSem as $sd) {

                                      if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                        if ($editExamSem[0]['sem_exam_id'] == $sd['id']) {
                                          $selected = 'selected';
                                        } else {
                                          $selected = '';
                                        }
                                      }


                                  ?>
                                      <option <?= $selected ?> value="<?= $sd['id'] ?>"><?= $sd['sem_exam_name'] . ' - '. $sd['exam_year'] ?></option>
                                  <?php }
                                  } ?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <label>Select Date</label>
                              </td>
                              <td>
                              <input type="date" name="exam_date" class="form-control" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editExamSem[0]['exam_date'];} ?>" required>
                              </td>
                            </tr>
                            <!-- <tr>
                              <td>
                                <label>Exam Start Time (optional)</label>
                              </td>
                              <td>
                              <input type="time" name="exam_start_time" class="form-control" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editExamSem[0]['exam_start_time'];} ?>" >
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <label>Exam End Time (optional)</label>
                              </td>
                              <td>
                              <input type="time" name="exam_end_time" class="form-control" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editExamSem[0]['exam_end_time'];} ?>" >
                              </td>
                            </tr> -->
                            <tr>
                              <td>
                                <label>Select Class</label>
                              </td>
                              <td>
                                <select id="class_id" class="form-control  select2 select2-danger" name="class_id" data-dropdown-css-class="select2-danger" style="width: 100%;" required>
                                  <option></option>
                                  <?php
                                  $selected = '';
                                  if (isset($classData) && !empty($classData)) {


                                    foreach ($classData as $sd) {

                                      if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                        if ($editExamSem[0]['class_id'] == $sd['id']) {
                                          $selected = 'selected';
                                        } else {
                                          $selected = '';
                                        }
                                      }


                                  ?>
                                      <option <?= $selected ?> value="<?= $sd['id'] ?>"><?= $sd['className'] ?></option>
                                  <?php }
                                  } ?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <label>Select Section</label>
                              </td>
                              <td>
                                <select id="section_id" class="form-control  select2 select2-danger" name="section_id" data-dropdown-css-class="select2-danger" style="width: 100%;" required>
                                  <option></option>
                                  <?php
                                  $selected = '';
                                  if (isset($sectionData) && !empty($sectionData)) {


                                    foreach ($sectionData as $sd) {

                                      if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                        if ($editExamSem[0]['section_id'] == $sd['id']) {
                                          $selected = 'selected';
                                        } else {
                                          $selected = '';
                                        }
                                      }


                                  ?>
                                      <option <?= $selected ?> value="<?= $sd['id'] ?>"><?= $sd['sectionName'] ?></option>
                                  <?php }
                                  } ?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <label>Select Subject</label>
                              </td>
                              <td>
                                <select id="subject_id" class="form-control  select2 select2-danger" name="subject_id" data-dropdown-css-class="select2-danger" style="width: 100%;" required>
                                  <option></option>
                                  <?php
                                  $selected = '';
                                  if (isset($subjectData) && !empty($subjectData)) {


                                    foreach ($subjectData as $sd) {

                                      if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                        if ($editExamSem[0]['subject_id'] == $sd['id']) {
                                          $selected = 'selected';
                                        } else {
                                          $selected = '';
                                        }
                                      }


                                  ?>
                                      <option <?= $selected ?> value="<?= $sd['id'] ?>"><?= $sd['subjectName'] ?></option>
                                  <?php }
                                  } ?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <label>Exam Max Marks</label>
                              </td>
                              <td>
                              <input type="number" name="max_marks" class="form-control" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editExamSem[0]['max_marks'];} ?>" required>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <label>Exam Min Marks</label>
                              </td>
                              <td>
                              <input type="number" name="min_marks" class="form-control" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editExamSem[0]['min_marks'];} ?>" required>
                              </td>
                            </tr>
                            <tr>
                              <td>#</td>
                              <td>
                              <button type="submit" name="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo 'update'; } else { echo 'submit';  } ?>" class="btn btn-primary btn-lg btn-block">Submit</button>
                              </td>
                            </tr>

                          </table>
                        </div>
                                </div>
                    </form>
                  </div>
                  <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <!-- right column -->
              </div>


              <!--/.col (right) -->
            </div>

            <!-- /.container-fluid -->
          </div>
          <!-- /.content -->
        </div>
      </div>
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->

    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>