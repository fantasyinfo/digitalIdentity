<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');

  // fetching city data
    $setDigiCoinData = $this->db->query("SELECT * FROM " . Table::setDigiCoinTable . " WHERE status != '4' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ")->result_array();
    


    // edit and delete action
    if(isset($_GET['action']))
    {
     
      if($_GET['action'] == 'edit')
      {
        $editId = $_GET['edit_id'];
        $editUserData = $this->db->query("SELECT * FROM " . Table::setDigiCoinTable . " WHERE id='$editId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ")->result_array();
      }

 
      if($_GET['action'] == 'delete')
      {
        $deleteId = $_GET['delete_id'];
        $deleteMonthData = $this->db->query("DELETE FROM " . Table::setDigiCoinTable . " WHERE id='$deleteId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ");
        if($deleteMonthData)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'DigiCoin Set Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'DigiCoin Set Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/setDigiCoinMaster");
      }

      if($_GET['action'] == 'status')
      {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];
        $updateStatus = $this->db->query("UPDATE " . Table::setDigiCoinTable . " SET status = '$status' WHERE id = '$updateId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ");

        if($updateStatus)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'DigiCoin Set Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'DigiCoin Set Status Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/setDigiCoinMaster");
      }

    }


    // insert new DigiCoin
    if(isset($_POST['submit']))
    {
      $user_type = $_POST['user_type'];
      $for_what = $_POST['for_what'];
      $digiCoin = $_POST['digiCoin'];
      $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
      

      $alreadyEnter = $this->db->query("SELECT * FROM " . Table::setDigiCoinTable . " WHERE user_type = '$user_type' AND 	for_what = '$for_what' AND schoolUniqueCode = '$schoolUniqueCode' AND status != '4'")->result_array();

      if(!empty($alreadyEnter))
      {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'This Digicoin is already inserted for that occasion, Please Edit That',
          ];
          $this->session->set_userdata($msgArr);
          header('Location: setDigiCoinMaster');
          exit(0);
      }


      $insertNewDigiCoin = $this->db->query("INSERT INTO " . Table::setDigiCoinTable . " (schoolUniqueCode,digiCoin,user_type,for_what) VALUES ('$schoolUniqueCode','$digiCoin','$user_type','$for_what')");
      if($insertNewDigiCoin)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New DigiCoin Set Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'DigiCoin Set Not Added Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 ".base_url()."master/setDigiCoinMaster");
    }

    // update exiting city
    if(isset($_POST['update']))
    {
      $user_type = $_POST['user_type'];
      $for_what = $_POST['for_what'];
      $digiCoin = $_POST['digiCoin'];
      $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
      $monthEditId = $_POST['updateMonthId'];

      $updateMonth = $this->db->query("UPDATE " . Table::setDigiCoinTable . " SET digiCoin = '$digiCoin', user_type = '$user_type',for_what = '$for_what' WHERE id = '$monthEditId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ");
      if($updateMonth)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'DigiCoin Set Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'DigiCoin Set Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 ".base_url()."master/setDigiCoinMaster");
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
              if(!empty($this->session->userdata('msg')))
              {  
                if($this->session->userdata('class') == 'success')
                 {
                   HelperClass::swalSuccess($this->session->userdata('msg'));
                 }else if($this->session->userdata('class') == 'danger')
                 {
                   HelperClass::swalError($this->session->userdata('msg'));
                 }
                
                
                ?>

              <div class="alert alert-<?=$this->session->userdata('class')?> alert-dismissible fade show" role="alert">
                <strong>New Message!</strong> <?=$this->session->userdata('msg')?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php
              $this->session->unset_userdata('class') ;
              $this->session->unset_userdata('msg') ;
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
            <div class="col-md-10 mx-auto">
              <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  DigiCoin Master
                  <!-- <h3 class="card-title">Add / Edit Month</h3> -->
                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="row">
                  <div class="card-body">
                    <form method="post" action="">
                    <?php 
                    if(isset($_GET['action']) && $_GET['action'] == 'edit')
                    {?>
                     <input type="hidden" name="updateMonthId" value="<?=$editId?>">
                    <?php }
                    
                    ?>
                      <div class="row">

                      <div class="col-md-8">
                        <table class="table striped">
                          <tbody>
                          <tr>
                            <td>Select User Type </td>
                            <td>
                            <div class="form-group">
                                <select name="user_type" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                                  <?php

                                  $selectedUserType = '';
                                  foreach (HelperClass::userType as $key => $value) {
                                    if (isset($editUserData) && $editUserData[0]['user_type'] == $value) {
                                      $selectedUserType = 'selected';
                                    } else {
                                      $selectedUserType = '';
                                    }
                                  ?>

                                    <option <?= $selectedUserType ?> value="<?= $value ?>"><?= $key ?></option>
                                  <?php }


                                  ?>

                                </select>
                              </div>
                            </td>
                          </tr>
                          <tr>
                          <td>Select Action Type ( For What )</td>
                          <td>
                          <div class="form-group">
                                <select name="for_what" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                                  <?php

                                  $selectedUserType = '';
                                  foreach (HelperClass::actionType as $key => $value) {
                                    if (isset($editUserData) && $editUserData[0]['for_what'] == $value) {
                                      $selectedUserType = 'selected';
                                    } else {
                                      $selectedUserType = '';
                                    }
                                  ?>

                                    <option <?= $selectedUserType ?> value="<?= $value ?>"><?= $key ?></option>
                                  <?php }


                                  ?>

                                </select>
                              </div>
                          </td>
                          </tr>
                          <tr>
                            <td>Enter DigiCoin</td>
                            <td>
                            <input type="number" name="digiCoin" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editUserData[0]['digiCoin'];}?>" class="form-control" required>
                            </td>
                          </tr>
                          <tr>
                            <td>#</td>
                            <td>
                            <button type="submit" name="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo 'update';}else{echo 'submit';}?>" class="btn btn-primary btn-lg btn-block">Add</button>
                            </td>
                          </tr>
                          </tbody>
                         
                        </table>
                     
                          
                       
                      </div>
                      </div>
                    </form>
                  </div>
                </div> 
                <!--/.col (left) -->
                <!-- right column -->
              </div>

              <div class="row">

                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Showing All DigiCoin Set</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="MonthDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>DigiCoin Id</th>
                            <th>User Type</th>
                            <th>For What Occasion</th>
                            <th>DigiCoin</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($setDigiCoinData)) {
                            $i = 0;
                            foreach ($setDigiCoinData as $cn) { ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <td><?= $cn['id'];?></td>
                                <td><?= HelperClass::userTypeR[$cn['user_type']];?></td>
                                <td><?= HelperClass::actionTypeR[$cn['for_what']];?></td>
                                <td><i class="fa-solid fa-coins"></i> <?= $cn['digiCoin'];?> Coins </td>
                                <td>
                                <a href="?action=status&edit_id=<?= $cn['id'];?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1';?>"
                                    class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger';?>">
                                    <?php  echo ($cn['status'] == '1')? 'Active' : 'Inactive';?>
                                </td>
                                <td>
                                  <a href="?action=edit&edit_id=<?= $cn['id'];?>" class="btn btn-warning">Edit</a>
                                  <a href="?action=delete&delete_id=<?= $cn['id'];?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this?');">Delete</a>
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
  <?php $this->load->view("adminPanel/pages/footer.php");?>
  <!-- ./wrapper -->
  <script>
    // var ajaxUrl = '<?= base_url() . 'ajax/listStudentsAjax' ?>';


    $("#MonthDataTable").DataTable();
  </script>