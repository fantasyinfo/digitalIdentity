<style>
  #dwnResult
  {
    display: none;
  }
</style>

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
          $classData = $this->CrudModel->allClass(Table::classTable, $_SESSION['schoolUniqueCode']);
          $sectionData = $this->CrudModel->allSection(Table::sectionTable, $_SESSION['schoolUniqueCode']);
          $semesterExamName = $this->db->query("SELECT * FROM " . Table::semExamNameTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND session_table_id = '{$_SESSION['currentSession']}' ORDER BY id DESC")->result_array();
          $examIdValue = '';
          if (isset($_GET['action'])) {


            if ($_GET['action'] == 'resultPublished') {
              $examIdValue = $_GET['examId'];
            }





            if ($_GET['action'] == 'status') {
              $status = $_GET['status'];
              $updateId = $_GET['edit_id'];
              $updateStatus = $this->db->query("UPDATE " . Table::studentTable . " SET status = '$status' WHERE id = '$updateId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

              if ($updateStatus) {
                $msgArr = [
                  'class' => 'success',
                  'msg' => 'Student Status Updated Successfully',
                ];
                $this->session->set_userdata($msgArr);
              } else {
                $msgArr = [
                  'class' => 'danger',
                  'msg' => 'Student Status Not Updated Due to this Error. ' . $this->db->last_query(),
                ];
                $this->session->set_userdata($msgArr);
              }
              header("Refresh:1 " . base_url() . "student/list");
            }
          }





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
              <select id="semesterExam" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                <option></option>
                <?php
                if (isset($semesterExamName)) {
                  foreach ($semesterExamName as $sm) {  ?>
                    <option value="<?= $sm['id'] ?>"><?= $sm['sem_exam_name'] . " - " . $sm['exam_year']; ?></option>
                <?php }
                } ?>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label>Select Class </label>
              <select id="studentClass" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                <option></option>
                <?php
                if (isset($classData)) {
                  foreach ($classData as $cd) {  ?>
                    <option value="<?= $cd['id'] ?>"><?= $cd['className'] ?></option>
                <?php }
                } ?>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label>Select Section </label>
              <select id="studentSection" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="showStudents()">
                <option></option>
                <?php
                if (isset($sectionData)) {
                  foreach ($sectionData as $sd) {  ?>
                    <option value="<?= $sd['id'] ?>"><?= $sd['sectionName'] ?></option>
                <?php }
                } ?>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label>Select Students </label>
              <select name="studentName" id="studentName" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                <option></option>
              </select>
            </div>
            <div class="form-group col-md-2 pt-4">
              <button id="search" class="btn btn-primary">Submit</button>
              <button onclick="window.location.reload();" class="btn btn-warning">Clear</button>
            </div>
            <!-- <div class="form-group col-md-2">
            
          </div> -->

            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Showing All Exams Data</h3>
                  <button onclick="dwnResult()" id="dwnResult" class="btn btn-primary">Download Result</button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="listDatatable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <!-- <th>Result Id</th> -->
                        <!-- <th>Exam Id</th> -->
                        <th>Exam Name</th>
                        <th>Subject Name</th>
                        <th>Max Marks</th>
                        <th>Min Marks</th>
                        <th>Student Name</th>
                        <th>Class & Section</th>
                        <th>Marks Obtained</th>
                        <th>Result Status</th>
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

      <!-- /.control-sidebar -->
    </div>
    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->
  <script>
    var ajaxUrlForStudentList = '<?= base_url() . 'ajax/showAllSemesterResultsList' ?>';
    // datatable student list intilizing
    //  loadStudentDataTable();

    function loadStudentDataTable(sm = '', sc = '', ss = '', sn = '') {
      $("#listDatatable").DataTable({
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
          url: ajaxUrlForStudentList,
          data: {
            semesterExam: sm,
            studentClass: sc,
            studentSection: ss,
            studentName: sn
          },
          error: function() {
            console.log('something went wrong.');
          }
        }
      });
    }

    $("#search").click(function(e) {
      e.preventDefault();
      $("#listDatatable").DataTable().destroy();
      loadStudentDataTable(
        $("#semesterExam").val(),
        $("#studentClass").val(),
        $("#studentSection").val(),
        $("#studentName").val()
      );

      if($("#semesterExam").val() != '' && $("#studentClass").val() != '' && $("#studentSection").val() != '' && $("#studentName").val() != '')
      {
        $("#dwnResult").show();
      }
    });


    window.onload = function() {
      document.getElementById("search").click();
    };




    function showStudents() {
      var classId = $("#studentClass").val();
      var sectionId = $("#studentSection").val();
      if (classId != '' && sectionId != '') {
        console.log(classId + ' and ' + sectionId);
        $.ajax({
          url: '<?= base_url() . 'ajax/showStudentViaClassAndSectionId'; ?>',
          method: 'post',
          processData: 'false',
          data: {
            classId: classId,
            sectionId: sectionId
          },
          success: function(response) {
            //console.log(response);
            response = $.parseJSON(response);
            $('#studentName').append(response);
          },
          error: function(error) {
            console.log(error);
          }

        });
      }
    }


    function dwnResult() {
      let classId = $("#studentClass").val();
      let sectionId = $("#studentSection").val();
      let semExamNameId = $("#semesterExam").val();
      let studentId = $("#studentName").val();
      let baseUrl = '<?= base_url(); ?>';
      if (classId != '' && sectionId != '' && semExamNameId != '') {
        window.location.href = baseUrl + 'semResult?data=' + classId + '-' + sectionId + '-' + semExamNameId + '-' + studentId;
      } else {
        alert('Please Select Exam Name And Class And Section with Student Name On Filters');
      }




    }
  </script>