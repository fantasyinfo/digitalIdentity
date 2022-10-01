
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


// Array ( [__ci_last_regenerate] => 1664592104 [id] => 14 [name] => Principle Test [email] => admin@koel.com [user_type] => Principal [schoolUniqueCode] => 683611 [userData] => Array ( [id] => 14 [name] => Principle Test [email] => admin@koel.com [user_type] => Principal [schoolUniqueCode] => 683611 ) )

if(isset($_GET['submit']))

{
  $action = trim(htmlspecialchars($_GET['action'],ENT_QUOTES));
  $cId = $_GET['cId'];




  $actionTakenId = $_SESSION['id'];
  $actionTakenUserType = HelperClass::userType[$_SESSION['user_type']];
  $actionTakenDate = date('Y-m-d');

  $updateAction = $this->db->query("UPDATE " . Table::complaintTable . " SET action = '$action', action_taken_id = '$actionTakenId' , action_taken_user_type = '$actionTakenUserType',action_taken_date = '$actionTakenDate',status = '2' WHERE id = '$cId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

  if($updateAction)
    {


      $userId = $_GET['userId'];

      $tokens = $this->db->query("SELECT fcm_token FROM " . Table::studentTable . " WHERE id = '$userId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

      if(!empty($tokens))
      {
        $image = null;
        $sound = null;
        $token = [$tokens[0]['fcm_token']];
        $title = "Hey, Dear Your We Have Take Action Against Your Complaint Please Check The Details.";
        $body = $action;
        $sendPushSMS= $this->CrudModel->sendFireBaseNotificationWithDeviceId($token, $title,$body,$image,$sound);
      }






      $msgArr = [
        'class' => 'success',
        'msg' => 'Action Taken Successfully',
      ];
      $this->session->set_userdata($msgArr);
    }else
    {
      $msgArr = [
        'class' => 'danger',
        'msg' => 'Action Taken Not Updated Due to this Error. ' . $this->db->last_query(),
      ];
      $this->session->set_userdata($msgArr);
    }
    header("Refresh:1 ".base_url()."academic/allComplaints");

}








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
          <label >Complaint Id</label>
            <input type="text" class="form-control" id="complaintId" placeholder="Search by complaint Id">
          </div>
          <div class="form-group col-md-2">
          <label >Guilty Person Name</label>
            <input type="text" class="form-control" id="guiltyPersonName" placeholder="Search by Guilty Person Name">
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
                <table id="listDatatable" class="table table-bordered table-striped table-responsive">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <!-- <th>Complaint Row Id</th> -->
                    <th>Complaint Refrence N0</th>
                    <th>Raised By</th>
                    <th>Raised User Id</th>
                    <th>Guilty Person Name</th>
                    <th>Guilty Person Type</th>
                    <th>Subject</th>
                    <th>Issue</th>
                    <th>Action</th>
                    <th>Action By User Type</th>
                    <th>Action By User Id</th>
                    <th>Action Taken Date</th>
                    <th>Status</th>
                    <th>Created At</th>
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







  <div class="modal fade" id="takeActionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <form method="GET">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Take Action</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
                 <input type="hidden" name="cId" id="cId">
                 <input type="hidden" name="userId" id="userId">
                 <div class="form-group">
                  <label for="exampleInputEmail1">Action</label>
                  <textarea class="form-control" name="action"></textarea>
                </div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
  </form>
</div>
















  <!-- /.control-sidebar -->
</div>
  <?php $this->load->view("adminPanel/pages/footer-copyright.php");?>
</div>
<?php $this->load->view("adminPanel/pages/footer.php");?>
<!-- ./wrapper -->
<script>
  var ajaxUrlForStudentList = '<?= base_url() . 'ajax/allComplaintList'?>';
   // datatable student list intilizing
 loadStudentDataTable();

function loadStudentDataTable(ci = '',gpm = '',fd = '',td = '')
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
            complaintId : ci,
            guiltyPersonName : gpm,
           studentFromDate: fd,
           studentToDate: td
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
     $("#complaintId").val(),
     $("#guiltyPersonName").val(),
     $("#fromDate").val(),
     $("#toDate").val(),
   
     );
 })


 function takeAction(x,y)
 {
  console.log(x);
  $("#cId").val(x);
  $("#userId").val(y);
  $("#takeActionModal").modal("show");

 }
</script>
