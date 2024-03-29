
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
      <?php 
       $this->CrudModel->checkPermission();
              if(!empty($this->session->userdata('msg')))
              {?>

              <div class="alert alert-<?=$this->session->userdata('class')?> alert-dismissible fade show" role="alert">
                <?=$this->session->userdata('msg');

                  if($this->session->userdata('class') == 'success')
                  {
                    HelperClass::swalSuccess($this->session->userdata('msg'));
                  }else if($this->session->userdata('class') == 'danger')
                  {
                    HelperClass::swalError($this->session->userdata('msg'));
                  }
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php
              $this->session->unset_userdata('class') ;
              $this->session->unset_userdata('msg') ;
              }
              ?>
      <h5>Search Filters</h5>
        <div class="row">
        
          <div class="form-group col-md-3">
          <label >Name</label>
            <input type="text" class="form-control" id="name" placeholder="Search by name">
          </div>
          <div class="form-group col-md-3">
          <label >User Id</label>
            <input type="text" class="form-control" id="userId" placeholder="Search by User Id">
          </div>
          <div class="form-group col-md-3">
            <label >From Date</label>
            <input type="date" class="form-control" id="fromDate">
          </div>
          <div class="form-group col-md-3">
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
                <h3 class="card-title">Showing All Users DigiCoin Data</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="listDatatableDigiCoins" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>User Type</th>
                    <th>Name</th>
                    <th>User Id</th>
                    <th>For What</th>
                    <th>DigiCoin <i class="nav-icon fa-solid fa-coins"></i></th>
                    <th>Status</th>
                    <!-- <th>Action</th>  -->
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
  var ajaxUrlForTeacherList = '<?= base_url() . 'ajax/listDigiCoinAjax'?>';
    // datatable for teacher
    loadTeacherDataTable();
  function loadTeacherDataTable(n = '',ui = '',fd = '',td = '')
  {
     $("#listDatatableDigiCoins").DataTable({
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
            user_type : 'Teacher',
            name: n,
            userId: ui,
            fromDate: fd,
            toDate: td,
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
     $("#listDatatableDigiCoins").DataTable().destroy();
     loadTeacherDataTable(
       $("#name").val(),
       $("#userId").val(),
       $("#fromDate").val(),
       $("#toDate").val(),
       );
   })
  </script>


