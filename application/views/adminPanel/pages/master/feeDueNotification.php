<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php"); ?>



    <?php


    function totalFeesDue(array $p, $db)
    {
      if (!empty($p)) {
        // sessionStrtingFrom
        $school =  $db->query("SELECT session_started_from,session_started_from_year, session_ended_to,session_ended_to_year FROM " . Table::schoolMasterTable . " WHERE unique_id = '{$_SESSION['schoolUniqueCode']}' LIMIT 1")->result_array();


        if (!empty($school)) {
          $sessionStartingFrom = $school[0]['session_started_from']; // month
          $sessionStartingYear = $school[0]['session_started_from_year'];
          $sessionStartDate = 1;

          $sessionStart = date("$sessionStartingYear-$sessionStartingFrom-$sessionStartDate");

          $sessionEndingFrom =  $school[0]['session_ended_to']; // month
          $sessionEndingYear =  $school[0]['session_ended_to_year'];
          $sessionEndDate = 31;

          $sessionEnd = date("$sessionEndingYear-$sessionEndingFrom-$sessionEndDate");
        } else {
          return json_encode(array('msg' => 'Please Select The School Session Started From & Ending To on Profile Section.', 'status' => 404));
        }



        $currentMonth = date('m'); // current month
        $currentYear = date('Y'); // current Year

        
          $totalStudentsInTheClass = $db->query("SELECT * FROM " . Table::studentTable . " WHERE 
          class_id = '{$p['classId']}' AND 
          section_id = '{$p['sectionId']}' AND
          status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ")->result_array();


          $sendArr = [];
          if (!empty($totalStudentsInTheClass)) {

            $d = $db->query("SELECT * FROM " . Table::feesTable . " WHERE class_id = '{$p['classId']}' AND status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

              $totalMonthsForFees = intval($currentMonth) - intval($sessionStartingFrom);

              $totalDueFeesTillThisMonth =  (intval($d[0]['fees_amt']) * intval($totalMonthsForFees));



            foreach ($totalStudentsInTheClass as $st) {

              $check = $db->query( "SELECT * FROM " . Table::feesForStudentTable . " WHERE 
                class_id = '{$p['classId']}' AND 
                section_id = '{$p['sectionId']}' AND
                student_id = {$st['id']} AND
                status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
                AND created_at BETWEEN '$sessionStart' AND '$sessionEnd'
                ")->result_array();
              

                      if (!empty($check)) {
                
                        $totalCountCheck = count($check);

                        $totalDepsitAmt = 0;
                        $totalOfferAmt = 0;
                        $totalDueAmt = 0;
                        for ($i = 0; $i < $totalCountCheck; $i++) {
                          $totalDepsitAmt = intval($totalDepsitAmt) + intval($check[$i]['deposit_amt']);
                          $totalOfferAmt = intval($totalOfferAmt) +  intval($check[$i]['offer_amt']);
                          $totalDueAmt = intval($totalDueAmt) + intval($check[$i]['total_due_balance']);
                        }



                        $totalDepositAmtAfterOfferAddedAndDueSubtracted = (intval($totalDepsitAmt) + intval($totalOfferAmt)) - intval($totalDueAmt);
                        // check totalDepositFees
  
                        $totalBalance =  (intval($totalDueFeesTillThisMonth) - intval($totalDepositAmtAfterOfferAddedAndDueSubtracted));
  
                        // calcualte total months fees deposit
                        $totalMonthFeesDue =  (intval($totalBalance) / intval($d[0]['fees_amt']));
               
                        $subArr = [];
                        $subArr = [
                          'perMonthFeesForThisClass' => $d[0]['fees_amt'],
                          'totalFeesTillThisMonth' => $totalDueFeesTillThisMonth,
                          'sessionStartedFrom' => $sessionStartingFrom,
                          'currentMonth' => $currentMonth,
                          'totalDepositAmount' => $totalDepsitAmt,
                          'totalOfferAmt' => $totalOfferAmt,
                          'totalDueAmt' => $totalDueAmt,
                          'totalDueAmountAfterOfferApplyAndDueSubstract' => $totalDepositAmtAfterOfferAddedAndDueSubtracted,
                          'totalBalanceForDeposit' => $totalBalance,
                          'totalMonthFeesDue' => floor($totalMonthFeesDue),
                          'studentId' => $st['id'],
                          'studentId' => $st['id'],
                          'studentName' => $st['name'],
                          'studentUserId' => $st['user_id']
  
                        ];
  
                         array_push($sendArr, $subArr);


                      }else
                      {
  
                        $totalBalance =  $totalDueFeesTillThisMonth;
  
                        $subArr = [];
                        $subArr = [
                          'perMonthFeesForThisClass' => $d[0]['fees_amt'],
                          'totalFeesTillThisMonth' => $totalDueFeesTillThisMonth,
                          'sessionStartedFrom' => $sessionStartingFrom,
                          'currentMonth' => $currentMonth,
                          'totalDepositAmount' => 0,
                          'totalOfferAmt' => 0,
                          'totalDueAmt' => $totalBalance,
                          'totalDueAmountAfterOfferApplyAndDueSubstract' => $totalBalance,
                          'totalBalanceForDeposit' => $totalBalance,
                          'totalMonthFeesDue' => 0,
                          'studentId' => $st['id'],
                          'studentName' => $st['name'],
                          'studentUserId' => $st['user_id']
  
                        ];
  
                         array_push($sendArr, $subArr);
                      }

                      
                       
            }
          }
          return $sendArr;
        } 
        //return json_encode($sql);
      }
  

    $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
    $sectionData = $this->db->query("SELECT * FROM " . Table::sectionTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();


    if(isset($_POST['submit']))
    {
      $p = [
        'classId' => $_POST['classId'],
        'sectionId' => $_POST['sectionId']
      ];
      $students = totalFeesDue($p, $this->db);
   
    }



    if(isset($_POST['submit_not']))
    {

      // echo '<pre>';
      // print_r($_POST['stuIds']);die();

      $token = [];
      foreach($_POST['stuIds'] as $key => $value)
      {
            // fetch notification from db
            $notificationFromDB = $this->db->query("SELECT title, body FROM ".Table::setNotificationTable." WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND for_what = '13' LIMIT 1")->result_array();

            if(!empty($notificationFromDB))
            {
              $title = $this->CrudModel->replaceNotificationsWords((String)$notificationFromDB[0]['title']);
              $body =  $this->CrudModel->replaceNotificationsWords((String)$notificationFromDB[0]['body']);
            }else
            {
              $title = "Fees Due Notification ✅";
              $body = "Hey 👋 Dear Parents, Please Pay Your Fee. Check The App Fees Section For Total Due.";
            }

          $image = null;
          $sound = null;
  
            // students token
            $tokens = [];
          $tokens =  $this->db->query("SELECT fcm_token FROM " . Table::studentTable . " WHERE id = '{$key}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'  AND status = '1' ")->result_array()[0]['fcm_token'];
  
          array_push($token,$tokens);
  
      }

      $sendPushSMS= $this->CrudModel->sendFireBaseNotificationWithDeviceId($token, $title,$body,$image,$sound);

      if($sendPushSMS)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Fees Due Notification Sent Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Fees Due Notification Not Sent. Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/feeDueNotification");

    }







    ?>







    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0"><?= $data['pageTitle'] ?> </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><?= $data['pageTitle'] ?> </li>
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
          <form method="post" action="">
            <div class="row">
              <div class="col-md-12"> 
              <div class="card">
                  <div class="card-header">Please Select Correct Details</div>
                  <div class="card-body">
                    <div class="row">
                    <div class="form-group col-md-3">
                      <label>Select Class </label>
                      <select name="classId" id="classId" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                        <option>Please Select Class</option>
                        <?php
                        if (isset($classData)) {
                          foreach ($classData as $class) {
                        ?>
                            <option value="<?= $class['id'] ?>"><?= $class['className'] ?></option>
                        <?php }
                        }

                        ?>

                      </select>
                    </div>
                    <div class="form-group col-md-3">
                      <label>Select Section </label>
                      <select name="sectionId" id="sectionId" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                        <option>Please Select Section</option>
                        <?php
                        if (isset($sectionData)) {
                          foreach ($sectionData as $section) {
                        ?>
                            <option value="<?= $section['id'] ?>"><?= $section['sectionName'] ?></option>
                        <?php }
                        }

                        ?>
                      </select>
                    </div>
                    <div class="col-md-4">
                    <input type="submit" name="submit" class="btn btn-primary mt-4">
                    </div>
                    </div>
                    
                    
                  </div>
                </div>
              </div>
           
     
            </div>

          </form>


          <div class="row">
          <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Showing All Fees Submit This Session</h3>
                   
                    </div>
                    <!-- /.card-header -->
                    <form method="POST">
                    <div class="card-body">
                      <table id="MonthDataTable" class="table table-bordered table-striped ">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Id</th>
                            <th>Per Month Fees </th>
                            <th>Total Fees Till Now</th>
                            <th>Student Id</th>
                            <th>Student Name</th>
                            <th>Student User Id</th>
                            <th>Total Fees For Deposit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset( $students)) {
                            $i = 0;
                            foreach ($students as $cn) { ?>
                           
                              <tr>
                                <td>
                                  <input type="checkbox" name="stuIds[<?=$cn['studentId']?>]" id="check_<?=$cn['studentId']?>">
                                </td>
                                <td><?=++$i?></td>
                              <td><?=number_format($cn['perMonthFeesForThisClass'],2)?></td>
                              <td><?=number_format($cn['totalFeesTillThisMonth'],2)?></td>
                              <td> <?=@$cn['studentId']?> </td>
                              <td> <?=@$cn['studentName']?> </td>
                              <td> <?=@$cn['studentUserId']?> </td>
                              <td><?=number_format($cn['totalBalanceForDeposit'],2)?></td>
                              </tr>
                           
                          <?php  }
                          } ?>

                        </tbody>

                      </table>
                      <hr>
                      <input type="submit" class="btn btn-primary my-5 btn-block" value="submit" name="submit_not">
                    </div>
                    </form>
                    <!-- /.card-body -->
                  </div>
                </div>
          </div>
        </div>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <!-- Control Sidebar -->

      <!-- /.control-sidebar -->
    </div>
    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
