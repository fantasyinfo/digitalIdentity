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




      $schoolLogo = base_url() . HelperClass::schoolLogoImagePath;
      $studentImage = base_url() . HelperClass::studentImagePath;


      $sd = $data['studentData'][0];
      //print_r($sd);
      $string = HelperClass::fullPathQR . $sd['user_id'];


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
          <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item active" role="presentation">
              <a href="#profile" class="nav-link active" aria-selected="true" role="tab" data-toggle="tab">Profile</a>
            </li>
            <li class="nav-item" role="presentation">
              <a href="#login_details" class="nav-link" aria-selected="false" role="tab" data-toggle="tab">Login Details</a>
            </li>
            <li class="nav-item" role="presentation">
              <a href="<?= base_url('feesManagement/collectStudentFee?stu_id=')  . $sd['id'] ?>" class="nav-link" aria-selected="false" target="_blank">Fees</a>
            </li>
            <li class="nav-item" role="presentation">
              <a href="#history" class="nav-link" aria-selected="false" role="tab" data-toggle="tab">TimeLine</a>
            </li>

          </ul>


          <div class="container-fluid">
            <div class="tab-content">
              <div id="profile" role="tabpanel" class="tab-pane fade in active show">
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <div class="card border-top-3">
                      <div class="card-header">
                        <h3 class="card-title">Profile Image</h3>
                      </div>
                      <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                          <img src="<?= $sd['image']; ?>" alt="Student Image" class="img-responsive border" width="150" height="150">
                          <div class="mt-3">
                            <h4><?= $sd['name']; ?></h4>
                            <p class="text-secondary mb-1"><?= $sd['className'] . " - " . $sd['sectionName']; ?></p>
                            <span class="badge badge-success"><?= $sd['user_id']; ?></span>

                          </div>
                          <a href="<?= $string ?>" class="btn btn-info text-white my-2" target="_blank">User Profile View</a>
                        </div>
                      </div>
                    </div>
                    <div class="card mt-3 border-top-3">
                      <div class="card-header">Student QR </div>
                      <div class="card-body">
                        <?php

                        $google_chart_api_url = "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=" . $string . "&choe=UTF-8";
                        // let's display the generated QR code
                        echo "<img class='img-responsive' src='" . $google_chart_api_url . "' alt='" . $string . "'>";
                        ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 mb-3">
                    <div class="card border-top-3 mb-3">
                      <div class="card-header">
                        <h3 class="card-title">Personal Details</h3>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Full Name</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <?= $sd['name']; ?>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Email</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <?= $sd['email']; ?>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Gender</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <?php
                            if ($sd['gender'] == 1) {
                              echo 'Male';
                            } elseif ($sd['gender'] == 2) {
                              echo 'Female';
                            } else {
                              echo 'Other';
                            }
                            ?>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Roll No</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <?= $sd['roll_no']; ?>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Phone</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <?= $sd['mobile']; ?>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Mother Name</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <?= $sd['mother_name']; ?>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Father Name</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <?= $sd['father_name']; ?>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Address</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <?= $sd['address'] . " - " . $sd['cityName'] . " - " . $sd['stateName'] . " - " . $sd['pincode'] . " - India"; ?>
                          </div>
                        </div>
                        <hr>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="login_details" role="tabpanel" class="tab-pane fade">
                <div class="row">
                  <div class="col-md-12 mb-3">
                    <div class="card h-100">
                      <div class="card-header border-top-3">
                        <h6 class="d-flex align-items-center mb-3">Login Details</h6>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0" style="font-family:'Times New Roman', Times, serif">Mobile Number</h6>
                          </div>
                          <div class="col-sm-9 text-secondary" style="font-family:'Times New Roman', Times, serif">
                            <?= $sd['mobile']; ?>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">PassWord</h6>
                          </div>
                          <div class="col-sm-9 text-secondary" style="font-family:'Times New Roman', Times, serif">
                            <?= strtoupper($sd['password']); ?>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-3">
                            <h6 class="mb-0">School Code</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <?= $_SESSION['schoolUniqueCode']; ?>
                          </div>
                        </div>


                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <div id="history" role="tabpanel" class="tab-pane fade">
                <div class="row">
                  <div class="col-md-12 mb-3">
                    <div class="card h-100">
                      <div class="card-header border-top-3">
                        <h6 class="d-flex align-items-center mb-3">Student Class Timeline</h6>
                      </div>
                      <div class="card-body">
                        <?php

                        $history = $this->db->query("SELECT st.*, s.name, s.father_name, CONCAT(c.className , ' ( ' , sec.sectionName , ' ) ') as className, CONCAT(session.session_start_year, ' - ' , session.session_end_year) as years FROM " . Table::studentHistoryTable . " st 
                       JOIN " . Table::studentTable . " s ON s.id = st.student_id
                       JOIN " . Table::classTable . " c ON c.id = st.class_id
                       JOIN " . Table::sectionTable . " sec ON sec.id = st.section_id
                       JOIN " . Table::schoolSessionTable . " session ON session.id = st.session_table_id
                       WHERE st.student_id = '{$sd['id']}' AND st.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
                       ORDER BY st.id DESC")->result_array();

                        //  echo '<pre>';
                        //  print_r($history);
                        ?>

                        <section style="background-color: #F0F2F5;">
                          <div class="container py-5">

                            <div class="main-timeline">
                              <?php 
                              
                              foreach($history as $h){ ?>
                              <div class="timeline left">
                                <div class="card">
                                  <div class="card-body p-4">
                                    <h3><?= $h['years'];?></h3>
                                      <table class="table mb-0 bg-white">
                                        <thead>
                                          <!-- <th>Name</th>
                                          <th>Father's Name</th> -->
                                          <th>Class</th>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <!-- <td><?= $h['name'];?></td>
                                            <td><?= $h['father_name'];?></td> -->
                                            <td><?= $h['className'];?></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                  </div>
                                </div>
                              </div>
                            <?php  }
                              
                              ?>
                             

                            </div>
                        </section>
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
    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <!-- ./wrapper -->
  <script>
    var ajaxUrlForStudentList = '<?= base_url() . 'ajax/listStudentsAjax' ?>';
  </script>