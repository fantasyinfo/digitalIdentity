<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');

    // fetching city data
    $userData = $this->db->query("SELECT * FROM " . Table::userTable . " WHERE status != '4' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ORDER BY id DESC")->result_array();

    // edit and delete action
    if (isset($_GET['action'])) {
      // fetch city for edit the  city 
      if ($_GET['action'] == 'edit') {

        $editId = $_GET['edit_id'];

        if ($editId == '3' || $editId == '4' || $editId == '5') {
          header('Location: panelUserMaster');
          exit(0);
        }
        $editUserData = $this->db->query("SELECT * FROM " . Table::userTable . " WHERE id='$editId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
      }

      // delete the city
      if ($_GET['action'] == 'delete') {
        $deleteId = $_GET['delete_id'];
        if ($deleteId == '3' || $deleteId == '4' || $deleteId == '5') {
          header('Location: panelUserMaster');
          exit(0);
        }
        $deleteUser = $this->db->query("UPDATE " . Table::userTable . " SET status = '4' WHERE id='$deleteId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
        if ($deleteUser) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'User Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'User Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "master/panelUserMaster");
      }


      if ($_GET['action'] == 'status') {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];
        $updateStatus = $this->db->query("UPDATE " . Table::userTable . " SET status = '$status' WHERE id = '$updateId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if ($updateStatus) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'User Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'User Status Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "master/panelUserMaster");
      }
    }


    // insert new city
    if (isset($_POST['submit'])) {
      $name = $_POST['name'];
      $email = $_POST['email'];
      $mobile = $_POST['mobile'];
      $user_type = $_POST['user_type'];
      $password = $_POST['password'];
      $salt = HelperClass::generateRandomToken();

      $passWordForSave = HelperClass::encode($password, $salt);

      // check email id is already used 
      $alreadyEmail = $this->db->query("SELECT email FROM " . Table::userTable . " WHERE email = '$email' AND mobile = '$mobile'AND status = 1 AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' LIMIT 1")->result_array();
      if (!empty($alreadyEmail)) {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Email Id OR Mobile Number Already Used in Other Account, Please Use Another Email Id.',
        ];
        $this->session->set_userdata($msgArr);
        header('Location: panelUserMaster');
        exit(0);
      }

      $insertNewUser = $this->db->query("INSERT INTO " . Table::userTable . " (schoolUniqueCode,name, email,password,user_type,salt,mobile) VALUES ('{$_SESSION['schoolUniqueCode']}','$name','$email','$passWordForSave','$user_type','$salt','$mobile')");

      $insertId = $this->db->insert_id();

      if ($insertNewUser) {

        if ($user_type == 'Admin') {
          $permissions = $this->db->query("SELECT permissions FROM " . Table::panelMenuPermissionTable . " WHERE user_type = '$user_type' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND is_head = '1' AND status = '1'")->result_array();
        } else if ($user_type == 'Staff') {
          $permissions = $this->db->query("SELECT permissions FROM " . Table::panelMenuPermissionTable . " WHERE user_type = '$user_type' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'  AND is_head = '1' AND status = '1'")->result_array();
        } else if ($user_type == 'Principal') {
          $permissions = $this->db->query("SELECT permissions FROM " . Table::panelMenuPermissionTable . " WHERE  user_type = '$user_type' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'  AND is_head = '1' AND status = '1'")->result_array();
        }else
        {
          $permissions = $this->db->query("SELECT permissions FROM " . Table::panelMenuPermissionTable . " WHERE user_type = 'Admin' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND is_head = '1' AND status = '1'")->result_array();
        }

        $p = $this->db->query("INSERT INTO " . Table::panelMenuPermissionTable . " (schoolUniqueCode,user_id, user_type,permissions) VALUES ('{$_SESSION['schoolUniqueCode']}','$insertId','$user_type','{$permissions[0]['permissions']}')");

        if ($p) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'New User Added Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'User Not Added Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'User Not Added Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "master/panelUserMaster");
    }

    // update exiting city
    if (isset($_POST['update'])) {
      $updateUserId = $_POST['updateUserId'];

      if ($updateUserId == '3' || $updateUserId == '4' || $updateUserId == '5') {
        header('Location: panelUserMaster');
        exit(0);
      }
      $name = $_POST['name'];
      // $mobile = $_POST['mobile'];
      // $email = $_POST['email'];
      $user_type = $_POST['user_type'];
      $password = $_POST['password'];
      $salt = HelperClass::generateRandomToken();

      $passWordForSave = HelperClass::encode($password, $salt);

      $updateUser = $this->db->query("UPDATE " . Table::userTable . " SET name = '$name', password = '$passWordForSave', user_type = '$user_type', salt = '$salt'  WHERE id = '$updateUserId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
      if ($updateUser) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'User Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'User Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "master/panelUserMaster");
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
                  <h3 class="card-title">Add / Edit User</h3>

                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="row">
                  <div class="card-body">
                    <form method="post" action="">
                      <?php
                      if (isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
                        <input type="hidden" name="updateUserId" value="<?= $editId ?>">
                      <?php }

                      ?>
                      <div class="row">
                        <div class="form-group col-md-4">
                          <label>Name </label>
                          <input type="text" name="name" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                                  echo $editUserData[0]['name'];
                                                                } ?>" class="form-control" id="name" required>
                        </div>
                        <div class="form-group col-md-4">
                          <label>Mobile No </label>
                          <input type="number" name="mobile" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                                  echo $editUserData[0]['mobile'];
                                                                } ?>" <?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { echo 'disabled';} ?> class="form-control" id="name" required>
                        </div>
                        <div class="form-group col-md-4">
                          <label>Email </label>
                          <input type="email" name="email" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                                    echo $editUserData[0]['email'];
                                                                  } ?>" <?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { echo 'disabled';} ?> class="form-control" id="name" required>
                        </div>
                        <div class="form-group col-md-4">
                          <label>Password </label>
                          <input type="text" name="password" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                                      echo HelperClass::decode($editUserData[0]['password'], $editUserData[0]['salt']);
                                                                    } ?>" class="form-control" id="name" required>
                        </div>
                        <div class="form-group col-md-4">
                          <label>Select User Type </label>
                          <select name="user_type" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                            <?php

                            $selectedUserType = '';
                            foreach (HelperClass::userTypeForPanel as $key => $value) {
                              if (isset($editUserData) && $editUserData[0]['user_type'] == $key) {
                                $selectedUserType = 'selected';
                              } else {
                                $selectedUserType = '';
                              }
                            ?>


                              <option <?= $selectedUserType ?> value="<?= $key ?>"><?= $key ?></option>
                            <?php }


                            ?>

                          </select>
                        </div>
                        <div class="form-group col-md-3 mt-4">
                          <button type="submit" name="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                        echo 'update';
                                                      } else {
                                                        echo 'submit';
                                                      } ?>" class="btn btn-primary">Submit</button>
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
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Showing All Users Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="hourDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <!-- <th>User Id</th> -->
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>User Type</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($userData)) {
                            $i = 0;
                            foreach ($userData as $cn) { ?>
                              <tr>
                                <td><?= ++$i; ?></td>
                                <!-- <td><?= $cn['id']; ?></td> -->
                                <td><?= $cn['name']; ?></td>
                                <td><?= $cn['email']; ?></td>
                                <td><?= $cn['mobile']; ?></td>
                                <td><?= $cn['user_type']; ?></td>
                                <td>
                                  <?php

                                  $checkIfDefaultUsers = $this->db->query($sql = "SELECT * FROM " . Table::panelMenuPermissionTable . " WHERE user_id = '{$cn['id']}' AND is_head = '1' AND is_head IS NOT NULL AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
                                // echo $sql;
                                  if (empty($checkIfDefaultUsers)) { ?>
                                    <a href="?action=status&edit_id=<?= $cn['id']; ?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1'; ?>" class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger'; ?>">
                                      <?php echo ($cn['status'] == '1') ? 'Active' : 'Inactive'; ?> </a>
                                  <?php }else{echo 'Default Permissions';} ?>
                                </td>
                                <td>
                                  <a href="editPermission/<?= $cn['id']; ?>/<?= $cn['user_type']; ?>" class="btn btn-info">Edit Permission</a>
                                 <?php if (empty($checkIfDefaultUsers)) { ?>
                                    <a href="?action=edit&edit_id=<?= $cn['id']; ?>" class="btn btn-warning">Edit</a>
                                    <a href="?action=delete&delete_id=<?= $cn['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this?');">Delete</a>
                                  <?php } ?>
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