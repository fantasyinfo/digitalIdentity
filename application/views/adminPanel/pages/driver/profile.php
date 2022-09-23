
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
<?php $this->load->view('adminPanel/pages/navbar.php');?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php $this->load->view("adminPanel/pages/sidebar.php");?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <?php 
    
    $sd = $data['teacherData'][0];
    //print_r($sd);
    $string = HelperClass::qrcodeUrl . "?tecid=" . HelperClass::schoolPrefix.$sd['user_id'];
    ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?=$data['pageTitle']?> </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"><?=$data['pageTitle']?> </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
    <div class="main-body">
          <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
              <div class="card card-primary">
              <div class="card-header">
                  <h3 class="card-title">Profile Image</h3>
                </div>
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <img src="<?=$sd['image'];?>" alt="Admin" class="rounded-circle" width="150">
                    <div class="mt-3">
                      <h4><?=$sd['name'];?></h4>
                      <p class="text-secondary mb-1"><?=$sd['className'] . " - " . $sd['sectionName'];?></p>
                      <span class="badge badge-success"><?=$sd['user_id'];?></span>
                    </div>
                    <a href="<?=$string?>" class="btn btn-info text-white my-2" target="_blank">User Profile View</a>
                  </div>
                </div>
              </div>
              <div class="card mt-3">
                <?php
               
              $google_chart_api_url = "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=".$string."&choe=UTF-8";
                // let's display the generated QR code
                  echo "<img src='".$google_chart_api_url."' alt='".$string."'>";
                ?>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card card-primary mb-3">
              <div class="card-header">
                  <h3 class="card-title">Personal Details</h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Full Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?=$sd['name'];?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Email</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?=$sd['email'];?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Gender</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php 
                    if($sd['gender'] == 1) {echo 'Male';}elseif($sd['gender'] == 2){echo 'Female';}else{echo 'Other';}
                    ?>
                    </div>
                  </div>
                  
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Phone</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?=$sd['mobile'];?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Mother Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?=$sd['mother_name'];?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Father Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?=$sd['father_name'];?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Address</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?=$sd['address'] . " - " . $sd['cityName'] . " - " . $sd['stateName'] . " - " .$sd['pincode']." - India";?>
                    </div>
                  </div>
                  <hr>
                  <!-- <div class="row">
                    <div class="col-sm-12">
                      <a class="btn btn-info " target="__blank" href="https://www.bootdey.com/snippets/view/profile-edit-data-and-skills">Edit</a>
                    </div>
                  </div> -->
                </div>
              </div>

              <div class="row gutters-sm">
                <div class="col-sm-12 mb-3">
                  <div class="card h-100">
                    <div class="card-header bg-primary">
                    <h6 class="d-flex align-items-center mb-3">Login Details</h6>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">User Id</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?=$sd['user_id'];?>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">PassWord</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?=$sd['password'];?>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">School Code</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <?=$_SESSION['schoolUniqueCode'];?>
                        </div>
                      </div>


                    </div>
                  </div>
                </div>
               
              </div>



            </div>
          </div>

        </div>
    
     
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->

  <!-- /.control-sidebar -->
</div>
  <?php $this->load->view("adminPanel/pages/footer-copyright.php");?>
</div>
<!-- ./wrapper -->
<script>
  var ajaxUrlForStudentList = '<?= base_url() . 'ajax/listStudentsAjax'?>';
</script>
