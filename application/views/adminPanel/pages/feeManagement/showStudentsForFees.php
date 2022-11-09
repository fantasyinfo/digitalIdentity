<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php



if (isset($_POST['assignStudents'])) {
 
  $totalStudents = count($_POST['stuId']);

  for($i=0; $i < $totalStudents; $i++){

    $alreadyID = $this->db->query("SELECT id FROM ". Table::newfeeclasswiseTable." WHERE student_id = '{$_POST['stuId'][$i]}' AND fee_group_id = '{$_POST['groupId']}' AND status = '1' AND class_id = '{$_POST['classId']}' AND section_id = '{$_POST['sectionId']}' LIMIT 1")->result_array();

    if(!empty($alreadyID))
    {
      continue;
    }
    // insert 
    $insertArr = [
      "schoolUniqueCode" => $this->CrudModel->sanitizeInput($_SESSION['schoolUniqueCode']),
      "student_id" => $this->CrudModel->sanitizeInput($_POST['stuId'][$i]),
      "class_id" => $this->CrudModel->sanitizeInput($_POST['classId']),
      "section_id" => $this->CrudModel->sanitizeInput($_POST['sectionId']),
      "fee_group_id" => $this->CrudModel->sanitizeInput($_POST['groupId']),
      "session_table_id" => $this->CrudModel->sanitizeInput($_SESSION['currentSession'])
    ];


    $insertId = $this->CrudModel->insert(Table::newfeeclasswiseTable, $insertArr);
  }

  if ($insertId) {
    $cid = trim(@$_POST['classId']);
    $sid = trim(@$_POST['sectionId']);
    $gid = trim(@$_POST['groupId']);
    $msgArr = [
      'class' => 'success',
      'msg' => 'Fees Assign To Students Successfully',
    ];
    $this->session->set_userdata($msgArr);

    header("Refresh:1 " . base_url() . "feesManagement/showStudentsForFees?classId=$cid&sectionId=$sid&groupId=$gid");
  } else {
    $msgArr = [
      'class' => 'danger',
      'msg' => 'Fees Not Assign Try Again.',
    ];
    $this->session->set_userdata($msgArr);

    header("Refresh:1 " . base_url() . "feesManagement/showStudentsForFees?classId=$cid&sectionId=$sid&groupId=$gid");
  }


}


$cid = trim(@$_GET['classId']);
$sid = trim(@$_GET['sectionId']);
$gid = trim(@$_GET['groupId']);

