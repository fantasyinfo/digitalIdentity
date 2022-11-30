
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
      $updateDriver = $this->db->query("UPDATE " . Table::studentTable . " SET vechicle_type = '{$_POST['vechicle_type']}', driver_id = '{$_POST['driver_id']}' WHERE id = '{$_POST['stu_id']}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

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
      header("Refresh:1 ".base_url()."student/list");
      }















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
     
        <div class="row">
        
        <div class="col-md-12">
        <div class="card border-top-3">
              <div class="card-header">
              <h5>Search Filters</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  
              
              <div class="form-group col-md-2">
          <label >Name</label>
            <input type="text" class="form-control" id="studentName" placeholder="Search by name">
          </div>
          <div class="form-group col-md-2">
          <label>Select Class </label>
          <select  id="studentClass" class="form-control  select2 select2-dark" required  data-dropdown-css-class="select2-dark" style="width: 100%;">
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
          <select  id="studentSection" class="form-control  select2 select2-dark" required  data-dropdown-css-class="select2-dark" style="width: 100%;">
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
          <label >Mobile No</label>
            <input type="number" class="form-control" id="studentMobile" placeholder="Search by mobile">
          </div>
          <div class="form-group col-md-2">
          <label >User Id</label>
            <input type="text" class="form-control" id="studentUserId" placeholder="Search by Student UserId">
          </div>
          <!-- <div class="form-group col-md-2">
            <label >From Date</label>
            <input type="date" class="form-control" id="fromDate">
          </div>
          <div class="form-group col-md-2">
          <label >To Date</label>
            <input type="date" class="form-control" id="toDate">
          </div> -->
          <div class="form-group col-md-2 margin-top-30">
          <button id="search" class="btn mybtnColor">Search</button>
          <button onclick="window.location.reload();" class="btn mybtnColor">Clear</button>
        </div>
        </div>
              </div>
        </div>
         
        </div>  


        
          <div class="col-md-12">
          <div class="card border-top-3">
              <div class="card-header">
                <h3 class="card-title">Showing All Students Data</h3>
                <a href="<?=base_url('student/addStudent')?>" class="btn mybtnColor float-right">Add New Student</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="table-responsive">

                
                <table id="listDatatable" class="table bg-white mb-0 align-middle">
                  <thead class="bg-light">
                  <tr>
                    <th>Id</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Father's Name</th>
                    <!-- <th>User Id</th> -->
                    <th>Mobile</th>
                    <th>Password</th>
                    <th>School Code</th>
                    <th>Class</th>
                    <th>State - City - Pincode</th>
                    <th>Status</th>
                    <!-- <th>Date of Birth</th> -->
                    <th>Assign Transport</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
               
                </table>
                </div>
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
  var ajaxUrlForStudentList = '<?= base_url() . 'ajax/listStudentsAjax'?>';
   // datatable student list intilizing
 loadStudentDataTable();

function loadStudentDataTable(sn = '',sc = '',ss = '',sm = '',si = '',fd = '',td = '')
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
           studentMobile: sm,
           studentUserId: si,
           studentFromDate: fd,
           studentToDate: td,
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
     $("#studentMobile").val(),
     $("#studentUserId").val(),
     $("#fromDate").val(),
     $("#toDate").val(),
     );
 });



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
