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
    $feeMasterData = $this->CrudModel->dbSqlQuery("SELECT FHM.newFeeGroupId FROM " . Table::newfeemasterTable . " FHM 
    WHERE FHM.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND FHM.status != '4' GROUP BY newFeeGroupId");

    $feeGroupData = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeesgroupsTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' ");

    $feeTypesData = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeestypesTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' ");



    // edit and delete action
    if (isset($_GET['action'])) {

      if ($_GET['action'] == 'delete' && $_GET['assingClass'] == '1') {

        $deleteId = $this->CrudModel->sanitizeInput($_GET['delete_assign_class_id']);
        $ids = explode('-', $deleteId);


        $deleteDiscountTypeData = $this->CrudModel->runQueryIUD("DELETE FROM " . Table::newfeeclasswiseTable . " WHERE class_id='{$ids[1]}' AND section_id = '{$ids[2]}' AND fee_group_id = '{$ids[0]}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if ($deleteDiscountTypeData) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Assign Fees Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Assign Fees Not Deleted Try Again.'
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:0 " . base_url() . "feesManagement/feeHeadMaster");
      }



      if ($_GET['action'] == 'edit') {
        $editId = $this->CrudModel->sanitizeInput($_GET['edit_id']);

        $editFeesHeadMasterData =   $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeemasterTable . " WHERE id='$editId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' LIMIT 1");
      }


      if ($_GET['action'] == 'delete' && $_GET['assingClass'] == '2') {

        $deleteId = $this->CrudModel->sanitizeInput($_GET['delete_id']);

        $deleteDiscountTypeData = $this->CrudModel->runQueryIUD("DELETE FROM " . Table::newfeemasterTable . " WHERE id='$deleteId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if ($deleteDiscountTypeData) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Fees Head Master Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Fees Head Master Deleted Try Again.'
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "feesManagement/feeHeadMaster");
      }


      if ($_GET['action'] == 'status') {

        $status = $this->CrudModel->sanitizeInput($_GET['status']);
        $updateId = $this->CrudModel->sanitizeInput($_GET['edit_id']);

        $updateStatus = $this->CrudModel->runQueryIUD("UPDATE " . Table::newfeemasterTable . " SET status = '$status' WHERE id = '$updateId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if ($updateStatus) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Fees Head Master Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Fees Head Master Status Not Updated Try Again.',
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "feesManagement/feeHeadMaster");
      }
    }


    // insert new city
    if (isset($_POST['submit']) && isset($_POST['newFeeGroupId'])) {

      $alreadyFeeTypeAdded = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeemasterTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND newFeeGroupId = '{$this->CrudModel->sanitizeInput($_POST['newFeeGroupId'])}' AND newFeeType = '{$this->CrudModel->sanitizeInput($_POST['newFeeType'])}' LIMIT 1");

      if (!empty($alreadyFeeTypeAdded)) {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'This Fees Master is already inserted, Please Edit That',
        ];
        $this->session->set_userdata($msgArr);
        header("Refresh:0 " . base_url() . "feesManagement/feeHeadMaster");
        exit(0);
      }
      // date_create()->format('Y-m-d')
      $insertArr = [
        "schoolUniqueCode" => $this->CrudModel->sanitizeInput($_SESSION['schoolUniqueCode']),
        "newFeeGroupId" => $this->CrudModel->sanitizeInput($_POST['newFeeGroupId']),
        "newFeeType" => $this->CrudModel->sanitizeInput($_POST['newFeeType']),
        "dueDate" => $this->CrudModel->sanitizeInput($_POST['dueDate']),
        "amount" => $this->CrudModel->sanitizeInput($_POST['amount']),
        "fineType" => $this->CrudModel->sanitizeInput($_POST['fineType']),
        "finePercentage" => isset($_POST['finePercentage']) ? $this->CrudModel->sanitizeInput($_POST['finePercentage']) : 0,
        "fineFixAmount" => isset($_POST['fineFixAmount']) ? $this->CrudModel->sanitizeInput($_POST['fineFixAmount']) : 0,
        "session_table_id" => $this->CrudModel->sanitizeInput($_SESSION['currentSession'])
      ];


      $insertId = $this->CrudModel->insert(Table::newfeemasterTable, $insertArr);

      if ($insertId) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Fees Head Master Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Fees Head Master Not Added, Try Again.',
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "feesManagement/feeHeadMaster");
    }

    // update exiting city
    if (isset($_POST['update'])) {

      $updateId = $this->CrudModel->sanitizeInput($_POST['updateStateId']);

      $updateArr = [
        "newFeeGroupId" => $this->CrudModel->sanitizeInput($_POST['newFeeGroupId']),
        "newFeeType" => $this->CrudModel->sanitizeInput($_POST['newFeeType']),
        "dueDate" => $this->CrudModel->sanitizeInput($_POST['dueDate']),
        "amount" => $this->CrudModel->sanitizeInput($_POST['amount']),
        "fineType" => $this->CrudModel->sanitizeInput($_POST['fineType']),
        "finePercentage" => isset($_POST['finePercentage']) ? $this->CrudModel->sanitizeInput($_POST['finePercentage']) : 0,
        "fineFixAmount" => isset($_POST['fineFixAmount']) ? $this->CrudModel->sanitizeInput($_POST['fineFixAmount']) : 0,
      ];

      $updateId = $this->CrudModel->update(Table::newfeemasterTable, $updateArr, $updateId);

      if ($updateId) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Fees Head Master Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'New Fees Head Master Not Updated, Try Again.',
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "feesManagement/feeHeadMaster");
    }


    if (isset($_POST['assignFeesToClass']) && isset($_POST['feesGroupId'])) {



      $studentData = $this->CrudModel->dbSqlQuery("SELECT id FROM " . Table::studentTable . " WHERE class_id = '{$_POST['class_id']}' 
      AND section_id = '{$_POST['section_id']}' AND status = '1'");

      $total =  count($studentData);

      for($a=0; $a < $total; $a++)
      {

      $feeGroupData = $this->CrudModel->dbSqlQuery("SELECT ft.id as feesTypeId FROM " . Table::newfeemasterTable . " m JOIN " . Table::newfeestypesTable . " ft ON m.newFeetype = ft.id  JOIN " . Table::newfeesgroupsTable . " fgt ON m.newFeeGroupId = fgt.id WHERE m.newFeeGroupId = '{$_POST['feesGroupId']}' AND m.status = '1'");

      foreach ($feeGroupData as $ff) {
        $alreadyFeeTypeAdded = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeeclasswiseTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND class_id = '{$this->CrudModel->sanitizeInput($_POST['class_id'])}' AND section_id = '{$this->CrudModel->sanitizeInput($_POST['section_id'])}' AND fee_group_id = '{$this->CrudModel->sanitizeInput($_POST['feesGroupId'])}' AND fee_type_id = '{$this->CrudModel->sanitizeInput($ff['feesTypeId'])}' AND student_id = '{$studentData[$a]['id']}'LIMIT 1");

        if (!empty($alreadyFeeTypeAdded)) {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'This Fees Section is already inserted, Please Edit That',
          ];
          $this->session->set_userdata($msgArr);
          header("Refresh:0 " . base_url() . "feesManagement/feeHeadMaster");
          return;
        }

        // date_create()->format('Y-m-d')
        $insertArr = [
          "schoolUniqueCode" => $this->CrudModel->sanitizeInput($_SESSION['schoolUniqueCode']),
          "student_id" => $studentData[$a]['id'],
          "class_id" => $this->CrudModel->sanitizeInput($_POST['class_id']),
          "section_id" => $this->CrudModel->sanitizeInput($_POST['section_id']),
          "fee_group_id" => $this->CrudModel->sanitizeInput($_POST['feesGroupId']),
          "fee_type_id" => $this->CrudModel->sanitizeInput($ff['feesTypeId']),
          "session_table_id" => $this->CrudModel->sanitizeInput($_SESSION['currentSession'])
        ];


        $insertId = $this->CrudModel->insert(Table::newfeeclasswiseTable, $insertArr);

        if ($insertId) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Fees Assign To Class & Section Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Fees Not Assign Try Again.',
          ];
          $this->session->set_userdata($msgArr);
        }
        
      }
    }
    header("Refresh:1 " . base_url() . "feesManagement/feeHeadMaster");
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
                  <h4><?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                        echo 'Edit Fees Master';
                      } else {
                        echo 'Add Fees Master';
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
                        <label>Fee Group <span style="color:red;">*</span></label>
                        <select name="newFeeGroupId" id="newFeeGroupId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                          <option>Select Fee Group</option>
                          <?php
                          $selected = '';
                          if (isset($feeGroupData)) {
                            foreach ($feeGroupData as $group) {
                              if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                if ($editFeesHeadMasterData[0]['newFeeGroupId'] == $group['id']) {
                                  $selected = 'selected';
                                } else {
                                  $selected = '';
                                }
                              }


                          ?>
                              <option <?= $selected; ?> value="<?= $group['id'] ?>"><?= $group['feeGroupName'] ?></option>
                          <?php }
                          }

                          ?>
                        </select>
                      </div>
                      <div class="form-group col-md-12">
                        <label>Fee Type <span style="color:red;">*</span></label>
                        <select name="newFeeType"  id="newFeeType" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                          <option>Select Fee Type</option>
                          <?php
                          $selected = '';
                          if (isset($feeTypesData)) {
                            foreach ($feeTypesData as $type) {
                              if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                if ($editFeesHeadMasterData[0]['newFeeType'] == $type['id']) {
                                  $selected = 'selected';
                                } else {
                                  $selected = '';
                                }
                              }


                          ?>
                              <option <?= $selected; ?> value="<?= $type['id'] ?>"><?= $type['feeTypeName'] ?></option>
                          <?php }
                          }

                          ?>
                        </select>
                      </div>
                      <div class="form-group col-md-12">
                        <label>Due Date</label>
                        <input type="date" name="dueDate" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                                    echo $editFeesHeadMasterData[0]['dueDate'];
                                                                  } ?>" class="form-control">
                      </div>
                      <div class="form-group col-md-12">
                        <label>Amount <span style="color:red;">*</span></label>
                        <input type="number" name="amount" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                                    echo $editFeesHeadMasterData[0]['amount'];
                                                                  } ?>" class="form-control" required>
                      </div>
                      <div class="form-group col-md-12">
                        <label>Fine Type <span style="color:red;">*</span></label>
                        <select name="fineType" id="fineType" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;" onchange="showHide()">
                          <!-- <option>Select Fee Type</option> -->
                          <?php
                          $selected = '';
                          $fineType = ['1' => 'None', '2' => 'Percentage', '3' => 'Fixed Amount'];
                          if (isset($fineType)) {
                            foreach ($fineType as $fType => $value) {
                              if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                if ($editFeesHeadMasterData[0]['fineType'] == $fType) {
                                  $selected = 'selected';
                                } else {
                                  $selected = '';
                                }
                              }


                          ?>
                              <option <?= $selected; ?> value="<?= $fType ?>"><?= $value ?></option>
                          <?php }
                          }

                          ?>
                        </select>
                      </div>
                      <div class="form-group col-md-6">
                        <label>Fixed Amount</label>
                        <input type="number" id="ffA" name="fineFixAmount" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                                                    echo $editFeesHeadMasterData[0]['fineFixAmount'];
                                                                                  } ?>" class="form-control">
                      </div>
                      <div class="form-group col-md-6">
                        <label>Percentage</label>
                        <input type="number" id="fPA" name="finePercentage" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                                                      echo $editFeesHeadMasterData[0]['finePercentage'];
                                                                                    } ?>" class="form-control">
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
                  <h4>Fees Master List</h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="stateDataTable" class="table align-middle mb-0 bg-white">
                      <thead class="bg-light">
                        <tr>
                          <!-- <th>Id</th> -->
                          <!-- <th>Name</th> -->
                          <th>Fee Group</th>
                          <th>Fee Type</th>
                          <!-- <th>Amount</th> -->
                          <th>Already Assign Classes</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (isset($feeMasterData)) {
                          $i = 0;
                          foreach ($feeMasterData as $cn) {

                            $feeGroupData = $this->CrudModel->dbSqlQuery("SELECT m.id, m.newFeeType,m.amount,ft.feeTypeName,fgt.feeGroupName FROM " . Table::newfeemasterTable . " m JOIN " . Table::newfeestypesTable . " ft ON m.newFeetype = ft.id 
                          JOIN " . Table::newfeesgroupsTable . " fgt ON m.newFeeGroupId = fgt.id 
                          WHERE m.newFeeGroupId = '{$cn['newFeeGroupId']}' AND m.status = '1'");

                        ?>


                            <tr>
                              <td><?= $feeGroupData[0]['feeGroupName']; ?></td>
                              <td><?php
                                  foreach ($feeGroupData as $fg) { ?>
                                  <i class="fa-solid fa-money-bill"></i> <?= $fg['feeTypeName'] . ' - ₹ ' . $fg['amount'] ?> <a class="px-2" href="?action=edit&edit_id=<?= $fg['id']; ?>"><i class="fa-solid fa-pencil"></i></a> <a class="py-2" href="?action=delete&assingClass=2&delete_id=<?= $fg['id']; ?>" onclick="return confirm('Are you sure want to delete this?');"><i class="fa-sharp fa-solid fa-trash"></i></a><br>

                                <?php } ?>
                              </td>


                              <!-- <td><?= $cn['amount']; ?></td> -->
                              <!-- <td>
                              <a href="?action=status&edit_id=<?= $cn['id']; ?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1'; ?>" class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger'; ?>">
                                <?php echo ($cn['status'] == '1') ? 'Active' : 'Inactive'; ?>
                            </td> -->
                              <td>
                                <?php
                                $assignClasses =  $this->CrudModel->dbSqlQuery("SELECT DISTINCT(fcwt.fee_group_id), c.id as classId, c.className,s.sectionName, s.id as sectionId FROM " . Table::newfeeclasswiseTable . " fcwt
                          JOIN " . Table::classTable . " c ON c.id = fcwt.class_id
                          JOIN " . Table::sectionTable . " s ON s.id = fcwt.section_id 
                          WHERE fcwt.fee_group_id = '{$cn['newFeeGroupId']}' AND fcwt.status = '1'");
                                // echo $this->db->last_query();
                                foreach ($assignClasses as $aC) {
                                  $ids = $aC['fee_group_id'] . '-' . $aC['classId'] . '-' .  $aC['sectionId'];
                                  echo $aC['className'] . ' ( ' . $aC['sectionName'] . ' ) ';
                                  echo '<a class="py-2" href="?action=delete&assingClass=1&delete_assign_class_id=' . $ids . '" onclick="return confirm(\'Are you sure want to delete this?\');"> &nbsp;&nbsp; <i class="fa-solid fa-circle-xmark"></i></a>';
                                  echo "</br>";
                                }


                                ?>

                              </td>
                              <td>
                                <button onclick="showPopUp('<?= $cn['newFeeGroupId']; ?>','<?= $feeGroupData[0]['feeGroupName']; ?>')" class="btn btn-dark">Assign to class</button>
                                <button onclick="showPopUpForStudentWise('<?= $cn['newFeeGroupId']; ?>','<?= $feeGroupData[0]['feeGroupName']; ?>')" class="btn btn-secondary">Assign to Students</button>
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
  <?php $this->load->view("adminPanel/pages/footer.php");
  $classData = $this->CrudModel->allClass(Table::classTable, $_SESSION['schoolUniqueCode']);
  $sectionData = $this->CrudModel->allSection(Table::sectionTable, $_SESSION['schoolUniqueCode']);
  ?>
  <!-- ./wrapper -->





  <!-- Modal -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <form method="POST">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="feeGroupName"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="form-group col-md-12">
                <label>Select Class </label>
                <select id="studentClassI" class="form-control  select2 select2-dark" required name="class_id" data-dropdown-css-class="select2-dark" style="width: 100%;">
                  <option>Please Select Class</option>
                  <?php
                  if (isset($classData)) {
                    foreach ($classData as $cd) {  ?>
                      <option value="<?= $cd['id'] ?>"><?= $cd['className'] ?></option>
                  <?php }
                  } ?>
                </select>
              </div>
              <div class="form-group col-md-12">
                <label>Select Section </label>
                <select id="studentSectionI" class="form-control  select2 select2-dark" name="section_id" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                  <option>Please Select Section</option>
                  <?php
                  if (isset($sectionData)) {
                    foreach ($sectionData as $sd) {  ?>
                      <option value="<?= $sd['id'] ?>"><?= $sd['sectionName'] ?></option>
                  <?php }
                  } ?>
                </select>
              </div>
            </div>
            <input type="hidden" name="feesGroupId" id="feesGroupId">

          </div>
          <div class="modal-footer">
            <button type="submit" name="assignFeesToClass" class="btn mybtnColor">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>











  <!-- Modal -->
  <div class="modal fade" id="assingStudentWise" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <form method="POST" action="<?= base_url('feesManagement/showStudentsForFees');?>">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="feeGroupNameShow"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="form-group col-md-12">
                <label>Select Class </label>
                <select id="studentClassIL" class="form-control  select2 select2-dark" required name="class_id" data-dropdown-css-class="select2-dark" style="width: 100%;">
                  <option>Please Select Class</option>
                  <?php
                  if (isset($classData)) {
                    foreach ($classData as $cd) {  ?>
                      <option value="<?= $cd['id'] ?>"><?= $cd['className'] ?></option>
                  <?php }
                  } ?>
                </select>
              </div>
              <div class="form-group col-md-12">
                <label>Select Section </label>
                <select id="studentSectionIL" class="form-control  select2 select2-dark" name="section_id" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                  <option>Please Select Section</option>
                  <?php
                  if (isset($sectionData)) {
                    foreach ($sectionData as $sd) {  ?>
                      <option value="<?= $sd['id'] ?>"><?= $sd['sectionName'] ?></option>
                  <?php }
                  } ?>
                </select>
              </div>
            </div>
            <input type="hidden" name="feesGroupId" id="feesGroupIdShow">

          </div>
          <div class="modal-footer">
            <button type="submit" name="assignFeesToStudentWise" class="btn mybtnColor">Search</button>
          </div>
        </div>
      </form>
    </div>
  </div>




  <script>
    // var ajaxUrl = '<?= base_url() . 'ajax/listStudentsAjax' ?>';


    $("#stateDataTable").DataTable();

    // $("#ffA").attr('disabled','disabled');
    //  $("#fPA").attr('disabled','disabled');

    function showHide() {
      $("#ffA").attr('disabled', 'disabled');
      $("#fPA").attr('disabled', 'disabled');

      if ($("#fineType").val() == '2') {
        $("#fPA").removeAttr('disabled');

      } else if ($("#fineType").val() == '3') {
        $("#ffA").removeAttr('disabled');
      }
    }



    function showPopUp(x, y) {
      $("#feesGroupId").val(x);
      $("#feeGroupName").html(y);
      $("#exampleModalCenter").modal('show');
      console.log(x);
    }

    function showPopUpForStudentWise(x, y) {
      $("#feesGroupIdShow").val(x);
      $("#feeGroupNameShow").html(y);
      $("#assingStudentWise").modal('show');
      console.log(x);
    }




  </script>