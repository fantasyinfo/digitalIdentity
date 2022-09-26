<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');
    $this->load->model('CrudModel');

    $classData = $this->CrudModel->allClass(Table::classTable, $_SESSION['schoolUniqueCode']);
$sectionData = $this->CrudModel->allSection(Table::sectionTable, $_SESSION['schoolUniqueCode']);

    $dir = base_url().HelperClass::uploadImgDir;

  // fetching city data

  // SELECT SUM(digiCoin), user_id, (SELECT name FROM students WHERE id = get_digi_coin.user_id) as userName FROM get_digi_coin 
  //    WHERE schoolUniqueCode = '683611' AND user_type = 'Student'
  //    GROUP BY user_id ORDER BY SUM(digiCoin) DESC
  $condition = '';

  if(isset($_GET['submit']))
  {
    if(isset($_GET['sName']) && !empty($_GET['sName']))
    {
      $condition .= " AND st.name LIKE '%{$_GET['sName']}%' ";
    }

    if(isset($_GET['cId']) && !empty($_GET['cId']))
    {
      $condition .= " AND ct.id = '{$_GET['cId']}' ";
    }


    if(isset($_GET['sId']) && !empty($_GET['sId']))
    {
      $condition .= " AND sect.id = '{$_GET['sId']}' ";
    }

    if(isset($_GET['userId']) && !empty($_GET['userId']))
    {
      $condition .= " AND st.user_id = '{$_GET['userId']}' ";
    }
  }

  

     $sql = "SELECT ffst.*,st.name,st.user_id,ct.className,sect.sectionName FROM ".Table::feesForStudentTable." ffst 
    LEFT JOIN ".Table::studentTable." st ON st.id = ffst.student_id AND st.schoolUniqueCode= '{$_SESSION['schoolUniqueCode']}'
    LEFT JOIN ".Table::classTable." ct ON ct.id = ffst.class_id AND ct.schoolUniqueCode= '{$_SESSION['schoolUniqueCode']}'
    LEFT JOIN ".Table::sectionTable." sect ON sect.id = ffst.section_id AND sect.schoolUniqueCode= '{$_SESSION['schoolUniqueCode']}'
    WHERE ffst.status = '1' AND ffst.schoolUniqueCode= '{$_SESSION['schoolUniqueCode']}' $condition ORDER BY ffst.id DESC";
  
    $feesSubmitData = $this->db->query($sql)->result_array();
    
    //  HelperClass::prePrintR($feesSubmitData);
    ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
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
         
              <div class="row">
              <div class="col-md-12">
                  <form method="GET">
                    <div class="row">
                    <div class="form-group col-md-2">
                      <label >Name</label>
                        <input type="text" class="form-control" name="sName" placeholder="Search by name">
                      </div>
                      <div class="form-group col-md-2">
                      <label>Select Class </label>
                      <select  id="cId" class="form-control  select2 select2-danger" name="cId"   data-dropdown-css-class="select2-danger" style="width: 100%;">
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
                        <select  id="sId" class="form-control  select2 select2-danger" name="sId"  data-dropdown-css-class="select2-danger" style="width: 100%;">
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
                      <label >User Id</label>
                        <input type="text" class="form-control" name="userId" placeholder="Search by Student UserId">
                      </div>
                     
                    </div>
                    <div class="form-group mt-3">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        <a href="<?= base_url('master/feesListingMaster')?>" class="btn btn-warning">Clear</a>
                      </div>

                </form>
              </div>
             
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Showing All Fees Submit This Session</h3>
                   
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="MonthDataTable" class="table table-bordered table-striped ">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Invoice Id</th>
                            <th>Student Name - ID</th>
                            <th>Class - Section</th>
                            <th>Fees Deposit</th>
                            <th>Fees Deposit Mode</th>
                            <th>Depositor Name</th>
                            <th>Depositor Mobile</th>
                            <th>Depositor Address</th>
                            <th>Total Old Due</th>
                            <th>Fees Submited Date</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset( $feesSubmitData)) {
                            $i = 0;
                            foreach ( $feesSubmitData as $cn) { ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <td><?= $cn['invoice_id'];?></td>
                                <td><?= $cn['name'] . ' - '. $cn['user_id'];?></td>
                                <td><?= $cn['className'] . ' - '. $cn['sectionName'];?></td>
                                <td>₹ <?= number_format($cn['deposit_amt'],2);?>/-</td>
                                <td><?= ($cn['payment_mode'] == '2') ? 'Offline' : 'Online';?></td>
                                <td><?= $cn['depositer_name'];?></td>
                                <td><?= $cn['depositer_mobile'];?></td>
                                <td><?= $cn['depositer_address'];?></td>
                                <td>₹ <?= number_format($cn['total_due_balance'],2);?>/-</td>
                                <td><?= $cn['fee_deposit_date'];?></td>
                                <td><a href="<?= base_url('master/feesInvoice')?>?id=<?= $cn['id'];?>" class="btn btn-primary" >Invoice</a></td>
                              </tr>
                          <?php  }
                          } ?>

                        </tbody>

                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
                </div>
              </div>
      </div>
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->
                        </div>
    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php");?>
  <!-- ./wrapper -->
  <script>

    $("#MonthDataTable").DataTable();
  
  </script>