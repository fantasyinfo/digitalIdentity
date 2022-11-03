<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php"); ?>



    <?php


    $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
    $sectionData = $this->db->query("SELECT * FROM " . Table::sectionTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

   function getInvoiceNumber($db)
    {
      $invoiceNumber = '';
      $checkFromSchoolTable = $db->query("SELECT fee_invoice_start FROM " .Table::schoolMasterTable ." WHERE fee_invoice_start IS NOT NULL AND unique_id = '{$_SESSION['schoolUniqueCode']}' LIMIT 1")->result_array();

      if(!empty($checkFromSchoolTable))
      {
        $invoiceNumber = HelperClass::invoicePrefix . $checkFromSchoolTable[0]['fee_invoice_start'];

        $dd = $db->query("SELECT invoice_id FROM " .Table::feesForStudentTable ." WHERE invoice_id IS NOT NULL AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ORDER BY id DESC LIMIT 1")->result_array();

        if(!empty($dd))
        {
          $id = explode(HelperClass::invoicePrefix,$dd[0]['invoice_id']);
          $invoiceNumber = HelperClass::invoicePrefix . ($id[1] + 1);
        }
         
         return $invoiceNumber;
      }else
      {
        $dd = $db->query("SELECT invoice_id FROM " .Table::feesForStudentTable ." WHERE invoice_id IS NOT NULL AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ORDER BY id DESC LIMIT 1")->result_array();

        if(!empty($dd))
        {
          $id = explode(HelperClass::invoicePrefix,$dd[0]['invoice_id']);
        }
         
          
        return HelperClass::invoicePrefix . ($id[1] + 1);
      }


     
    }

    if(isset($_POST['submit']))
    {

      $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
      $invoice_id = getInvoiceNumber($this->db);
      $login_user_id = $_SESSION['id'];
      $login_user_type = $_SESSION['user_type'];
      $student_id = $_POST['studentId'];
      $class_id = $_POST['classId'];
      $section_id = $_POST['sectionId'];
      $offer_amt = $_POST['offer_amt'];
      $deposit_amt = $_POST['deposit_amt'];
      $fee_deposit_date = $_POST['fee_deposit_date'];
      $payment_mode = $_POST['payment_mode'];
      $depositer_name = $_POST['depositer_name'];
      $depositer_mobile = $_POST['depositer_mobile'];
      $depositer_address = $_POST['depositer_address'];
      $total_due_balance = $_POST['total_due_balance'];

      $insert =  $this->db->query("INSERT INTO ".Table::feesForStudentTable." 
       (schoolUniqueCode,invoice_id,login_user_id,login_user_type,student_id,class_id,section_id,offer_amt,deposit_amt,fee_deposit_date,payment_mode,depositer_name,depositer_mobile,depositer_address,total_due_balance,session_table_id) 
       VALUES ('$schoolUniqueCode','$invoice_id','$login_user_id','$login_user_type','$student_id','$class_id','$section_id','$offer_amt','$deposit_amt','$fee_deposit_date','$payment_mode','$depositer_name','$depositer_mobile','$depositer_address','$total_due_balance','{$_SESSION['currentSession']}')");

       if($insert)
       {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Fees Submited Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Fees Not Submitted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/feesListingMaster");
       
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
              if(!empty($this->session->userdata('msg')))
              {  
                if($this->session->userdata('class') == 'success')
                 {
                   HelperClass::swalSuccess($this->session->userdata('msg'));
                 }else if($this->session->userdata('class') == 'danger')
                 {
                   HelperClass::swalError($this->session->userdata('msg'));
                 }
                
                
                ?>

              <div class="alert alert-<?=$this->session->userdata('class')?> alert-dismissible fade show" role="alert">
                <strong>New Message!</strong> <?=$this->session->userdata('msg')?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php
              $this->session->unset_userdata('class') ;
              $this->session->unset_userdata('msg') ;
              }
              ?>
          <form method="post" action="">
            <div class="row">

              <div class="col-md-12 mx-auto">
                <div class="card">
                  <div class="card-header">Please Select Correct Details</div>
                  <div class="card-body">

                    <div class="form-group col-md-12">
                      <label>Select Class </label>
                      <select name="classId" id="classId" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                      <option >Please Select Class</option>
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
                    <div class="form-group col-md-12">
                      <label>Select Section </label>
                      <select name="sectionId" id="sectionId" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="showStudents()">
                        <option >Please Select Section</option>
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

                    <div class="form-group col-md-12">
                      <label>Select Students </label>
                      <select name="studentId" id="studentId" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="totalFeesDue(this)">
                        <option >Please Select Student</option>
                      </select>
                    </div>
                    <div class="form-group col-md-12">
                      <div class="row">
                      <div class="col-md-4">
                          <label>Fees Per Month</label>
                          <input type="text" class="form-control"  id="fees_per_month" readonly>
                        </div>
                        <div class="col-md-4">
                          <label>Total Fees Till This Month</label>
                          <input type="text" class="form-control"  id="total_fees_till_this_month" readonly>
                        </div>
                        <div class="col-md-4">
                          <label>Total Old Due Amount</label>
                          <input type="text" class="form-control"  id="total_old_due" readonly>
                        </div>
                        <div class="col-md-6">
                          <label>Total Deposit Fees This Year</label>
                          <input type="text" class="form-control bg-success"  id="total_deposit_till_this_session" readonly>
                        </div>
                        <div class="col-md-6">
                          <label>Total Offer Get On Fees</label>
                          <input type="text" class="form-control bg-warning"  id="total_offer_get" readonly>
                        </div>
                        <div class="col-md-4">
                          <label>Total Months Fees Due</label>
                          <input type="text" class="form-control"  id="total_month_fees_due" readonly>
                        </div>
                        <div class="col-md-4">
                          <label>Total Old Balance Due Today</label>
                          <input type="text" class="form-control"  id="totalOldBalanceDue" readonly>
                        </div>
                        <div class="col-md-4">
                          <label>Total Due Today</label>
                          <input type="text" class="form-control bg-danger"  id="total_due_today" readonly>
                        </div>
                      </div>
                      
                    </div>

                    <label>Offer Amount</label>
                    <input type="number" class="form-control mb-3"  name="offer_amt" value="0">

                    <label>Deposit Amount</label>
                    <input type="number" class="form-control mb-3"  name="deposit_amt" required>

                    <label>Date of Deposit</label>
                    <input type="date" class="form-control mb-3"  name="fee_deposit_date" required>

                    <label>Payment Mode</label>
                    <select name="payment_mode"  class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;" required>
                        <option value="1">Online</option>
                        <option value="2">Offline</option>
                      </select>

                    <label>Depositer Name</label>
                    <input type="text" class="form-control mb-3"  name="depositer_name" required>

                    <label>Depositer Mobile</label>
                    <input type="number" class="form-control mb-3"  name="depositer_mobile" required>

                    <label>Depositer Address</label>
                    <input type="text" class="form-control mb-3"  name="depositer_address" required>

                    <label>Old Due Balance ( last year )</label>
                    <input type="number" class="form-control mb-3"  name="total_due_balance" value="0">

                    <input type="submit" name="submit" class="btn btn-block btn-lg btn-primary my-5"></input>
                  </div>
                </div>
              </div>






            </div>

          </form>

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
  <!-- ./wrapper -->
  <script>

    function showStudents() {
      var classId = $("#classId").val();
      var sectionId = $("#sectionId").val();
      if (classId != '' && sectionId != '') {
        console.log(classId + ' and ' + sectionId);
        $.ajax({
          url: '<?= base_url() . 'ajax/showStudentViaClassAndSectionId';?>',
          method: 'post',
          processData: 'false',
          data : {
            classId : classId,
            sectionId : sectionId
          },
          success: function (response)
          {
            //console.log(response);
            response =  $.parseJSON(response);
            $('#studentId').append(response);
          },
          error: function (error)
          {
            console.log(error);
          }

        });
      }
    }

    function totalFeesDue(x)
    {
      let classId = $("#classId").val();
      var sectionId = $("#sectionId").val();
      let studentId = x.value;
      $.ajax({
          url: '<?= base_url() . 'ajax/totalFeesDue';?>',
          method: 'post',
          processData: 'false',
          data : {
            classId : classId,
            sectionId: sectionId,
            studentId : studentId
          },
          success: function (response)
          {
            //console.log(response);
            response =  $.parseJSON(response);
            console.log(response);
            console.log(response['data']['totalmonthFeesDeposit']);

            let fees_per_month = $("#fees_per_month").val(response['data']['perMonthFeesForThisClass']);
            let total_fees_till_this_month = $("#total_fees_till_this_month").val(response['data']['totalFeesTillThisMonth']);
            let total_old_due = $("#total_old_due").val(response['data']['totalDueAmt']);
            let total_deposit_till_this_session = $("#total_deposit_till_this_session").val(response['data']['totalDepositAmount']);
            let total_offer_get = $("#total_offer_get").val(response['data']['totalOfferAmt']);
            // let total_old_deposit_dues_offers = $("#total_old_deposit_dues_offers").val(response['data']['totalDueAmountAfterOfferApplyAndDueSubstract']);
            let total_month_fees_due = $("#total_month_fees_due").val(response['data']['totalMonthFeesDue']);
            let total_due_today = $("#total_due_today").val(response['data']['totalBalanceForDeposit']);
            let totalOldBalanceDue = $("#totalOldBalanceDue").val(response['data']['totalOldBalanceDue']);
          },
          error: function (error)
          {
            console.log(error);
          }

        });










    }
  </script>