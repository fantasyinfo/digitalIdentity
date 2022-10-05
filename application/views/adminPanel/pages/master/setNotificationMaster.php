<style>
  .big-checkbox {width: 1.5rem; height: 1.5rem;top:0.5rem}
</style>

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
    $notificatonData = $this->db->query("SELECT * FROM " . Table::setNotificationTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ")->result_array();


    // insert new city
    if(isset($_POST['submit']))
    {
      

      $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
      $id = $_POST['id'];
      $title = $_POST['title'];
      $body = trim($_POST['body']);
      $updatedAt = date('Y-m-d h:i:s');

        $updateNotification = $this->db->query("UPDATE " . Table::setNotificationTable . " SET title = '$title',body = '$body', updated_at = '$updatedAt' WHERE id = '$id' AND schoolUniqueCode = '$schoolUniqueCode'");
        if($updateNotification)
        {
        
          $msgArr = [
            'class' => 'success',
            'msg' => 'Notification Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Notification Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/setNotificationMaster");
     

      
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
   
              <div class="row">

                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Showing All Notifications</h3>
                      <a href="<?= base_url('master/notificationDefault')?>" class="ml-2 btn btn-primary float-right" >Update Default Notifications</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="cityDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th width="18%">Occasions</th>
                            <th width="22%">Title</th>
                            <th width="50%">Body</th>
                            <th width="10%">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          
                          if(!empty($notificatonData))
                          {
                            foreach($notificatonData as $n)
                            { ?>
                           
                              <tr>
                              <form method="POST">
                              <input type="hidden" name="id" value="<?=$n['id'];?>">
                                <td>
                                <select name="for_what" id="for_what" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                                <option ></option>
                                  <?php
                                 $selected = '';
                                foreach(HelperClass::setNotificationForWhat as $key => $forWhat)
                                {
                                  if($key == $n['for_what'])
                                  {
                                    $selected = 'selected';
                                  }else
                                  {
                                    $selected = '';
                                  }
                                  ?>
                                  <option <?=$selected?> value="<?= $key ?>"><?= $forWhat ?></option>
                              <?php  }
                               
                                ?>
                                 </select>
                              </td>
                                <td> <input type="text" name="title" class="form-control" id="" value="<?=$n['title'];?>"> </td>
                                <td> <textarea  name="body" class="form-control" required><?= trim($n['body']);?></textarea></td>
                                <td><input type="submit" name="submit" class="btn btn-warning" value="Update"></td>
                                </form>
                              </tr>
                           
                          <?php  }
                          }
                          
                          ?>
                          
                       
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


    $("#cityDataTable").DataTable();
  </script>