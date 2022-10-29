<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');
    $this->load->model('CrudModel');

  // fetching city data
    $designationData = $this->db->query("SELECT des.*, dep.departmentName FROM " . Table::designationTable . " des 
    LEFT JOIN ".Table::departmentTable." dep ON dep.id = des.departmentId AND des.schoolUniqueCode = dep.schoolUniqueCode
    WHERE des.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ORDER BY des.id DESC")->result_array();

    $departmentData = $this->db->query("SELECT * FROM " . Table::departmentTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' ORDER BY id DESC")->result_array();
    // edit and delete action
    if(isset($_GET['action']))
    {
 
      if($_GET['action'] == 'edit')
      {
        $editId = $_GET['edit_id'];
        $editCityData = $this->db->query("SELECT * FROM " . Table::designationTable . " WHERE id='$editId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
      }


      if($_GET['action'] == 'delete')
      {
        $deleteId = $_GET['delete_id'];
        $deleteCityData = $this->db->query("DELETE FROM " . Table::designationTable . " WHERE id='$deleteId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
        if($deleteCityData)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Designation Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Designation Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/designation");
      }

      if($_GET['action'] == 'status')
      {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];
        $updateStatus = $this->db->query("UPDATE " . Table::designationTable . " SET status = '$status' WHERE id = '$updateId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if($updateStatus)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Designation Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Designation Status Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/designation");
      }

    }


    // insert new city
    if(isset($_POST['submit']))
    {
      $designationName = $_POST['designationName'];
      $departmentId = $_POST['departmentId'];


      $alreadyCity = $this->db->query("SELECT * FROM " . Table::designationTable . " WHERE designationName = '$designationName'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

      if(!empty($alreadyCity))
      {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'This Designation is already inserted, Please Edit That',
          ];
          $this->session->set_userdata($msgArr);
          header('Location: designation');
          exit(0);
      }

      $insertNewCity = $this->db->query("INSERT INTO " . Table::designationTable . " (schoolUniqueCode,designationName,departmentId) VALUES ('{$_SESSION['schoolUniqueCode']}','$designationName','$departmentId')");
      if($insertNewCity)
      {
      
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Designation Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Designation Not Added Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 ".base_url()."master/designation");
    }

    // update exiting city
    if(isset($_POST['update']))
    {
      $designationName = $_POST['designationName'];
      $cityEditId = $_POST['updateCityId'];
      $departmentId = $_POST['departmentId'];
      $updateCity = $this->db->query("UPDATE " . Table::designationTable . " SET designationName = '$designationName' , departmentId = '$departmentId' WHERE id = '$cityEditId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

      // echo $this->db->last_query(); die();
      if($updateCity)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Designation Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Designation Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 ".base_url()."master/designation");
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
                <strong>New Message!</strong> <?=$this->session->userdata('msg');
                ?>
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
            <div class="col-md-12 mx-auto">
        
                      <div class="card card-primary">
                        <div class="card-header">
                          <h3 class="card-title">Add / Edit Desgination</h3>
                        </div>
                        <div class="card-body">
                          <div class="col-md-8 mx-auto">
                          <form method="post" action="">
                          <?php 
                          if(isset($_GET['action']) && $_GET['action'] == 'edit')
                          {?>
                          <input type="hidden" name="updateCityId" value="<?=$editId?>">
                          <?php }
                          
                          ?>
                          <div class="form-group ">
                            <label>Select Department </label>
                            <select name="departmentId" id="departmentId" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                              <option disabled >Departments</option>
                              <?php
                               $selected = '';
                              if (isset($departmentData)) {
                                foreach ($departmentData as $department) {
                                  if(isset($_GET['action']) && $_GET['action'] == 'edit')
                                  { 
                                    if($editCityData[0]['departmentId'] == $department['id'])
                                    {
                                      $selected = 'selected';
                                    }else
                                    {
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
                              <div class="form-group">
                                <input type="text" name="designationName" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editCityData[0]['designationName'];}?>" class="form-control" id="name" placeholder="Enter designation name" required>
                              </div>
                              <div class="form-group">
                                <button type="submit" name="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo 'update';}else{echo 'submit';}?>" class="btn btn-success btn-lg btn-block">Submit</button>
                              </div>
                           
                          </form>
                          </div>
                         
                        </div>
                      </div>
          
               
           

              <div class="row">

                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Showing All Designation Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="cityDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Designation Id</th>
                            <th>Desgination Name</th>
                            <th>Department Name</th>
                            <th>Status</th> 
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($designationData)) {
                            $i = 0;
                            foreach ($designationData as $cn) { ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <td><?= $cn['id'];?></td>
                                <td><?= $cn['designationName'];?></td>
                                <td><?= $cn['departmentName'];?></td>
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
