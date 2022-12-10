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

    $chaptersData = $this->CrudModel->dbSqlQuery("SELECT c.*,b.book_name,b.class_name,b.publication_name,b.writer_name FROM " . Table::chaptersTable . " c JOIN ".Table::booksTable." b ON b.id = c.book_id WHERE c.status != '4' ORDER BY c.id DESC");



    // edit and delete action
    if (isset($_GET['action'])) {

      if ($_GET['action'] == 'edit') {
        $editId = $this->CrudModel->sanitizeInput($_GET['edit_id']);

        $editFeeTypeData =   $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::chaptersTable . " WHERE id='$editId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' LIMIT 1");
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


      $schoolUniqueCode = $this->CrudModel->sanitizeInput($_SESSION['schoolUniqueCode']);
      $book_id = $this->CrudModel->sanitizeInput($_POST['book_id']);
      $chapter_name = $this->CrudModel->sanitizeInput($_POST['chapter_name']);


      $alreadyBookAdded = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::chaptersTable . " WHERE schoolUniqueCode = '$schoolUniqueCode' AND book_id = '$book_id' AND chapter_name = '$chapter_name' LIMIT 1");

      if (!empty($alreadyBookAdded)) {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'The Chapter is already added.',
        ];
        $this->session->set_userdata($msgArr);
        header("Refresh:0 " . base_url() . "questionBank/chapterMaster");
        exit(0);
      }
      // date_create()->format('Y-m-d')
      $insertArr = [
        "schoolUniqueCode" => $schoolUniqueCode,
        "book_id" => $book_id,
        "chapter_name" => $chapter_name
      ];


      $insertId = $this->CrudModel->insert(Table::chaptersTable, $insertArr);

      if ($insertId) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Chapter Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Chapter Not Added, Try Again.',
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "questionBank/chapterMaster");
    }

    // update exiting city
    if (isset($_POST['update'])) {

      $schoolUniqueCode = $this->CrudModel->sanitizeInput($_SESSION['schoolUniqueCode']);
      $book_id = $this->CrudModel->sanitizeInput($_POST['book_id']);
      $chapter_name = $this->CrudModel->sanitizeInput($_POST['chapter_name']);

      $updateId = $this->CrudModel->sanitizeInput($_POST['updateStateId']);

      $updateArr = [
        "book_id" => $book_id,
        "chapter_name" => $chapter_name
      ];

      $updateId = $this->CrudModel->update(Table::chaptersTable, $updateArr, $updateId);

      if ($updateId) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Chapter Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Chapter Not Updated, Try Again.',
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "questionBank/chapterMaster");
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
            <!-- left column -->
            <?php //print_r($data['class']);
            ?>
            <div class="col-md-4 mx-auto">
              <!-- jquery validation -->
              <div class="card border-top-3">
                <div class="card-header">
                  <h4><?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                        echo 'Edit Book';
                      } else {
                        echo 'Add Book';
                      } ?></h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <form method="post" action="">
                    <?php
                    if (isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
                      <input type="hidden" name="updateStateId" value="<?= $editId ?>">
                    <?php }

                    ?>
                    <div class="row">
                      <div class="form-group col-md-12">
                        <label>Select Book <span style="color:red;">*</span></label>
                        <select name="book_id" id="bookId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                          <option>Select Book Name</option>
                          <?php
                          $selected = '';
                          foreach ($booksData as $b) {
                            if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                              if ($editFeeTypeData[0]['book_id'] == $b['id']) {
                                $selected = 'selected';
                              } else {
                                $selected = '';
                              }
                            }


                          ?>
                            <option <?= $selected; ?> value="<?= $b['id'] ?>"><?= $b['book_name'] . " - " . $b['class_name'] . " Class " ?></option>
                          <?php }


                          ?>
                        </select>
                      </div>

                      <div class="form-group col-md-12">
                        <label>Chapter Name <span style="color:red;">*</span></label>
                        <input type="text" name="chapter_name" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                                        echo $editFeeTypeData[0]['chapter_name'];
                                                                      } ?>" class="form-control" id="name" placeholder="Kati Patang" required>
                      </div>
                      <div class="form-group col-md-12">
                        <button type="submit" name="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                      echo 'update';
                                                    } else {
                                                      echo 'submit';
                                                    } ?>" class="btn btn-lg float-right mybtnColor">Save</button>
                      </div>
                    </div>
                  </form>
                </div>

              </div>

            </div>



            <div class="col-md-8">
              <div class="card border-top-3">
                <div class="card-header">
                  <h4>Books List</h4>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="stateDataTable" class="table align-middle mb-0 bg-white">
                      <thead class="bg-light">
                        <tr>
                          <!-- <th>Id</th> -->
                          <th>Book Name</th>
                          <th>Class</th>
                          <th>Chapter Name</th>
                          <th>Publication / Writer</th>
                          <!-- <th>Status</th> -->
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (isset($chaptersData)) {
                          $i = 0;
                          foreach ($chaptersData as $cn) { ?>
                            <tr>
                              <!-- <td><?= ++$i; ?></td> -->
                              <!-- <td><?= $cn['id']; ?></td> -->
                              <td><?= $cn['book_name']; ?></td>
                              <td><?= $cn['class_name']; ?></td>
                              <td><?= $cn['chapter_name']; ?></td>
                              <td><?= $cn['publication_name'] . " / " . $cn['writer_name']; ?></td>
                              <!-- <td>
                              <a href="?action=status&edit_id=<?= $cn['id']; ?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1'; ?>" class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger'; ?>">
                                <?php echo ($cn['status'] == '1') ? 'Active' : 'Inactive'; ?>
                            </td> -->
                              <td>
                                <?php

                                if ($_SESSION['schoolUniqueCode'] == $cn['schoolUniqueCode']) { ?>
                                  <a href="?action=edit&edit_id=<?= $cn['id']; ?>"><i class="fa-solid fa-pencil"></i></a>
                                <?php } else {
                                  echo '---';
                                }



                                ?>

                                <!-- <a href="?action=delete&delete_id=<?= $cn['id']; ?>" onclick="return confirm('Are you sure want to delete this?');"><i class="fa-sharp fa-solid fa-trash"></i></a> -->
                              </td>
                            </tr>
                        <?php  }
                        } ?>

                      </tbody>

                    </table>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
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


    $("#stateDataTable").DataTable();
  </script>