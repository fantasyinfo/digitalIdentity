
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
$this->load->model('StudentModel');

    if(isset($_POST['submit']))
    {
   
      // insert into history table
      // update current class & section on student table
      // check due balance

     $feesDueToday =  $this->StudentModel->totalFeesDueToday($_SESSION['schoolUniqueCode'],$_POST['class_id'],$_POST['section_id'],$_POST['student_id']);

     $feeDue = (@$feesDueToday['totalBalanceForDepositToday']) ? @$feesDueToday['totalBalanceForDepositToday'] : 0;


     $insertPermoteHistory = $this->db->query("INSERT INTO " . Table::studentHistoryTable . " (schoolUniqueCode,student_id,session_table_id,class_id,section_id,fees_due) VALUES ('{$_SESSION['schoolUniqueCode']}','{$_POST['student_id']}','{$_POST['session_table_id']}','{$_POST['class_id']}','{$_POST['section_id']}','$feeDue')");

     if($insertPermoteHistory)
     {
        $updateStudent = $this->db->query("UPDATE " . Table::studentTable . " SET class_id = '{$_POST['class_id']}', section_id = '{$_POST['section_id']}' WHERE id = '{$_POST['student_id']}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if($updateStudent)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Student Permoted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Student Not Permoted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."student/permoteStudent");
     }



      
      }
















$classData = $this->CrudModel->allClass(Table::classTable, $_SESSION['schoolUniqueCode']);
$sectionData = $this->CrudModel->allSection(Table::sectionTable, $_SESSION['schoolUniqueCode']);
$sessionData = $this->db->query("SELECT * FROM ".Table::schoolSessionTable." WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1'")->result_array();


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
          <div class="form-group col-md-2 pt-4">
            <button id="search" class="btn btn-primary">Submit</button>
            <button onclick="window.location.reload();" class="btn btn-warning">Clear</button>
          </div>
          <!-- <div class="form-group col-md-2">
            
          </div> -->
        
          <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">Showing All Students Data</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="listDatatable" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>User Id</th>
                    <th>Mobile</th>
                    <th>Current Class - Section</th>
                    <th>Permote Student</th>
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
        <h5 class="modal-title" id="exampleModalLongTitle">Select Correct Detials</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="row">
       <div class="form-group col-md-12">
          <label>Select Session </label>
          <select  id="session" class="form-control  select2 select2-danger" required name="session_table_id" data-dropdown-css-class="select2-danger" style="width: 100%;">
          <option></option>
              <?php 
              if(isset($sessionData))
              {
               foreach($sessionData as $ssd)
                {  ?>
              <option value="<?=$ssd['id']?>"><?=$ssd['session_start_year'] . " - " . $ssd['session_end_year'];?></option>
            <?php } } ?>   
           </select>
          </div>
       <div class="form-group col-md-12">
          <label>Select Class </label>
          <select  id="studentClassI" class="form-control  select2 select2-danger" required name="class_id"  data-dropdown-css-class="select2-danger" style="width: 100%;">
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
          <div class="form-group col-md-12">
          <label>Select Section </label>
          <select  id="studentSectionI" class="form-control  select2 select2-danger" name="section_id" required  data-dropdown-css-class="select2-danger" style="width: 100%;">
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
       </div>
            <input type="hidden" name="student_id" id="stu_id" >
           
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="btn btn-primary">Permote Student</button>
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
  var ajaxUrlForStudentList = '<?= base_url() . 'ajax/listStudentsPermote'?>';
   // datatable student list intilizing
//  loadStudentDataTable();

function loadStudentDataTable(sc = '',ss = '')
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
           studentClass: sc,
           studentSection: ss,
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
     $("#studentClass").val(),
     $("#studentSection").val(),
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

   function permoteStudent(x)
  {
    $("#stu_id").val(x);
    $("#exampleModalCenter").modal('show');
    console.log(x);
   
  }
</script>
