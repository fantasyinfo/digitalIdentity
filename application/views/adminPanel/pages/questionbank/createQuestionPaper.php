<style>
  #draggable {
    width: 150px;
    height: 150px;
    padding: 0.5em;
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

    // fetching fees type data
    $booksData = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::booksTable . " WHERE status != '4' ORDER BY id DESC");


    // edit and delete action
    if (isset($_GET['action'])) {

      if ($_GET['action'] == 'edit') {
        $editId = $this->CrudModel->sanitizeInput($_GET['edit_id']);

        $editFeeTypeData =   $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::booksTable . " WHERE id='$editId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' LIMIT 1");
      }


      // if ($_GET['action'] == 'delete') {

      //   $deleteId = $this->CrudModel->sanitizeInput($_GET['delete_id']);

      //   $deleteFeeTypeData = $this->CrudModel->runQueryIUD("DELETE FROM " . Table::newfeestypesTable . " WHERE id='$deleteId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

      //   if ($deleteFeeTypeData) {
      //     $msgArr = [
      //       'class' => 'success',
      //       'msg' => 'Fees Type Deleted Successfully',
      //     ];
      //     $this->session->set_userdata($msgArr);
      //   } else {
      //     $msgArr = [
      //       'class' => 'danger',
      //       'msg' => 'Fees Type Deleted Try Again.'
      //     ];
      //     $this->session->set_userdata($msgArr);
      //   }
      //   header("Refresh:1 " . base_url() . "feesManagement/feeTypeMaster");
      // }

      // if ($_GET['action'] == 'status') {

      //   $status = $this->CrudModel->sanitizeInput($_GET['status']);
      //   $updateId = $this->CrudModel->sanitizeInput($_GET['edit_id']);

      //   $updateStatus = $this->CrudModel->runQueryIUD("UPDATE " . Table::newfeestypesTable . " SET status = '$status' WHERE id = '$updateId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

      //   if ($updateStatus) {
      //     $msgArr = [
      //       'class' => 'success',
      //       'msg' => 'Fee Type Status Updated Successfully',
      //     ];
      //     $this->session->set_userdata($msgArr);
      //   } else {
      //     $msgArr = [
      //       'class' => 'danger',
      //       'msg' => 'Fee Type Status Not Updated Try Again.',
      //     ];
      //     $this->session->set_userdata($msgArr);
      //   }
      //   header("Refresh:1 " . base_url() . "feesManagement/feeTypeMaster");
      // }
    }


    // insert new city
    if (isset($_POST['submit'])) {
      HelperClass::prePrintR($_POST);


      $schoolUniqueCode = $this->CrudModel->sanitizeInput($_SESSION['schoolUniqueCode']);
      $book_name = $this->CrudModel->sanitizeInput($_POST['book_name']);
      $class_name = $this->CrudModel->sanitizeInput($_POST['class_name']);
      $subject_name = $this->CrudModel->sanitizeInput($_POST['subject_name']);
      $board_name = $this->CrudModel->sanitizeInput($_POST['board_name']);
      $publication_name = $this->CrudModel->sanitizeInput($_POST['publication_name']);
      $writer_name = $this->CrudModel->sanitizeInput($_POST['writer_name']);

      $alreadyFeeTypeAdded = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::booksTable . " WHERE schoolUniqueCode = '$schoolUniqueCode' AND book_name = '$book_name' AND class_name = '$class_name' AND publication_name = '$publication_name' AND board_name = '$board_name' AND writer_name = '$writer_name' AND subject_name = '$subject_name' LIMIT 1");

      if (!empty($alreadyFeeTypeAdded)) {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'The Book is already added.',
        ];
        $this->session->set_userdata($msgArr);
        header("Refresh:0 " . base_url() . "questionBank/booksMaster");
        exit(0);
      }
      // date_create()->format('Y-m-d')
      $insertArr = [
        "schoolUniqueCode" => $schoolUniqueCode,
        "book_name" => $book_name,
        "class_name" => $class_name,
        "board_name" => $board_name,
        "publication_name" => $publication_name,
        "writer_name" => $writer_name,
        "subject_name" => $subject_name
      ];


      $insertId = $this->CrudModel->insert(Table::booksTable, $insertArr);

      if ($insertId) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Book Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Book Not Added, Try Again.',
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "questionBank/booksMaster");
    }

    // update exiting city
    if (isset($_POST['update'])) {

      $schoolUniqueCode = $this->CrudModel->sanitizeInput($_SESSION['schoolUniqueCode']);
      $book_name = $this->CrudModel->sanitizeInput($_POST['book_name']);
      $class_name = $this->CrudModel->sanitizeInput($_POST['class_name']);
      $subject_name = $this->CrudModel->sanitizeInput($_POST['subject_name']);
      $board_name = $this->CrudModel->sanitizeInput($_POST['board_name']);
      $publication_name = $this->CrudModel->sanitizeInput($_POST['publication_name']);
      $writer_name = $this->CrudModel->sanitizeInput($_POST['writer_name']);

      $updateId = $this->CrudModel->sanitizeInput($_POST['updateStateId']);

      $updateArr = [
        "book_name" => $book_name,
        "class_name" => $class_name,
        "board_name" => $board_name,
        "publication_name" => $publication_name,
        "writer_name" => $writer_name,
        "subject_name" => $subject_name
      ];

      $updateId = $this->CrudModel->update(Table::booksTable, $updateArr, $updateId);

      if ($updateId) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Book Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Book Not Updated, Try Again.',
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "questionBank/booksMaster");
    }


    // print_r($cityData);


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
              <!-- <h1 class="m-0"><?= $data['pageTitle'] ?> </h1> -->
            </div><!-- /.col -->
            <!-- <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><?= $data['pageTitle'] ?> </li>
              </ol> 
            </div> -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">

            <div class="col-md-7 py-2 border">

              <div class="card border-top-3">
                <div class="card-header">Search Filter</div>
                <div class="card-body">
                  <form method="GET">
                    <div class="row my-3">

                      <!-- class -->
                      <div class="form-group col-md-3">
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
                      <div class="form-group col-md-3">
                        <label>Subject</label>
                        <select name="subject_name" id="subjectId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;" onchange="loadBooks()">
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

                      <div class="form-group col-md-3">
                        <label>Select Book </label>
                        <select id="book" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;" onchange="loadChapters()">
                          <option></option>
                        </select>
                      </div>
                      <div class="form-group col-md-3">
                        <label>Select Chapter </label>
                        <select id="chapter" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;" onchange="loadQuestions()">
                          <option></option>
                        </select>
                      </div>


                    </div>
                  </form>
                </div>
              </div>




              <div id="leftSide" class="ui-widget-content">
              </div>


            </div>
            <div class="col-md-5 py-2 border">
              <form method="POST" id="myForm">
                <div id="rightSide" class="ui-widget-content">
                  <li class="rightSide card px-2 py-2">Drag Question Below Here</li>
                </div>
                <button type="submit" name="submit" class="btn btn-block mybtnColor">Save</button>
              </form>
            </div>

          </div>



          <!--/.col (right) -->
        </div>

        <!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>


    <!-- </div>
      </div>
    </div> -->
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->

    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->
  <script>
    // var ajaxUrl = '<?= base_url() . 'ajax/listStudentsAjax' ?>';


    $("#myQuestionLists").DataTable();
  </script>

  <script>
    $(function() {


      // $(".leftSide").draggable({
      //   connectToSortable: "#rightSide",

      // });


      // $(".rightSide").draggable({
      //   connectToSortable: "#leftSide",

      // });

      // $(".rightSide").droppable({
      //   connectToSortable: "#leftSide",

      // });



      $("#leftSide").sortable({
        connectWith: "#rightSide",
        dropOnEmpty: true
      });

      $("#rightSide").sortable({
        connectWith: "#leftSide",
        dropOnEmpty: true
      });

      // $(".rightSide").on("sortupdate", function(event, ui) {
      //   console.log(event);
      //   console.log(ui);
      // });

    });


    function loadBooks() {
      // $("#book").empty();
      $.ajax({
        url: '<?= base_url('ajax/showBooksViaSubject') ?>',
        method: 'POST',
        data: {
          classId: $("#classId").val(),
          subjectId: $("#subjectId").val()
        },
        success: function(response) {
          console.log(response);
          response = $.parseJSON(response);
          $('#book').append(response);
        }
      })
    }

    function loadChapters() {
      // $("#chapter").empty();
      $.ajax({
        url: '<?= base_url('ajax/bookIdtoChapters') ?>',
        method: 'POST',
        data: {
          bookId: $("#book").val()
        },
        success: function(response) {
          console.log(response);
          response = $.parseJSON(response);
          $('#chapter').append(response);
        }
      })
    }

    function loadQuestions() {
      $("#leftSide").empty();
      $.ajax({
        url: '<?= base_url('ajax/loadQuestions') ?>',
        method: 'POST',
        data: {
          bookId: $("#book").val(),
          chapterId: $('#chapter').val()
        },
        success: function(response) {
          console.log(response);
          response = $.parseJSON(response);
          $('#leftSide').append(response);
        }
      })
    }
  </script>