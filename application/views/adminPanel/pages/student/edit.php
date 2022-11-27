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
      $sd = $data['studentData'][0];
      // print_r($sd);
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
          <div class="row">
            <!-- left column -->
            <div class="col-md-12 mx-auto">
              <!-- jquery validation -->
              <div class="card border-top-3">
                <div class="card-header">
                  <h3 class="card-title">All * Field Are Mandatory</h3>
                </div>

                <form id="addStudentForm" method="post" enctype="multipart/form-data" action="<?= $data['submitFormUrl'] ?>">
                  <input type="hidden" name="stuId" class="form-control" id="name" value="<?= $sd['id']; ?>">
                  <input type="hidden" name="user_id" class="form-control" id="name" value="<?= $sd['user_id']; ?>">
                  <?php
                  if (isset($sd['image'])) {
                    $bExt = $dir = base_url() . HelperClass::uploadImgDir;
                    $imgDD = explode($bExt, $sd['image']);
                    //print_r($imgDD);
                  ?>
                    <input type="hidden" name="image" class="form-control" id="name" value="<?= $imgDD[1]; ?>">

                  <?php } ?>

                  <div class="row">
                    <div class="card-body">


                      <div class="col-md-12">
                        <div class="row">
                          <div class="form-group col-md-3">
                            <label for="admission_no">Admission Number</label>
                            <input type="text" name="admission_no" class="form-control" id="admission_no" placeholder="Admission No" value="<?= $sd['admission_no']; ?>">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="date_of_admission">Date of Admission</label>
                            <input type="text" name="date_of_admission" class="form-control datePicker" id="date_of_admission" placeholder="Date of Admission" value="<?= $sd['date_of_admission']; ?>">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="name">Full Name</label>
                            <input type="text" name="name" class="form-control" id="name" value="<?= $sd['name']; ?>">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="gender">Select Gender</label>
                            <select class="form-control select2 select2-danger" name="gender" id="gender" data-dropdown-css-class="select2-danger" style="width: 100%;">
                              <?php

                              $genderArr = [
                                1 => 'Male',
                                2 => 'Female',
                                3 => 'Other'
                              ];
                              if (isset($sd['gender']) && !empty($sd['gender'])) {
                                $selectedGender = '';
                                foreach ($genderArr as $kk => $gg) {
                                  if ($kk == $sd['gender']) {
                                    $selectedGender = 'selected';
                                  } else {
                                    $selectedGender = '';
                                  }

                              ?>
                                  <option <?= $selectedGender ?> value="<?= $kk ?>"><?= $gg ?></option>
                              <?php }
                              } ?>
                            </select>
                          </div>




                        </div>

                        <div class="row">
                          <div class="form-group col-md-3">
                            <label for="class">Select Class</label>
                            <select class="form-control select2 select2-dark" name="class" data-dropdown-css-class="select2-dark" style="width: 100%;">
                              <?php
                              $selectedClass = '';

                              if (isset($data['class']) && !empty($data['class'])) {

                                foreach ($data['class'] as $class) {

                                  if ($class['id'] == $sd['class_id']) {
                                    $selectedClass = 'selected';
                                  } else {
                                    $selectedClass = '';
                                  }

                              ?>
                                  <option <?= $selectedClass ?> value="<?= $class['id'] ?>"><?= $class['className'] ?></option>
                              <?php }
                              }
                              ?>
                            </select>
                          </div>
                          <div class="form-group col-md-3">
                            <label for="section">Select Section</label>
                            <select class="form-control select2 select2-dark" name="section" data-dropdown-css-class="select2-dark" style="width: 100%;">
                              <?php
                              if (isset($data['section']) && !empty($data['section'])) {
                                $selectedSection = '';
                                foreach ($data['section'] as $section) {
                                  if ($section['id'] == $sd['section_id']) {
                                    $selectedSection = 'selected';
                                  } else {
                                    $selectedSection = '';
                                  }

                              ?>
                                  <option <?= $selectedSection ?> value="<?= $section['id'] ?>"><?= $section['sectionName'] ?></option>
                              <?php }
                              }
                              ?>
                            </select>
                          </div>
                          <div class="form-group col-md-3">
                            <label for="roll_no">Roll Number</label>
                            <input type="text" name="roll_no" class="form-control" id="roll_no" value="<?= $sd['roll_no']; ?>">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="category">Select Category</label>
                            <select class="form-control select2 select2-danger" name="cast_category" data-dropdown-css-class="select2-danger" style="width: 100%;">
                              <?php
                              $selectedCategory = '';
                              foreach (HelperClass::casteCategory as $value => $caste) {
                                if (isset($sd['cast_category']) && $sd['cast_category'] == $value) {
                                  $selectedCategory = 'selected';
                                } else {
                                  $selectedCategory = '';
                                }
                              ?>
                                <option <?= $selectedCategory ?> value="<?= $value ?>"><?= $caste ?></option>
                              <?php }

                              ?>
                            </select>
                          </div>

                        </div>

                        <div class="row">
                          <div class="form-group col-md-3">
                            <label for="father">Father Name</label>
                            <input type="text" name="father" class="form-control" id="father" value="<?= $sd['father_name']; ?>">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="mother">Mother Name</label>
                            <input type="text" name="mother" class="form-control" id="mother" value="<?= $sd['mother_name']; ?>">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="email">Email address</label>
                            <input type="email" name="email" class="form-control" value="<?= $sd['email']; ?>">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="mobile">Mobile Number</label>
                            <input type="number" name="mobile" class="form-control" id="mobile" value="<?= $sd['mobile']; ?>">
                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group col-md-3">
                            <label for="address">Address</label>
                            <input type="text" name="address" class="form-control" id="address" value="<?= $sd['address']; ?>">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="state">Select State</label>
                            <select class="form-control select2 select2-danger" name="state" data-dropdown-css-class="select2-danger" style="width: 100%;" id="stateIdd" onchange="showCity()">
                              <?php
                              if (isset($data['state']) && !empty($data['state'])) {
                                $selectedState = '';
                                foreach ($data['state'] as $state) {

                                  if ($state['id'] == $sd['state_id']) {
                                    $selectedState = 'selected';
                                  } else {
                                    $selectedState = '';
                                  }

                              ?>
                                  <option <?= $selectedState ?> value="<?= $state['id'] ?>"><?= $state['stateName'] ?></option>
                              <?php }
                              }
                              ?>
                            </select>
                          </div>
                          <div class="form-group col-md-3">
                            <label for="city">Select City</label>
                            <select class="form-control select2 select2-danger" id="cityData" name="city" data-dropdown-css-class="select2-danger" style="width: 100%;">
                            </select>
                          </div>
                          <div class="form-group col-md-3">
                            <label for="pincode">Pincode</label>
                            <input type="number" name="pincode" class="form-control" id="pincode" value="<?= $sd['pincode']; ?>">
                          </div>
                        </div>


                        <div class="row">
                          <div class="form-group col-md-3">
                            <label for="dob">Select Date of Birth</label>
                            <input type="text" name="dob" class="form-control datePicker" id="dob" value="<?= $sd['dob']; ?>">
                          </div>
                          <div class="form-group col-md-3">
                            <img src="<?= $sd['image'] ?>" alt='100x100' id="img" height='100px' width='100px' class='img-fluid' />
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
                          <div class="form-group col-md-3">
                            <label for="sr_number">SR Number</label>
                            <input type="text" name="sr_number" class="form-control" id="sr_number" value="<?= $sd['sr_number']; ?>">
                          </div>

                        </div>
                        
                        <div class="row">
                          <div class="form-group col-md-3">
                            <label for="occupation">Father Occupation</label>
                            <input type="text" name="occupation" class="form-control"   value="<?= $sd['occupation']; ?>">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="last_schoool_name">Last School Name</label>
                            <input type="text" name="last_schoool_name" class="form-control"  value="<?= $sd['last_schoool_name']; ?>">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="aadhar_no">Aadhar Card No</label>
                            <input type="text" name="aadhar_no" class="form-control"  value="<?= $sd['aadhar_no']; ?>">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="residence_in_india_since">Residence in India Since</label>
                            <input type="textr" name="residence_in_india_since" class="form-control"  value="<?= $sd['residence_in_india_since']; ?>">
                          </div>
                        </div>
                        <button type="submit" name="submit" class="btn mybtnColor btn-block btn-lg">Save</button>
                      </div>
                    </div>
                  </div>
                  <!-- /.card-body -->
              </div>
              </form>


              <!-- /.card-header -->
              <!-- form start -->


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
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->
  <script>
    var ajaxUrl = '<?= base_url() . 'ajax/showCityViaStateId' ?>';

    // load default city
    let alreadyCityId = '<?= $sd['city_id']; ?>';
    showCity(alreadyCityId);

    function showCity(alreadyCityId = '') {
      console.log(alreadyCityId);
      $('#cityData option').remove();
      let stateId = $("#stateIdd").val();
      $.ajax({
        url: ajaxUrl,
        method: 'post',
        processData: 'false',
        data: {
          stateId: stateId,
          alreadyCityId: alreadyCityId
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