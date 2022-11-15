<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php"); ?>

    <?php

    $dir = base_url() . HelperClass::studentImagePath;

  $studentData = $this->db->query("SELECT s.*,CONCAT('$dir',s.image) as image,cl.className,se.sectionName FROM " . Table::studentTable . " s
  JOIN " . Table::classTable . " cl ON cl.id = s.class_id
  JOIN " . Table::sectionTable . " se ON se.id = s.section_id
  WHERE s.status = '1' AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND s.id = '{$_GET['stu_id']}'")->result_array()[0];

  $oldSessionData = $this->db->query("SELECT sh.id as historyId, sh.student_id, sh.fees_due, ss.session_start_year, ss.session_end_year FROM ".Table::studentHistoryTable." sh 
  JOIN ".Table::schoolSessionTable." ss ON ss.id = sh.old_session_id OR ss.id = sh.session_table_id
  WHERE sh.student_id = '{$studentData['id']}'")->result_array();

  if(empty($oldSessionData))
  {
      //echo $this->db->last_query();
  }


    $discountData = $this->db->query("SELECT * FROM " . Table::newfeesdiscountsTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

    $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
    $sectionData = $this->db->query("SELECT * FROM " . Table::sectionTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();


    if (isset($_POST['submit'])) {
      $p = [
        'classId' => $_POST['classId'],
        'sectionId' => $_POST['sectionId']
      ];

     
    }



    if (isset($_POST['depostFees'])) {

      $randomToken = HelperClass::generateRandomToken();
      $filterToken = "token=".$randomToken;

      $inserArr['schoolUniqueCode'] = $this->CrudModel->sanitizeInput($_SESSION['schoolUniqueCode']);
      $inserArr['stuId'] = $this->CrudModel->sanitizeInput($_POST['stuId']);
      $inserArr['classId'] = $this->CrudModel->sanitizeInput($_POST['classId']);
      $inserArr['sectionId'] = $this->CrudModel->sanitizeInput($_POST['sectionId']);
      $inserArr['fmtId'] = $this->CrudModel->sanitizeInput($_POST['fmtId']);
      $inserArr['nftId'] = $this->CrudModel->sanitizeInput($_POST['nftId']);
      $inserArr['nfgId'] = $this->CrudModel->sanitizeInput($_POST['nfgId']);
      $inserArr['depositDate'] = $this->CrudModel->sanitizeInput($_POST['depositDate']);
      $inserArr['depositAmount'] = $this->CrudModel->sanitizeInput($_POST['depositAmount']);

      $inserArr['invoiceId'] = ($invoiceID = $this->db->query("SELECT invoiceId FROM " . Table::newfeessubmitmasterTable . " WHERE invoiceId IS NOT NULL ORDER BY id DESC")->result_array()) ? $invoiceID[0]['invoiceId'] + 1 : '0';

      $inserArr['discount'] = $this->CrudModel->sanitizeInput(@$_POST['discount']);
      $inserArr['fine'] = $this->CrudModel->sanitizeInput(@$_POST['fine']);
      $inserArr['paid'] = $this->CrudModel->sanitizeInput((@$_POST['depositAmount'] + @$_POST['discount']) - @$_POST['fine']); // total payment after discount and fine
      $inserArr['paymentMode'] = $this->CrudModel->sanitizeInput($_POST['paymentMode']);
      $inserArr['depositerName'] = $this->CrudModel->sanitizeInput($_POST['depositerName']);
      $inserArr['depositerAddress'] = $this->CrudModel->sanitizeInput($_POST['depositerAddress']);
      $inserArr['depositerMobileNo'] = $this->CrudModel->sanitizeInput(@$_POST['depositerMobileNo']);
      $inserArr['note'] = $this->CrudModel->sanitizeInput(@$_POST['note']);
      $inserArr['randomToken'] = $filterToken;
      $inserArr['session_table_id'] = $this->CrudModel->sanitizeInput($_SESSION['currentSession']);

      $insertId = $this->CrudModel->insert(Table::newfeessubmitmasterTable, $inserArr);

      if ($insertId) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Fees Submited Successfully',
        ];
        $this->session->set_userdata($msgArr);

        

        $insertArr = [
        'schoolUniqueCode' => $_SESSION['schoolUniqueCode'],
        'token' => $filterToken,
        'for_what' => 'Fees Invoice',
        'insertId' => $insertId
        ];

        $d = $this->CrudModel->insert(Table::tokenFilterTable,$insertArr);

       
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Fees Not Submited',
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "feesManagement/collectStudentFee?stu_id=" . $inserArr['stuId']);
    }


    if (isset($_POST['depositsOldDue'])) {

      $randomToken = HelperClass::generateRandomToken();
      $filterToken = "token=".$randomToken;


      $details =   $this->db->query("SELECT sh.fees_due FROM ".Table::studentHistoryTable." sh 
      WHERE sh.student_id = '{$_POST['stuId']}' AND id = '{$_POST['historyId']}' LIMIT 1")->result_array()[0];

     $oldDueAmout =  $details['fees_due'];
      $depositAmt = $_POST['depositAmount'];
      $nowBalance = $oldDueAmout - ($depositAmt + @$_POST['discount']);
      if($nowBalance > 0)
      {
        $updateAmount = $nowBalance;
      }else{
        $updateAmount = 0;
      }
      $updateFees = $this->db->query("UPDATE ".Table::studentHistoryTable." SET fees_due = '$updateAmount' WHERE student_id = '{$_POST['stuId']}' AND id = '{$_POST['historyId']}' ");

      $inserArr['schoolUniqueCode'] = $this->CrudModel->sanitizeInput($_SESSION['schoolUniqueCode']);
      $inserArr['stuId'] = $this->CrudModel->sanitizeInput($_POST['stuId']);
      $inserArr['classId'] = '0';
      $inserArr['sectionId'] = '0';
      $inserArr['fmtId'] = $_POST['historyId'];
      $inserArr['nftId'] = '0';
      $inserArr['nfgId'] = '0';
      $inserArr['depositDate'] = $this->CrudModel->sanitizeInput(date('Y-m-d'));
      $inserArr['depositAmount'] = $this->CrudModel->sanitizeInput($_POST['depositAmount']);

      $inserArr['invoiceId'] = ($invoiceID = $this->db->query("SELECT invoiceId FROM " . Table::newfeessubmitmasterTable . " WHERE invoiceId IS NOT NULL ORDER BY id DESC")->result_array()) ? $invoiceID[0]['invoiceId'] + 1 : '0';

      $inserArr['discount'] = $this->CrudModel->sanitizeInput(@$_POST['discount']);
      $inserArr['fine'] = '0';
      $inserArr['paid'] = $this->CrudModel->sanitizeInput($_POST['depositAmount']+ @$_POST['discount']);
      $inserArr['paymentMode'] = '0';
      $inserArr['depositerName'] = '0';
      $inserArr['depositerAddress'] = '0';
      $inserArr['depositerMobileNo'] = '0';
      $inserArr['note'] = 'Old Session Due Deposits';
      $inserArr['randomToken'] = $filterToken;
      $inserArr['session_table_id'] = $this->CrudModel->sanitizeInput($_SESSION['currentSession']);

      $insertId = $this->CrudModel->insert(Table::newfeessubmitmasterTable, $inserArr);

      if ($insertId) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Fees Submited Successfully',
        ];
        $this->session->set_userdata($msgArr);

        

        $insertArr = [
        'schoolUniqueCode' => $_SESSION['schoolUniqueCode'],
        'token' => $filterToken,
        'for_what' => 'Fees Invoice',
        'insertId' => $insertId
        ];

        $d = $this->CrudModel->insert(Table::tokenFilterTable,$insertArr);

       
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Fees Not Submited',
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "feesManagement/collectStudentFee?stu_id=" . $inserArr['stuId']);
    }




    if (isset($_GET['action']) && $_GET['action'] == 'deleteInvoice') {
      $delete =  $this->db->query("UPDATE " . Table::newfeessubmitmasterTable . " SET status = '4' WHERE id = '{$_GET['delete_id']}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

      if ($delete) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Fees Deleted Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Fees Not Deleted',
        ];
        $this->session->set_userdata($msgArr);
      }
        header("Refresh:1 " . base_url() . "feesManagement/collectStudentFee?stu_id=" . $_GET['stu_id']);

    }





    if(isset($_POST['depostFeesArr']))
    {
     //HelperClass::prePrintR($_POST);
      $totalFeesSubmited = count($_POST['fmtId']);
      for($i=0; $i < $totalFeesSubmited; $i++)
      {
        $randomToken = HelperClass::generateRandomToken();
        $filterToken = "token=".$randomToken;

        $inserArr['schoolUniqueCode'] = $this->CrudModel->sanitizeInput($_SESSION['schoolUniqueCode']);
        $inserArr['stuId'] = $this->CrudModel->sanitizeInput($_POST['stuId'][$i]);
        $inserArr['classId'] = $this->CrudModel->sanitizeInput($_POST['classId'][$i]);
        $inserArr['sectionId'] = $this->CrudModel->sanitizeInput($_POST['sectionId'][$i]);
        $inserArr['fmtId'] = $this->CrudModel->sanitizeInput($_POST['fmtId'][$i]);
        $inserArr['nftId'] = $this->CrudModel->sanitizeInput($_POST['nftId'][$i]);
        $inserArr['nfgId'] = $this->CrudModel->sanitizeInput($_POST['nfgId'][$i]);
        $inserArr['depositDate'] = $this->CrudModel->sanitizeInput($_POST['depositDate']);
        $inserArr['depositAmount'] = $this->CrudModel->sanitizeInput($_POST['depositAmount'][$i]);

        $inserArr['invoiceId'] = ($invoiceID = $this->db->query("SELECT invoiceId FROM " . Table::newfeessubmitmasterTable . " WHERE invoiceId IS NOT NULL ORDER BY id DESC")->result_array()) ? $invoiceID[0]['invoiceId'] + 1 : '0';

        $inserArr['discount'] = $this->CrudModel->sanitizeInput(@$_POST['discount']);
        $inserArr['fine'] = $this->CrudModel->sanitizeInput(@$_POST['fine']);
        $inserArr['paid'] = $this->CrudModel->sanitizeInput(@$_POST['totalDepositAmount']); // total payment after discount and fine
        $inserArr['paymentMode'] = $this->CrudModel->sanitizeInput($_POST['paymentMode']);
        $inserArr['depositerName'] = $this->CrudModel->sanitizeInput(@$_POST['depositerName']);
        $inserArr['depositerAddress'] = $this->CrudModel->sanitizeInput(@$_POST['depositerAddress']);
        $inserArr['depositerMobileNo'] = $this->CrudModel->sanitizeInput(@$_POST['depositerMobileNo']);
        $inserArr['note'] = $this->CrudModel->sanitizeInput(@$_POST['note']);
        $inserArr['randomToken'] = $filterToken;
        $inserArr['session_table_id'] = $this->CrudModel->sanitizeInput($_SESSION['currentSession']);

        $insertId = $this->CrudModel->insert(Table::newfeessubmitmasterTable, $inserArr);

        if ($insertId) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Fees Submited Successfully',
          ];
          $this->session->set_userdata($msgArr);

          

          $insertArr = [
          'schoolUniqueCode' => $_SESSION['schoolUniqueCode'],
          'token' => $filterToken,
          'for_what' => 'Fees Invoice',
          'insertId' => $insertId
          ];

          $d = $this->CrudModel->insert(Table::tokenFilterTable,$insertArr);

        
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Fees Not Submited',
          ];
          $this->session->set_userdata($msgArr);
        }
      }
      header("Refresh:1 " . base_url() . "feesManagement/collectStudentFee?stu_id=" . $_GET['stu_id']);

    }



    ?>









    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <!-- <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0"><?= $data['pageTitle'] ?> </h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><?= $data['pageTitle'] ?> </li>
              </ol>
            </div>
          </div>
        </div> -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
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
          <div class="row">
            <div class="col-md-12">
              <div class="card border-top-3">
                <div class="card-header">

                </div>
                <div class="card-body">

                <div class="row">
                  <div class="col-md-2">
                   <img src="<?= $studentData['image'];?>" width="200px;">
                  </div>
                  <div class="col-md-10">
                    <div class="table-responsive">
                    <table class="table align-middle mb-0 bg-white ">
                      <tr>
                        <td><b>Name:</b> </td>
                        <td><?= $studentData['name'];?></td>
                        <td><b>Class: </b></td>
                        <td><?= $studentData['className'] .  " ( {$studentData['sectionName']} )";?></td>
                      </tr>
                      <tr>
                        <td><b>Father Name: </b></td>
                        <td><?= @$studentData['father_name'];?></td>
                        <td><b>User Id: </b></td>
                        <td><?= $studentData['user_id'] ;?></td>
                      </tr>
                      <tr>
                        <td><b>Mobile No: </b></td>
                        <td><?= @$studentData['mobile'];?></td>
                        <td><b>Roll No: </b></td>
                        <td><?= $studentData['roll_no'] ;?></td>
                      </tr>
                      <tr>
                        <td><b>Admission No: </b></td>
                        <td><?= @$studentData['admission_no'];?></td>
                        <td><b>SR No: </b></td>
                        <td><?= $studentData['sr_number'] ;?></td>
                      </tr>
                    </table>
                    </div>
                  </div>
                </div>
               
                </div>
              </div>
              <?php 
                  
                  if(!empty($oldSessionData))
                  { ?>
                  <div class="card border-top border-danger">
                   <div class="card-header">
                   <h4>Old Session Due:</h4>
                  </div>
                  <div class="card-body bg-light border-top border-danger">
                    <div class="table-responsive">
                      <table class="table mb-0 bg-white align-middle">
                        <thead class="bg-light">
                          <th>Session Name</th>
                          <th>Invoice Id</th>
                          <th>Deposit Date</th>
                          <th>Discounts</th>
                          <th>Deposits Amount</th>
                          <th>Balance</th>
                          <th>Action</th>
                        </thead>
                        <?php foreach($oldSessionData as $old){ 
                          if($old['fees_due'] < 0){continue;}
                          ?>
                        <tr>
                          <td><h5>Session Years : <?= $old['session_start_year'] . ' - ' . $old['session_end_year']; ?></h5></td>
                          <td colspan="4" ></td>
                          <td><h5>Amount : <i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($old['fees_due'],2);  ?></h5></td>
                          <td><button type="button" class="btn btn-dark" onclick="submitOldFees('<?= $old['student_id']?>','<?= $old['historyId']?>','<?= $old['fees_due'] ?>')"><i class="fa-solid fa-plus"></i></button></td>
                          <tr>
                          <?php
                        
                        
                        $feesDepositsOld = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeessubmitmasterTable . " WHERE stuId = '{$old['student_id']}' AND fmtId = '{$old['historyId']}' AND status = '1'");
                        //print_r($feesDepositsOld);

                        foreach($feesDepositsOld as $fDO){?>

                            <tr class="bg-light-dark">
                                <td><img src="<?= base_url() . HelperClass::uploadImgDir . 'table-arrow.png' ?>"></td>
                                <td>
                                    <?= $fDO['invoiceId']; ?>
                                </td>
                                <td>
                                    <?= date('d-m-y', strtotime($fDO['depositDate'])); ?>
                                </td>
                                <td>
                                    <?= number_format($fDO['discount'], 2); ?>
                                </td>
                                <td>
                                    <?= number_format($fDO['depositAmount'], 2); ?>
                                </td>
                                <td></td>
                                <td>
                                    <a target="_blank" href="<?= base_url('feesInvoice?fees_id=') . $fDO['randomToken'] ?>" class="btn btn-info"> <i
                                            class="fa-solid fa-file-invoice"></i> </a>&nbsp;&nbsp;&nbsp;
                                    <a href="?action=deleteInvoice&delete_id=<?= $fDO['id'] ?>&stu_id=<?= $old['student_id'] ?>"
                                        onclick="return confirm('Are you sure want to delete this?');"><i
                                            class="fa-sharp fa-solid fa-trash"></i></a>
                                </td>

                            </tr>

                      <?php  }


                        
                        
                        }
                        
                        
                        ?>
                      </table>
                    </div>
                 
                  </div>
                   
                  </div>
                 <?php }
                  
                  
                  ?>
              <div class="card border-top-3">
                <div class="card-header">
                  Fees Details
                </div>
                <div class="row px-5 py-2">
                  <div class="col-md-12">
                    <button class="btn btn-warning" id="collectAllPayment"><i class="fa-solid fa-indian-rupee-sign"></i> Collect All</button>
                    <!-- <button class="btn btn-dark"> Print All</button> -->
                  </div>
                </div>
                <div class="card-body">
                
                  <div class="table-responsive">
                    <table class="table align-middle mb-0 bg-white ">
                      <thead class="bg-light">
                        <tr>
                          <th><input type="checkbox" id="checkAll"></th>
                          <th>Group</th>
                          <th>Fees Code</th>
                          <th>Due Date</th>
                          <th>Status</th>
                          <th>Amount - Fine ( <i class="fa-solid fa-indian-rupee-sign"></i> )</th>
                          <th>Invoice Id</th>
                          <th>Mode</th>
                          <th>Date</th>
                          <th>Discount ( <i class="fa-solid fa-indian-rupee-sign"></i> )</th>
                          <th>Fine ( <i class="fa-solid fa-indian-rupee-sign"></i> )</th>
                          <th>Paid ( <i class="fa-solid fa-indian-rupee-sign"></i> )</th>
                          <th>Balance ( <i class="fa-solid fa-indian-rupee-sign"></i> )</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                    
                          <?php 

                          include('normalFees.php');
                          //include('studentExtrafees.php');
                           ?>

                        <tr>
                        <input type="hidden" value="<?=$j?>" id="jNO">
                          <td colspan="4"><h4 class="text-center">Grand Total</h3></td>
                          <td><?php echo number_format($gAmount,2) . " + " .  " " .   number_format($gFine,2) . "</span>" ?> </td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td><?= number_format($gdiscount ,2);?></td>
                          <td><?= number_format($gFineD ,2);?></td>
                          <td><?= number_format($gPaid ,2);?></td>
                          <td><?= number_format( $gBalance ,2);?></td>
                          <td></td>
                        </tr>
                       
                      </tbody>

                    </table>
                  
                  </div>
                </div>
              </div>

            </div>

          </div>
          <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
      </div>
      <!-- /.control-sidebar -->
    </div>
    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>


  <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog border border-success modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-light">
          <h5 id="modalTitle"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="feesDetails">

        </div>
        <div class="modal-footer" style=" border-bottom:2px solid red;">
        </div>
      </div>
    </div>
  </div>



  <!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Collect All Fees</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer"  style=" border-bottom:2px solid red;">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>




  <script>
    function submitFees(x) {
      // console.log(x);
      let fgName = $("#fgName_" + x).val();
      let todayDate = $("#todayDate_" + x).val();
      let amount = $("#amount_" + x).val();
      let fine = $("#fine_" + x).val();
      let fmtId = $("#fmtId_" + x).val();
      let nftId = $("#nftId_" + x).val();
      let nfgId = $("#nfgId_" + x).val();
      let stuId = $("#stuId_" + x).val();
      let classId = $("#classId_" + x).val();
      let sectionId = $("#sectionId_" + x).val();


      let discountD = $("#discount_" + x).val();
      let fineD = $("#fineD_" + x).val();
      let depositD = $("#deposit_" + x).val();

      let amountNow;
      if (amount > 0) {
        amountNow = amount;
        if (depositD != null || depositD != 0) {
          amountNow = amount - depositD;
          if(discountD != null || discountD != 0)
          {
            amountNow = amount - depositD - discountD ;
          }
        }
      } else {
        amountNow = 0;
      }

      let fineNow;
      if (fine > 0) {
        fineNow = fine;
        if (fineD != null || fineD != 0) {
          fineNow = fine - fineD;
        }
      } else {
        fineNow = 0.00;
      }


      console.log("Discount: " + discountD);
      console.log("Fine : " + fineD);
      console.log("Deposits: " + depositD);
      console.log("Fine : " + fine);
      console.log("Fine Now: " + fineNow);

      //  console.log(todayDate)
      $("#modalTitle").html(fgName);
      let html = `<form method="POST">
                <input type="hidden" name="stuId" value="${stuId}">
                <input type="hidden" name="classId" value="${classId}">
                <input type="hidden" name="sectionId" value="${sectionId}">
                <input type="hidden" name="fmtId" value="${fmtId}">
                <input type="hidden" name="nftId" value="${nftId}">
                <input type="hidden" name="nfgId" value="${nfgId}">
                <table class="table"> 
                  <tbody>
                    <tr>
                     <td><label>Date <span style="color:red;">*</span></label></td>
                     <td><input class="form-control" type="date" name="depositDate" value="${todayDate}" required></td>
                    </tr>
                    <tr>
                     <td><label>Amount <span style="color:red;">*</span></label></td>
                     <td>
                     <input class="form-control" type="hidden" id="depositAmountFixValue" name="depositAmount" value="${amountNow}" required>
                     <input class="form-control" type="number" id="depositAmount" name="depositAmount" value="${amountNow}" required>
                     </td>
                    </tr>
                    <tr>
                     <td> <label>Discounts</label></td>
                     <td> <select  id="dis" class="form-control  select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="showDiscount()">
                              <option>Select Discounts</option>
                              <?php
                              if (isset($discountData)) {
                                foreach ($discountData as $group) { ?>
                                  <option value="<?= $group['amount'] ?>"><?= $group['feeDiscountName'] . ' - ' . $group['amount']; ?></option>
                              <?php }
                              }  ?>
                            </select>
                    </td>
                    </tr>
                    <tr>
                   
                    <td><label>Discounts</label></td>
                     <td><input class="form-control" type="number" id="showDis" name="discount" value="0" ></td>
                     </tr>
                    <tr>
                    <td><label>Fine</label></td>
                     <td><input class="form-control" type="number" name="fine" value="${fineNow}" ></td>
                    </tr>
                    <tr>
                    <td><label>Payment Mode <span style="color:red;">*</span></label></td>
                     <td>
                     <input class="form-check-input" type="radio" name="paymentMode" id="inlineRadio1" value="1" required> Offline &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                     <input class="form-check-input" type="radio" name="paymentMode" id="inlineRadio1" value="2" required> Online
                     </td>
                    </tr>
                    <tr>
                     <td><label>Depositer Name <span style="color:red;">*</span></label></td>
                     <td><input class="form-control" type="text" name="depositerName" required></td>
                    </tr>
                    <tr>
                     <td><label>Depositer Address <span style="color:red;">*</span></label></td>
                     <td><input class="form-control" type="text" name="depositerAddress" required></td>
                    </tr>
                    <tr>
                     <td><label>Depositer Mobile </label></td>
                     <td><input class="form-control" type="number" name="depositerMobileNo" ></td>
                    </tr>
                    <tr>
                     <td><label>Note</label></td>
                     <td>
                     <textarea class="form-control" id="exampleFormControlTextarea1" name="note" rows="3"></textarea>
                     </td>
                    </tr>
                    <tr>
                    <td colspan="2"><button type="submit" name="depostFees" class="btn btn-warning btn-lg btn-block">Collect Fees</button>
                    </tr>
                   </tbody>
                </table>
                   </form>`;
      $("#feesDetails").html(html);
      $("#detailsModal").modal('show');
    }


    function showDiscount() {
      $("#showDis").val("");
      let dis = $("#dis").val();
      let damount = $("#depositAmount").val();
      let damountFixValue = $("#depositAmountFixValue").val();
      if (dis != 'Select Discounts') {
        $("#showDis").val(dis);

        let newVal = parseFloat(damount) - parseFloat(dis);
        if(newVal > 0)
        {
          $("#depositAmount").val(newVal);
        }else{
          $("#depositAmount").val(0);
        }
        
        //damount.val(newVal);
      } else {
        $("#showDis").val(0);
        // damount = $("#depositAmount").val();
        console.log(damount);
        $("#depositAmount").val(damountFixValue);
      }

    }



    $("#checkAll").change(function() {
    if($(this).prop('checked')) {
        console.log("Checked Box Selected");
        $(".feeTypeCheckbox").attr('checked', 'checked');
        
        let totalRows = parseInt($("#jNO").val());
        //console.log(totalRows);
        let abc;
       
        for(abc = 1; abc < totalRows; abc++)
        {
          //console.log($("#fees_id_"+abc).val());
        }
    } else {
      console.log("Checked Box deselect");
      $(".feeTypeCheckbox").attr('checked', false);
    }
});




  $("#collectAllPayment").click(function(e){
    e.preventDefault();
    let totalRows = parseInt($("#jNO").val());
    console.log(totalRows);
    let abc;
    


    let fgName;
    let todayDate;
    let amount;
    let fine;
    let fmtId;
    let nftId;
    let nfgId;
    let stuId;
    let classId;
    let sectionId;
    let discountD;
    let fineD;
    let depositD;
    let amountNow;
    let fineNow;
    let html;

    let totalAmount = 0;
    let totalFine = 0;
    

    html += `<form method="POST">
                
                <table class="table"> 
                  <tbody>
                  
                  <tr>
                     <td><label>Date <span style="color:red;">*</span></label></td>
                     <td><input class="form-control" type="date" name="depositDate" required></td>
                    </tr>
                    <td><label>Payment Mode <span style="color:red;">*</span></label></td>
                     <td>
                     <input class="form-check-input" type="radio" name="paymentMode" id="inlineRadio1" value="1" required> Offline &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                     <input class="form-check-input" type="radio" name="paymentMode" id="inlineRadio1" value="2" required> Online
                     </td>
                    </tr>
                    <tr>
                     <td><label>Note</label></td>
                     <td>
                     <textarea class="form-control" id="exampleFormControlTextarea1" name="note" rows="3"></textarea>
                     </td>
                    </tr>
                    <tr>
                     <td><label>Depositer Name <span style="color:red;">*</span></label></td>
                     <td><input class="form-control" type="text" name="depositerName" required></td>
                    </tr>
                    <tr>
                     <td><label>Depositer Address <span style="color:red;">*</span></label></td>
                     <td><input class="form-control" type="text" name="depositerAddress" required></td>
                    </tr>
                    <tr>
                     <td><label>Depositer Mobile </label></td>
                     <td><input class="form-control" type="number" name="depositerMobileNo" ></td>
                    </tr>
                  `;

    for(abc = 1; abc < totalRows; abc++)
    {
      if($("#fees_id_"+abc)[0].checked == true)
      {
        //console.log("yeh payment le lo ji..." + $("#fees_id_"+abc).val());
        let x = $("#fees_id_"+abc).val();
        //console.log(x);
        fgName = $("#fgName_" + x).val();
        todayDate = $("#todayDate_" + x).val();
        amount = $("#amount_" + x).val();
        fine = $("#fine_" + x).val();
        fmtId = $("#fmtId_" + x).val();
        nftId = $("#nftId_" + x).val();
        nfgId = $("#nfgId_" + x).val();
        stuId = $("#stuId_" + x).val();
        classId = $("#classId_" + x).val();
        sectionId = $("#sectionId_" + x).val();


        discountD = $("#discount_" + x).val();
        fineD = $("#fineD_" + x).val();
        depositD = $("#deposit_" + x).val();

        
        if (amount > 0) {
          amountNow = amount;
          if (depositD != null || depositD != 0) {
            amountNow = amount - depositD;
            if(discountD != null || discountD != 0)
            {
              amountNow = amount - depositD - discountD ;
            }
          }
        } else {
          amountNow = 0;
        }

       
        if (fine > 0) {
          fineNow = fine;
          if (fineD != null || fineD != 0) {
            fineNow = fine - fineD;
          }
        } else {
          fineNow = 0.00;
        }

        if(amountNow <=0)
        {
          continue;
        }
        let showFine = true;
        if(fineNow <= 0)
        {
          showFine = false;
        }

        html += `<input type="hidden" name="stuId[]" value="${stuId}">
                <input type="hidden" name="classId[]" value="${classId}">
                <input type="hidden" name="sectionId[]" value="${sectionId}">
                <input type="hidden" name="fmtId[]" value="${fmtId}">
                <input type="hidden" name="nftId[]" value="${nftId}">
                
                <input type="hidden" name="nfgId[]" value="${nfgId}">`;

                html += `
                <tr>
                  <td><b>${fgName}</b></td>`;
                if(showFine)
                {
                  totalAmount = totalAmount + amountNow + fineNow;
                  totalFine = totalFine + fineNow;
                  html += `
                  <input type="hidden" name="depositAmount[]" value="${amountNow}">
                  <td><i class="fa-solid fa-indian-rupee-sign"></i> ${amountNow} + <span style="color:red;"><i class="fa-solid fa-indian-rupee-sign"></i> ${fineNow}</span></td>
                  </tr>`;
                
                }else{
                  totalAmount = totalAmount + amountNow;
                  html += `
                  <input type="hidden" name="depositAmount[]" value="${amountNow}">
                  <td><i class="fa-solid fa-indian-rupee-sign"></i> ${amountNow}</td>
                  </tr>`;
                }
      }else{
        //console.log(abc + " Ishki Pyament rehnge do../.");
      }
    }

    console.log(totalAmount);
    html += `<tr><td><b>Total Amount </b></td>`;

    if(totalFine > 0)
    {
      html +=`<td><h4>Amount: <i class="fa-solid fa-indian-rupee-sign"></i>  ${totalAmount} +  Fine: <i class="fa-solid fa-indian-rupee-sign"></i> ${totalFine}</h4></td>`;
    }else{
      html +=`<td><h4></i> Amount: <i class="fa-solid fa-indian-rupee-sign"></i>  ${totalAmount}</h4></td>`;
    }
    
    let totalToPay = parseInt(totalAmount)+ parseInt(totalFine);
   html += `<tr>
   <input type="hidden" name="totalDepositAmount" value="${totalToPay}">
   <td><h4>Total Amount To Collect: </td><td><h4><i class="fa-solid fa-indian-rupee-sign"></i> ${totalToPay}</td></h4></tr>
    
    <tr><td colspan="2"><button type="submit" name="depostFeesArr" class="btn btn-warning btn-lg btn-block">Collect Fees</button>
                    </tr>
                   </tbody>
                </table>
                   </form>`;
                   $("#modalTitle").html("Collect All Fees");
    $("#feesDetails").html(html);

       $("#detailsModal").modal('show');
    
  })






  function submitOldFees(studentId,studentHistoryId,amount)
  {
    console.log(studentId);
    console.log(studentHistoryId);
    //  console.log(todayDate)
    $("#modalTitle").html("Old Session Dues Fees");
      let html = `<form method="POST">
                <input type="hidden" name="stuId" value="${studentId}">
                <input type="hidden" name="historyId" value="${studentHistoryId}">
                <table class="table"> 
                  <tbody>
                    <tr>
                     <td><label>Amount <span style="color:red;">*</span></label></td>
                     <td>
                     <input class="form-control" type="hidden" id="depositAmountFixValue" name="depositAmountFixValue" value="${amount}" required>
                     <input class="form-control" type="number" id="depositAmount" name="depositAmount" value="${amount}" required>
                     </td>
                    </tr>
                    <tr>
                     <td> <label>Discounts</label></td>
                     <td> <select  id="dis" class="form-control  select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="showDiscount()">
                              <option>Select Discounts</option>
                              <?php
                              if (isset($discountData)) {
                                foreach ($discountData as $group) { ?>
                                  <option value="<?= $group['amount'] ?>"><?= $group['feeDiscountName'] . ' - ' . $group['amount']; ?></option>
                              <?php }
                              }  ?>
                            </select>
                    </td>
                    </tr>
                    <tr>
                    <td><label>Discounts</label></td>
                     <td><input class="form-control" type="number" id="showDis" name="discount" value="0" ></td>
                     </tr>
                    <tr>
                    <td colspan="2"><button type="submit" name="depositsOldDue" class="btn btn-warning btn-lg btn-block">Collect Fees</button>
                    </tr>
                   </tbody>
                </table>
                   </form>`;
      $("#feesDetails").html(html);
      $("#detailsModal").modal('show');
  }



  </script>