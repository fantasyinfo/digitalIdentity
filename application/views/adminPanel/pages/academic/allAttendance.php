
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



$this->load->model('CrudModel');
$classData = $this->CrudModel->allClass(Table::classTable, $_SESSION['schoolUniqueCode']);
$sectionData = $this->CrudModel->allSection(Table::sectionTable, $_SESSION['schoolUniqueCode']);

if(isset($_GET['action']) )
{
  if($_GET['action'] == 'status')
  {
    $status = $_GET['status'];
    $updateId = $_GET['edit_id'];
    $updateStatus = $this->db->query("UPDATE " . Table::studentTable . " SET status = '$status' WHERE id = '$updateId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

    if($updateStatus)
    {
      $msgArr = [
        'class' => 'success',
        'msg' => 'Student Status Updated Successfully',
      ];
      $this->session->set_userdata($msgArr);
    }else
    {
      $msgArr = [
        'class' => 'danger',
        'msg' => 'Student Status Not Updated Due to this Error. ' . $this->db->last_query(),
      ];
      $this->session->set_userdata($msgArr);
    }
    header("Refresh:1 ".base_url()."student/list");
  }
}





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
        
          <div class="form-group col-md-2">
          <label >Student Name</label>
            <input type="text" class="form-control" id="studentName" placeholder="Search by student name">
          </div>
          <div class="form-group col-md-2">
          <label>Select Class </label>
          <select  id="studentClass" class="form-control  select2 select2-danger" required  data-dropdown-css-class="select2-danger" style="width: 100%;">
          <option></option>
              <?php 
              if(isset($classData))
              {
               foreach($classData as $cd)
                {  ?>
              <option value="<?=$cd['id']?>"><?=$cd['className']?></option>
            <?php } } ?>   
           </select>
          </div>
          <div class="form-group col-md-2">
          <label>Select Section </label>
          <select  id="studentSection" class="form-control  select2 select2-danger" required  data-dropdown-css-class="select2-danger" style="width: 100%;">
          <option></option>
              <?php 
              if(isset($sectionData))
              {
               foreach($sectionData as $sd)
                {  ?>
              <option value="<?=$sd['id']?>"><?=$sd['sectionName']?></option>
            <?php } } ?>   
           </select>
          </div>
          <div class="form-group col-md-2">
          <label>Attendance Status </label>
          <select  id="attendanceStatus" class="form-control  select2 select2-danger" required  data-dropdown-css-class="select2-danger" style="width: 100%;">
          <option></option>
           <option value="ab">Absent</option>
           <option value="1">Present</option>  
           </select>
          </div>
          <div class="form-group col-md-2">
          <label >Teacher Name</label>
            <input type="text" class="form-control" id="teacherName" placeholder="Search by teacher name">
          </div>
          <div class="form-group col-md-2">
            <label >From Date</label>
            <input type="date" class="form-control" id="fromDate">
          </div>
          <div class="form-group col-md-2">
          <label >To Date</label>
            <input type="date" class="form-control" id="toDate">
          </div>
          <div class="form-group col-md-2 pt-4">
            <button id="search" class="btn btn-primary">Submit</button>
            <button onclick="window.location.reload();" class="btn btn-warning">Clear</button>
          </div>
          <!-- <div class="form-group col-md-2">
            
          </div> -->
        
          <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">Showing All Attendance Data</h3>
                
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="listDatatable" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Attendance Id</th>
                    <th>Student Image</th>
                    <th>Student Name</th>
                    <th>User Id</th>
                    <th>Class & Section</th>
                    <th>Attendance Status</th>
                    <th>Attendance Time</th>
                    <th>Attandance Taken By</th>
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
  var ajaxUrlForStudentList = '<?= base_url() . 'ajax/allAttendanceList'?>';
   // datatable student list intilizing
 loadStudentDataTable();

function loadStudentDataTable(sn = '',sc = '',ss = '',sm = '',fd = '',td = '', as = '')
{
   $("#listDatatable").DataTable({
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
         url: ajaxUrlForStudentList,
         data : {
          studentName: sn,
           studentClass: sc,
           studentSection: ss,
           teacherName: sm,
           studentFromDate: fd,
           studentToDate: td,
           attendanceStatus: as,
         },
         error: function ()
         {
           console.log('something went wrong.');
         }
       }
   });
}

 $("#search").click(function(e){
   e.preventDefault();
   $("#listDatatable").DataTable().destroy();
   loadStudentDataTable(
     $("#studentName").val(),
     $("#studentClass").val(),
     $("#studentSection").val(),
     $("#teacherName").val(),
     $("#fromDate").val(),
     $("#toDate").val(),
     $("#attendanceStatus").val(),
     );
 })
</script>
