<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');
    $this->load->model('CrudModel');


    $sessionData = $this->db->query("SELECT * FROM " . Table::schoolSessionTable . " WHERE status = '1'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
   
    $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE status ='1'   AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ")->result_array();

    $sectionData = $this->db->query("SELECT * FROM " . Table::sectionTable . " WHERE status = '1'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();




    if(isset($_POST['search']))
    {
        $studentsData = $this->db->query("SELECT s.*,cl.className,sc.sectionName FROM " . Table::studentTable . " s
        LEFT JOIN ".Table::studentHistoryTable." sht ON sht.student_id = s.id
        INNER JOIN ".Table::classTable." cl ON cl.id = s.class_id
        INNER JOIN ".Table::sectionTable." sc ON sc.id = s.section_id
        WHERE s.status = '3'  AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
        AND sht.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND sht.session_table_id = '{$_POST['sessionId']}'
        AND sht.class_id = '{$_POST['classId']}' AND sht.section_id = '{$_POST['sectionId']}' ")->result_array();

        // if(!empty($studentsData))
        // {
        //     print_r($studentsData);
        // }
    }


 







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
              <h1 class="m-0"><?= $data['pageTitle'] ?> </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <!-- <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><?= $data['pageTitle'] ?> </li>
              </ol> -->
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
              <div class="card border-top-3">
                <div class="card-header">
                  <h4>Select Correct Details</h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <form method="post" action="">
                    <div class="row">
                    <div class="form-group col-md-3">
                        <label>Select Session<span style="color:red;">*</span></label>
                        <select name="sessionId" id="sessionId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                          <option>Select Sessions</option>
                          <?php
                          if (isset($sessionData)) {
                            foreach ($sessionData as $d) {
                          ?>
                              <option value="<?= $d['id'] ?>"><?= $d['session_start_year'] . " - " . $d['session_end_year'];?></option>
                          <?php }
                          }

                          ?>
                        </select>
                      </div>
                    <div class="form-group col-md-3">
                        <label>Select Class<span style="color:red;">*</span></label>
                        <select name="classId" id="classId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                          <option>Select Classs</option>
                          <?php
                          if (isset($classData)) {
                            foreach ($classData as $d) {
                          ?>
                              <option value="<?= $d['id'] ?>"><?= $d['className'];?></option>
                          <?php }
                          }

                          ?>
                        </select>
                      </div>
                    <div class="form-group col-md-3">
                        <label>Select Section<span style="color:red;">*</span></label>
                        <select name="sectionId" id="sectionId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                          <option>Select Sections</option>
                          <?php
                          if (isset($sectionData)) {
                            foreach ($sectionData as $d) {
                          ?>
                              <option value="<?= $d['id'] ?>"><?= $d['sectionName'] ;?></option>
                          <?php }
                          }

                          ?>
                        </select>
                      </div>
                     
                      <div class="form-group col-md-3 margin-top-30">
                        <button type="submit" name="search" class="btn btn-block mybtnColor">Search</button>
                      </div>
                    </div>
                  </form>
                </div>

              </div>

            </div>



            <div class="col-md-12">
              <div class="card border-top-3">
                <div class="card-header">
                  <h4>Old Students Lists</h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <div class="table-responsive"> 
                  <table id="stateDataTable" class="table align-middle mb-0 bg-white">
                    <thead class="bg-light">
                      <tr>
                        <!-- <th>Id</th> -->
                        <th>Name</th>
                        <th>User Id</th>
                        <th>Class</th>
                        <th>Father Name</th>
                       <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (isset($studentsData)) {
                        $i = 0;
                        foreach ($studentsData as $cn) { ?>
                          <tr>
                            <!-- <td><?= ++$i; ?></td> -->
                            <!-- <td><?= $cn['id']; ?></td> -->
                            <td><?= $cn['name']; ?></td>
                            <td><?= $cn['user_id']; ?></td>
                            <td><?= $cn['className'] . ' ( ' .$cn['sectionName'] . ' ) '; ?></td>
                            <td><?= $cn['father_name']; ?></td>
                            <td>
                            <a href="<?= base_url('student/viewStudent/'). $cn['id']; ?>" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                                </td>
                          </tr>
                      <?php  }
                      } ?>

                    </tbody>

                  </table>
                    </div>
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
    <!-- </div>
      </div>
    </div> -->
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->

    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->
  <script>
    // var ajaxUrl = '<?= base_url() . 'ajax/listStudentsAjax' ?>';


    $("#stateDataTable").DataTable();
  </script>