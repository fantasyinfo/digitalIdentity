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
    $notificatonData = $this->db->query("SELECT * FROM " . Table::pushNotificationTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND device_type = 'Web' ORDER BY id DESC")->result_array();


    // insert new city
    if(isset($_POST['submit']))
    {
      $title = $_POST['title'];
      $body = trim($_POST['body']);

      $image = null;
      if(isset($_FILES['image']))
      {
        $image = $this->CrudModel->uploadImg($_FILES,'NOTIFICATION',HelperClass::notificationsImagePath);
      }
      $sound = null;

      // students token
     $tokens =  $this->db->query("SELECT fcm_token FROM " . Table::studentTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'  AND status = '1' ")->result_array();


     //stoken
     $totalTokens = count($tokens);
     $token = [];
     if(!empty( $tokens))
     {
       
        for($i=0; $i < $totalTokens; $i++)
        {
          if( $i > 499  && $i % 500 == '0')
          {
            // if token is modules 500 then send push
            $sendPushSMS= $this->CrudModel->sendFireBaseNotificationWithDeviceId($token, $title,$body,$image,$sound);
            $token = [];
            continue;
          }
          array_push($token,$tokens[$i]['fcm_token']);
        }
        
        
     }


     // teachers token
     $ttokens =  $this->db->query("SELECT fcm_token FROM " . Table::teacherTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'  AND status = '1' ")->result_array();

     // ttoken
     $ttotalTokens = count($ttokens);
    
     if(!empty( $ttokens))
     {

          for($i=0; $i < $ttotalTokens; $i++)
          {
            array_push($token,$ttokens[$i]['fcm_token']);
          }
        
        
     }



       
     
       $sendPushSMS= $this->CrudModel->sendFireBaseNotificationWithDeviceId($token, $title,$body,$image,$sound);
      //  print_r($sendPushSMS);
      //  die();
        $insertNotification = $this->db->query("INSERT INTO " . Table::pushNotificationTable . " (schoolUniqueCode,title,body,device_type,image) VALUES ('{$_SESSION['schoolUniqueCode']}','$title','$body','Web','$image')");
        if($insertNotification)
        {
        
          $msgArr = [
            'class' => 'success',
            'msg' => 'New Notification Added Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Notification Not Added Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/notificationMaster");
     

      
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
              <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Sent New Notification</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="row">
                  <div class="card-body">
                    <form method="post" action="" enctype="multipart/form-data">

                      <div class="row">

                        <div class="form-group col-md-12 mb-2">
                          <label>Enter Notification Title</label>
                          <input type="text" name="title" class="form-control" id="name" placeholder="Enter notification title" required>
                        </div>
                        <div class="form-group col-md-12 mb-2">
                        <label>Enter Body Content</label>
                          <textarea  name="body" class="form-control"  required></textarea>
                        </div>
                        <div class="form-group col-md-12 mb-2">
                       <label for="city">Select Gift Image</label>
                        <img id="img" height='100px' width='100px' class='img-fluid' />
                       
                          <div class="custom-file mt-1">
                            <input type="file" class="custom-file-input" name="image" onchange="document.getElementById('img').src = window.URL.createObjectURL(this.files[0])">
                            <label class="custom-file-label" for="img">Choose file</label>
            </div>
                        <div class="form-group col-md-12 my-3">
                          <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Send New Notification</button>
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
                      <h3 class="card-title">Showing All Notifications</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="cityDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Notification Id</th>
                            <th>Title</th>
                            <th>Body</th>
                            <th>Date of Sent</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($notificatonData)) {
                            $i = 0;
                            foreach ($notificatonData as $cn) { ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <td><?= $cn['id'];?></td>
                                <td><?= $cn['title'];?></td>
                                <td><?= $cn['body'];?></td>
                                <td><?= date('d-m-Y H:i:A', strtotime($cn['created_at']));?></td>
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


    $("#cityDataTable").DataTable();
  </script>