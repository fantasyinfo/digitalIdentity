<?php

if(isset($_GET['stu_id']) && !empty($_GET['stu_id'])){

}






if (isset($_POST['submit'])) {
  $this->load->model('CrudModel');

  $fileName = "";

  if (!empty($_POST['img'])) {
    $fileName = $_POST['img'];
  }


  if (!empty($_FILES['image']['name'])) {
    // upload files and get image path
    $fileName = $this->CrudModel->uploadImg($_FILES, 'REGISTRATION', HelperClass::registrationImagePath);
  }


  $regNoAlready = @$this->db->query("SELECT regNo FROM ".Table::registrationTable." WHERE regNo != null  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ORDER BY id DESC LIMIT 1")->result_array()[0]['regNo'];

  $regNo = isset($_POST['regNo']) ? $_POST['regNo'] : $regNoAlready + 1;

  $insertArr = [
    'schoolUniqueCode'  => $this->CrudModel->sanitizeInput($_SESSION['schoolUniqueCode']),
    'regNo'             => $this->CrudModel->sanitizeInput($regNo),
    'regDate'           => $this->CrudModel->sanitizeInput($_POST['regDate']),
    'stuName'           => $this->CrudModel->sanitizeInput($_POST['stuName']),
    'gender'            => $this->CrudModel->sanitizeInput($_POST['gender']),
    'class'             => $this->CrudModel->sanitizeInput($_POST['class']),
    'category'          => $this->CrudModel->sanitizeInput($_POST['category']),
    'father_name'       => $this->CrudModel->sanitizeInput($_POST['father_name']),
    'mother_name'       => $this->CrudModel->sanitizeInput($_POST['mother_name']),
    'email'             => $this->CrudModel->sanitizeInput($_POST['email']),
    'mobile'            => $this->CrudModel->sanitizeInput($_POST['mobile']),
    'address'           => $this->CrudModel->sanitizeInput($_POST['address']),
    'state'             => $this->CrudModel->sanitizeInput($_POST['state']),
    'city'              => $this->CrudModel->sanitizeInput($_POST['city']),
    'pincode'           => $this->CrudModel->sanitizeInput($_POST['pincode']),
    'dob'               => $this->CrudModel->sanitizeInput($_POST['dob']),
    'father_occupation' => $this->CrudModel->sanitizeInput($_POST['father_occupation']),
    'last_school_name'  => $this->CrudModel->sanitizeInput($_POST['last_school_name']),
    'last_class'        => $this->CrudModel->sanitizeInput($_POST['last_class']),
    'reg_fee'           => $this->CrudModel->sanitizeInput($_POST['reg_fee']),
    'image'             => $this->CrudModel->sanitizeInput($fileName),
    'session_table_id'  => $this->CrudModel->sanitizeInput($_SESSION['currentSession']),
  ];

  $insert = $this->CrudModel->insert(Table::registrationTable, $insertArr);

  if ($insert) {
    $msgArr = [
      'class' => 'success',
      'msg' => 'New Student Registration Successfully',
    ];
    $this->session->set_userdata($msgArr);
  } else {
    $msgArr = [
      'class' => 'danger',
      'msg' => 'Student Registration Not Done, Try Again.',
    ];
    $this->session->set_userdata($msgArr);
  }
  header("Refresh:1 " . base_url('registration/newRegistration'));
}


