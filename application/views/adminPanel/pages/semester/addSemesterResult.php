<style>
  .big-checkbox {
    width: 1.5rem;
    height: 1.5rem;
    top: 0.5rem
  }

  .custom_width {
    width: 40px;
    margin-left: 20px;
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
    $examNameSem = $this->db->query("SELECT * FROM " . Table::semExamNameTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND  status = '1'")->result_array();

    $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND  status = '1'")->result_array();

    $sectionData = $this->db->query("SELECT * FROM " . Table::sectionTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND  status = '1'")->result_array();

    $subjectData = $this->db->query("SELECT * FROM " . Table::subjectTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND  status = '1'")->result_array();


    if (isset($_POST['submit'])) {

      HelperClass::prePrintR($_POST);


      $totalStudentsCount = count($_POST['student_id']);

      // outerLoop
      $sem_id = $_POST['sem_id'];
      $class_id = $_POST['class_id'];
      $section_id = $_POST['section_id'];
      $schoolUniqueCode = $_SESSION['schoolUniqueCode'];

      for ($a = 0; $a < $totalStudentsCount; $a++) {

        $studentId = $_POST['student_id'][$a][$a];
        // innerLoop
        $totalExamsCount = count($_POST['sec_exam_id']);

        for ($b = 0; $b < $totalExamsCount; $b++) {
          $sec_exam_id = $_POST['sec_exam_id'][$b][$a];
          $subject_id = $_POST['subject_id'][$b][$a];
          $marks = $_POST['marks'][$b][$a];

          $addResult = $this->db->query("INSERT INTO " . Table::semExamResults . " 
      (schoolUniqueCode,sem_id,sec_exam_id,class_id,section_id,subject_id,student_id,marks) 
      VALUES ('$schoolUniqueCode','$sem_id','$sec_exam_id','$class_id','$section_id', '$subject_id','$studentId','$marks')");

          // if($addResult)
          // {
          //   $c++;
          // }

        }
      }

      // first check if this class & section has already any exam added for same exam date


      // $checkAlreadyToday = $this->db->query("SELECT * FROM " . Table::secExamTable . " WHERE schoolUniqueCode = '$schoolUniqueCode' AND  status = '1' AND class_id = '$class_id' AND section_id = '$section_id' AND exam_date = '$exam_date' AND sem_exam_id = '$sem_exam_id' ORDER BY id DESC")->result_array();

      // if (!empty($checkAlreadyToday)) {
      //   $msgArr = [
      //     'class' => 'danger',
      //     'msg' => 'This Class & Section Has Already Exam Added Today, Please Edit That.',
      //   ];
      //   $this->session->set_userdata($msgArr);
      //   header("Refresh:1 " . base_url() . "semester/dateSheetMaster");
      //   exit(0);
      // }




      if ($addResult) {

        $msgArr = [
          'class' => 'success',
          'msg' => 'New Semester Result Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Semester Result Not Added Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      // header("Refresh:1 " . base_url() . "semester/addSemesterResult");
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

      if (!empty($checkAlreadyToday)) {
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
                  <h3 class="card-title">Add Semester Result</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="row">
                  <div class="card-body">

                    <?php
                    if (isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
                      <input type="hidden" name="updateSemExamId" value="<?= $editId ?>">
                    <?php }

                    ?>
                    <div class="row">


                      <div class="col-md-4">
                        <select id="sem_id" class="form-control  select2 select2-danger" name="sem_id" data-dropdown-css-class="select2-danger" style="width: 100%;" required>
                          <option>Please Select Semester</option>
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
                              <option <?= $selected ?> value="<?= $sd['id'] ?>"><?= $sd['sem_exam_name'] . ' - ' . $sd['exam_year'] ?></option>
                          <?php }
                          } ?>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <select id="class_id" class="form-control  select2 select2-danger" name="class_id" data-dropdown-css-class="select2-danger" style="width: 100%;" required>
                          <option>Please Select Class</option>
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
                      </div>
                      <div class="col-md-4">
                        <select id="section_id" class="form-control  select2 select2-danger" name="section_id" data-dropdown-css-class="select2-danger" style="width: 100%;" required onchange="showExamsWithStudents()">
                          <option>Please Select Section</option>
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
                      </div>








                    </div>
                    <div class="row mt-3">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>UserId</th>
                            <th>Name</th>
                            <th>Roll No</th>
                            <th>Subjects</th>
                            <!-- <th>Marks</th> -->
                          </tr>
                        </thead>
                        <tbody id="showStudents">
                              <tr class="newClass">

                              </tr>
                        </tbody>
                      </table>

                    </div>
                    <!-- <button type="submit" onclick="return confirm('Are You Sure Want to Submit?');" name="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                                                                                  echo 'update';
                                                                                                                } else {
                                                                                                                  echo 'submit';
                                                                                                                } ?>" class="btn btn-primary btn-lg btn-block">Submit</button> -->

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

  <script>
    function showExamsWithStudents() {
      let semExamId = $("#sem_id").val();
      let classId = $("#class_id").val();
      let sectionId = $("#section_id").val();

      console.log(`${semExamId} + ${classId} + ${sectionId}`);
      $.ajax({
        url: '<?= base_url() . 'ajax/showAllSemExamsWithStudents'; ?>',
        method: 'POST',
        data: {
          'semExamId': semExamId,
          'classId': classId,
          'sectionId': sectionId
        },
        success: function(response) {
          response = $.parseJSON(response);
          console.log(response);

          let totalStudents = response.students.length;
          let examDetails = response.examDetails.length;
          let showHTML = '';
          for (let $j = 0; $j < totalStudents; $j++) {

            let subjects = '';
            for (let $k = 0; $k < examDetails; $k++) {
              subjects += `
                  <input type="hidden" name="sec_exam_id" value="${response.examDetails[$k].id}">
                  <input type="hidden" name="subject_id[]" value="${response.examDetails[$k].subject_id}">
                  <div class="col ml-1">
                    ${response.examDetails[$k].subjectName} <input type="number" name="marks[]" class="custom_width">
                  </div>`;
            
            }

            showHTML += `  
            
              <form method="post">
             
                <input type="hidden" name="student_id" value="${response.students[$j].id}">  
                  <td>${$j}</td>
                  <td>${response.students[$j].name}</td>
                  <td>${response.students[$j].roll_no}</td>
                  <td><div class="row">` + subjects + `</div></td>
                  <td><input type="submit" name="submit"></td>
                 
              </form>
              `;
        

          }


   

          // $("#showStudents").html(showHTML);
          $(".newClass").html(showHTML);
        }
      })

    }
  </script>