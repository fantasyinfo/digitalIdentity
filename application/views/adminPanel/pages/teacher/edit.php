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
      $sd = $data['teacherData'][0];
      // print_r($sd);
      ?>
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">
                <?= $data['pageTitle'] ?>
              </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">
                  <?= $data['pageTitle'] ?>
                </li>
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
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">All * Field Are Mandatory</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="addStudentForm" method="post" enctype="multipart/form-data"
                  action="<?= $data['submitFormUrl'] ?>">
                  <input type="hidden" name="tecId" class="form-control" id="name" value="<?= $sd['id']; ?>">
                  <input type="hidden" name="user_id" class="form-control" id="name" value="<?= $sd['user_id']; ?>">
                  <?php
                  if (isset($sd['image'])) {
                    $bExt = $dir = base_url() . HelperClass::teacherImagePath;
                    $imgDD = explode($bExt, $sd['image']);
                    //print_r($imgDD);
                    ?>
                    <input type="hidden" name="image" class="form-control" id="name" value="<?= $imgDD[1]; ?>">

                  <?php } ?>

                  <div class="row">
                    <div class="card-body">


                      <div class="col-md-8">
                        <table class="table">
                          <tbody>
                            <tr>
                              <td><label for="name">Full Name</label></td>
                              <td><input type="text" name="name" class="form-control" id="name"
                                  value="<?= $sd['name']; ?>"></td>
                            </tr>
                            <tr>
                              <td> <label for="class">Select Class</label></td>
                              <td><select class="form-control select2 select2-danger" name="class"
                                  data-dropdown-css-class="select2-danger" style="width: 100%;"
                                  onchange="loadSections()" id='classIdD'>
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
                                      <option <?= $selectedClass ?> value="<?= $class['id'] ?>"><?= $class['className'] ?>
                                      </option>
                                    <?php }
                                  }
                                  ?>
                                </select></td>
                            </tr>
                            <tr>
                              <td><label for="section">Select Section</label></td>
                              <td> <select class="form-control select2 select2-danger" name="section"
                                  data-dropdown-css-class="select2-danger" style="width: 100%;" id='sectionData'>
                                  <?php
                                  // if(isset($data['section']) && !empty($data['section']))
                                  // {
                                  //   $selectedSection = '';
                                  //   foreach($data['section'] as $section)
                                  //   {
                                  //     if($section['id'] == $sd['section_id'])
                                  //     {
                                  //       $selectedSection = 'selected';
                                  //     }else
                                  //     {
                                  //       $selectedSection= '';
                                  //     }
                                  
                                  ?>
                                  <!-- <option <?= $selectedSection ?> value="<?= $section['id'] ?>"><?= $section['sectionName'] ?></option> -->
                                  <?php
                                  //   }
                                  // }
                                  ?>
                                </select></td>
                            </tr>
                            <tr>
                              <td> <label for="mother">Mother / Wife Name</label></td>
                              <td> <input type="text" name="mother" class="form-control" id="mother"
                                  value="<?= $sd['mother_name']; ?>"></td>
                            </tr>
                            <tr>
                              <td> <label for="father">Father / Husband Name</label></td>
                              <td> <input type="text" name="father" class="form-control" id="father"
                                  value="<?= $sd['father_name']; ?>"></td>
                            </tr>
                            <tr>
                              <td> <label for="email">Email address</label></td>
                              <td> <input type="email" name="email" class="form-control" id="email"
                                  value="<?= $sd['email']; ?>"></td>
                            </tr>
                            <tr>
                              <td> <label for="mobile">Mobile Number</label></td>
                              <td> <input type="number" name="mobile" class="form-control" id="mobile"
                                  value="<?= $sd['mobile']; ?>"></td>
                            </tr>
                            <tr>
                              <td> <label for="education">Education</label></td>
                              <td> <input type="text" name="education" class="form-control"
                                  value="<?= $sd['education']; ?>"></td>
                            </tr>
                            <tr>
                              <td> <label for="experience">Experience</label></td>
                              <td> <select class="form-control select2 select2-danger" name="experience"
                                  data-dropdown-css-class="select2-danger" style="width: 100%;">
                                  <?php


                                  $selectedExperience = '';
                                  foreach (HelperClass::experience as $exp => $val) {
                                    if (isset($sd['experience']) && !empty($sd['experience'])) {
                                      if ($sd['experience'] == $exp) {
                                        $selectedExperience = 'selected';
                                      } else {
                                        $selectedExperience = '';
                                      }
                                    }
                                    ?>
                                    <option <?= $selectedExperience; ?> value="<?= $exp; ?>"><?= $val; ?></option>
                                  <?php

                                  }
                                  ?>
                                </select></td>
                            </tr>
                            <tr>
                              <td> <label for="dob">Select Date of Birth</label></td>
                              <td> <input type="text" name="dob" class="form-control datepicker" id="dob"
                                  value="<?= $sd['dob']; ?>"></td>
                            </tr>
                            <tr>
                              <td> <label for="dob">Select Date of Joining</label></td>
                              <td> <input type="text" name="doj" class="form-control  datepicker" id="doj"
                                  value="<?= $sd['doj']; ?>"></td>
                            </tr>
                            <tr>
                              <td> <label for="address">Address</label></td>
                              <td> <input type="text" name="address" class="form-control" id="address"
                                  value="<?= $sd['address']; ?>"></td>
                            </tr>
                            <tr>
                              <td> <label for="state">Select State</label></td>
                              <td><select class="form-control select2 select2-danger" name="state"
                                  data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="showCity()"
                                  id="stateIdd">
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
                                      <option <?= $selectedState ?> value="<?= $state['id'] ?>"><?= $state['stateName'] ?>
                                      </option>
                                    <?php }
                                  }
                                  ?>
                                </select></td>
                            </tr>
                            <tr>
                              <td><label for="city">Select City</label></td>
                              <td> <select class="form-control select2 select2-danger" id="cityData" name="city"
                                  data-dropdown-css-class="select2-danger" style="width: 100%;">
                                  <?php
                                  if (isset($data['city']) && !empty($data['city'])) {
                                    $selectedCity = '';
                                    foreach ($data['city'] as $city) {
                                      if ($city['id'] == $sd['city_id']) {
                                        $selectedCity = 'selected';
                                      } else {
                                        $selectedCity = '';
                                      }

                                      ?>
                                      <option <?= $selectedCity ?> value="<?= $city['id'] ?>"><?= $city['cityName'] ?>
                                      </option>
                                    <?php }
                                  }
                                  ?>
                                </select></td>
                            </tr>
                            <tr>
                              <td> <label for="pincode">Pincode</label></td>
                              <td> <input type="text" name="pincode" class="form-control" id="pincode"
                                  value="<?= $sd['pincode']; ?>"></td>
                            </tr>
                            <tr>
                              <td> <label for="city">Select Image</label></td>
                              <td><img src="<?= $sd['image'] ?>" alt='100x100' id="img" height='100px' width='100px'
                                  class='img-fluid' />
                                <div class="input-group mt-2">
                                  <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="image"
                                      onchange="document.getElementById('img').src = window.URL.createObjectURL(this.files[0])">
                                    <label class="custom-file-label" for="img">Choose file</label>
                                  </div>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td> <label for="gender">Select Gender</label></td>
                              <td><select class="form-control select2 select2-danger" name="gender" id="gender"
                                  data-dropdown-css-class="select2-danger" style="width: 100%;">
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
                                </select></td>
                            </tr>
                            <tr>
                              <td> <label for="cbse_id">Board Id</label></td>
                              <td> <input type="text" name="cbse_id" class="form-control" id="cbse_id"
                                  value="<?= $sd['cbse_id']; ?>"></td>
                            </tr>
                            <tr>
                              <td>#</td>
                              <td><button type="submit" name="submit"
                                  class="btn btn-primary btn-block btn-lg">Update</button></td>
                            </tr>
                            <!-- <tr>
                              <td>3</td>
                              <td>John</td>
                          </tr> -->
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
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->
  <script>
    var ajaxUrl = '<?= base_url() . 'ajax/showCityViaStateId' ?>';
    var ajaxUrl1 = '<?= base_url() . 'ajax/showSectionViaClassId' ?>';

    // load default city
    let alreadyCityId = '<?= $sd['city_id']; ?>';
    let alreadySectionId = '<?= $sd['section_id']; ?>';


    showCity(alreadyCityId);
    loadSections(alreadySectionId);



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
        success: function (response) {
          response = $.parseJSON(response);
          $('#cityData').append(response);
        },
        error: function (error) {
          console.log(error);
        }

      });
    }


    function loadSections(alreadySectionId = '') {
      console.log('loading sections')
      $('#sectionData option').remove();
      let classId = $("#classIdD").val();
      $.ajax({
        url: ajaxUrl1,
        method: 'post',
        processData: 'false',
        data: {
          classId: classId,
          alreadySectionId: alreadySectionId
        },
        success: function (response) {
          console.log(response);
          response = $.parseJSON(response);
          $('#sectionData').append(response);
        },
        error: function (error) {
          console.log(error);
        }

      });
    }

  </script>