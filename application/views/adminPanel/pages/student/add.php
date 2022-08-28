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
                <?=$this->session->userdata('msg')?>
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
            <div class="col-md-10 mx-auto">
              <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Add Correct Details</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="addStudentForm" method="post" enctype="multipart/form-data" action="<?= $data['submitFormUrl'] ?>">

                  <div class="row">
                    <div class="card-body">
                      <div class="row">
                        <div class="form-group col-md-3">
                          <label for="name">Name</label>
                          <input type="text" name="name" class="form-control" id="name" placeholder="Enter name">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="class">Select Class</label>
                          <select class="form-control select2 select2-danger" name="class" data-dropdown-css-class="select2-danger" style="width: 100%;">
                          <?php 
                          if(isset($data['class']) && !empty($data['class']))
                          {
                            
                            foreach($data['class'] as $class)
                            {?>
                                <option value="<?= $class['id'] ?>"><?= $class['className'] ?></option>
                           <?php }
                          }
                          ?>
                        </select>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="section">Select Section</label>
                          <select class="form-control select2 select2-danger" name="section" data-dropdown-css-class="select2-danger" style="width: 100%;">
                          <?php 
                          if(isset($data['section']) && !empty($data['section']))
                          {
                            
                            foreach($data['section'] as $section)
                            {?>
                                <option value="<?= $section['id'] ?>"><?= $section['sectionName'] ?></option>
                           <?php }
                          }
                          ?>
                        </select>
                        </div>
                          <div class="form-group col-md-3">
                          <label for="mother">Mother Name</label>
                          <input type="text" name="mother" class="form-control" id="mother" placeholder="Enter mother name">
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="form-group col-md-3">
                          <label for="father">Father Name</label>
                          <input type="text" name="father" class="form-control" id="father" placeholder="Enter father name">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="email">Email address</label>
                          <input type="email" name="email" class="form-control" id="email" placeholder="Enter email">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="mobile">Mobile Number</label>
                          <input type="number" name="mobile" class="form-control" id="mobile" placeholder="Enter mobile number">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="dob">Select Date of Birth</label>
                          <input type="date" name="dob" class="form-control" id="dob">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" class="form-control" id="address" placeholder="Enter address">
                          </div>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="state">Select State</label>
                          <select class="form-control select2 select2-danger" name="state" data-dropdown-css-class="select2-danger" style="width: 100%;">
                          <?php 
                          if(isset($data['state']) && !empty($data['state']))
                          {
                            
                            foreach($data['state'] as $state)
                            {?>
                                <option value="<?= $state['id'] ?>"><?= $state['stateName'] ?></option>
                           <?php }
                          }
                          ?>
                        </select>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="city">Select City</label>
                          <select class="form-control select2 select2-danger" name="city" data-dropdown-css-class="select2-danger" style="width: 100%;">
                          <?php 
                          if(isset($data['city']) && !empty($data['city']))
                          {
                            
                            foreach($data['city'] as $city)
                            {?>
                                <option value="<?= $city['id'] ?>"><?= $city['cityName'] ?></option>
                           <?php }
                          }
                          ?>
                        </select>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                            <label for="pincode">Pincode</label>
                            <input type="text" name="pincode" class="form-control" id="pincode" placeholder="Enter pincode">
                          </div>
                        </div>
                      </div>
                  
                      <div class="row">
                       
                        <div class="form-group col-md-2">
                          <label for="city">Image Preview</label><br>
                          <img src="<?= base_url()?>assets/uploads/avatar.webp" alt='100x100' id="img" height='100px' width='100px' class='img-fluid' />
                        </div>
                        <div class="form-group col-md-3">
                        <label for="city">Select Image</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" name="image" id="exampleInputFile" onchange="document.getElementById('img').src = window.URL.createObjectURL(this.files[0])">
                              <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            </div>
                          </div>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="roll_no">Roll Number</label>
                          <input type="text" name="roll_no" class="form-control" id="roll_no">
                        </div>
                        <div class="form-group col-md-3">
                        <label for="gender">Select Gender</label>
                          <select class="form-control select2 select2-danger" name="gender" id="gender" data-dropdown-css-class="select2-danger" style="width: 100%;">
                          <?php 

                          $genderArr = [
                            1 => 'Male',
                            2 => 'FeMale',
                            3 => 'Other'
                          ];
                         
                            foreach($genderArr as $kk => $gg)
                            { ?>
                              <option value="<?= $kk ?>"><?= $gg ?></option>
                              <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <!-- /.card-body -->
                  </div>

                  <div class="card-footer">
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
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
  <!-- ./wrapper -->
  <script>
    var ajaxUrl = '<?= base_url() . 'ajax/listStudentsAjax' ?>';
  </script>