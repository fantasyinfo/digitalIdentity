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
    $feeDiscountData = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeesdiscountsTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status != '4' ORDER BY id DESC");
   

    // edit and delete action
    if (isset($_GET['action'])) {

      if ($_GET['action'] == 'edit') {
        $editId = $this->CrudModel->sanitizeInput($_GET['edit_id']);

        $editFeesDiscountData =   $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeesdiscountsTable . " WHERE id='$editId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' LIMIT 1");
      }


      if ($_GET['action'] == 'delete') {

        $deleteId = $this->CrudModel->sanitizeInput($_GET['delete_id']);

        $deleteDiscountTypeData = $this->CrudModel->runQueryIUD("DELETE FROM " . Table::newfeesdiscountsTable . " WHERE id='$deleteId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if ($deleteDiscountTypeData) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Fees Discount Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Fees Discount Deleted Try Again.'
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "feesManagement/feeDisctountMaster");
      }

      if ($_GET['action'] == 'status') {

        $status = $this->CrudModel->sanitizeInput($_GET['status']);
        $updateId = $this->CrudModel->sanitizeInput($_GET['edit_id']);

        $updateStatus = $this->CrudModel->runQueryIUD("UPDATE " . Table::newfeesdiscountsTable . " SET status = '$status' WHERE id = '$updateId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if ($updateStatus) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Fees Discount Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Fees Discount Status Not Updated Try Again.',
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "feesManagement/feeDisctountMaster");
      }
    }


    // insert new city
    if (isset($_POST['submit'])) {

      $alreadyFeeTypeAdded = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeesdiscountsTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND shortCode = '{$this->CrudModel->sanitizeInput($_POST['shortCode'])}' ");

      if (!empty($alreadyFeeTypeAdded)) {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'This Fees Discount Short Code is already inserted, Please Edit That',
        ];
        $this->session->set_userdata($msgArr);
        header("Refresh:0 " . base_url() . "feesManagement/feeDisctountMaster");
        exit(0);
      }
      // date_create()->format('Y-m-d')
      $insertArr = [
        "schoolUniqueCode" => $this->CrudModel->sanitizeInput($_SESSION['schoolUniqueCode']),
        "feeDiscountName" => $this->CrudModel->sanitizeInput($_POST['feeDiscountName']),
        "shortCode" => $this->CrudModel->sanitizeInput($_POST['shortCode']),
        "description" => $this->CrudModel->sanitizeInput($_POST['description']),
        "amount" => $this->CrudModel->sanitizeInput($_POST['amount']),
        "session_table_id" => $this->CrudModel->sanitizeInput($_SESSION['currentSession'])
    ];


      $insertId = $this->CrudModel->insert(Table::newfeesdiscountsTable, $insertArr);

      if ($insertId) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Fees Discount Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Fees Discount Not Added, Try Again.',
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "feesManagement/feeDisctountMaster");
    }

    // update exiting city
    if (isset($_POST['update'])) {

      $updateId = $this->CrudModel->sanitizeInput($_POST['updateStateId']);

      $updateArr = [
        "feeDiscountName" => $this->CrudModel->sanitizeInput($_POST['feeDiscountName']),
        "shortCode" => $this->CrudModel->sanitizeInput($_POST['shortCode']),
        "description" => $this->CrudModel->sanitizeInput($_POST['description']),
        "amount" => $this->CrudModel->sanitizeInput($_POST['amount']),
    ];

      $updateId = $this->CrudModel->update(Table::newfeesdiscountsTable, $updateArr,$updateId);
      
      if ($updateId) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Fees Discount Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'New Fees Discount Not Updated, Try Again.',
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "feesManagement/feeDisctountMaster");
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
            <div class="col-sm-6">
              <!-- <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><?= $data['pageTitle'] ?> </li>
              </ol> -->
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
            <div class="col-md-4 mx-auto">
              <!-- jquery validation -->
              <div class="card border-top-3">
                <div class="card-header">
                  <h4><?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo 'Edit Fees Discount';}else{echo 'Add Fees Discount';} ?></h4>
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
                        <label>Name <span style="color:red;">*</span></label>
                        <input type="text" name="feeDiscountName" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editFeesDiscountData[0]['feeDiscountName'];} ?>" class="form-control" id="name" placeholder="Monthly Fees Discount" required>
                      </div>
                      <div class="form-group col-md-12">
                        <label>Short Name / Code <span style="color:red;">*</span></label>
                        <input type="text" name="shortCode" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editFeesDiscountData[0]['shortCode'];} ?>" class="form-control" id="name" placeholder="montly fees Discount" required>
                      </div>
                      <div class="form-group col-md-12">
                        <label>Amount <span style="color:red;">*</span></label>
                        <input type="number" name="amount" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editFeesDiscountData[0]['amount'];} ?>" class="form-control" id="name" placeholder="" required>
                      </div>
                      <div class="form-group col-md-12">
                        <label>Description</label>
                        <textarea name="description" class="form-control" id="name"><?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo trim($editFeesDiscountData[0]['description']);} ?></textarea>
                      </div>
                      <div class="form-group col-md-12">
                        <button type="submit" name="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { echo 'update';} else {echo 'submit';} ?>" class="btn btn-lg float-right mybtnColor">Save</button>
                      </div>
                    </div>
                  </form>
                </div>

              </div>

            </div>



            <div class="col-md-8">
              <div class="card border-top-3">
                <div class="card-header">
                  <h4>Fees Discount List</h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <div class="table-responsive"> 
                  <table id="stateDataTable" class="table align-middle mb-0 bg-white">
                    <thead class="bg-light">
                      <tr>
                        <!-- <th>Id</th> -->
                        <th>Name</th>
                        <th>Short Code</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                       <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (isset($feeDiscountData)) {
                        $i = 0;
                        foreach ($feeDiscountData as $cn) { ?>
                          <tr>
                            <!-- <td><?= ++$i; ?></td> -->
                            <!-- <td><?= $cn['id']; ?></td> -->
                            <td><?= $cn['feeDiscountName']; ?></td>
                            <td><?= $cn['shortCode']; ?></td>
                            <td><?= $cn['description']; ?></td>
                            <td><?= $cn['amount']; ?></td>
                            <td>
                              <a href="?action=status&edit_id=<?= $cn['id']; ?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1'; ?>" class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger'; ?>">
                                <?php echo ($cn['status'] == '1') ? 'Active' : 'Inactive'; ?>
                            </td>
                            <td>
                                  <a href="?action=edit&edit_id=<?= $cn['id']; ?>"><i class="fa-solid fa-pencil"></i></a>   
                                  <a href="?action=delete&delete_id=<?= $cn['id']; ?>" onclick="return confirm('Are you sure want to delete this?');"><i class="fa-sharp fa-solid fa-trash"></i></a>
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