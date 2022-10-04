
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


if(isset($_POST['submit']))
{
  $updateDriver = $this->db->query("UPDATE " . Table::teacherTable . " SET vechicle_type = '{$_POST['vechicle_type']}', driver_id = '{$_POST['driver_id']}' WHERE id = '{$_POST['stu_id']}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

  if($updateDriver)
  {
    $msgArr = [
      'class' => 'success',
      'msg' => 'Driver Assign Successfully',
    ];
    $this->session->set_userdata($msgArr);
  }else
  {
    $msgArr = [
      'class' => 'danger',
      'msg' => 'Driver Not Assign Due to this Error. ' . $this->db->last_query(),
    ];
    $this->session->set_userdata($msgArr);
  }
  header("Refresh:1 ".base_url()."teacher/list");
  }


if(isset($_GET['action']) )
{
  if($_GET['action'] == 'status')
  {
    $status = $_GET['status'];
    $updateId = $_GET['edit_id'];
    $updateStatus = $this->db->query("UPDATE " . Table::teacherTable . " SET status = '$status' WHERE id = '$updateId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

    if($updateStatus)
    {
      $msgArr = [
        'class' => 'success',
        'msg' => 'Teacher Status Updated Successfully',
      ];
      $this->session->set_userdata($msgArr);
    }else
    {
      $msgArr = [
        'class' => 'danger',
        'msg' => 'Teacher Status Not Updated Due to this Error. ' . $this->db->last_query(),
      ];
      $this->session->set_userdata($msgArr);
    }
    header("Refresh:1 ".base_url()."teacher/list");
  }
}











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
        
          <div class="form-group col-md-2">
          <label >Name</label>
            <input type="text" class="form-control" id="teacherName" placeholder="Search by name">
          </div>
          <!-- <div class="form-group col-md-2">
          <label >Class</label>
            <input type="text" class="form-control" id="teacherClass" placeholder="Search by class">
          </div> -->
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
          <div class="form-group col-md-2 pt-4">
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
                <table id="listDatatableTeacher" class="table table-bordered table-striped table-responsive">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Semester Exam Name</th>
                    <th>SemId</th>
                    <th>Exam Date</th>
                    <th>Exam Day</th>
                    <th>Class - Section</th>
                    <th>Subject</th>
                    <th>Exam Time </th>
                    <th>Exam Marks</th>
                    <th>Status</th>
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


   <!-- bootstrap modal box -->
  <!-- Modal -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <form method="POST">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Select Driver</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="row">
        <div class="col-md-12">
          <label>Select Vechicle Type</label>
            <select  id="vechicle_type" class="form-control  select2 select2-danger" name="vechicle_type" required  data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="vechicleType(this)">
            <option></option>
                <?php 
                
                foreach(HelperClass::vehicleType as $key => $dList)
                  {  ?>
                <option value="<?=$key?>"><?=HelperClass::vehicleType[$key]?></option>
              <?php  } ?>   
            </select>
          
        </div>
        <div class="col-md-12">
          <label>Select Driver Name</label>
            <select  id="driver_id" class="form-control  select2 select2-danger" name="driver_id" required  data-dropdown-css-class="select2-danger" style="width: 100%;">
            <option></option>
                  
            </select>
        </div>
       </div>
            <input type="hidden" name="stu_id" id="stu_id" >
           
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="btn btn-primary">Assign Driver</button>
      </div>
    </div>
      </form>
  </div>
</div>










  <!-- /.control-sidebar -->
</div>
  <?php $this->load->view("adminPanel/pages/footer-copyright.php");?>
</div>
<?php $this->load->view("adminPanel/pages/footer.php");?>
<!-- ./wrapper -->

  <script>
  var ajaxUrlForTeacherList = '<?= base_url() . 'ajax/dateSheetList'?>';
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
            //  teacherClass: sc,
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
      //  $("#teacherClass").val(),
      '',
       $("#teacherMobile").val(),
       $("#teacherUserId").val(),
       $("#fromDate").val(),
       $("#toDate").val(),
       );
   })
  </script>


<script>



function vechicleType(x)
{
  $('#driver_id').empty();

  $.ajax({
      url: '<?= base_url() . 'ajax/showDriverListViaVechicleType';?>' ,
      method: 'POST',
      data: {
        'vechicleType' : x.value
      },
      success:function(response)
      {
        response = $.parseJSON(response);
          $("#driver_id").append(response);
      }
    })
}

   function assingDriver(x)
  {
    $("#stu_id").val(x);
    $("#exampleModalCenter").modal('show');
    console.log(x);
   
  }
</script>
