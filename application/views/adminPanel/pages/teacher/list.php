
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
<?php $this->load->view('adminPanel/pages/navbar.php');?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php $this->load->view("adminPanel/pages/sidebar.php");?>

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
        
          <div class="form-group col-md-2">
          <label >Name</label>
            <input type="text" class="form-control" id="teacherName" placeholder="Search by name">
          </div>
          <div class="form-group col-md-2">
          <label >Class</label>
            <input type="text" class="form-control" id="teacherClass" placeholder="Search by class">
          </div>
          <div class="form-group col-md-2">
          <label >Mobile No</label>
            <input type="number" class="form-control" id="teacherMobile" placeholder="Search by mobile">
          </div>
          <div class="form-group col-md-2">
          <label >User Id</label>
            <input type="text" class="form-control" id="teacherUserId" placeholder="Search by teacher UserId">
          </div>
          <div class="form-group col-md-2">
            <label >From Date</label>
            <input type="date" class="form-control" id="fromDate">
          </div>
          <div class="form-group col-md-2">
          <label >To Date</label>
            <input type="date" class="form-control" id="toDate">
          </div>
          <div class="form-group col-md-2">
            <button id="searchTeacher" class="btn btn-primary">Submit</button>
            <button onclick="window.location.reload();" class="btn btn-warning">Clear</button>
          </div>
          <!-- <div class="form-group col-md-2">
            
          </div> -->
        
          <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">Showing All Teachers Data</h3>
                <a href="<?=base_url('teacher/addTeacher')?>" class="btn btn-primary ml-4">Add New Teacher</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="listDatatableTeacher" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>User Id</th>
                    <th>Mobile</th>
                    <th>Class - Section</th>
                    <th>State - City - Pincode</th>
                    <th>Status</th>
                    <th>Date of Birth</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
               
                </table>
              </div>
              <!-- /.card-body -->
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
  <?php $this->load->view("adminPanel/pages/footer-copyright.php");?>
</div>
<?php $this->load->view("adminPanel/pages/footer.php");?>
<!-- ./wrapper -->

  <script>
  var ajaxUrlForTeacherList = '<?= base_url() . 'ajax/listTeachersAjax'?>';
    // datatable for teacher
    loadTeacherDataTable();
  function loadTeacherDataTable(sn = '',sc = '',sm = '',si = '',fd = '',td = '')
  {
     $("#listDatatableTeacher").DataTable({
       "responsive": true, "lengthChange": true, "autoWidth": true,
       "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
         dom: 'lBfrtip',
         buttons: [
             'copyHtml5',
             'excelHtml5',
             'csvHtml5',
             'pdfHtml5'
         ],
         lengthMenu: [10,50,100,500,1000,2000,5000,10000,50000,100000],
         pageLength: 10,
         processing: true,
         serverSide: true,
         searching: false,
         paging: true,
         ajax : {
           method: 'post',
           url: ajaxUrlForTeacherList,
           data : {
             teacherName: sn,
             teacherClass: sc,
             teacherMobile: sm,
             teacherUserId: si,
             teacherFromDate: fd,
             teacherToDate: td,
           },
           error: function ()
           {
             console.log('something went wrong.');
           }
         }
     });
  }
 
   $("#searchTeacher").click(function(e){
     e.preventDefault();
     $("#listDatatableTeacher").DataTable().destroy();
     loadTeacherDataTable(
       $("#teacherName").val(),
       $("#teacherClass").val(),
       $("#teacherMobile").val(),
       $("#teacherUserId").val(),
       $("#fromDate").val(),
       $("#toDate").val(),
       );
   })
  </script>


