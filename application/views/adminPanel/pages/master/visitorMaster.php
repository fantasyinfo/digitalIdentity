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
    $visitorMasterData = $this->db->query("SELECT * FROM " . Table::visitorTable . " WHERE status != '4' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ")->result_array();
    


    // edit and delete action
    // if(isset($_GET['action']))
    // {
     
      // if($_GET['action'] == 'edit')
      // {
      //   $editId = $_GET['edit_id'];
      //   $editUserData = $this->db->query("SELECT * FROM " . Table::visitorTable . " WHERE id='$editId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ")->result_array();
      // }

 
      // if($_GET['action'] == 'delete')
      // {
      //   $deleteId = $_GET['delete_id'];

      //   $sql = "SELECT gift_image FROM " . Table::giftTable . " WHERE id='$deleteId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ";

      //   $delImg = $this->db->query($sql)->result_array();

      //   if(!empty(@$delImg))
      //   {
      //     $imgN = @$delImg[0]['gift_image'];
      //     @unlink(HelperClass::uploadImgDir . $imgN);
      //   }
   
      //   $deleteMonthData = $this->db->query("DELETE FROM " . Table::giftTable . " WHERE id='$deleteId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ");

      //   if($deleteMonthData)
      //   {
      //     $msgArr = [
      //       'class' => 'success',
      //       'msg' => 'Gift Deleted Successfully',
      //     ];
      //     $this->session->set_userdata($msgArr);
      //   }else
      //   {
      //     $msgArr = [
      //       'class' => 'danger',
      //       'msg' => 'Gift Not Deleted Due to this Error. ' . $this->db->last_query(),
      //     ];
      //     $this->session->set_userdata($msgArr);
      //   }
      //   //header("Refresh:1 ".base_url()."digicoin/giftMaster");
      // }

      // if($_GET['action'] == 'status')
      // {
      //   $status = $_GET['status'];
      //   $updateId = $_GET['edit_id'];
      //   $updateStatus = $this->db->query("UPDATE " . Table::giftTable . " SET status = '$status' WHERE id = '$updateId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ");

      //   if($updateStatus)
      //   {
      //     $msgArr = [
      //       'class' => 'success',
      //       'msg' => 'Gift Status Updated Successfully',
      //     ];
      //     $this->session->set_userdata($msgArr);
      //   }else
      //   {
      //     $msgArr = [
      //       'class' => 'danger',
      //       'msg' => 'Gift Status Not Updated Due to this Error. ' . $this->db->last_query(),
      //     ];
      //     $this->session->set_userdata($msgArr);
      //   }
      //   header("Refresh:1 ".base_url()."digicoin/giftMaster");
      // }

    //}


    // insert new DigiCoin
    // if(isset($_POST['submit']))
    // {
    //   $userType = $_POST['user_type'];
    //   $giftName = $_POST['gift_name'];
    //   $redeemDigiCoin = $_POST['redeem_digiCoins'];
    //   $schoolUniqueCode = $_SESSION['schoolUniqueCode'];

    //   if(isset($_FILES['image']))
    //   {
    //     $giftImage = $this->CrudModel->uploadImg($_FILES,'GIFT');
    //   }
      

      // $alreadyEnter = $this->db->query("SELECT * FROM " . Table::giftTable . " WHERE user_type = '$userType' AND  schoolUniqueCode = '$schoolUniqueCode' AND status != '4' AND redeem_digiCoins = '$redeemDigiCoin'")->result_array();

      // if(!empty($alreadyEnter))
      // {
      //     $msgArr = [
      //       'class' => 'danger',
      //       'msg' => 'This Digicoin is already inserted for that occasion, Please Edit That',
      //     ];
      //     $this->session->set_userdata($msgArr);
      //     header('Location: digicoin/giftMaster');
      //     exit(0);
      // }


    //   $insertNewDigiCoin = $this->db->query("INSERT INTO " . Table::visitorTable . " (schoolUniqueCode,visit_date,visit_time,visitor_name,person_to_meet,purpose_to_meet,visitor_mobile_no,visitor_image) VALUES ('$schoolUniqueCode','$giftName','$giftImage','$redeemDigiCoin','$userType')");
    //   if($insertNewDigiCoin)
    //   {
    //     $msgArr = [
    //       'class' => 'success',
    //       'msg' => 'New Gift Added Successfully',
    //     ];
    //     $this->session->set_userdata($msgArr);
    //   }else
    //   {
    //     $msgArr = [
    //       'class' => 'danger',
    //       'msg' => 'Gift Not Added Due to this Error. ' . $this->db->last_query(),
    //     ];
    //     $this->session->set_userdata($msgArr);
    //   }
    //   header("Refresh:1 ".base_url()."digicoin/giftMaster");
    // }

    // update exiting city
    // if(isset($_POST['update']))
    // {
    //   $userType = $_POST['user_type'];
    //   $giftName = $_POST['gift_name'];
    //   $redeemDigiCoin = $_POST['redeem_digiCoins'];
    //   $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
    //   $set = '';
    //   if(isset($_FILES['image']['size']) && $_FILES['image']['size'] > 0)
    //   {
    //     $giftImage = $this->CrudModel->uploadImg($_FILES,'GIFT');
    //     $set = " gift_image = '$giftImage', ";
    //   }else
    //   {
    //     $set;
    //   }
    //   $monthEditId = $_POST['updateMonthId'];

    //   $updateMonth = $this->db->query("UPDATE " . Table::giftTable . " SET  $set gift_name = '$giftName',redeem_digiCoins = '$redeemDigiCoin' WHERE id = '$monthEditId'  AND schoolUniqueCode = '$schoolUniqueCode' ");

    //   if($updateMonth)
    //   {
    //     $msgArr = [
    //       'class' => 'success',
    //       'msg' => 'Gifts Updated Successfully',
    //     ];
    //     $this->session->set_userdata($msgArr);
    //   }else
    //   {
    //     $msgArr = [
    //       'class' => 'danger',
    //       'msg' => 'Gifts Not Updated Due to this Error. ' . $this->db->last_query(),
    //     ];
    //     $this->session->set_userdata($msgArr);
    //   }
    //   header("Refresh:1 ".base_url()."digicoin/giftMaster");
    // }


    // print_r($cityData);
  

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
            <!-- left column -->
            <?php //print_r($data['class']);
            ?>
            <div class="col-md-12 mx-auto">
              <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  Visitor Master
                  <!-- <h3 class="card-title">Add / Edit Month</h3> -->
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <!--/.col (left) -->
                <!-- right column -->
              </div>

              <div class="row">

                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Showing All Visitors Information</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="MonthDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Visitor Id</th>
                            <th>Visitor Image</th>
                            <th>Visitor Name</th>
                            <th>Date & Time Of Visit</th>
                            <th>Person to Meet</th>
                            <th>Meeting Purpose</th>
                            <th>Visitor Mobile</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($visitorMasterData)) {
                            $i = 0;
                            foreach ($visitorMasterData as $cn) { ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <td><?= $cn['id'];?></td>
                                <td><img src="<?= $dir.$cn['visitor_image'];?>" alt='100x100' height='50px' width='50px' class='img-fluid rounded-circle' /></td>
                                <td><?= $cn['visitor_name'];?></td>
                                <td><?= "Date : " . $cn['visit_date'] . " Time: " . $cn['visit_time'];?></td>
                                <td><?= $cn['person_to_meet'];?></td>
                                <td><?= $cn['purpose_to_meet'];?></td>
                                <td><?= $cn['visitor_mobile_no'];?></td>
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



              <!--/.col (right) -->
            </div>

            <!-- /.container-fluid -->
          </div>
          <!-- /.content -->
        </div>
      </div>
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->

    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php");?>
  <!-- ./wrapper -->
  <script>
    // var ajaxUrl = '<?= base_url() . 'ajax/listStudentsAjax' ?>';


    $("#MonthDataTable").DataTable();
  </script>