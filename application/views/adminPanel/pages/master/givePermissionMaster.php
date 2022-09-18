<?php

(HelperClass::checkIfItsACEOAccount()) ?: redirect(base_url());


// HelperClass::sendEmail('gs27349gs@gmail.com', 'User Login Details', 'Hey, You have sucefully login now!!');
?>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');

    // fetching city data
    $registerdSchoolData = $this->db->query("SELECT * FROM " . Table::schoolMasterTable . " WHERE status != '4' ORDER BY id DESC")->result_array();

    // edit and delete action
    if (isset($_GET['action'])) {

      // delete the city
      if ($_GET['action'] == 'delete') {
        $deleteId = $_GET['delete_id'];

        $deleteUser = $this->db->query("UPDATE " . Table::schoolMasterTable . " SET status = '4' WHERE id='$deleteId'");
        if ($deleteUser) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'School Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'School Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "master/givePermissionMaster");
      }


      if ($_GET['action'] == 'status') {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];

        $schoolUniqueCode = $_GET['schoolUniqueCode'];

        if ($status == '1') {
          $alreadyUser = $this->db->query($sql = "SELECT count(1) as count FROM " . Table::userTable . " ut 
          INNER JOIN " . Table::panelMenuPermissionTable . " pmp ON ut.schoolUniqueCode = pmp.schoolUniqueCode AND ut.id = pmp.user_id
          WHERE ut.schoolUniqueCode = '$schoolUniqueCode' AND pmp.is_head = '1'")->result_array();

          if ($alreadyUser[0]['count'] != '0') {
            $msgArr = [
              'class' => 'danger',
              'msg' => 'This School Default Users & Permission is already inserted, Please Edit That',
            ];
            $this->session->set_userdata($msgArr);
            header("Refresh:0 " . base_url() . "master/givePermissionMaster");
            exit(0);
          }
          // echo $sql;
          // die();
        }

        $schoolDetails = $this->db->query($sql = "SELECT * FROM " . Table::schoolMasterTable . " WHERE id = '$updateId'")->result_array();


        $updateStatus = $this->db->query("UPDATE " . Table::schoolMasterTable . " SET status = '$status' WHERE id = '$updateId'");

        // insert default userPermissions

        $userDefaultPermissionArr = [
          'Admin' => ["2","3","5","6","13","14","16","17","18","19","20","22","23","24","25","28","39","32","34","35","36","37","38","29","31","41"],
          'Staff' => ["2", "4", "7", "10", "11", "12", "13", "14", "16"],
          'Principal' => ["2","3","5","6","13","14","16","17","18","19","20","22","23","24","25","28","39","32","34","35","36","37","38","29","31","41"]
        ];

        foreach ($userDefaultPermissionArr as $key => $value) {

          $password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" . rand(000000000, 999999999)), 0, 6);
          $salt = HelperClass::generateRandomToken();
          $passWordForSave = HelperClass::encode($password, $salt);

          // inserting default users
          $userArr = [];
          $userArr['schoolUniqueCode'] = $schoolUniqueCode;
          $userArr['name'] = $key;
          $userArr['email'] = $key . '@email.com';
          $userArr['password'] = $passWordForSave;
          $userArr['user_type'] = $key;
          $userArr['salt'] = $salt;
          $lastInserId = $this->CrudModel->insert(Table::userTable, $userArr);

          // inserting permmison
          $insertNewArr = [];
          $insertNewArr['schoolUniqueCode'] = $schoolUniqueCode;
          $insertNewArr['user_id'] = $lastInserId;
          $insertNewArr['user_type'] = $key;
          $insertNewArr['permissions'] = json_encode($value);
          $insertNewArr['is_head'] = '1';
          $insertNewArr['status'] = '1';
          $this->CrudModel->insert(Table::panelMenuPermissionTable, $insertNewArr);
        }


        

        // insert a user for login to panel
        $pass = 'abc012';
        $saltY = HelperClass::generateRandomToken();
        $passWordForSaveY = HelperClass::encode($pass, $saltY);
        $loginUser = [];
        $loginUser['schoolUniqueCode'] = $schoolUniqueCode;
        $loginUser['name'] = $schoolDetails[0]['school_name'] . ' Admin';
        $loginUser['email'] = $schoolDetails[0]['email'];
        $loginUser['password'] = $passWordForSaveY;
        $loginUser['user_type'] = 'Admin';
        $loginUser['salt'] = $saltY;
        $loginDefaultSchool = $this->CrudModel->insert(Table::userTable, $loginUser);

        if ($loginDefaultSchool) {

          
         $permissions = $this->db->query("SELECT permissions FROM " . Table::panelMenuPermissionTable . " WHERE user_type = 'Admin' AND schoolUniqueCode = '$schoolUniqueCode' AND is_head = '1' AND status = '1'")->result_array();
          
          $p = $this->db->query("INSERT INTO " . Table::panelMenuPermissionTable . " (schoolUniqueCode,user_id, user_type,permissions) VALUES ('$schoolUniqueCode','$loginDefaultSchool','Admin','{$permissions[0]['permissions']}')");

          if($p)
          {

            $to = $schoolDetails[0]['email'];
            $subject = 'Login Details for ' . HelperClass::brandName . ' on '. date('d-m-y h:i:A');
            $body = 
                "<table style='border 1px solid black;'>
                  <tr>
                    <th>School Name</th>
                    <th>School Unique Code</th>
                    <th>Email</th>
                    <th>Password</th>
                  </tr>
                  <tr>
                    <td>{$schoolDetails[0]['school_name']}</td>
                    <td>{$schoolDetails[0]['unique_id']}</td>
                    <td>{$schoolDetails[0]['email']}</td>
                    <td>$pass</td>
                  </tr>
                </table>"
            ;
            HelperClass::sendEmail($to, $subject, $body);
          }

        }

// update status now
        if ($updateStatus) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'School Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'School Status Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:30 " . base_url() . "master/givePermissionMaster");
      }
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
                
                if($this->session->userdata('class') == 'success')
                 {
                   HelperClass::swalSuccess($this->session->userdata('msg'));
                 }else if($this->session->userdata('class') == 'danger')
                 {
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
                  <h3 class="card-title">Permission Master</h3>

                </div>

              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Showing All Registerd School Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="hourDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>School Id</th>
                            <th>School Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Address</th>
                            <th>Classes Up To</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($registerdSchoolData)) {
                            $i = 0;
                            foreach ($registerdSchoolData as $cn) { ?>
                              <tr>
                                <td><?= ++$i; ?></td>
                                <td><?= $cn['unique_id']; ?></td>
                                <td><?= $cn['school_name']; ?></td>
                                <td><?= $cn['email']; ?></td>
                                <td><?= $cn['mobile']; ?></td>
                                <td><?= $cn['address']; ?></td>
                                <td><?= $cn['classes_up_to']; ?></td>
                                <td>
                                  <a href="?action=status&schoolUniqueCode=<?= $cn['unique_id']; ?>&edit_id=<?= $cn['id']; ?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1'; ?>" class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger'; ?>">
                                    <?php echo ($cn['status'] == '1') ? 'Active' : 'Inactive'; ?> </a>
                                </td>
                                <td>
                                  <a href="?action=delete&delete_id=<?= $cn['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this?');">Delete</a>
                                </td>
                              </tr>
                          <?php  }
                          } ?>

                        </tbody>

                      </table>
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
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->

    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->
  <script>
    $("#hourDataTable").DataTable();
  </script>