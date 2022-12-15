<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php $this->load->view('adminPanel/pages/navbar.php');

    $booksData = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::booksTable . " WHERE status != '4' ORDER BY id DESC");

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
                  <h3 class="card-title">Showing All Questions Data</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="table-responsive">


                    <table id="listDatatable" class="table bg-white mb-0 align-middle">
                      <thead class="bg-light">
                        <tr>
                          <th>Id</th>
                          <th>Question Types</th>
                          <th>Question</th>
                          <th>Board Name</th>
                          <th>Class</th>
                          <th>Subject</th>
                          <th>Book</th>
                          <th>Chapter</th>
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

      <!-- /.control-sidebar -->
    </div>
    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->
  <script>
    var ajaxUrlForStudentList = '<?= base_url() . 'ajax/questionLists' ?>';
    // datatable student list intilizing
    loadStudentDataTable();

    function loadStudentDataTable(cn = '', sn = '', bn = '', sm = '', ccn = '', qn = '') {
      $("#listDatatable").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": true,
        lengthMenu: [500, 1000, 2000, 5000, 10000, 50000, 100000],
        pageLength: 100,
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
  </script>