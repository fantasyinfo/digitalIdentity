<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');
    $this->load->model('CrudModel');



    $salaryData = $this->db->query("SELECT s.*, dep.departmentName,des.designationName FROM " . Table::salaryTable . " s 
    INNER JOIN ".Table::departmentTable." dep ON dep.id = s.departmentId
    INNER JOIN ".Table::designationTable." des ON des.id = s.designationId
     WHERE s.status NOT IN ('3','4')  AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

// die($this->db->last_query());


$departmentData = $this->db->query("SELECT * FROM " . Table::departmentTable . " WHERE status = '1'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();



    // fetching section data
    $sectionData = $this->db->query("SELECT * FROM " . Table::salaryTable . " WHERE status NOT IN ('3','4')  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();


    // edit and delete action
    if (isset($_GET['action'])) {

      if ($_GET['action'] == 'edit') {
        $editId = $_GET['edit_id'];
        $editsectionData = $this->db->query("SELECT * FROM " . Table::salaryTable . " WHERE id='$editId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
      }


      if ($_GET['action'] == 'delete') {
        $deleteId = $_GET['delete_id'];
        $deletesectionData = $this->db->query("UPDATE " . Table::salaryTable . " SET status = '4' WHERE id='$deleteId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
        if ($deletesectionData) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Salary Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Salary Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "master/salaryMaster");
      }

      if ($_GET['action'] == 'status') {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];
        $updateStatus = $this->db->query("UPDATE " . Table::salaryTable . " SET status = '$status' WHERE id = '$updateId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if ($updateStatus) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Salary Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Salary Status Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "master/salaryMaster");
      }
    }


    // insert new section
    if (isset($_POST['submit'])) {


      $inserArr = [
        'schoolUniqueCode' => $_SESSION['schoolUniqueCode'],
        'empId'  => $_POST['empId'],
        'employeeName' => $_POST['employeeName'],
        'departmentId' => $_POST['departmentId'],
        'designationId' => $_POST['designationId'],
        'location' => $_POST['location'],
        'panNo' => $_POST['panNo'],
        'pfAccNo' => $_POST['pfAccNo'],
        'doj' => $_POST['doj'],
        'basicSalaryMonth' => $_POST['basicSalaryMonth'],
        'basicSalaryDay' => $_POST['basicSalaryDay'],
        'dearnessAll' => $_POST['dearnessAll'],
        'hra' => $_POST['hra'],
        'conAll' => $_POST['conAll'],
        'medicalAll' => $_POST['medicalAll'],
        'specialAll' => $_POST['specialAll'],
        'leavesPerMonth' => $_POST['leavesPerMonth'],
        'lwp' => $_POST['lwp'],
        'ded_half_day' => $_POST['ded_half_day'],
        'professionalTaxPerMonth' => $_POST['professionalTaxPerMonth'],
        'pfPerMonth' => $_POST['pfPerMonth'],
        'tdsPerMonth' => $_POST['tdsPerMonth'],
        'session_table_id' => $_SESSION['currentSession']
      ];




      $insertNewSalary =  $this->CrudModel->insert(Table::salaryTable,$inserArr);

      if ($insertNewSalary) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Salary Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Salary Not Added Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "master/salaryMaster");
    }

    // update exiting section
    if (isset($_POST['update'])) {
 
      $sectionEditId = $_POST['updatesectionId'];
      
      $updateArr = [
        'empId'  => $_POST['empId'],
        'employeeName' => $_POST['employeeName'],
        'departmentId' => $_POST['departmentId'],
        'designationId' => $_POST['designationId'],
        'location' => $_POST['location'],
        'panNo' => $_POST['panNo'],
        'pfAccNo' => $_POST['pfAccNo'],
        'doj' => $_POST['doj'],
        'basicSalaryMonth' => $_POST['basicSalaryMonth'],
        'basicSalaryDay' => $_POST['basicSalaryDay'],
        'dearnessAll' => $_POST['dearnessAll'],
        'hra' => $_POST['hra'],
        'conAll' => $_POST['conAll'],
        'medicalAll' => $_POST['medicalAll'],
        'specialAll' => $_POST['specialAll'],
        'leavesPerMonth' => $_POST['leavesPerMonth'],
        'lwp' => $_POST['lwp'],
        'ded_half_day' => $_POST['ded_half_day'],
        'professionalTaxPerMonth' => $_POST['professionalTaxPerMonth'],
        'pfPerMonth' => $_POST['pfPerMonth'],
        'tdsPerMonth' => $_POST['tdsPerMonth'],
        'session_table_id' => $_SESSION['currentSession']
      ];
      
      $updateSalary =  $this->CrudModel->update(Table::salaryTable,$updateArr,$sectionEditId);

      if ($updateSalary) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Salary Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Salary Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "master/salaryMaster");
    }


    // print_r($sectionData);


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
              <div class="card  border-top-3">
                <div class="card-header">
                  <h3 class="card-title">Add / Edit Salary</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->

                <!-- <?php print_r($editsectionData[0]);?> -->

                <div class="row">
                  <div class="card-body">
                    <form method="post" action="">
                      <?php
                      if (isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
                        <input type="hidden" name="updatesectionId" value="<?= $editId ?>">
                      <?php 
                      
                      $dess = $this->db->query("SELECT * FROM ".Table::designationTable." WHERE departmentId = '{$editsectionData[0]['departmentId']}' AND status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
                    
                    }
                      
                      ?>
                      <div class="row">
                        <div class="form-group col-md-3">
                          <label>Employee Id</label>
                          <input type="text" name="empId" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { echo $editsectionData[0]['empId']; } ?>" class="form-control" id="name" placeholder="Enter Employee Id" required>
                        </div>
                        <div class="form-group col-md-3">
                          <label>Employee Name</label>
                          <input type="text" name="employeeName" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { echo $editsectionData[0]['employeeName']; } ?>" class="form-control" id="name" placeholder="Enter Employee Name" required>
                        </div>


                        <div class="form-group col-md-3">
                          <label>Select Department </label>
                          <select name="departmentId" id="departmentId" class="form-control  select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="showDesignation()" required>
                            <option>Please Select Departments</option>
                            <?php
                            $selected = '';
                            if (isset($departmentData)) {
                              foreach ($departmentData as $department) {
                                if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                  if ($editsectionData[0]['departmentId'] == $department['id']) {
                                    $selected = 'selected';
                                  } else {
                                    $selected = '';
                                  }
                                }


                            ?>
                                <option <?= $selected; ?> value="<?= $department['id'] ?>"><?= $department['departmentName'] ?></option>
                            <?php }
                            }

                            ?>
                          </select>
                        </div>


                        <div class="form-group col-md-3">
                          <label>Select Designation </label>
                          <select name="designationId" id="designationId" class="form-control  select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;" required>
                            <option>Please Select Designations</option>
                            <?php
                            $selectedA = '';
                              if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                              foreach ($dess as $des) {
                                  if ($editsectionData[0]['designationId'] == $des['id']) {
                                    $selectedA = 'selected';
                                  } else {
                                    $selectedA = '';
                                  }
                                

                            ?>
                                <option <?= $selectedA; ?> value="<?= $des['id'] ?>"><?= $des['designationName'] ?></option>
                            <?php } }
                            

                            ?>
                          </select>
                        </div>

                      </div>
                      <div class="row">
                        <div class="form-group col-md-3">
                          <label>Work Location</label>
                          <input type="text" name="location" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { echo $editsectionData[0]['location']; } ?>" class="form-control" id="name" placeholder="Enter Working City Name" required>
                        </div>

                        <div class="form-group col-md-3">
                          <label>Pancard Number</label>
                          <input type="text" name="panNo" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { echo $editsectionData[0]['panNo'];} ?>" class="form-control" id="name" placeholder="Enter Pan No">
                        </div>
                        <div class="form-group col-md-3">
                          <label>PF Acc Number</label>
                          <input type="text" name="pfAccNo" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { echo $editsectionData[0]['pfAccNo'];  } ?>" class="form-control" id="name" placeholder="Enter Providend Fund Acc No" >
                        </div>
                        <div class="form-group col-md-3">
                          <label>Date of Joining</label>
                          <input type="date" name="doj" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editsectionData[0]['doj'];} ?>" class="form-control" id="name" placeholder="Date of Joining" required>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label>Basic Pay <span style="color:red;">(Per Month)</span></label>
                          <div class="input-group-prepend">
                            <div class="input-group-text">₹</div>
                            <input type="text" name="basicSalaryMonth" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {  echo $editsectionData[0]['basicSalaryMonth'];} ?>" class="form-control" id="name" placeholder="Basic Salary Per Month" required>
                          </div>

                        </div>
                        <div class="form-group col-md-6">
                          <label>Basic Pay <span style="color:red;">(Per Day)</span></label>
                          <div class="input-group-prepend">
                            <div class="input-group-text">₹</div>
                            <input type="text" name="basicSalaryDay" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editsectionData[0]['basicSalaryDay'];} ?>" class="form-control" id="name" placeholder="Basic Salary Per Day" required>
                          </div>
                        </div>
                          </div>
                          <div class="row">
                        <div class="form-group col-md-4">
                          <label>Leaves Per Month <span style="color:green;">( Allowed )</span></label>
                          <input type="text" name="leavesPerMonth" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { echo $editsectionData[0]['leavesPerMonth'];} ?>" class="form-control" id="name" placeholder="Enter Leaves Per Month">
                        </div>
                        <div class="form-group col-md-4">
                          <label>Deducation Per Day <span style="color:red;">(If Absent)</span></label>
                          <input type="text" name="lwp" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { echo $editsectionData[0]['lwp'];} ?>" class="form-control" id="name" placeholder="Enter Absent Deducation Amt Per Day">
                        </div>
                        <div class="form-group col-md-4">
                          <label>Deducation For Half Day <span style="color:red;">(If Present Half Day)</span></label>
                          <input type="text" name="ded_half_day" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { echo $editsectionData[0]['ded_half_day'];} ?>" class="form-control" id="name" placeholder="Enter Half Day Deducation Amt">
                        </div>
                      </div>
                  
                  
                      <!-- // allownes -->
                      <div class="row">
                        <div class="col-md-12">
                          <div class="card border-top-3">
                            <div class="card-header ">
                             <h4 class="font-bold">Allowances <span style="color:green;">(Per Month)</span></h4> 
                            </div>
                            <div class="card-body">
                              <div class="row">
                              <div class="form-group col-md-2">
                                <label>Dearness All. ( DA ) % </label>
                                <input type="number" name="dearnessAll" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editsectionData[0]['dearnessAll'];} ?>" class="form-control" id="name" step="0.1" lang="nb" placeholder="Dearness Allowances %" >
                              </div>
                              <div class="form-group col-md-2">
                                <label>House Rent All.(HRA) % </label>
                                <input type="number" name="hra" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editsectionData[0]['hra'];} ?>" class="form-control" id="name" step="0.1" lang="nb" placeholder="HRA Allowances %">
                              </div>
                              <div class="form-group col-md-2">
                                <label>Conveyence All. ( CA ) % </label>
                                <input type="number" name="conAll" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editsectionData[0]['conAll'];} ?>" class="form-control" id="name"  step="0.1" lang="nb" placeholder="Conveyence Allowances %">
                              </div>
                              <div class="form-group col-md-2">
                                <label>Medical All. % </label>
                                <input type="number" name="medicalAll" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editsectionData[0]['medicalAll'];} ?>" class="form-control" id="name" step="0.1" lang="nb" placeholder="Medical Allowances %">
                              </div>
                              <div class="form-group col-md-2">
                                <label>Special All. % </label>
                                <input type="number" name="specialAll" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editsectionData[0]['specialAll'];} ?>" class="form-control" id="name" step="0.1" lang="nb" placeholder="Special Allowances %">
                              </div>
                              </div>
                            </div>
                          </div>
                        </div>
                       
                      </div>

                      <!-- // deducations -->
                      <div class="row">
                        <div class="col-md-12">
                          <div class="card border-top-3">
                            <div class="card-header ">
                             <h4 class="font-bold">Deducations <span style="color:red;">(Per Month)</span></h4> 
                            </div>
                            <div class="card-body">
                              <div class="row">
                              <div class="form-group col-md-4">
                                <label>Professinal Tax % </label>
                                <input type="number" name="professionalTaxPerMonth" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editsectionData[0]['professionalTaxPerMonth'];} ?>" class="form-control" id="name" placeholder="Professinal Tax Deducations % " step="0.1" lang="nb" >
                              </div>
                              <div class="form-group col-md-4">
                                <label>Provident Funds  % </label>
                                <input type="number" name="pfPerMonth" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editsectionData[0]['pfPerMonth'];} ?>" class="form-control" id="name" placeholder="PF Deducations  % " step="0.1" lang="nb">
                              </div>
                              <div class="form-group col-md-4">
                                <label>TDS % </label>
                                <input type="number" name="tdsPerMonth" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo $editsectionData[0]['tdsPerMonth'];} ?>" class="form-control" id="name" placeholder="TDS Deducations  % " step="0.1" lang="nb">
                              </div>
                              </div>
                            </div>
                          </div>
                        </div>
                       
                      </div>
                      <div class="row">
                        <div class="form-group col-md-12">
                          <button type="submit" name="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {echo 'update';} else {echo 'submit';} ?>" class="btn btn-lg btn-block mybtnColor">Save</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <!-- right column -->
              </div>

              <div class="row">

                <div class="col-md-12">
                  <div class="card border-top-3">
                    <div class="card-header">
                      <h3 class="card-title">Showing All Employees Salary Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <div class="table-responsive">

                      
                      <table id="sectionDataTable" class="table mb-0 text-white align-middle">
                        <thead class="bg-light">
                          <tr>
                            <th>Id</th>
                            <th>Employee Id</th>
                            <th>Employee Name</th>
                            <th>Department Name</th>
                            <th>Designation Name</th>
                            <th>Basic Pay ( Per Month )</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($salaryData)) {
                            $i = 0;
                            foreach ($salaryData as $cn) { ?>
                              <tr>
                                <td><?= ++$i; ?></td>
                                <td><?= $cn['id']; ?></td>
                                <td><?= $cn['employeeName']; ?></td>
                                <td><?= $cn['departmentName']; ?></td>
                                <td><?= $cn['designationName']; ?></td>
                                <td><?= $cn['basicSalaryMonth']; ?></td>
                                <td>
                                  <a href="?action=status&edit_id=<?= $cn['id']; ?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1'; ?>" class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger'; ?>">
                                    <?php echo ($cn['status'] == '1') ? 'Active' : 'Inactive'; ?>
                                </td>
                                <td>
                                  <button class="btn btn-info" onclick="showDetails('<?= $cn['id']; ?>')">View</button>
                                  <a href="?action=edit&edit_id=<?= $cn['id']; ?>" class="btn btn-warning">Edit</a>
                                  <a href="?action=delete&delete_id=<?= $cn['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this?');">Delete</a>
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
      </div>
    </div>


    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog border border-success modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="exampleModalLabel">Employee Salary Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="employeeDetails">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-danger btn-lg btn-block" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>












    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->

    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->
  <script>
    function showDesignation() {
      $('#designationId').html("");
      var departmentId = $("#departmentId").val();
      if (departmentId != '') {
        console.log(departmentId);
        $.ajax({
          url: '<?= base_url() . 'ajax/showDesignationsViaDepartmentId'; ?>',
          method: 'post',
          processData: 'false',
          data: {
            departmentId: departmentId
          },
          success: function(response) {
            console.log(response);
            response = $.parseJSON(response);
            $('#designationId').append(response);
          },
          error: function(error) {
            console.log(error);
          }

        });
      }
    }





    function showDetails(x)
    {
      console.log(x);
      if (x != '') {
        $.ajax({
          url: '<?= base_url() . 'ajax/showSalaryDetails'; ?>',
          method: 'post',
          processData: 'false',
          data: {
            salaryId: x
          },
          success: function(response) {
            console.log(response);
            response = $.parseJSON(response);
            console.log(response);
            let showDetailsHtml = `<table class="table table-striped">
                                    <tbody>
                                        <tr>
                                          <td scope="row font-bold" class="font-bold"><b>Employee Id</b></td>
                                          <td>${response.empId}</td>
                                          <td scope="row font-bold" class="font-bold"><b>Employee Name</b></td>
                                          <td>${response.employeeName}</td>
                                        </tr>
                                        <tr>
                                          <td scope="row font-bold" class="font-bold"><b>Joining Date</b></td>
                                          <td>${response.doj}</td>
                                          <td scope="row font-bold" class="font-bold"><b>Work Location</b></td>
                                          <td>${response.location}</td>
                                        </tr>
                                        <tr>
                                          <td scope="row font-bold" class="font-bold"><b>Pancard Number</b></td>
                                          <td>${response.panNo}</td>
                                          <td scope="row font-bold" class="font-bold"><b>PF Account Number</b></td>
                                          <td>${response.pfAccNo}</td>
                                        </tr>
                                        <tr>
                                          <td scope="row font-bold" class="font-bold"><b>Department Name</b></td>
                                          <td>${response.departmentName}</td>
                                          <td scope="row font-bold" class="font-bold"><b>Designation Name</b></td>
                                          <td>${response.designationName}</td>
                                        </tr>
                                        <tr>
                                          <td scope="row font-bold" class="font-bold"><b>Basic Salary ( Per Month )</b></td>
                                          <td>₹ ${response.basicSalaryMonth}</td>
                                          <td scope="row font-bold" class="font-bold"><b>Basic Salary ( Per Day )</b></td>
                                          <td>₹ ${response.basicSalaryDay}</td>
                                        </tr>
                                        <tr>
                                          <td scope="row font-bold" class="font-bold  text-success"><b>DA Allowances</b></td>
                                          <td>${response.dearnessAll} %</td>
                                          <td scope="row font-bold" class="font-bold  text-success"><b>HRA Allowances</b></td>
                                          <td>${response.hra} %</td>
                                        </tr>
                                        <tr>
                                          <td scope="row font-bold" class="font-bold  text-success"><b>Conveyence Allowances</b></td>
                                          <td>${response.conAll} %</td>
                                          <td scope="row font-bold" class="font-bold  text-success"><b>Medial Allowances</b></td>
                                          <td>${response.medicalAll} %</td>
                                        </tr>
                                        <tr>
                                          <td scope="row font-bold" class="font-bold  text-success"><b>Special Allowances</b></td>
                                          <td>${response.specialAll} %</td>
                                          <td scope="row font-bold" class="font-bold text-danger"><b>TDS Deducation</b></td>
                                          <td>${response.tdsPerMonth} %</td>
                                        </tr>
                                        <tr>
                                          <td scope="row font-bold" class="font-bold  text-danger"><b>Professional Tax Deducation</b></td>
                                          <td>${response.professionalTaxPerMonth} %</td>
                                          <td scope="row font-bold" class="font-bold  text-danger"><b>Provident Funds (PF) Deducation</b></td>
                                          <td>${response.pfPerMonth} %</td>
                                        </tr>
                                    </tbody>
                                  </table>`;
            $("#employeeDetails").html(showDetailsHtml);
            $("#detailsModal").modal('show');
            // $('#designationId').append(response);
          },
          error: function(error) {
            console.log(error);
          }

        });
      }
    }
  </script>