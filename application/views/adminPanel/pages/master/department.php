<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');

  // fetching section data
    $sectionData = $this->db->query("SELECT * FROM " . Table::departmentTable . " WHERE status != '4'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();


    // edit and delete action
    if(isset($_GET['action']))
    {
   
      if($_GET['action'] == 'edit')
      {
        $editId = $_GET['edit_id'];
        $editsectionData = $this->db->query("SELECT * FROM " . Table::departmentTable . " WHERE id='$editId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
      }


      if($_GET['action'] == 'delete')
      {
        $deleteId = $_GET['delete_id'];
        $deletesectionData = $this->db->query("UPDATE " . Table::departmentTable . " SET status = '4' WHERE id='$deleteId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
        if($deletesectionData)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Department Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Department Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/department");
      }

      if($_GET['action'] == 'status')
      {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];
        $updateStatus = $this->db->query("UPDATE " . Table::departmentTable . " SET status = '$status' WHERE id = '$updateId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if($updateStatus)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Department Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Department Status Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/department");
      }
    }


    // insert new section
    if(isset($_POST['submit']))
    {
      $departmentName = $_POST['departmentName'];

      $alreadySection = $this->db->query("SELECT * FROM " . Table::departmentTable . " WHERE departmentName = '$departmentName'   AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

      if(!empty($alreadySection))
      {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'This Department is already inserted, Please Edit That',
          ];
          $this->session->set_userdata($msgArr);
          header('Location: department');
          exit(0);
      }


      $insertNewsection = $this->db->query("INSERT INTO " . Table::departmentTable . " (schoolUniqueCode,departmentName) VALUES ('{$_SESSION['schoolUniqueCode']}','$departmentName')");
      if($insertNewsection)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Department Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Department Not Added Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 ".base_url()."master/department");
    }

    // update exiting section
    if(isset($_POST['update']))
    {
      $departmentName = $_POST['departmentName'];
      $sectionEditId = $_POST['updatesectionId'];
      $updatesection = $this->db->query("UPDATE " . Table::departmentTable . " SET departmentName = '$departmentName' WHERE id = '$sectionEditId'   AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
      if($updatesection)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Department Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Department Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 ".base_url()."master/department");
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
            <div class="col-md-4 mx-auto">
              <!-- jquery validation -->
              <div class="card  border-top-3">
                <div class="card-header">
                  <h3 class="card-title">Add / Edit Department</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="row">
                  <div class="card-body">
                    <form method="post" action="">
                    <?php 
                    if(isset($_GET['action']) && $_GET['action'] == 'edit')
                    {?>
                     <input type="hidden" name="updatesectionId" value="<?=$editId?>">
                    <?php }
                    
                    ?>
                      <div class="row">
                        <div class="form-group col-md-12">
                          <input type="text" name="departmentName" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editsectionData[0]['departmentName'];}?>" class="form-control" id="name" placeholder="Enter department name" required>
                        </div>
                        <div class="form-group col-md-12">
                          <button type="submit" name="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo 'update';}else{echo 'submit';}?>" class="btn btn-lg float-right mybtnColor">Save</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <!-- right column -->
              </div>
                    </div>
                    <div class="col-md-8 mx-auto"> 
              <div class="row">

                <div class="col-md-12">
                  <div class="card  border-top-3">
                    <div class="card-header">
                      <h3 class="card-title">Showing All Department Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <div class="table-responsive">

                      
                      <table id="sectionDataTable" class="table align-middle mb-0 bg-white">
                        <thead class="bg-light">
                          <tr>
                            <!-- <th>Id</th>
                            <th>Department Id</th> -->
                            <th>Department Name</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($sectionData)) {
                            $i = 0;
                            foreach ($sectionData as $cn) { ?>
                              <tr>
                                <!-- <td><?= ++$i;?></td>
                                <td><?= $cn['id'];?></td> -->
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
