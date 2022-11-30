<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');

  // fetching section data
    $sectionData = $this->db->query("SELECT * FROM " . Table::schoolSessionTable . " WHERE status != '4'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

    $monthD = $this->db->query("SELECT * FROM ".Table::monthTable." WHERE Status = '1'")->result_array();


    // edit and delete action
    if(isset($_GET['action']))
    {
   
      if($_GET['action'] == 'edit')
      {
        $editId = $_GET['edit_id'];
        $editsectionData = $this->db->query("SELECT * FROM " . Table::schoolSessionTable . " WHERE id='$editId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
      }


      if($_GET['action'] == 'delete')
      {
        $deleteId = $_GET['delete_id'];
        $deletesectionData = $this->db->query("UPDATE " . Table::schoolSessionTable . " SET status = '4' WHERE id='$deleteId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
        if($deletesectionData)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Session Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Session Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/sessionMaster");
      }

      if($_GET['action'] == 'status')
      {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];
        $updateStatus = $this->db->query("UPDATE " . Table::schoolSessionTable . " SET status = '$status' WHERE id = '$updateId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if($updateStatus)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Session Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Session Status Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/sessionMaster");
      }
    }


    // insert new section
    if(isset($_POST['submit']))
    {
      $session_start_year = $_POST['session_start_year'];
      $session_start_month = $_POST['session_start_month'];
      $session_end_year = $_POST['session_end_year'];
      $session_end_month = $_POST['session_end_month'];

      $alreadySection = $this->db->query("SELECT * FROM " . Table::schoolSessionTable . " WHERE session_start_year = '$session_start_year'  AND session_end_year = '$session_end_year' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status !='4'")->result_array();

      if(!empty($alreadySection))
      {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'This Session is already inserted, Please Edit That',
          ];
          $this->session->set_userdata($msgArr);
          header('Location: sessionMaster');
          exit(0);
      }


      $insertNewsection = $this->db->query("INSERT INTO " . Table::schoolSessionTable . " (schoolUniqueCode,session_start_year,session_start_month,session_end_year,session_end_month) VALUES ('{$_SESSION['schoolUniqueCode']}','$session_start_year','$session_start_month','$session_end_year','$session_end_month')");
      if($insertNewsection)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Session Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Session Not Added Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 ".base_url()."master/sessionMaster");
    }

    // update exiting section
    if(isset($_POST['update']))
    {
      $session_start_year = $_POST['session_start_year'];
      $session_start_month = $_POST['session_start_month'];
      $session_end_year = $_POST['session_end_year'];
      $session_end_month = $_POST['session_end_month'];

      $sectionEditId = $_POST['updatesectionId'];
      $updatesection = $this->db->query("UPDATE " . Table::schoolSessionTable . " SET session_start_year = '$session_start_year', session_start_month = '$session_start_month',session_end_year = '$session_end_year', session_end_month = '$session_end_month'  WHERE id = '$sectionEditId'   AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
      if($updatesection)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Session Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Session Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 ".base_url()."master/sessionMaster");
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
              <div class="card border-top-3">
                <div class="card-header">
                  <h3 class="card-title">Add / Edit New Session</h3>
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
                        <table class="table">
                         
                            <thead>
                              <th>Labels</th>
                              <th>Values</th>
                            </thead>
                            <tbody>
                            <tr>
                              <td> <label for="dob">Session Started</label></td>
                              <td>  
                                <div class="row">

                              
                                <div class="col-md-6">
                                    <select name="session_start_month" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                                  <option value="" selected>Select Month</option>
                                  <?php  foreach ($monthD as $mon) { 
                                      if($sd['session_start_month'] == $mon['monthName'])
                                      {
                                        $monthSelected= 'selected';
                                      }else
                                      {
                                        $monthSelected = '';
                                      }
                                  
                                  ?>
                              <option <?=$monthSelected?> value="<?= $mon['monthName'] ?>"><?= $mon['monthName'] ?></option>
                              <?php } ?>
                                  </select>
                                </div>
                                <div class="col-md-6">
 
                                <select name="session_start_year" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                              <option value="" selected>Select Year</option>
                              <?php  foreach (HelperClass::sessionYears as $sec => $val) { 
                                  if($sd['session_start_year'] == $val)
                                  {
                                    $secSelected= 'selected';
                                  }else
                                  {
                                    $secSelected = '';
                                  }
                              
                              ?>
                          <option <?=$secSelected?> value="<?= $val ?>"><?= $val ?></option>
                          <?php } ?>
                              </select>
                                </div>
                                </div>
                            </td>
                          </tr>
                          <tr>
                              <td> <label for="dob">Session End</label></td>
                              <td> 
                              <div class="row">
                                <div class="col-md-6">
                                <select name="session_end_month" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                              <option value="" selected>Select Month</option>
                              <?php  foreach ($monthD as $monA) { 
                            
                                  if($sd['session_end_month'] == $monA['monthName'])
                                  {
                                    $monthSelectedY= 'selected';
                                  }else
                                  {
                                    $monthSelectedY = '';
                                  }
                                
                                
                              
                              ?>
                          <option <?=$monthSelectedY?> value="<?= $monA['monthName'] ?>"><?= $monA['monthName'] ?></option>
                          <?php } ?>
                              </select>
                                </div> 
                                <div class="col-md-6">
                                <select name="session_end_year" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                              <option value="" selected>Select Year</option>
                              <?php  foreach (HelperClass::sessionYears as $secA => $valA) { 
                            
                                  if($sd['session_end_year'] == $valA)
                                  {
                                    $secSelectedY= 'selected';
                                  }else
                                  {
                                    $secSelectedY = '';
                                  }
                                
                              ?>
                          <option <?=$secSelectedY?> value="<?= $valA ?>"><?= $valA ?></option>
                          <?php } ?>
                              </select>
                                </div>
                            
                                </div>
                              </td>
                          </tr>
                            </tbody>
                        
                        </table>
                       
                        <tr >
                          <td colspan="2">
                          <button type="submit" name="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo 'update';}else{echo 'submit';}?>" class="btn btn-block mybtnColor">Save</button>
                          </td>
                          
                        </tr>
                      </div>
                    </form>
                  </div>
                  <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <!-- right column -->
              </div>

                                </div>

                <div class="col-md-8">
                  <div class="card">
                    <div class="card-header border-top-3">
                      <h3 class="card-title">Showing All Session Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <div class="table-responsive">
                      <table id="sectionDataTable" class="table mb-0 bg-white align-middle">
                        <thead class="bg-white">
                          <tr>
                            <th>Id</th>
                            <!-- <th>Session Id</th> -->
                            <th>Session Start</th>
                            <th>Session End</th>
                            <!-- <th>Status</th> -->
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($sectionData)) {
                            $i = 0;
                            foreach ($sectionData as $cn) { ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <!-- <td><?= $cn['id'];?></td> -->
                                <td><?= $cn['session_start_month'] . ' - ' . $cn['session_start_year'];?></td>
                                <td><?= $cn['session_end_month'] . ' - ' . $cn['session_end_year'];?></td>
                                <!-- <td>
                                <a href="?action=status&edit_id=<?= $cn['id'];?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1';?>"
                                    class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger';?>">
                                    <?php  echo ($cn['status'] == '1')? 'Active' : 'Inactive';?>
                                </td> -->
                                <td>
                                  <a href="?action=edit&edit_id=<?= $cn['id'];?>" class="btn btn-warning">Edit</a>
                                  <!-- <a href="?action=delete&delete_id=<?= $cn['id'];?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this?');">Delete</a> -->
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
          


              <!--/.col (right) -->
           

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


    $("#sectionDataTable").DataTable();
  </script>