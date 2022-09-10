<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');

  // fetching city data
    $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE status !='4'   AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ")->result_array();


    // edit and delete action
    if(isset($_GET['action']))
    {

      if($_GET['action'] == 'edit')
      {
        $editId = $_GET['edit_id'];
        $editclassData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE id='$editId'   AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ")->result_array();
      }


      if($_GET['action'] == 'delete')
      {
        $deleteId = $_GET['delete_id'];
        $deleteclassData = $this->db->query("UPDATE " . Table::classTable . " SET status = '4' WHERE id='$deleteId'   AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
        if($deleteclassData)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'class Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'class Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/classMaster");
      }

      
      if($_GET['action'] == 'status')
      {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];
        $updateStatus = $this->db->query("UPDATE " . Table::classTable . " SET status = '$status' WHERE id = '$updateId'   AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if($updateStatus)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Class Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Class Status Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/classMaster");
      }
      

    }


    // insert new city
    if(isset($_POST['submit']))
    {
      $className = $_POST['className'];


      $alreadyClassName = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE className = '$className'   AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

      if(!empty($alreadyClassName))
      {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'This Class is already inserted, Please Edit That',
          ];
          $this->session->set_userdata($msgArr);
          header('Location: classMaster');
          exit(0);
      }



      $insertNewCity = $this->db->query("INSERT INTO " . Table::classTable . " (schoolUniqueCode,className) VALUES ('{$_SESSION['schoolUniqueCode']}','$className')");
      if($insertNewCity)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New class Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Class Not Added Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 ".base_url()."master/classMaster");
    }

    // update exiting city
    if(isset($_POST['update']))
    {
      $className = $_POST['className'];
      $classEditId = $_POST['updateclassId'];
      $updateclass = $this->db->query("UPDATE " . Table::classTable . " SET className = '$className' WHERE id = '$classEditId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
      if($updateclass)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Class Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Class Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 ".base_url()."master/classMaster");
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
            <div class="col-md-12 mx-auto">
              <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Add / Edit Class</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="row">
                  <div class="card-body">
                    <form method="post" action="">
                    <?php 
                    if(isset($_GET['action']) && $_GET['action'] == 'edit')
                    {?>
                     <input type="hidden" name="updateclassId" value="<?=$editId?>">
                    <?php }
                    
                    ?>
                      <div class="row">
                        <div class="form-group col-md-3">
                          <input type="text" name="className" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editclassData[0]['className'];}?>" class="form-control" id="name" placeholder="Enter class name" required>
                        </div>
                        <div class="form-group col-md-3">
                          <button type="submit" name="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo 'update';}else{echo 'submit';}?>" class="btn btn-primary">Submit</button>
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
                      <h3 class="card-title">Showing All class Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="classDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Class Id</th>
                            <th>Class Name</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($classData)) {
                            $i = 0;
                            foreach ($classData as $cn) { ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <td><?= $cn['id'];?></td>
                                <td><?= $cn['className'];?></td>
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


    $("#classDataTable").DataTable();
  </script>