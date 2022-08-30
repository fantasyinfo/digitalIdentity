<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');

  // fetching parent menu
    $parentMenu = $this->db->query("SELECT id,name FROM " . Table::adminPanelMenuTable . " WHERE is_parent = 1 AND status = '1'")->result_array();

    $childMenu = $this->db->query("SELECT id,name,parent_id FROM " . Table::adminPanelMenuTable . " WHERE is_child = 1 AND status = '1'")->result_array();


    $exitingPermission = $this->db->query("SELECT permissions FROM " . Table::panelMenuPermissionTable . " WHERE user_id = '{$data['id']}' AND user_type = '{$data['userType']}' AND status = '1'")->result_array();

    $exitingPermission = json_decode($exitingPermission[0]['permissions'],TRUE);

    // update exiting city
    if(isset($_POST['update']))
    {
      $menuIds = json_encode($_POST['menu']);
      $userId = $_POST['userId'];
      $userType = $_POST['userType'];
    
      $updatePermission = $this->db->query("UPDATE " . Table::panelMenuPermissionTable . " SET permissions = '$menuIds' WHERE user_id = '$userId' AND user_type = '$userType'");
      if($updatePermission)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Menu Permissions Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Menu Permissions Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:3");
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
                  <h3 class="card-title">Update Permission</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="row">
                  <div class="card-body">
                    <form method="post" action="">
                     <input type="hidden" name="userId" value="<?=$data['id']?>">
                     <input type="hidden" name="userType" value="<?=$data['userType']?>">
                     <div class="row">
                     <?php 
                     $checked = '';
                      if(isset($parentMenu))
                      {
                        foreach($parentMenu as $pM)
                        { ?>

                          <div class="col-md-4">
                            <div class="my-1 h4"><?=$pM['name'];?></div>
                        </br>
                          <?php 
                            if(isset($childMenu))
                            {
                              foreach($childMenu as $cM)
                              { 
                              
                                if($pM['id'] == $cM['parent_id'])
                                { 
                                  
                                  // check already permissions
                                  if(isset($exitingPermission))
                                  {
                                    for($i=0;$i<count($exitingPermission);$i++)
                                    {
                                      if($exitingPermission[$i] == $cM['id'])
                                      {
                                        $checked = 'checked';
                                        break;
                                      }else
                                      {
                                        $checked = '';
                                      }
                                    }
                                  }
                                  
                                  ?>
                                  <input <?=$checked?> type="checkbox" name="menu[]" value="<?=$cM['id']?>" class="form-input"><span class="ml-2"><?=$cM['name'];?></span></br>
                             <?php }
                              }
                            }
                          ?>
                           </br>
                          </div>
                          
                        <?php }
                      }
                     ?>
                     
                       
                      </div>
                      <input type="submit" class="btn btn-block btn-lg btn-primary" name="update" value="update"> 
                    </form>
                  </div>
                  <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <!-- right column -->
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


    $("#cityDataTable").DataTable();
  </script>