<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php"); ?>



    <?php

    $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
    $sectionData = $this->db->query("SELECT * FROM " . Table::sectionTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();


    if(isset($_POST['submit']))
    {
      $p = [
        'classId' => $_POST['classId'],
        'sectionId' => $_POST['sectionId']
      ];
    
      $studentsData = $this->db->query("SELECT s.id,s.name,s.user_id,s.father_name,cl.className,se.sectionName FROM " . Table::studentTable . " s
      JOIN ".Table::classTable." cl ON cl.id = s.class_id
      JOIN ".Table::sectionTable." se ON se.id = s.section_id
      WHERE s.status = '1' AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND s.class_id = '{$_POST['classId']}' AND s.section_id = '{$_POST['sectionId']}' ")->result_array();
    }

    ?>







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
          <?php
          if (!empty($this->session->userdata('msg'))) {
            if ($this->session->userdata('class') == 'success') {
              HelperClass::swalSuccess($this->session->userdata('msg'));
            } else if ($this->session->userdata('class') == 'danger') {
              HelperClass::swalError($this->session->userdata('msg'));
            }


          ?>

            <div class="alert alert-<?= $this->session->userdata('class') ?> alert-dismissible fade show" role="alert">
              <strong>New Message!</strong> <?= $this->session->userdata('msg') ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
            $this->session->unset_userdata('class');
            $this->session->unset_userdata('msg');
          }
          ?>
          <form method="post" action="">
            <div class="row">
              <div class="col-md-12"> 
              <div class="card border-top-3">
                  <div class="card-header">Please Select Correct Details</div>
                  <div class="card-body">
                    <div class="row">
                    <div class="form-group col-md-3">
                      <label>Select Class </label>
                      <select name="classId" id="classId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                        <option>Please Select Class</option>
                        <?php
                        if (isset($classData)) {
                          foreach ($classData as $class) {
                        ?>
                            <option value="<?= $class['id'] ?>"><?= $class['className'] ?></option>
                        <?php }
                        }

                        ?>

                      </select>
                    </div>
                    <div class="form-group col-md-3">
                      <label>Select Section </label>
                      <select name="sectionId" id="sectionId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                        <option>Please Select Section</option>
                        <?php
                        if (isset($sectionData)) {
                          foreach ($sectionData as $section) {
                        ?>
                            <option value="<?= $section['id'] ?>"><?= $section['sectionName'] ?></option>
                        <?php }
                        }

                        ?>
                      </select>
                    </div>
                    <div class="col-md-4 margin-top-30">
                    <input type="submit" name="submit" class="btn btn-block mybtnColor" value="Filter">
                    </div>
                    </div>
                    
                    
                  </div>
                </div>
              </div>
           
     
            </div>

          </form>


          <div class="row">
          <div class="col-md-12">
                  <div class="card border-top-3">
                    <div class="card-header">
                      <h3 class="card-title">Showing All Students Data</h3>
                   
                    </div>
                    <!-- /.card-header -->
                  
                    <div class="card-body">
                      <div class="table-responsive">

                     
                      <table id="MonthDataTable" class="table align-middle mb-0 bg-white ">
                        <thead class="bg-light">
                          <tr>
                            <th>User Id</th>
                            <th>Student Name</th>
                            <th>Class - Section</th>
                            <th>Father Name</th>
                            <th>Collect Fees</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset( $studentsData)) {
                            $i = 0;
                            foreach ($studentsData as $cn) { ?>
                           
                              <tr>
                              <td> <?=$cn['user_id']?> </td>
                              <td> <?=$cn['name']?> </td>
                              <td> <?=$cn['className'] .' - '. $cn['sectionName'];?> </td>
                              <td><?=$cn['father_name'];?></td>
                              <td><a class="btn btn-dark" href="collectStudentFee?stu_id=<?=$cn['id']?>">Collect Fees</a></td>
                              </tr>
                           
                          <?php  }
                          } ?>

                        </tbody>

                      </table>
                      </div>
                      <hr>
                     
                    </div>
                
                    <!-- /.card-body -->
                  </div>
                </div>
          </div>
        </div>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <!-- Control Sidebar -->

      <!-- /.control-sidebar -->
    </div>
    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>

  <script>
    $("#MonthDataTable").DataTable();
  </script>