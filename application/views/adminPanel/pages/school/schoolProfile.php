<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <?php 
          $this->load->model('CrudModel');
          $dir = base_url().HelperClass::schoolLogoImagePath;
      $sd = $data['schoolData'][0];


      $monthD = $this->db->query("SELECT * FROM ".Table::monthTable." WHERE Status = '1'")->result_array();
      $sessionSchool = $this->db->query("SELECT * FROM ".Table::schoolSessionTable." WHERE Status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();







      if(isset($_POST['submit']))
      {
        $school_name = $_POST['school_name'];
        $mobile = $_POST['mobile'];
        $address = $_POST['address'];
        $pincode = $_POST['pincode'];
        $schoolId = $_POST['schoolId'];
        $current_session = $_POST['current_session'];
        $fee_invoice_start = $_POST['fee_invoice_start'];
        $gifts_system = $_POST['gifts_system'];


        $fileName = "";
        if(!empty($_FILES['image']))
        {
          // upload files and get image path
          $fileName = $this->CrudModel->uploadImg($_FILES,'SCHOOL',HelperClass::schoolLogoImagePath);
        }



        $updateSchool = $this->db->query("UPDATE " . Table::schoolMasterTable . " SET 
        school_name = '$school_name',
        mobile = '$mobile',
        address = '$address', 
        pincode = '$pincode', 
        image = '$fileName', 
        current_session = '$current_session', 
        fee_invoice_start = '$fee_invoice_start',
        gifts_system = '$gifts_system'
        WHERE id = '$schoolId' AND unique_id = '{$_SESSION['schoolUniqueCode']}'");
        if($updateSchool)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'School Details Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'School Details Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."school/schoolProfile");
      }














      //  print_r($sd);
      ?>
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
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
          <div class="row">
            <!-- left column -->
            <div class="col-md-12 mx-auto">
              <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">All * Field Are Mandatory</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form  method="post" enctype="multipart/form-data" >
                <input type="hidden" name="schoolId" class="form-control" id="name" value="<?=$sd['id'];?>">
                <?php 
                if(isset($sd['logo']))
                {
                  $bExt = $dir = base_url().HelperClass::schoolLogoImagePath;
                  $imgDD = explode($bExt,$sd['logo']);
                  //print_r($imgDD);
                ?>
                <input type="hidden" name="image" class="form-control" id="name" value="<?=@$imgDD[1];?>">
                
               <?php }?>
                
                  <div class="row">
                    <div class="card-body">
                    <div class="col-md-12">
                    <table class="table">
                      <tbody>
                          <tr>
                              <td width="40%"><label for="name">School Code</label></td>
                              <td><input type="text"  class="form-control" value="<?=$sd['unique_id'];?>" readonly></td>
                          </tr>
                          <tr>
                              <td><label for="name">School Name</label></td>
                              <td><input type="text" name="school_name" class="form-control" id="name" value="<?=$sd['school_name'];?>"></td>
                          </tr>
                          <tr>
                              <td> <label for="city">Logo</label></td>
                              <td><img src="<?= $dir.$sd['image'] ?>" alt='100x100' id="img" height='100px' width='100px' class='img-fluid' />
                              <div class="input-group mt-2">
                              <div class="custom-file">
                              <input type="file" class="custom-file-input" name="image" onchange="document.getElementById('img').src = window.URL.createObjectURL(this.files[0])">
                              <label class="custom-file-label" for="img">Choose file</label>
                            </div>
                          </div>
                            </td>
                          </tr>
                          <tr>
                              <td> <label for="email">Email address</label></td>
                              <td>  <input class="form-control" id="email" value="<?=$sd['email'];?>" readonly></td>
                          </tr>
                          <tr>
                              <td> <label for="mobile">Mobile Number</label></td>
                              <td> <input class="form-control" name="mobile" id="mobile" value="<?=$sd['mobile'];?>"></td>
                          </tr>
                          <tr>
                              <td> <label for="dob">Select Date of Approval</label></td>
                              <td>  <input type="text" class="form-control"  value="<?= date('d-M-Y h:i:A', strtotime($sd['doa']));?>" readonly></td>
                          </tr>
                          <tr>
                              <td> <label for="current_session">Select Current Session</label></td>
                              <td> 
                                    <select name="current_session" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                                  <option value="" selected>Select Sessions Years</option>
                                  <?php  
                                  $currentSessionSelected = '';
                                  if(isset($sessionSchool) && !empty($sessionSchool))
                                  {
                                  foreach ($sessionSchool as $ses ) { 
                                      if($sd['current_session'] == $ses['id'])
                                      {
                                        $currentSessionSelected= 'selected';
                                      }else
                                      {
                                        $currentSessionSelected = '';
                                      }
                                  
                                  ?>
                              <option <?=$currentSessionSelected?> value="<?= $ses['id'] ?>"><?= $ses['session_start_year'] . " - " . $ses['session_end_year'] ?></option>
                              <?php } }?>
                                  </select>
                               
                            </td>
                          </tr>
                          <tr>
                              <td> <label for="address">Address</label></td>
                              <td>  <input type="text" name="address" class="form-control" id="address" value="<?=$sd['address'];?>"></td>
                          </tr>
                          <tr>
                              <td> <label for="pincode">Pincode</label></td>
                              <td> <input type="text" name="pincode" class="form-control" id="pincode" value="<?=$sd['pincode'];?>"></td>
                          </tr>
                          <tr>
                              <td> <label for="fee_invoice_start">Fees Invoice Start</label></td>
                              <td> <input type="text" name="fee_invoice_start" class="form-control" id="fee_invoice_start" value="<?=$sd['fee_invoice_start'];?>" placeholder="1001"></td>
                          </tr>
                          <tr>
                            <td><label>Gifts System</label></td>
                            <td>
                              <select name="gifts_system" class="form-control  select2 select2-danger" id="gifts_system">
                                <?php
                                $selectedGiftsSystem = '';
                                $giftsSystem = [
                                  '1' => 'Yes, Enabled',
                                  '2' => 'No, Disabled'
                                ];
                                foreach($giftsSystem as $key => $val)
                                { 
                                  if(isset($sd['gifts_system'])) {
                                    if($sd['gifts_system'] == $key)
                                    {
                                      $selectedGiftsSystem = 'selected';
                                    }else
                                    {
                                      $selectedGiftsSystem = '';
                                    }
                                  }
                                  
                                  ?>
                                <option value="<?=$key?>" <?=$selectedGiftsSystem?>><?=$val?></option>
                             <?php   }
                               
                                 ?>
                              </select>
                            </td>
                          </tr>
                           <tr>
                              <td>#</td>
                              <td><button type="submit" name="submit" class="btn btn-primary btn-block btn-lg">Update</button></td>
                          </tr> 
                      </tbody>
                  </table>
                    </div>
                    </div>
                    <!-- /.card-body -->
                  </div>

                  
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
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->

    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>

                </div>