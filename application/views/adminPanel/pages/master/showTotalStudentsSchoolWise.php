<?php

(HelperClass::checkIfItsACEOAccount()) ?: redirect(base_url());


// HelperClass::sendEmail('gs27349gs@gmail.com', 'User Login Details', 'Hey, You have sucefully login now!!');
?>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');

    // fetching city data
    $registerdSchoolData = $this->db->query("SELECT *  FROM " . Table::schoolMasterTable . " 
    WHERE status != '4'")->result_array();

    //  print_r($registerdSchoolData);




    ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <?php
              if (!empty($this->session->userdata('msg'))) { 
                
                if($this->session->userdata('class') == 'success')
                 {
                   HelperClass::swalSuccess($this->session->userdata('msg'));
                 }else if($this->session->userdata('class') == 'danger')
                 {
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
                  <h3 class="card-title">School Total Students</h3>

                </div>

              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Showing All Students School Wise</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="hourDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>School Id</th>
                            <th>School Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Address</th>
                            <th>Classes Up To</th>
                            <th>Total Students</th>
                            <!-- <th>Status</th>
                            <th>Action</th> -->
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($registerdSchoolData)) {
                            $i = 0;
                            foreach ($registerdSchoolData as $cn) { 
                              $totalStudents = @$this->db->query("SELECT count(1) as c FROM ".Table::studentTable." WHERE status NOT IN ('3','4') AND schoolUniqueCode = '{$cn['unique_id']}' ")->result_array()[0]['c'];
                              
                              ?>
                              <tr>
                                <td><?= ++$i; ?></td>
                                <td><?= $cn['unique_id']; ?></td>
                                <td><?= $cn['school_name']; ?></td>
                                <td><?= $cn['email']; ?></td>
                                <td><?= $cn['mobile']; ?></td>
                                <td><?= $cn['address']; ?></td>
                                <td><?= $cn['classes_up_to']; ?></td>
                                <td><?= $totalStudents; ?></td>
                    
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
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->
  <script>
    $("#hourDataTable").DataTable();
  </script>