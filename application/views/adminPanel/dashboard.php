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

          <?php

          $this->CrudModel->checkPermission();

          $currentDate = date('Y-m-d');
          ?>

<div id="accordion">

          <div class="card card-primary card-outline">
            <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="false">
                <div class="card-header">
                <h4 class="card-title w-100">
                Students Section
                </h4>
                </div>
            </a>
              <div id="collapseOne" class="collapse show" data-parent="#accordion">
              <div class="card-body">
              <?php include("dashinclude/student_section.php"); ?>
              </div>
            </div>
          </div>

          <div class="card card-primary card-outline">
            <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false">
                <div class="card-header">
                <h4 class="card-title w-100">
                Academic Section
                </h4>
                </div>
            </a>
              <div id="collapseTwo" class="collapse show" data-parent="#accordion">
              <div class="card-body">
              <?php include("dashinclude/academic_section.php"); ?>
              </div>
            </div>
          </div>

          <div class="card card-primary card-outline">
            <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false">
                <div class="card-header">
                <h4 class="card-title w-100">
                Fees Section
                </h4>
                </div>
            </a>
              <div id="collapseThree" class="collapse show" data-parent="#accordion">
              <div class="card-body">
              <?php include("dashinclude/fees_section.php"); ?>
              </div>
            </div>
          </div>

</div>

<?php

$sheduleData = $this->db->query("
     SELECT cST.shedule_json, cST.id, ct.className,st.sectionName,cST.status FROM " . Table::classSheduleTable . " cST 
     JOIN ".Table::classTable." ct ON ct.id = cST.class_id
     JOIN ".Table::sectionTable." st ON st.id = cST.section_id 
     WHERE cST.status != '4' AND cST.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
     ORDER BY cST.id DESC
     ")->result_array();

     $totalCount = count($sheduleData);
    //  HelperClass::prePrintR($sheduleData);
     $sheduleD = [];
     $teacherArr = [];
     for($i=0; $i < $totalCount; $i++)
     {
      array_push($sheduleD,json_decode($sheduleData[$i]['shedule_json'],TRUE));
     }
    

     $totalY = count($sheduleD);
     for($j=0; $j < $totalY; $j++)
     {
        for($k=0; $k < count($sheduleD[$j]); $k++)
        {
          array_push($teacherArr,$sheduleD[$j][$k]);
        }
     }


     $totalT = count($teacherArr);
     $teachersArr = [];
     for($l=0; $l < $totalT; $l++)
     {
        // if()
     }
// HelperClass::prePrintR($teacherArr);


?>
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

  </script>