?>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
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
          <div class="row">
            <?php
            if (!empty($this->session->userdata('msg'))) { ?>

              <div class="alert alert-<?= $this->session->userdata('class') ?> alert-dismissible fade show" role="alert">
                <?= $this->session->userdata('msg');
                if ($this->session->userdata('class') == 'success') {
                  HelperClass::swalSuccess($this->session->userdata('msg'));
                } else if ($this->session->userdata('class') == 'danger') {
                  HelperClass::swalError($this->session->userdata('msg'));
                }
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php
              $this->session->unset_userdata('class');
              $this->session->unset_userdata('msg');
            }
            ?>
            <!-- left column -->
            <?php //print_r($data['class']);
            ?>
            <div class="col-md-12 mx-auto">
              <!-- jquery validation -->
              <div class="card border-top-3">
                <div class="card-header">
                  <h3 class="card-title">Add Correct Details</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="post" enctype="multipart/form-data">

                  <div class="row">
                    <div class="card-body">


                      <div class="col-md-12">
                        <div class="row">
                          <div class="form-group col-md-3">
                            <label for="admission_no">Registration Number</label>
                            <input type="text" name="regNo" disabled class="form-control" id="regNo" placeholder="Auto Generated">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="date_of_admission">Date of Registration</label>
                            <input type="date" name="regDate" class="form-control" value="<?= date('Y-m-d') ?>" id="regDate" placeholder="Date of Admission">
                          </div>





                        </div>

                        <div class="row">
                          <div class="form-group col-md-3">
                            <label for="name">Full Name</label>
                            <input type="text" name="stuName" class="form-control" id="stuName" placeholder="Enter full name">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="gender">Select Gender</label>
                            <select class="form-control select2 select2-dark" name="gender" id="gender" data-dropdown-css-class="select2-dark" style="width: 100%;">
                              <?php

                              $genderArr = [
                                1 => 'Male',
                                2 => 'Female',
                                3 => 'Other'
                              ];

                              foreach ($genderArr as $kk => $gg) {
                              ?>
                                <option value="<?= $kk ?>"><?= $gg ?></option>
                              <?php }
                              ?>
                            </select>
                          </div>
                          <div class="form-group col-md-3">
                            <label for="class">Select Class</label>
                            <select class="form-control select2 select2-dark" name="class" data-dropdown-css-class="select2-dark" style="width: 100%;">
                              <?php
                              $selectedClass = '';

                              if (isset($data['class']) && !empty($data['class'])) {

                                foreach ($data['class'] as $class) {
                              ?>
                                  <option value="<?= $class['id'] ?>"><?= $class['className'] ?></option>
                              <?php }
                              }
                              ?>
                            </select>
                          </div>
                          <div class="form-group col-md-3">
                            <label for="category">Select Category</label>
                            <select class="form-control select2 select2-dark" name="category" data-dropdown-css-class="select2-dark" style="width: 100%;">
                              <?php

                              foreach (HelperClass::casteCategory as $value => $caste) {

                              ?>
                                <option value="<?= $value ?>"><?= $caste ?></option>
                              <?php }

                              ?>
                            </select>
                          </div>

                        </div>

                        <div class="row">
                          <div class="form-group col-md-3">
                            <label for="father">Father Name</label>
                            <input type="text" name="father_name" class="form-control" id="father_name" placeholder="Enter father name">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="mother">Mother Name</label>
                            <input type="text" name="mother_name" class="form-control" id="mother_name" placeholder="Enter mother name">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="email">Email address</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter email address">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="mobile">Mobile Number</label>
                            <input type="number" name="mobile" class="form-control" id="mobile" placeholder="Enter mobile number">
                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group col-md-3">
                            <label for="address">Address</label>
                            <input type="text" name="address" class="form-control" id="address" placeholder="Enter address">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="state">Select State</label>
                            <select class="form-control select2 select2-dark" name="state" id="stateIdd" data-dropdown-css-class="select2-dark" style="width: 100%;" onchange="showCity()">
                              <?php
                              if (isset($data['state']) && !empty($data['state'])) {
                                $selectedState = '';
                                foreach ($data['state'] as $state) {
                              ?>
                                  <option value="<?= $state['id'] ?>"><?= $state['stateName'] ?></option>
                              <?php }
                              }
                              ?>
                            </select>
                          </div>
                          <div class="form-group col-md-3">
                            <label for="city">Select City</label>
                            <select class="form-control select2 select2-dark" id="cityData" name="city" data-dropdown-css-class="select2-dark" style="width: 100%;">

                            </select>
                          </div>
                          <div class="form-group col-md-3">
                            <label for="pincode">Pincode</label>
                            <input type="number" name="pincode" class="form-control" id="pincode" placeholder="Enter Pincode">
                          </div>
                        </div>


                        <div class="row">
                          <div class="form-group col-md-3">
                            <label for="dob">Select Date of Birth</label>
                            <input type="date" name="dob" class="form-control " id="dob" placeholder="Enter Date of Birth">
                          </div>
                          <div class="form-group col-md-3">
                            <img src="<?= base_url() ?>assets/uploads/avatar.webp" alt='100x100' id="img" height='100px' width='100px' class='img-fluid' />
                          </div>
                          <div class="form-group col-md-3">
                            <label for="city">Select Image</label>
                            <div class="input-group mt-2">
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" onchange="document.getElementById('img').src = window.URL.createObjectURL(this.files[0])">
                                <label class="custom-file-label" for="img">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group col-md-3">
                            <label for="occupation">Father Occupation</label>
                            <input type="text" name="father_occupation" class="form-control" placeholder="Doctor, Job">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="last_schoool_name">Last School Name</label>
                            <input type="text" name="last_school_name" class="form-control" placeholder="Digitalfied School">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="aadhar_no">Last Class</label>
                            <input type="text" name="last_class" class="form-control" placeholder="5th">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="residence_in_india_since">Registration Fees</label>
                            <input type="textr" name="reg_fee" class="form-control" placeholder="300">
                          </div>
                        </div>
                        <button type="submit" name="submit" class="btn mybtnColor btn-block btn-lg">Save</button>
                      </div>
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
    <!-- </div>
                            </div> -->
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->

    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->
  <script>
    var ajaxUrl = '<?= base_url() . 'ajax/showCityViaStateId' ?>';

    // load default city
    showCity();

    function showCity() {

      $('#cityData option').remove();
      let stateId = $("#stateIdd").val();
      $.ajax({
        url: ajaxUrl,
        method: 'post',
        processData: 'false',
        data: {
          stateId: stateId,
        },
        success: function(response) {
          response = $.parseJSON(response);
          $('#cityData').append(response);
        },
        error: function(error) {
          console.log(error);
        }

      });
    }
  </script>