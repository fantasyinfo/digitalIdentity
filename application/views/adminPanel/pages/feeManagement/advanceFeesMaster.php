<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');
    $this->load->model('CrudModel');

    // fetching fees type data
    $feeMasterData = $this->CrudModel->dbSqlQuery("SELECT FHM.newFeeGroupId FROM " . Table::newfeemasterTable . " FHM 
    WHERE FHM.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND FHM.status != '4' GROUP BY newFeeGroupId");

    $feeGroupData = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeesgroupsTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' ORDER BY id DESC");

    $feeTypesData = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeestypesTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' ORDER BY id DESC");


    $classData = $this->CrudModel->allClass(Table::classTable, $_SESSION['schoolUniqueCode']);
  $sectionData = $this->CrudModel->allSection(Table::sectionTable, $_SESSION['schoolUniqueCode']);




  if(isset($_POST['submit'])){
     //HelperClass::prePrintR($_POST);
    $totalChk = count($_POST['chk']);
    $feesData = [];
    for($i=0; $i < $totalChk; $i++){

     $feesTypeName =  $this->db->query("SELECT feeTypeName, durationType FROM " . Table::newfeestypesTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' AND id = '{$_POST['chk'][$i]}' ")->result_array()[0];

     if($feesTypeName['durationType'] == '1'){

        $monthlyFees = [
          'January' => $_POST['feesAmount'][$i],
          'February' => $_POST['feesAmount'][$i],
          'March' => $_POST['feesAmount'][$i],
          'April' => $_POST['feesAmount'][$i],
          'May' => $_POST['feesAmount'][$i],
          'June' => $_POST['feesAmount'][$i],
          'July' => $_POST['feesAmount'][$i],
          'August' => $_POST['feesAmount'][$i],
          'September' => $_POST['feesAmount'][$i],
          'October' => $_POST['feesAmount'][$i],
          'November' => $_POST['feesAmount'][$i],
          'December' => $_POST['feesAmount'][$i],
        ];

        array_push($feesData,[$feesTypeName['feeTypeName'] => $monthlyFees]);
     } else if($feesTypeName['durationType'] == '2'){
      array_push($feesData,[$feesTypeName['feeTypeName'] => $_POST['feesAmount'][$i]]);
     }

    }
    
    $insert = $this->db->query("INSERT INTO ".Table::advancefeessystem." (schoolUniqueCode,class_id,section_id,feesData,session_table_id) 
    VALUES ('{$_SESSION['schoolUniqueCode']}','{$_POST['class_id']}','{$_POST['section_id']}','".json_encode($feesData)."','{$_SESSION['currentSession']}')");
  }

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
              <!-- <h1 class="m-0"><?= $data['pageTitle'] ?> </h1> -->
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
          <div class="row">
            <!-- left column -->
            <?php //print_r($data['class']);
            ?>



              <div class="col-md-12">
              <form method="post" action="">
              <div class="card border-top-3">
                <div class="card-header">
                  <h4><?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                        echo 'Edit Fees Master';
                      } else {
                        echo 'Add Fees Master';
                      } ?></h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                 
                    <?php
                    if (isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
                      <input type="hidden" name="updateStateId" value="<?= $editId ?>">
                    <?php }

                    ?>
                    <div class="row">
                    <div class="form-group col-md-12">
                        <label>Select Class </label>
                        <select id="studentClassO" class="form-control  select2 select2-dark" required name="class_id" data-dropdown-css-class="select2-dark" style="width: 100%;">
                        <option>Please Select Class</option>
                        <?php
                        if (isset($classData)) {
                            foreach ($classData as $cd) {  ?>
                            <option value="<?= $cd['id'] ?>"><?= $cd['className'] ?></option>
                        <?php }
                        } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Select Section </label>
                        <select id="studentSectionO" class="form-control  select2 select2-dark" name="section_id" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                        <option>Please Select Section</option>
                        <?php
                        if (isset($sectionData)) {
                            foreach ($sectionData as $sd) {  ?>
                            <option value="<?= $sd['id'] ?>"><?= $sd['sectionName'] ?></option>
                        <?php }
                        } ?>
                        </select>
                    </div>
                        <hr>
                    <div class="col-md-12 mt-3">
                      <?php 
                      $i = 1;
                      foreach($feeTypesData as $f){ ?>

                        <div class="row">
                          <div class="col-md-4">
                            <input type="checkbox" class="checkClassBox" id="chkId_<?=$i?>" name="chk[]" value="<?= $f['id'];?>">
                            <span class="ml-3"><?=  $f['feeTypeName'] . " ( " . HelperClass::durationType[$f['durationType']] . " ) ";?></span>
                          </div>
                          <div class="col-md-6">
                            <input type="input" disabled id="txtId_<?=$i?>" class="form-control" name="feesAmount[]">
                          </div>

                        </div>
                        <hr>
                  <?php   $i++; } ?>
                       
                    </div>
                      <input type="hidden" id="iValue" value="<?= $i;?>">
                   
                    </div>
                 
                </div>

              </div>
              <div class="form-group col-md-12">
                        <button type="submit" name="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { echo 'update'; } else { echo 'submit';  } ?>" class="btn btn-block mybtnColor">Save</button>
                      </div>
              </form>
              </div>

          </div>



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
  <?php $this->load->view("adminPanel/pages/footer.php");
  
  ?>

  <script>


$(".checkClassBox").change(function(){

  let totalI = $("#iValue").val();
  console.log(totalI);
  for(let a = 1; a < totalI; a++){
    // console.log(a)
    // console.log($("#txtId_"+a))
    if($("#chkId_"+a).prop('checked')){
      console.log(a);
      $("#txtId_"+a).removeAttr('disabled');
    }else{
      $("#txtId_"+a).attr('disabled', 'disabled');
    }
  }

 
});
    

  </script>