if(isset($_GET['action']) && $_GET['action'] == 'delete')
{

 $del =  $this->db->query("DELETE FROM ".Table::newfeeclasswiseTable." WHERE id='{$_GET['delete_id']}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

 if ($del) {
  $msgArr = [
    'class' => 'success',
    'msg' => 'Fees Assign To Students Deleted Successfully',
  ];
  $this->session->set_userdata($msgArr);


  header("Refresh:1 " . base_url() . "feesManagement/showStudentsForFees?classId=$cid&sectionId=$sid&groupId=$gid");
}

}








if(isset($_POST['feesGroupId']))
{
  $feesGroupId = $_POST['feesGroupId'];
}else if(isset($_GET['groupId'])){
  $feesGroupId = $_GET['groupId'];
}else{
  $feesGroupId = $_POST['groupId'];
}
    


    $feesDetails = $this->CrudModel->dbSqlQuery("SELECT nfm.*,nfgt.feeGroupName,nftt.feeTypeName, nftt.id as ftId, nfgt.id as fgId FROM " . Table::newfeemasterTable . " nfm
    JOIN " . Table::newfeesgroupsTable . " nfgt ON nfgt.id = nfm.newFeeGroupId
    JOIN " . Table::newfeestypesTable . " nftt ON nftt.id = nfm.newFeeType
    WHERE nfm.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND nfm.status = '1' AND nfgt.id = '$feesGroupId'");

    // print_r($feesDetails);

    $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');
    $this->load->model('CrudModel');



    if(isset($_POST['class_id']))
    {
      $classIdFor = $_POST['class_id'];
      $sectionIdFor = $_POST['section_id'];
    }else if(isset($_GET['classId'])){
      $classIdFor = $_GET['classId'];
      $sectionIdFor = $_GET['sectionId'];
    }else{
      $classIdFor = $_POST['classId'];
      $sectionIdFor = $_POST['sectionId'];
    }


    // fetching fees type data
    $studentsLists = $this->CrudModel->dbSqlQuery("SELECT s.*, c.className, se.sectionName,c.id as classId, se.id as sectionId FROM " . Table::studentTable . " s 
    JOIN " . Table::classTable . " c ON c.id = s.class_id
    JOIN " . Table::sectionTable . " se ON se.id = s.section_id
    WHERE s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND s.status = '1' AND s.class_id = '$classIdFor' AND s.section_id = '$sectionIdFor' ORDER BY s.id DESC");


    // echo $this->db->last_query();


  





    ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <?php
              if (!empty($this->session->userdata('msg'))) {
                if ($this->session->userdata('class') == 'success') {
                  HelperClass::swalSuccess($this->session->userdata('msg'));
                } else if ($this->session->userdata('class') == 'danger') {
                  HelperClass::swalError($this->session->userdata('msg'));
                }

              ?>

                <div class="alert alert-<?= $this->session->userdata('class') ?> alert-dismissible fade show" role="alert">
                  <strong>New Message!</strong> <?= $this->session->userdata('msg') ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <?php
                $this->session->unset_userdata('class');
                $this->session->unset_userdata('msg');
              }
              ?>
              <h1 class="m-0"><?= $data['pageTitle'] ?> </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <!-- <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><?= $data['pageTitle'] ?> </li>
              </ol> -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <form method="POST">
            <div class="row">
              <div class="col-md-4">
                <div class="card border-top-3">
                  <div class="card-header">
                    <h4>Fees Details</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table mb-0 align-middle bg-white">
                        <thead class="bg-light">
                          <th>Group Name</th>
                          <th>Type Name</th>
                          <th>Amount</th>
                        </thead>
                        <tbody>
                          
                          <?php
                          foreach ($feesDetails as $fd) { ?>
                            <tr>
                              <input type="hidden" name="groupId" value="<?= $fd['fgId'] ?>" >
                              <td><?= $fd['feeGroupName'] ?></td>
                              <td><?= $fd['feeTypeName'] ?></td>
                              <td><i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($fd['amount'], 2); ?></td>
                            </tr>
                          <?php  }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-8">
                <div class="card border-top-3">
                  <div class="card-header">
                    <h4>Students List</h4>

                  </div>
                  <!-- /.card-header -->

                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table align-middle mb-0 bg-white">
                        <thead class="bg-light">
                          <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>User Id</th>
                            <th>Father Name</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($studentsLists)) {
                            $i = 0;
                            $chked = '';
                            $disabled = '';
                            foreach ($studentsLists as $cn) { 
                              
                              $alreadyD = $this->db->query("SELECT id FROM ". Table::newfeeclasswiseTable." WHERE student_id = '{$cn['id']}' AND fee_group_id = '$feesGroupId' AND status = '1' LIMIT 1")->result_array();
                              if(!empty($alreadyD))
                              {
                                $chked = 'checked';
                                $disabled = 'disabled';
                              }else{
                                $chked = '';
                                $disabled = '';
                              }
                              
                              ?>
                              <tr>
                              <input type="hidden" name="classId" value="<?=$cn['classId']?> ">
                              <input type="hidden" name="sectionId" value="<?=$cn['sectionId']?> ">
                                <td><input <?= $chked; ?> <?= $disabled;?> type="checkbox" name="stuId[]" value="<?= $cn['id'] ?>">
                                <?php 
                                if(!empty($alreadyD))
                                {
                                  echo "<a href='?action=delete&delete_id=".$alreadyD[0]['id']."&classId=".$cn['classId']."&sectionId=".$cn['sectionId']."&groupId=".$feesGroupId."' >Remove</a>";
                                }
                                ?>
                                </td>
                                <td><?= $cn['name']; ?></td>
                                <td><?= $cn['className'] . " ( {$cn['sectionName']} )"; ?></td>
                                <td><?= $cn['user_id']; ?></td>
                                <td><?= $cn['father_name']; ?></td>
                              </tr>
                          <?php $i++;
                            }
                          } ?>

                          <input type="hidden" id="iValue" value="<?= $i ?>">
                        </tbody>

                      </table>
                    </div>
                    <input type="submit" name="assignStudents" class="btn mybtnColor" value="Assign">
                  </div>

                  <!-- /.card-body -->
                </div>
              </div>



            </div>

          </form>

          <!--/.col (right) -->
        </div>

        <!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- </div>
      </div>
    </div> -->
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->

    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->
  <script>
    //     $("#checkAll").change(function() {
    //     if($(this).prop('checked')) {
    //         console.log("Checked Box Selected");
    //         $(".feeTypeCheckbox").attr('checked', 'checked');
    //     } else {
    //       console.log("Checked Box deselect");
    //       $(".feeTypeCheckbox").attr('checked', false);
    //     }
    // });



    // $("#assignBtn").click(function(e){
    //   e.preventDefault();
    //   let totalStudents = $("#iValue").val();
    //   console.log(totalStudents);
    //   let abc;

    //   for(abc = 1; abc < totalStudents; abc++)
    //     {
    //       if($("#stu_id_"+abc)[0].checked == true)
    //       {
    //         console.log($("#stu_id_"+abc).val());
    //       }
    //     }
    // })
  </script>