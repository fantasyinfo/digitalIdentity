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
              if(!empty($this->session->userdata('msg')))
              {?>

              <div class="alert alert-<?=$this->session->userdata('class')?> alert-dismissible fade show" role="alert">
                <?=$this->session->userdata('msg');
                  if($this->session->userdata('class') == 'success')
                  {
                    HelperClass::swalSuccess($this->session->userdata('msg'));
                  }else if($this->session->userdata('class') == 'danger')
                  {
                    HelperClass::swalError($this->session->userdata('msg'));
                  }
                
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php
              $this->session->unset_userdata('class') ;
              $this->session->unset_userdata('msg') ;
              }
              ?>
            <!-- left column -->
            <?php //print_r($data['class']);?>
            <div class="col-md-12 mx-auto">
              <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Add Correct Details</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="addTeacherForm" method="post" enctype="multipart/form-data" action="<?= $data['submitFormUrl'] ?>">

                  <div class="row">
                    <div class="card-body">


                    <div class="col-md-8">
                    <table class="table">
                      <tbody>
                          <tr>
                              <td><label for="name">Full Name</label></td>
                              <td><input type="text" name="name" class="form-control" id="name" placeholder="Enter full name"></td>
                          </tr>
                          <tr>
                              <td> <label for="class">Select Class</label></td>
                              <td><select class="form-control select2 select2-danger" name="class" data-dropdown-css-class="select2-danger" style="width: 100%;">
                          <?php 
                          $selectedClass = '';
                          
                          if(isset($data['class']) && !empty($data['class']))
                          {
                            
                            foreach($data['class'] as $class)
                            {
                              ?>
                                <option value="<?= $class['id'] ?>"><?= $class['className'] ?></option>
                           <?php }
                          }
                          ?>
                        </select></td>
                          </tr>
                          <tr>
                              <td><label for="section">Select Section</label></td>
                              <td> <select class="form-control select2 select2-danger" name="section" data-dropdown-css-class="select2-danger" style="width: 100%;">
                          <?php 
                          if(isset($data['section']) && !empty($data['section']))
                          {
                            $selectedSection = '';
                            foreach($data['section'] as $section)
                            {?>
                                <option  value="<?= $section['id'] ?>"><?= $section['sectionName'] ?></option>
                           <?php }
                          }
                          ?>
                        </select></td>
                          </tr>
                          <tr>
                              <td> <label for="mother">Mother / Wife Name</label></td>
                              <td> <input type="text" name="mother" class="form-control" id="mother" placeholder="Enter mother name"></td>
                          </tr>
                          <tr>
                              <td> <label for="father">Father / Husband Name</label></td>
                              <td> <input type="text" name="father" class="form-control" id="father" placeholder="Enter father name"></td>
                          </tr>
                          <tr>
                              <td> <label for="email">Email address</label></td>
                              <td>  <input type="email" name="email" class="form-control" id="email" placeholder="Enter email address"></td>
                          </tr>
                          <tr>
                              <td> <label for="mobile">Mobile Number</label></td>
                              <td> <input type="number" name="mobile" class="form-control" id="mobile" placeholder="Enter mobile number"></td>
                          </tr>
                          <tr>
                              <td> <label for="education">Education</label></td>
                              <td> <input type="text" name="education" class="form-control" placeholder="Enter Education eg. Bsc Maths"></td>
                          </tr>
                          <tr>
                              <td> <label for="experience">Experience</label></td>
                              <td> <select class="form-control select2 select2-danger" name="experience" data-dropdown-css-class="select2-danger" style="width: 100%;">
                              <?php 
                              
                                foreach(HelperClass::experience as $exp => $val)
                                {?>
                                    <option  value="<?= $exp;?>"><?= $val;?></option>
                              <?php 
                              }
                              
                              ?>
                              </select></td>
                          </tr>
                          <tr>
                              <td> <label for="dob">Select Date of Birth</label></td>
                              <td>  <input type="date" name="dob" class="form-control" id="dob" placeholder="Enter date of birth"></td>
                          </tr>
                          <tr>
                              <td> <label for="dob">Select Date of Joining</label></td>
                              <td>   <input type="date" name="doj" class="form-control " id="doj" placeholder="Enter date of joining"></td>
                          </tr>
                          <tr>
                              <td> <label for="address">Address</label></td>
                              <td>  <input type="text" name="address" class="form-control" id="address" placeholder="Enter address"></td>
                          </tr>
                          <tr>
                              <td>  <label for="state">Select State</label></td>
                              <td><select class="form-control select2 select2-danger" name="state" data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="showCity()" id="stateIdd">
                          <?php 
                          if(isset($data['state']) && !empty($data['state']))
                          {
                            $selectedState = '';
                            foreach($data['state'] as $state)
                            {?>
                                <option value="<?= $state['id'] ?>"><?= $state['stateName'] ?></option>
                           <?php }
                          }
                          ?>
                        </select></td>
                          </tr>
                          <tr>
                              <td><label for="city">Select City</label></td>
                              <td> <select class="form-control select2 select2-danger" name="city" id="cityData" data-dropdown-css-class="select2-danger" style="width: 100%;">
                        </select></td>
                          </tr>
                          <tr>
                              <td> <label for="pincode">Pincode</label></td>
                              <td> <input type="text" name="pincode" class="form-control" id="pincode" placeholder="Enter pincode"></td>
                          </tr>
                          <tr>
                              <td> <label for="city">Select Image</label></td>
                              <td><img src="<?= base_url()?>assets/uploads/avatar.webp" alt='100x100' id="img" height='100px' width='100px' class='img-fluid' />
                              <div class="input-group mt-2">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" name="image" onchange="document.getElementById('img').src = window.URL.createObjectURL(this.files[0])">
                              <label class="custom-file-label" for="img">Choose file</label>
                            </div>
                          </div>
                            </td>
                          </tr>
                          <tr>
                              <td>  <label for="gender">Select Gender</label></td>
                              <td><select class="form-control select2 select2-danger" name="gender" id="gender" data-dropdown-css-class="select2-danger" style="width: 100%;">
                          <?php 

                          $genderArr = [
                            1 => 'Male',
                            2 => 'Female',
                            3 => 'Other'
                          ];
                         
                            foreach($genderArr as $kk => $gg)
                            {?>
                              <option value="<?= $kk ?>"><?= $gg ?></option>
                              <?php }
                             ?>
                          </select></td>
                          </tr>
                          <tr>
                              <td> <label for="cbse_id">Board Id</label></td>
                              <td> <input type="text" name="cbse_id" class="form-control" id="cbse_id" placeholder="Enter cbs id"></td>
                          </tr>
                           <tr>
                              <td>#</td>
                              <td><button type="submit" name="submit" class="btn btn-primary btn-block btn-lg">Submit</button></td>
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

// load default city
  showCity();

  function showCity()
    {

      $('#cityData option').remove();
      let stateId = $("#stateIdd").val();
      $.ajax({
          url: ajaxUrl,
          method: 'post',
          processData: 'false',
          data : {
            stateId : stateId,
          },
          success: function (response)
          {
            response =  $.parseJSON(response);
            $('#cityData').append(response);
          },
          error: function (error)
          {
            console.log(error);
          }

        });
    }

  </script>