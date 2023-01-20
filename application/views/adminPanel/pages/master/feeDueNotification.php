<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php"); ?>



    <?php

    $students = [];
    function totalFeesDue(array $p, $db, $cModal)
    {
      if (!empty($p)) {
        $sendArr = [];
        // current session due;
        $stuArr = $db->query("SELECT s.*, 
        CONCAT(c.className , ' ( ' , ss.sectionName , ' ) ') as class, 
        CONCAT(s.address, ' - ' , ct.cityName, ' - ' , st.stateName , ' - ' , s.pincode) as address 
        FROM " . Table::studentTable . " s
        JOIN " . Table::classTable . " c ON c.id = s.class_id
        JOIN " . Table::sectionTable . " ss ON ss.id = s.section_id
        JOIN " . Table::cityTable . " ct ON ct.id = s.city_id
        JOIN " . Table::stateTable . " st ON st.id = s.state_id
        WHERE s.class_id = '{$p['classId']}' AND 
        s.section_id = '{$p['sectionId']}' AND 
        s.status = '1' AND 
        s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();




        for ($i = 0, $t = count($stuArr); $i < $t; $i++) {
          $subArr = [];

          $subArr['studentDetails'] = $stuArr[$i];

          $subArr['feesDetails'] = $cModal->showStudentFeesViaIdClassAndSection($stuArr[$i]['id'], $p['classId'], $p['sectionId'], $_SESSION['schoolUniqueCode'], $_SESSION['currentSession']);

          $subArr['oldSessionDues'] = $cModal->showStudentOldSessionFeesDetails($stuArr[$i]['id']);

          array_push($sendArr, $subArr);
        }

        return $sendArr;
      }
    }


    function totalMonthlyFeesDue(array $p, $db, $cModal)
    {
      if (!empty($p)) {
        $sendArr = [];
        // current session due;
        $stuArr = $db->query($s = "SELECT s.*, 
        CONCAT(c.className , ' ( ' , ss.sectionName , ' ) ') as class, 
        CONCAT(s.address, ' - ' , ct.cityName, ' - ' , st.stateName , ' - ' , s.pincode) as address 
        FROM " . Table::studentTable . " s
        JOIN " . Table::classTable . " c ON c.id = s.class_id
        JOIN " . Table::sectionTable . " ss ON ss.id = s.section_id
        JOIN " . Table::cityTable . " ct ON ct.id = s.city_id
        JOIN " . Table::stateTable . " st ON st.id = s.state_id
        WHERE s.class_id = '{$p['classId']}' AND 
        s.section_id = '{$p['sectionId']}' AND 
        s.status = '1' AND 
        s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

        $t = count($stuArr);

        for ($i = 0;  $i < $t; $i++) {
          $subArr = [];
        
          $subArr['studentDetails'] = $stuArr[$i];

          $subArr['feesDetails'] = $cModal->showStudentMonthWiseFeesViaIdClassAndSection($stuArr[$i]['id'], $p['classId'], $p['sectionId'], $_SESSION['schoolUniqueCode'], $_SESSION['currentSession']);

          $subArr['oldSessionDues'] = $cModal->showStudentOldSessionFeesDetails($stuArr[$i]['id']);

          array_push($sendArr, $subArr);
        }

     
        return $sendArr;
      }
    }









    $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
    $sectionData = $this->db->query("SELECT * FROM " . Table::sectionTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();


    if (isset($_POST['submit'])) {
      $p = [
        'classId' => $_POST['classId'],
        'sectionId' => $_POST['sectionId']
      ];
      //$students = totalFeesDue($p, $this->db,$this->CrudModel);
      $students = totalMonthlyFeesDue($p, $this->db, $this->CrudModel);

      // echo '<pre>';
      // print_r($students);
      // die();
    }


    if (isset($_POST['searchViaAddress'])) {

      $sendArr = [];
      $stuArr = $this->db->query("SELECT s.*, 
        CONCAT(c.className , ' ( ' , ss.sectionName , ' ) ') as class, 
        CONCAT(s.address, ' - ' , ct.cityName, ' - ' , st.stateName , ' - ' , s.pincode) as address 
        FROM " . Table::studentTable . " s
        JOIN " . Table::classTable . " c ON c.id = s.class_id
        JOIN " . Table::sectionTable . " ss ON ss.id = s.section_id
        JOIN " . Table::cityTable . " ct ON ct.id = s.city_id
        JOIN " . Table::stateTable . " st ON st.id = s.state_id
        WHERE s.address LIKE '%{$_POST['address']}%' OR ct.cityName LIKE '%{$_POST['address']}%' AND
        s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();


      for ($i = 0, $t = count($stuArr); $i < $t; $i++) {
        $subArr = [];

        $subArr['studentDetails'] = $stuArr[$i];

        $subArr['feesDetails'] = $this->CrudModel->showStudentMonthWiseFeesViaIdClassAndSection($stuArr[$i]['id'], $stuArr[$i]['class_id'], $stuArr[$i]['section_id'], $_SESSION['schoolUniqueCode'], $_SESSION['currentSession']);

        $subArr['oldSessionDues'] = $this->CrudModel->showStudentOldSessionFeesDetails($stuArr[$i]['id']);

        array_push($sendArr, $subArr);
      }

      $students =  $sendArr;


      // current session due;


    }
   

    


    if (isset($_POST['submit_not'])) {

      // echo '<pre>';
      // print_r($_POST['totalMonths']);die();

      $token = [];
     

      $schoolName = $this->db->query("SELECT school_name FROM ".Table::schoolMasterTable." WHERE unique_id = '{$_SESSION['schoolUniqueCode']}' ")->result_array()[0]['school_name'];

      foreach ($_POST['stuIds'] as $key => $value) {
        // fetch notification from db
        $notificationFromDB = $this->db->query("SELECT title, body FROM " . Table::setNotificationTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND for_what = '13' LIMIT 1")->result_array();

        if (!empty($notificationFromDB)) {
          $title = $this->CrudModel->replaceNotificationsWords((string)$notificationFromDB[0]['title']);
          $body =  $this->CrudModel->replaceNotificationsWords((string)$notificationFromDB[0]['body']);
        } else {
          $title = "Fees Due Notification ✅";
          $body = "Hey 👋 Dear Parents, Please Pay Your Fee. Check The App Fees Section For Total Due.";
        }

        $image = null;
        $sound = null;

        // students token
        $tokens = [];
        $tokens =  $this->db->query("SELECT * FROM " . Table::studentTable . " WHERE id = '{$key}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'  AND status = '1' ")->result_array();



        array_push($token, $tokens[0]['fcm_token']);

        $this->load->model('MessageSMSModel');

        $contactsToSendSMS = implode(',',$contact);
        $studentName = implode(',',$studentName);
        $studentName = 'Digitalfied Private Limited';
        $schoolName;
  
        MessageSMSModel::feesDueNotice($tokens[0]['mobile'],$tokens[0]['name'], '+1 or More Months', $schoolName);
      }

      $sendPushSMS = $this->CrudModel->sendFireBaseNotificationWithDeviceId($token, $title, $body, $image, $sound);

      

      if ($sendPushSMS) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Fees Due Notification Sent Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Fees Due Notification Not Sent. Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "master/feeDueNotification");
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

          <div class="row">
            <div class="col-md-6">
              <form method="post" action="">
                <div class="card">
                  <div class="card-header">Please Select Correct Details</div>
                  <div class="card-body">
                    <div class="row">
                      <div class="form-group col-md-4">
                        <label>Select Class </label>
                        <select name="classId" id="classId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
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
                      <div class="form-group col-md-4">
                        <label>Select Section </label>
                        <select name="sectionId" id="sectionId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
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
                        <input type="submit" name="submit" class="btn mybtnColor btn-block margin-top-30">
                      </div>
                    </div>


                  </div>
                </div>
              </form>
            </div>
            <div class="col-md-6">
              <form method="post" action="">
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">Search Via Address or City</div>
                      <div class="card-body">
                        <div class="row">

                          <div class="form-group col-md-8">
                            <label>Enter Address</label>
                            <input type="text" name="address" placeholder="Enter Address or City" class="form-control">
                          </div>
                          <div class="col-md-4">
                            <input type="submit" name="searchViaAddress" class="btn mybtnColor margin-top-30 btn-block">
                          </div>
                        </div>


                      </div>
                    </div>
                  </div>


                </div>

              </form>
            </div>


          </div>







          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Showing All Fees Submit This Session</h3>

                </div>
                <!-- /.card-header -->
                <form method="POST">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="feesDueTable" class="table mb-0 align-middle bg-white ">
                        <thead class="bg-light">
                          <tr>
                            <th>#</th>
                            <!-- <th>Student Id</th> -->
                            <th>Name</th>
                            <th>Class</th>
                            <th>Father Name</th>
                            <th>Address</th>
                            <th>Old Session Due</th>
                            <th>Current Session Due</th>
                            <th>Total Months Dues</th>
                            <th>Total Due Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($students)) {
                            $i = 0;
                            foreach ($students as $cn) {

                              $t = $cn['feesDetails']['totalDueNow'] + @$cn['oldSessionDues'][0]['fees_due'];

                              if ($t <= 0) {
                                continue;
                              }

                          ?>

                              <tr>
                                <td>
                                  <input type="checkbox" name="stuIds[<?= $cn['studentDetails']['id'] ?>]" id="check_<?= $cn['studentDetails']['id'] ?>">
                                </td>
                                <!-- <td> <?= @$cn['studentDetails']['id'] ?> </td> -->
                                <td> <?= @$cn['studentDetails']['name'] ?> </td>
                                <td> <?= @$cn['studentDetails']['class'] ?> </td>
                                <td> <?= @$cn['studentDetails']['father_name'] ?> </td>
                                <td> <?= @$cn['studentDetails']['address'] ?> </td>
                                <td><?= number_format(@$cn['oldSessionDues'][0]['fees_due'], 2) ?></td>
                                <td><?= number_format($cn['feesDetails']['totalDueNow'], 2) ?></td>
                                <td><?php 

                                if(isset($cn['feesDetails']['feesDuesMonths'])) {
                                 
                                  foreach($cn['feesDetails']['feesDuesMonths'] as $f){ ?>
                                    <?= $f ?> ,
                                 <?php }
                                }
                                  ?></td>
                                <td><?= number_format(($cn['feesDetails']['totalDueNow'] + @$cn['oldSessionDues'][0]['fees_due']), 2) ?></td>
                              </tr>

                          <?php  }
                          } ?>

                        </tbody>

                      </table>
                    </div>
                    <hr>
                    <input type="submit" class="btn mybtnColor my-5 btn-block" value="Send Fees Dues Notification" name="submit_not">
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
  <script>
    $("#feesDueTable").DataTable({
      "responsive": true,
      "lengthChange": true,
      "autoWidth": true,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
      dom: 'lBfrtip',
      buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5'
      ],
      lengthMenu: [10, 50, 100, 500, 1000, 2000, 5000, 10000, 50000, 100000],
      pageLength: 100,
    });
  </script>