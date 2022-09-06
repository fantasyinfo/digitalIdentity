<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');
    $this->load->model('CrudModel');

    $dir = base_url().HelperClass::uploadImgDir;

  // fetching city data

  // SELECT SUM(digiCoin), user_id, (SELECT name FROM students WHERE id = get_digi_coin.user_id) as userName FROM get_digi_coin 
  //    WHERE schoolUniqueCode = '683611' AND user_type = 'Student'
  //    GROUP BY user_id ORDER BY SUM(digiCoin) DESC
  $userType = 'Student';
  if(isset($_GET['user_type']))
  {
    if($_GET['user_type'] == 'Student')
    {
      $userType = 'Student';
    }else if ($_GET['user_type'] == 'Teacher')
    {
      $userType= 'Teacher';
    }
  }

$sql = "SELECT SUM(gdc.digiCoin) as totalDigiCoinsEarn, gdc.user_id,gdc.user_type, (SELECT name FROM students WHERE id = gdc.user_id) as userName FROM " . Table::getDigiCoinTable . " gdc WHERE gdc.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND user_type = '$userType' AND MONTH(gdc.created_at)=MONTH(now()) AND YEAR(gdc.created_at)=YEAR(now()) GROUP BY gdc.user_id ORDER BY SUM(gdc.digiCoin) DESC";
  
    $leaderBoard = $this->db->query($sql)->result_array();
    
    // HelperClass::prePrintR($leaderBoard);
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

              <div class="col-md-6 mx-auto">
              <form method="GET">
                <label> Select User Type </label>
                <select class="form-control" name="user_type">
                  <option value="Student">Student</option>
                  <option value="Teacher">Teacher</option>
                </select>
                <input type="submit" class="btn btn-warning my-3 btn-block">
             </form>
              </div>
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Showing LeaderBoard For This Month</h3>
                   
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="MonthDataTable" class="table table-bordered table-striped ">
                        <thead>
                          <tr>
                            <th>Position</th>
                            <th>Total DigiCoin</th>
                            <th>User Type</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Class - Section</th>
                            <th>Uniqe Id</th>
                           
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($leaderBoard)) {
                            $i = 0;
                            foreach ($leaderBoard as $cn) { 
                              $tableName = Table::studentTable;
                              if(isset($_GET['user_type']))
                              {
                                if($_GET['user_type'] == 'Student')
                                {
                                  $tableName = Table::studentTable;
                                }else if ($_GET['user_type'] == 'Teacher')
                                {
                                  $tableName = Table::teacherTable;
                                }
                              }
                              $studentDetails = $this->db->query("SELECT s.name,s.user_id,s.image,c.className,sc.sectionName FROM ".$tableName." s LEFT JOIN ".Table::classTable." c ON c.id = s.class_id LEFT JOIN ".Table::sectionTable." sc ON sc.id = s.section_id WHERE s.id = '{$cn['user_id']}'")->result_array();
                              
                              ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <td><i class="fa-solid fa-coins"></i>  <?= $cn['totalDigiCoinsEarn'];?> Coins</td>
                                <td><?= $cn['user_type'];?></td>
                                <td><img src="<?= $dir.@$studentDetails[0]['image'];?>" alt='100x100' height='50px' width='50px' class='img-fluid rounded-circle' /></td>
                                <td><?= @$studentDetails[0]['name'];?></td>
                                <td><?= @$studentDetails[0]['className'] . ' - ' . @$studentDetails[0]['sectionName'];?></td>
                                <td><?= @$studentDetails[0]['user_id'];?></td>
                               
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