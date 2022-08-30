<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');

  // fetching city data
    $stateData = $this->db->query("SELECT * FROM " . Table::stateTable . "  ORDER BY id DESC")->result_array();


    // edit and delete action
    // if(isset($_GET['action']))
    // {

    //   if($_GET['action'] == 'edit')
    //   {
    //     $editId = $_GET['edit_id'];
    //     $editStateData = $this->db->query("SELECT * FROM " . Table::stateTable . " WHERE id='$editId' ")->result_array();
    //   }


    //   if($_GET['action'] == 'delete')
    //   {
    //     $deleteId = $_GET['delete_id'];
    //     $deleteStateData = $this->db->query("DELETE FROM " . Table::stateTable . " WHERE id='$deleteId'");
    //     if($deleteStateData)
    //     {
    //       $msgArr = [
    //         'class' => 'success',
    //         'msg' => 'State Deleted Successfully',
    //       ];
    //       $this->session->set_userdata($msgArr);
    //     }else
    //     {
    //       $msgArr = [
    //         'class' => 'danger',
    //         'msg' => 'State Not Deleted Due to this Error. ' . $this->db->last_query(),
    //       ];
    //       $this->session->set_userdata($msgArr);
    //     }
    //     header("Refresh:3 ".base_url()."master/stateMaster");
    //   }

    //   if($_GET['action'] == 'status')
    //   {
    //     $status = $_GET['status'];
    //     $updateId = $_GET['edit_id'];
    //     $updateStatus = $this->db->query("UPDATE " . Table::stateTable . " SET status = '$status' WHERE id = '$updateId'");

    //     if($updateStatus)
    //     {
    //       $msgArr = [
    //         'class' => 'success',
    //         'msg' => 'State Status Updated Successfully',
    //       ];
    //       $this->session->set_userdata($msgArr);
    //     }else
    //     {
    //       $msgArr = [
    //         'class' => 'danger',
    //         'msg' => 'State Status Not Updated Due to this Error. ' . $this->db->last_query(),
    //       ];
    //       $this->session->set_userdata($msgArr);
    //     }
    //     header("Refresh:3 ".base_url()."master/stateMaster");
    //   }

    // }


    // insert new city
    // if(isset($_POST['submit']))
    // {
    //   $stateName = $_POST['stateName'];

    //   $alreadyState = $this->db->query("SELECT * FROM " . Table::stateTable . " WHERE stateName = '$stateName'")->result_array();

    //   if(!empty($alreadyState))
    //   {
    //       $msgArr = [
    //         'class' => 'danger',
    //         'msg' => 'This State is already inserted, Please Edit That',
    //       ];
    //       $this->session->set_userdata($msgArr);
    //       header('Location: stateMaster');
    //       exit(0);
    //   }


    //   $insertNewCity = $this->db->query("INSERT INTO " . Table::stateTable . " (stateName) VALUES ('$stateName')");
    //   if($insertNewCity)
    //   {
    //     $msgArr = [
    //       'class' => 'success',
    //       'msg' => 'New State Added Successfully',
    //     ];
    //     $this->session->set_userdata($msgArr);
    //   }else
    //   {
    //     $msgArr = [
    //       'class' => 'danger',
    //       'msg' => 'State Not Added Due to this Error. ' . $this->db->last_query(),
    //     ];
    //     $this->session->set_userdata($msgArr);
    //   }
    //   header("Refresh:3 ".base_url()."master/stateMaster");
    // }

    // update exiting city
    // if(isset($_POST['update']))
    // {
    //   $stateName = $_POST['stateName'];
    //   $stateEditId = $_POST['updateStateId'];
    //   $updateState = $this->db->query("UPDATE " . Table::stateTable . " SET stateName = '$stateName' WHERE id = '$stateEditId'");
    //   if($updateState)
    //   {
    //     $msgArr = [
    //       'class' => 'success',
    //       'msg' => 'State Updated Successfully',
    //     ];
    //     $this->session->set_userdata($msgArr);
    //   }else
    //   {
    //     $msgArr = [
    //       'class' => 'danger',
    //       'msg' => 'State Not Updated Due to this Error. ' . $this->db->last_query(),
    //     ];
    //     $this->session->set_userdata($msgArr);
    //   }
    //   header("Refresh:3 ".base_url()."master/stateMaster");
    // }


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
                  State Master
                  <!-- <h3 class="card-title">Add / Edit State</h3> -->
                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <!-- <div class="row">
                  <div class="card-body">
                    <form method="post" action="">
                    <?php 
                    if(isset($_GET['action']) && $_GET['action'] == 'edit')
                    {?>
                     <input type="hidden" name="updateStateId" value="<?=$editId?>">
                    <?php }
                    
                    ?>
                      <div class="row">
                        <div class="form-group col-md-3">
                          <input type="text" name="stateName" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editStateData[0]['stateName'];}?>" class="form-control" id="name" placeholder="Enter state name" required>
                        </div>
                        <div class="form-group col-md-3">
                          <button type="submit" name="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo 'update';}else{echo 'submit';}?>" class="btn btn-primary">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
   
                </div> -->
                <!--/.col (left) -->
                <!-- right column -->
              </div>

              <div class="row">

                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Showing All State Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="stateDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <!-- <th>State Id</th> -->
                            <th>State Name</th>
                            <!-- <th>Status</th>
                            <th>Action</th> -->
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($stateData)) {
                            $i = 0;
                            foreach ($stateData as $cn) { ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <!-- <td><?= $cn['id'];?></td> -->
                                <td><?= $cn['stateName'];?></td>
                                <!-- <td>
                                <a href="?action=status&edit_id=<?= $cn['id'];?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1';?>"
                                    class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger';?>">
                                    <?php  echo ($cn['status'] == '1')? 'Active' : 'Inactive';?>
                                </td> -->
                                <!-- <td>
                                  <a href="?action=edit&edit_id=<?= $cn['id'];?>" class="btn btn-warning">Edit</a>
                                  <a href="?action=delete&delete_id=<?= $cn['id'];?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this?');">Delete</a>
                                </td> -->
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


    $("#stateDataTable").DataTable();
  </script>