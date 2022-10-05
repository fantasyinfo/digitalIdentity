<style>
  #dwnDateSheet
  {
    display: none;
  }
</style>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php $this->load->view('adminPanel/pages/navbar.php');
    
    
    
    $examNameSem = $this->db->query("SELECT * FROM " . Table::semExamNameTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND  status = '1' ORDER BY id DESC")->result_array();

    $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND  status = '1' ORDER BY id DESC")->result_array();

    $sectionData = $this->db->query("SELECT * FROM " . Table::sectionTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND  status = '1' ORDER BY id DESC")->result_array();
    
    
    ?>
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


          if (isset($_POST['submit'])) {
            $updateDriver = $this->db->query("UPDATE " . Table::teacherTable . " SET vechicle_type = '{$_POST['vechicle_type']}', driver_id = '{$_POST['driver_id']}' WHERE id = '{$_POST['stu_id']}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

            if ($updateDriver) {
              $msgArr = [
                'class' => 'success',
                'msg' => 'Driver Assign Successfully',
              ];
              $this->session->set_userdata($msgArr);
            } else {
              $msgArr = [
                'class' => 'danger',
                'msg' => 'Driver Not Assign Due to this Error. ' . $this->db->last_query(),
              ];
              $this->session->set_userdata($msgArr);
            }
            header("Refresh:1 " . base_url() . "teacher/list");
          }


          if (isset($_GET['action'])) {
            if ($_GET['action'] == 'status') {
              $status = $_GET['status'];
              $updateId = $_GET['edit_id'];
              $updateStatus = $this->db->query("UPDATE " . Table::teacherTable . " SET status = '$status' WHERE id = '$updateId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

              if ($updateStatus) {
                $msgArr = [
                  'class' => 'success',
                  'msg' => 'Teacher Status Updated Successfully',
                ];
                $this->session->set_userdata($msgArr);
              } else {
                $msgArr = [
                  'class' => 'danger',
                  'msg' => 'Teacher Status Not Updated Due to this Error. ' . $this->db->last_query(),
                ];
                $this->session->set_userdata($msgArr);
              }
              header("Refresh:1 " . base_url() . "teacher/list");
            }
          }











          $this->CrudModel->checkPermission();
          if (!empty($this->session->userdata('msg'))) { ?>

            <div class="alert alert-<?= $this->session->userdata('class') ?> alert-dismissible fade show" role="alert">
              <?= $this->session->userdata('msg');

              if ($this->session->userdata('class') == 'success') {
                HelperClass::swalSuccess($this->session->userdata('msg'));
              } else if ($this->session->userdata('class') == 'danger') {
                HelperClass::swalError($this->session->userdata('msg'));
              }
              ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
            $this->session->unset_userdata('class');
            $this->session->unset_userdata('msg');
          }
          ?>
          <h5>Search Filters</h5>
          <div class="row">

            <div class="form-group col-md-2">
              <label>Select Semester Exam </label>
              <select id="sem_exam_id" class="form-control  select2 select2-danger" name="sem_exam_id" data-dropdown-css-class="select2-danger" style="width: 100%;" required>
                <option></option>
                <?php
                $selected = '';
                if (isset($examNameSem) && !empty($examNameSem)) {


                  foreach ($examNameSem as $sd) {
                    
                ?>
                    <option <?= $selected ?> value="<?= $sd['id'] ?>"><?= $sd['sem_exam_name'] . ' - ' . $sd['exam_year'] ?></option>
                <?php } }
                 ?>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label>Select Class</label>
              <select id="class_id" class="form-control  select2 select2-danger" name="class_id" data-dropdown-css-class="select2-danger" style="width: 100%;" required>
                <option></option>
                <?php
                $selected = '';
                if (isset($classData) && !empty($classData)) {


                  foreach ($classData as $sd) {
                    
                ?>
                    <option <?= $selected ?> value="<?= $sd['id'] ?>"><?= $sd['className'];?></option>
                <?php } }
                 ?>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label>Select Section</label>
              <select id="section_id" class="form-control  select2 select2-danger" name="section_id" data-dropdown-css-class="select2-danger" style="width: 100%;" required>
                <option></option>
                <?php
                $selected = '';
                if (isset($sectionData) && !empty($sectionData)) {


                  foreach ($sectionData as $sd) {
                    
                ?>
                    <option <?= $selected ?> value="<?= $sd['id'] ?>"><?= $sd['sectionName'];?></option>
                <?php } }
                 ?>
              </select>
            </div>
       
            <div class="form-group col-md-2 pt-4">
              <button id="searchTeacher" class="btn btn-primary">Submit</button>
              <button onclick="window.location.reload();" class="btn btn-warning">Clear</button>
            </div>
            <!-- <div class="form-group col-md-2">
            
          </div> -->

            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Showing All Class Exams</h3>
                 <button onclick="dwnDateSheet()" id="dwnDateSheet" class="btn btn-primary">Download DateSheet</button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="listDatatableTeacher" class="table table-bordered table-striped table-responsive">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Semester Exam Name</th>
                        <th>SemId</th>
                        <th>Exam Date</th>
                        <th>Exam Day</th>
                        <th>Class - Section</th>
                        <th>Subject</th>
                        <!-- <th>Exam Time </th> -->
                        <th>Exam Marks</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>

                  </table>
                </div>
                <!-- /.card-body -->
              </div>
            </div>
          </div>

          <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <!-- Control Sidebar -->


      <!-- bootstrap modal box -->
      <!-- Modal -->
      <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <form method="POST">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Select Driver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <label>Select Vechicle Type</label>
                    <select id="vechicle_type" class="form-control  select2 select2-danger" name="vechicle_type" required data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="vechicleType(this)">
                      <option></option>
                      <?php

                      foreach (HelperClass::vehicleType as $key => $dList) {  ?>
                        <option value="<?= $key ?>"><?= HelperClass::vehicleType[$key] ?></option>
                      <?php  } ?>
                    </select>

                  </div>
                  <div class="col-md-12">
                    <label>Select Driver Name</label>
                    <select id="driver_id" class="form-control  select2 select2-danger" name="driver_id" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                      <option></option>

                    </select>
                  </div>
                </div>
                <input type="hidden" name="stu_id" id="stu_id">

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="submit" class="btn btn-primary">Assign Driver</button>
              </div>
            </div>
          </form>
        </div>
      </div>










      <!-- /.control-sidebar -->
    </div>
    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->

  <script>
    var ajaxUrlForTeacherList = '<?= base_url() . 'ajax/dateSheetList' ?>';
    let baseUrl = '<?= base_url(); ?>';
    // datatable for teacher
    loadTeacherDataTable();

    function loadTeacherDataTable(se = '', c = '', s = '') {
      $("#listDatatableTeacher").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        dom: 'lBfrtip',
        buttons: [
          'copyHtml5',
          'excelHtml5',
          'csvHtml5',
          'pdfHtml5'
        ],
        lengthMenu: [10, 50, 100, 500, 1000, 2000, 5000, 10000, 50000, 100000],
        pageLength: 10,
        processing: true,
        serverSide: true,
        searching: false,
        paging: true,
        ajax: {
          method: 'post',
          url: ajaxUrlForTeacherList,
          data: {
            secExamNameId: se,
            classId: c,
            sectionId: s
          },
          error: function() {
            console.log('something went wrong.');
          }
        }
      });
    }

    $("#searchTeacher").click(function(e) {
      e.preventDefault();
      $("#listDatatableTeacher").DataTable().destroy();
      loadTeacherDataTable(
        $("#sem_exam_id").val(),
        $("#class_id").val(),
        $("#section_id").val()
      );
      if($("#sem_exam_id").val() != '' && $("#class_id").val() != '' && $("#section_id").val() != '')
      {
        $("#dwnDateSheet").show();
      }
      
    });

    function dwnDateSheet()
    {
      let classId = $("#class_id").val();
      let sectionId = $("#section_id").val();
      let semExamId = $("#sem_exam_id").val();

      if(classId != '' && sectionId!= '' && semExamId != '')
      {
        window.location.href = baseUrl + 'semester/downloadDateSheet?classId=' + classId + '&sectionId=' + sectionId + '&secExamNameId=' + semExamId;
      }else
      {
        alert('Please Select Exam Name And Class With Section On Filters');
      }



      
    }
  </script>


  <script>
    function vechicleType(x) {
      $('#driver_id').empty();

      $.ajax({
        url: '<?= base_url() . 'ajax/showDriverListViaVechicleType'; ?>',
        method: 'POST',
        data: {
          'vechicleType': x.value
        },
        success: function(response) {
          response = $.parseJSON(response);
          $("#driver_id").append(response);
        }
      })
    }

    function assingDriver(x) {
      $("#stu_id").val(x);
      $("#exampleModalCenter").modal('show');
      console.log(x);

    }
  </script>