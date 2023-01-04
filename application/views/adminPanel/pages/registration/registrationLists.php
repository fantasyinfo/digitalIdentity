<?php
if (isset($_POST['changeStatus'])) {
  $status = $_POST['status'];
  $id = $_POST['regId'];
  $updateStatus = $this->db->query("UPDATE " . Table::registrationTable . " SET status = '$status' WHERE id = '$id' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
}
?>

<style>
  #admBox{
    display: none;
  }
</style>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php $this->load->view('adminPanel/pages/navbar.php');



    ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <!-- <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0"><?= $data['pageTitle'] ?> </h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><?= $data['pageTitle'] ?> </li>
              </ol>
            </div>
          </div>
        </div>
      </div> -->
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <?php


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

          <div class="row">
            <div class="col-md-12">
              <div class="card border-top-3">
                <div class="card-body">
                  <div class="row">

                    <!-- class -->
                    <div class="form-group col-md-2">
                      <label>Class</label>
                      <select name="class_name" id="classId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                        <option></option>
                        <?php
                        $selected = '';
                        foreach (HelperClass::normalClass as $c) {
                          if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                            if ($editFeeTypeData[0]['class_name'] == $c) {
                              $selected = 'selected';
                            } else {
                              $selected = '';
                            }
                          }


                        ?>
                          <option <?= $selected; ?> value="<?= $c ?>"><?= $c ?></option>
                        <?php }


                        ?>
                      </select>
                    </div>

                    <!-- subject -->
                    <div class="form-group col-md-2">
                      <label>Subject</label>
                      <select name="subject_name" id="subjectId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                        <option></option>
                        <?php
                        $selected = '';
                        foreach (HelperClass::subjectNames as $c) {
                          if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                            if ($editFeeTypeData[0]['subject_name'] == $c) {
                              $selected = 'selected';
                            } else {
                              $selected = '';
                            }
                          }


                        ?>
                          <option <?= $selected; ?> value="<?= $c ?>"><?= $c ?></option>
                        <?php }


                        ?>
                      </select>
                    </div>

                    <div class="form-group col-md-2">
                      <label>Select Book </label>
                      <select id="book" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                        <option></option>
                        <?php
                        if (isset($booksData)) {
                          foreach ($booksData as $cd) {  ?>
                            <option value="<?= $cd['id'] ?>"><?= $cd['book_name'] ?></option>
                        <?php }
                        } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-2">
                      <label>Chapter Name</label>
                      <input type="text" class="form-control" id="chapterName" placeholder="Search by chapter Name">
                    </div>


                    <div class="form-group col-md-2">
                      <label>Select Question Type</label>
                      <select name="question_type" id="question_type" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                        <option></option>
                        <?php
                        foreach (HelperClass::questionTypes as $key => $qt) { ?>
                          <option value="<?= $key ?>"><?= $qt ?></option>


                        <?php  } ?>
                      </select>
                    </div>


                    <div class="form-group col-md-2 margin-top-30">
                      <button id="search" class="btn mybtnColor">Search</button>
                      <button onclick="window.location.reload();" class="btn mybtnColor">Clear</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>



            <div class="col-md-12">
              <div class="card border-top-3">
                <div class="card-header">
                  <h3 class="card-title">Showing All Registred Students Data</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="table-responsive">


                    <table id="listDatatable" class="table bg-white mb-0 align-middle">
                      <thead class="bg-light">
                        <tr>
                          <th>Id</th>
                          <th>Registration No</th>
                          <th>Registration Date</th>
                          <th>Student Name</th>
                          <th>Mobile</th>
                          <!-- <th>Date of Birth</th> -->
                          <th>Class</th>
                          <th>Address</th>
                          <th>Status</th>
                          <th>Chnage Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>

                    </table>
                  </div>
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


      <div class="modal" id="myModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">

          <form method="POST">
            <input type="hidden" name="regId" id="regId">
            <div class="modal-content">
              <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark ">Update Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <?php

                $regArr = [
                  1 => 'Registation Success',
                  2 => 'Admission Success',
                  3 => 'Doubt',
                  4 => 'Not Intrested'
                ];
                ?>
                <label>Change Status</label>
                <select id='reg_from_id' class='form-control select2 select2-dark' name='status' onchange='showAdmission()'>
                  <?php
                  foreach ($regArr as $k => $v) { ?>
                    <option value='<?= $k ?>'><?= $v ?></option>
                  <?php } ?>
                </select>

                <?php


                $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE status ='1'   AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ")->result_array();

                $sectionData = $this->db->query("SELECT * FROM " . Table::sectionTable . " WHERE status = '1'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();


                ?>

                <div id='admBox'>
                  <label>Select Class</label>
                  <select id='classId' class='form-control select2 select2-dark' name='class'>
                    <?php
                    foreach ($classData as $v) { ?>
                      <option value='<?= $v['id'] ?>'><?= $v['className'] ?></option>
                    <?php } ?>
                  </select>
                  <label>Select Section</label>
                  <select id='sectionId' class='form-control select2 select2-dark' name='section'>
                    <?php
                    foreach ($sectionData as $v) { ?>
                      <option value='<?= $v['id'] ?>'><?= $v['sectionName'] ?></option>
                    <?php } ?>
                  </select>
                </div>


              </div>
              <div class="modal-footer">
                <button type="submit" name="changeStatus" class="btn btn-dark btn-block">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    var ajaxUrlForStudentList = '<?= base_url() . 'ajax/registrationLists' ?>';
    // datatable student list intilizing
    loadStudentDataTable();

    function loadStudentDataTable(cn = '', sn = '', bn = '', sm = '', ccn = '', qn = '') {
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
            className: cn,
            subjectName: sn,
            book: bn,
            chapterName: ccn,
            questionType: qn
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
        $("#classId").val(),
        $("#subjectId").val(),
        $("#book").val(),
        $("#chapterName").val(),
        $("#question_type").val()
      );
    });






    function changeStatus(id) {
      $("#regId").val(id);
      $("#myModal").modal('show');

    }

    function showAdmission() {
      if($("#reg_from_id").val() == 2){
        $("#admBox").show();
      }else{
        $("#admBox").hide();
      }
      // $("#myModal").modal('show');

    }
  </script>