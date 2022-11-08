<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php"); ?>



    <?php

    $dir = base_url() . HelperClass::studentImagePath;

  //   $studentData = $this->db->query("SELECT s.* , CONCAT('$dir',s.image) as image FROM " . Table::studentTable . " s 
  // WHERE s.status = '1' 
  // AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
  // AND s.id = '{$_GET['stu_id']}' 
  // LIMIT 1")->result_array()[0];

  $studentData = $this->db->query("SELECT s.*,CONCAT('$dir',s.image) as image,cl.className,se.sectionName FROM " . Table::studentTable . " s
  JOIN " . Table::classTable . " cl ON cl.id = s.class_id
  JOIN " . Table::sectionTable . " se ON se.id = s.section_id
  WHERE s.status = '1' AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND s.id = '{$_GET['stu_id']}'")->result_array()[0];




    $sql = "SELECT fee_group_id FROM " . Table::newfeeclasswiseTable . " WHERE class_id = '{$studentData['class_id']}' AND section_id = '{$studentData['section_id']}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'  GROUP BY fee_group_id";

    $feesDetails = $this->db->query($sql)->result_array();




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

                        $gAmount = 0.00;
                        $gFine = 0.00;
                        $gdiscount = 0.00;
                        $gFine = 0.00;
                        $gFineD = 0.00;
                        $gPaid = 0.00;
                        $gBalance = 0.00;






                        $todayDate = date('Y-m-d');
                        // echo '<pre>'; print_r($feesDetails);


                        // filter data for current session only
                        $j = 1;
                        $a = 1;
                        $b = 1;
                        // $depositAmt = 0;
                        // $fineAmt = 0;
                        // $discountAmt = 0;
                        foreach ($feesDetails as $f) {
                          $sqln = "SELECT nfm.id as fmtId, nfm.amount, nfm.fineType,nfm.finePercentage,nfm.fineFixAmount, nfm.dueDate,
                     nft.id as nftId, nft.feeTypeName, nft.shortCode, nfg.id as nfgId, nfg.feeGroupName FROM " . Table::newfeemasterTable . " nfm 
                    JOIN " . Table::newfeestypesTable . " nft ON nft.id = nfm.newFeeType
                    JOIN " . Table::newfeesgroupsTable . " nfg ON nfg.id = nfm.newFeeGroupId
                    WHERE nfm.newFeeGroupId = '{$f['fee_group_id']}' ";

                          $groupWiseFeeDetails = $this->db->query($sqln)->result_array();
                          $fGN = @$groupWiseFeeDetails[0]['feeGroupName'];

                        ?>



                          <?php

                          foreach ($groupWiseFeeDetails as $gwf) {
                            // search student all depoists
                            $fineAmount = 0.00;
                            if ($todayDate > $gwf['dueDate']) {
                              if ($gwf['fineType'] == '1') {
                                $fineAmount = 0.00;
                              } else if ($gwf['fineType'] == '2') {
                                // percenrtage
                                $fineAmount =  ceil($gwf['amount'] * @$gwf['finePercentage'] / 100);
                              } else if ($gwf['fineType'] == '3') {
                                // fixed amount
                                $fineAmount =  @$gwf['fineFixAmount'];
                              }
                            } else {
                              $fineAmount = 0.00;
                            }

                            if ($fineAmount == 0) {
                              $fShow = false;
                            } else {
                              $fShow = true;
                            }



                            $feesDeposits =  $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeessubmitmasterTable . " WHERE stuId = '{$_GET['stu_id']}' AND classId = '{$studentData['class_id']}' AND sectionId = '{$studentData['section_id']}' AND fmtId = '{$gwf['fmtId']}' AND nftId = '{$gwf['nftId']}' AND nfgId = '{$gwf['nfgId']}' AND status = '1' AND session_table_id = '{$_SESSION['currentSession']}'");


                            $depositAmt = 0.00;
                            $fineAmt = 0.00;
                            $discountAmt = 0.00;
                            if (!empty($feesDeposits)) {


                              foreach ($feesDeposits as $fd) {
                                $depositAmt =  $depositAmt + $fd['depositAmount'];
                                $fineAmt = $fineAmt + $fd['fine'];
                                $discountAmt = $discountAmt + $fd['discount'];
                                $b++;
                              }
                            }

                            $amountNow = $gwf['amount'] - $depositAmt;
                          ?>
                            <tr>
                              <td><input type="checkbox" class="feeTypeCheckbox" id="fees_id_<?=$j?>" value="<?=$j?>"></td>
                              <td><?= $fGN; ?></td>
                              <td><?= $gwf['shortCode']; ?></td>
                              <td><?= date('d-m-Y', strtotime($gwf['dueDate'])); ?></td>
                              <td><?php

                                  $bstatusBalance =  ($gwf['amount'] - $depositAmt) - $discountAmt;
                                  if ($bstatusBalance > 0 && $bstatusBalance < $gwf['amount']) {
                                    // partail
                                    echo "<span class='badge badge-warning'>Partial</span>";
                                  } else if ($amountNow == $gwf['amount']) {
                                    // due
                                    echo "<span class='badge badge-danger'>UnPaid</span>";
                                  } else if($bstatusBalance == 0) {
                                    // paid
                                    echo "<span class='badge badge-success'>Paid</span>";
                                  }


                                  ?></td>
                              <td><?php
                                $gAmount = $gAmount + $gwf['amount'];
                                $gFine = $gFine + $fineAmount;
                                  if ($fShow) {
                                    echo number_format($gwf['amount'],2) . " + <span style='color:red;'> " . number_format($fineAmount,2) . "</span>";
                                  } else {
                                     $aaount = $gwf['amount'];
                                    echo number_format($aaount,2);
                                  }

                                  ?></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td><?php echo number_format($discountAmt,2);
                              $gdiscount  = $gdiscount  + $discountAmt;
                              ?></td>
                              <td><?php echo  number_format($fineAmt,2); 
                              $gFineD = $gFineD + $fineAmt;
                              
                              ?></td>
                              <td><?php echo number_format($depositAmt,2);
                              $gPaid = $gPaid + $depositAmt;
                              
                              ?></td>
                              <td><?php
                                  if ($amountNow > 0) {
                                   
                                    $bblance = $amountNow - $discountAmt;
                                    if($bblance > 0)
                                    {
                                      echo number_format($bblance,2);
                                      $gBalance = $gBalance + $bblance;
                                    }else{
                                      echo 0.00;
                                      $gBalance = $gBalance;
                                    }
                                    
                                    
                                  } else {
                                  
                                    $bblance =  ($gwf['amount'] - $depositAmt) - $discountAmt;
                                  
                                    if($bblance > 0)
                                    {
                                      echo number_format($bblance,2);
                                      $gBalance = $gBalance + $bblance;
                                    }else{
                                      echo 0.00;
                                      $gBalance = $gBalance;
                                    }
                                  
                                  }
                                  
                                  ?></td>
                              <td>

                                <input type="hidden" id="deposit_<?= $j ?>" value="<?= $depositAmt ?>">
                                <input type="hidden" id="discount_<?= $j ?>" value="<?= $discountAmt ?>">
                                <input type="hidden" id="fineD_<?= $j ?>" value="<?= $fineAmt ?>">
                                <input type="hidden" id="fgName_<?= $j ?>" value="<?= $fGN . ' ( ' . $gwf['feeTypeName'] . ' ) '; ?>">
                                <input type="hidden" id="todayDate_<?= $j ?>" value="<?= $todayDate ?>">
                                <input type="hidden" id="amount_<?= $j ?>" value="<?= $gwf['amount'] ?>">
                                <input type="hidden" id="fine_<?= $j ?>" value="<?= $fineAmount ?>">
                                <input type="hidden" id="fmtId_<?= $j ?>" value="<?= $gwf['fmtId']; ?>">
                                <input type="hidden" id="nftId_<?= $j ?>" value="<?= $gwf['nftId']; ?>">
                                <input type="hidden" id="nfgId_<?= $j ?>" value="<?= $gwf['nfgId']; ?>">
                                <input type="hidden" id="stuId_<?= $j ?>" value="<?= $_GET['stu_id']; ?>">
                                <input type="hidden" id="classId_<?= $j ?>" value="<?= $studentData['class_id']; ?>">
                                <input type="hidden" id="sectionId_<?= $j ?>" value="<?= $studentData['section_id']; ?>">
                                <?php

                                if ($bblance > 0) {
                                  echo ' <button type="button" class="btn btn-dark" onclick="submitFees(' . $j . ')"><i class="fa-solid fa-plus"></i></button>';
                                } else {
                                  echo "<a disabled class='badge badge-success'>Paid</a>";
                                } ?>
                              </td>

                            </tr>

                            <?php

                            $a = 1;
                            $depositAmt = 0.00;
                            $fineAmt = 0.00;
                            $discountAmt = 0.00;
                            if (!empty($feesDeposits)) {

                              foreach ($feesDeposits as $fd) {


                                $depositAmt =  @$depositAmt + $fd['depositAmount'];
                                $fineAmt = @$fineAmt + $fd['fine'];
                                $discountAmt = @$discountAmt + $fd['discount'];


                            ?>

                                <tr class="bg-light-dark">
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td><img src="<?= base_url() . HelperClass::uploadImgDir . 'table-arrow.png' ?>"></td>
                                  <td><?= $fd['invoiceId']; ?></td>
                                  <td><?= ($fd['paymentMode'] == '1') ? 'Offline' : 'Online'; ?></td>
                                  <td><?= date('d-m-y', strtotime($fd['depositDate'])); ?></td>
                                  <td><?= number_format($fd['discount'],2); ?></td>
                                  <td><?= number_format($fd['fine'],2); ?></td>
                                  <td><?= number_format($fd['depositAmount'],2); ?></td>
                                  <td></td>
                                  <td>
                                    <a target="_blank" href="<?=base_url('feesInvoice?fees_id=') . $fd['randomToken']?>" class="btn btn-info"> <i class="fa-solid fa-file-invoice"></i> </a>&nbsp;&nbsp;&nbsp;
                                    <a href="?action=deleteInvoice&delete_id=<?= $fd['id'] ?>&stu_id=<?= $_GET['stu_id'] ?>" onclick="return confirm('Are you sure want to delete this?');"><i class="fa-sharp fa-solid fa-trash"></i></a>
                                  </td>

                                </tr>
                            <?php  }
                            } ?>









                        <?php $j++;
                          }
                        }
                      
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









  </script>