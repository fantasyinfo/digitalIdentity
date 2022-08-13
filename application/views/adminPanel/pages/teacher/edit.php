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
            <div class="col-md-10 mx-auto">
              <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">All * Field Are Mandatory</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="addStudentForm" method="post" enctype="multipart/form-data" action="<?= $data['submitFormUrl'] ?>">
                <input type="hidden" name="tecId" class="form-control" id="name" value="<?=$sd['id'];?>">
                <input type="hidden" name="user_id" class="form-control" id="name" value="<?=$sd['user_id'];?>">
                <?php 
                if(isset($sd['image']))
                {
                  $bExt = $dir = base_url().HelperClass::uploadImgDir;
                  $imgDD = explode($bExt,$sd['image']);
                  //print_r($imgDD);
                ?>
                <input type="hidden" name="image" class="form-control" id="name" value="<?=$imgDD[1];?>">
                
               <?php }?>
                
                  <div class="row">
                    <div class="card-body">
                      <div class="row">
                        <div class="form-group col-md-2">
                          <label for="name">Name</label>
                          <input type="text" name="name" class="form-control" id="name" value="<?=$sd['name'];?>">
                        </div>
                        <div class="form-group col-md-2">
                          <label for="class">Select Class</label>
                          <select class="form-control select2 select2-danger" name="class" data-dropdown-css-class="select2-danger" style="width: 100%;">
                          <?php 
                          $selectedClass = '';
                          
                          if(isset($data['class']) && !empty($data['class']))
                          {
                            
                            foreach($data['class'] as $class)
                            {

                              if($class['id'] == $sd['class_id'])
                              {
                                $selectedClass = 'selected';
                              }else
                              {
                                $selectedClass= '';
                              }
                              
                              ?>
                                <option <?=$selectedClass?> value="<?= $class['id'] ?>"><?= $class['className'] ?></option>
                           <?php }
                          }
                          ?>
                        </select>
                        </div>
                        <div class="form-group col-md-2">
                          <label for="section">Select Section</label>
                          <select class="form-control select2 select2-danger" name="section" data-dropdown-css-class="select2-danger" style="width: 100%;">
                          <?php 
                          if(isset($data['section']) && !empty($data['section']))
                          {
                            $selectedSection = '';
                            foreach($data['section'] as $section)
                            {
                              if($section['id'] == $sd['section_id'])
                              {
                                $selectedSection = 'selected';
                              }else
                              {
                                $selectedSection= '';
                              }
                              
                              ?>
                                <option <?=$selectedSection?> value="<?= $section['id'] ?>"><?= $section['sectionName'] ?></option>
                           <?php }
                          }
                          ?>
                        </select>
                        </div>
                          <div class="form-group col-md-2">
                          <label for="mother">Mother Name</label>
                          <input type="text" name="mother" class="form-control" id="mother" value="<?=$sd['mother_name'];?>">
                        </div>
                        <div class="form-group col-md-2">
                          <label for="father">Father Name</label>
                          <input type="text" name="father" class="form-control" id="father" value="<?=$sd['father_name'];?>">
                        </div>
                      </div>
                      
                      <div class="row">
                      <div class="form-group col-md-3">
                          <label for="doj">Select Date of Joining</label>
                          <input type="date" name="doj" class="form-control" id="doj" value="<?=$sd['doj'];?>">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="email">Email address</label>
                          <input type="email" name="email" class="form-control" id="email" value="<?=$sd['email'];?>">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="mobile">Mobile Number</label>
                          <input type="number" name="mobile" class="form-control" id="mobile" value="<?=$sd['mobile'];?>">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="dob">Select Date of Birth</label>
                          <input type="date" name="dob" class="form-control" id="dob" value="<?=$sd['dob'];?>">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" class="form-control" id="address" value="<?=$sd['address'];?>">
                          </div>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="state">Select State</label>
                          <select class="form-control select2 select2-danger" name="state" data-dropdown-css-class="select2-danger" style="width: 100%;">
                          <?php 
                          if(isset($data['state']) && !empty($data['state']))
                          {
                            $selectedState = '';
                            foreach($data['state'] as $state)
                            {
                              
                              if($state['id'] == $sd['state_id'])
                              {
                                $selectedState = 'selected';
                              }else
                              {
                                $selectedState= '';
                              }
                              
                              ?>
                                <option <?=$selectedState?> value="<?= $state['id'] ?>"><?= $state['stateName'] ?></option>
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
                            $selectedCity = '';
                            foreach($data['city'] as $city)
                            {
                              if($city['id'] == $sd['city_id'])
                              {
                                $selectedCity = 'selected';
                              }else
                              {
                                $selectedCity= '';
                              }
                              
                              ?>
                                <option <?=$selectedCity?> value="<?= $city['id'] ?>"><?= $city['cityName'] ?></option>
                           <?php }
                          }
                          ?>
                        </select>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                            <label for="pincode">Pincode</label>
                            <input type="text" name="pincode" class="form-control" id="pincode" value="<?=$sd['pincode'];?>">
                          </div>
                        </div>
                      </div>
                      
                      <div class="row">
                      <div class="form-group col-md-2">
                      <label for="city">Image Preview</label><br>
                      <img src="<?= $sd['image'] ?>" alt='100x100' id="img" height='100px' width='100px' class='img-fluid' />
                      </div>
                      <div class="form-group col-md-3">
                        <label for="city">Select Image</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" name="image" onchange="document.getElementById('img').src = window.URL.createObjectURL(this.files[0])">
                              <label class="custom-file-label" for="img">Choose file</label>
                            </div>
                          </div>
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
                          if(isset($sd['gender']) && !empty($sd['gender']))
                          {
                            $selectedGender = '';
                            foreach($genderArr as $kk => $gg)
                            {
                              if($kk == $sd['gender'])
                              {
                                $selectedGender = 'selected';
                              }else
                              {
                                $selectedGender= '';
                              }
                              
                              ?>
                              <option <?=$selectedGender?> value="<?= $kk ?>"><?= $gg ?></option>
                              <?php }
                             }?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <!-- /.card-body -->
                  </div>

                  <div class="card-footer">
                    <button type="submit" name="submit" class="btn btn-primary">Update</button>
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