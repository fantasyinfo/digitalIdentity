<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php"); ?>



    <?php


    $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE status = '1'")->result_array();
    $sectionData = $this->db->query("SELECT * FROM " . Table::sectionTable . " WHERE status = '1'")->result_array();


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

          <form method="post" action="">
            <div class="row">

              <div class="col-md-8 mx-auto">
                <div class="card">
                  <div class="card-header">Please Select Correct Details</div>
                  <div class="card-body">

                    <div class="form-group col-md-12">
                      <label>Select Class </label>
                      <select name="classId" id="classId" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                      <option >Classes</option>
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
                    <div class="form-group col-md-12">
                      <label>Select Section </label>
                      <select name="sectionId" id="sectionId" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="showStudents()">
                        <option >Sections</option>
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

                    <div class="form-group col-md-12">
                      <label>Select Students </label>
                      <select name="studentId" id="studentId" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                        <option disabled >Students</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>






            </div>

          </form>

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
  <!-- ./wrapper -->
  <script>

    function showStudents() {
      var classId = $("#classId").val();
      var sectionId = $("#sectionId").val();
      if (classId != '' && sectionId != '') {
        console.log(classId + ' and ' + sectionId);
        $.ajax({
          url: '<?= base_url() . 'ajax/showStudentViaClassAndSectionId';?>',
          method: 'post',
          processData: 'false',
          data : {
            classId : classId,
            sectionId : sectionId
          },
          success: function (response)
          {
            //console.log(response);
            response =  $.parseJSON(response);
            $('#studentId').append(response);
          },
          error: function (error)
          {
            console.log(error);
          }

        });
      }
    }
  </script>