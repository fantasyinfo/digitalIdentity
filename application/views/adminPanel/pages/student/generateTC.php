

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

            $classData = $this->CrudModel->allClass(Table::classTable, $_SESSION['schoolUniqueCode']);
            $sectionData = $this->CrudModel->allSection(Table::sectionTable, $_SESSION['schoolUniqueCode']);
   
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
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Select Student Details</h3>

                </div>

                <div class="card-body" id="filter_frm">
                  <form method="POST" action="<?= base_url('student/editTC')?>">
                    <div class="form-group col-md-12">
                      <label>Select Class </label>
                      <select id="classId" name="class_id" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                      <option>Please Select Class</option>
                        <?php
                        if (isset($classData)) {
                          foreach ($classData as $cd) {  ?>
                            <option value="<?= $cd['id'] ?>"><?= $cd['className'] ?></option>
                        <?php }
                        } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-12">
                      <label>Select Section </label>
                      <select id="sectionId" name="section_id" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="showStudents()">
                      <option>Please Select Section</option>
                        <?php
                        if (isset($sectionData)) {
                          foreach ($sectionData as $sd) {  ?>
                            <option value="<?= $sd['id'] ?>"><?= $sd['sectionName'] ?></option>
                        <?php }
                        } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-12">
                      <label>Select Students </label>
                      <select name="studentId" id="studentId" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                        <option>Please Select Student</option>
                      </select>
                    </div>
                    <div class="form-group col-md-12 pt-4">
                      <button type="submit" name="search" class="btn btn-primary btn-block ">Submit</button>
                    </div>
                  </form>
                </div>
                <!-- /.card-header -->
                <!-- form start -->

              </div>
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

    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->

    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?> 
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->
  <script>
    var ajaxUrl = '<?= base_url() . 'ajax/showCityViaStateId' ?>';

    function showStudents() {
      var classId = $("#classId").val();
      var sectionId = $("#sectionId").val();
      if (classId != '' && sectionId != '') {
        console.log(classId + ' and ' + sectionId);
        $.ajax({
          url: '<?= base_url() . 'ajax/showStudentViaClassAndSectionId'; ?>',
          method: 'post',
          processData: 'false',
          data: {
            classId: classId,
            sectionId: sectionId
          },
          success: function(response) {
            //console.log(response);
            response = $.parseJSON(response);
            $('#studentId').append(response);
          },
          error: function(error) {
            console.log(error);
          }

        });
      }
    }
  </script>