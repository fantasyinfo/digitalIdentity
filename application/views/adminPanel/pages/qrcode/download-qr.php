
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
<?php $this->load->view('adminPanel/pages/navbar.php');?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php $this->load->view("adminPanel/pages/sidebar.php");
  
  $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
  $sectionData = $this->db->query("SELECT * FROM " . Table::sectionTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
  
  
  
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?=$data['pageTitle']?> </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"><?=$data['pageTitle']?> </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
      <h5>Search Filters</h5>
        <div class="row">
        <div class="form-group col-md-3">
                      <label>Select Class </label>
                      <select name="studentClass" id="studentClass" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
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
                      <select name="studentSection" id="studentSection" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
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
          <div class="form-group col-md-2 mt-4">
            <button id="search" class="btn btn-primary">Submit</button>
            <button onclick="window.location.reload();" class="btn btn-warning">Clear</button>
          </div>
          <!-- <div class="form-group col-md-2">
            
          </div> -->
        
</div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->

  <!-- /.control-sidebar -->
</div>
  <?php $this->load->view("adminPanel/pages/footer-copyright.php");?>
</div>
<?php $this->load->view("adminPanel/pages/footer.php");?>
<!-- ./wrapper -->
<script>

  var schoolUniqueCode = '<?= $_SESSION['schoolUniqueCode']?>';


 $("#search").click(function(e){
   e.preventDefault();
   let classId = $("#studentClass").val();
   let sectionId =   $("#studentSection").val();

     if(classId != '' && sectionId != '')
     {
      window.location.href = 'https://digitalfied.in/qrcodeDownload/new.php?classId='+classId+'&sectionId='+sectionId+'&schoolUniqueCode='+schoolUniqueCode;
     }

 })
</script>
