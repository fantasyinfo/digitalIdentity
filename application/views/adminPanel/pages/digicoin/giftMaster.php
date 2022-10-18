<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');
    $this->load->model('CrudModel');

    $dir = base_url().HelperClass::giftsImagePath;

  // fetching city data
    $setDigiCoinData = $this->db->query("SELECT * FROM " . Table::giftTable . " WHERE status != '4' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ORDER BY id DESC")->result_array();
    


    // edit and delete action
    // if(HelperClass::checkIfItsACEOAccount()) {
      if(isset($_GET['action']))
      {
      
        if($_GET['action'] == 'edit')
        {
          $editId = $_GET['edit_id'];
          $editUserData = $this->db->query("SELECT * FROM " . Table::giftTable . " WHERE id='$editId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
        }

  
        if($_GET['action'] == 'delete')
        {
          $deleteId = $_GET['delete_id'];

          $sql = "SELECT gift_image FROM " . Table::giftTable . " WHERE id='$deleteId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'";

          $delImg = $this->db->query($sql)->result_array();

          if(!empty(@$delImg))
          {
            $imgN = @$delImg[0]['gift_image'];
            @unlink(HelperClass::giftsImagePath . $imgN);
          }
    
          $deleteMonthData = $this->db->query("DELETE FROM " . Table::giftTable . " WHERE id='$deleteId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

          if($deleteMonthData)
          {
            $msgArr = [
              'class' => 'success',
              'msg' => 'Gift Deleted Successfully',
            ];
            $this->session->set_userdata($msgArr);
          }else
          {
            $msgArr = [
              'class' => 'danger',
              'msg' => 'Gift Not Deleted Due to this Error. ' . $this->db->last_query(),
            ];
            $this->session->set_userdata($msgArr);
          }
          header("Refresh:1 ".base_url()."digicoin/giftMaster");
        }

        if($_GET['action'] == 'status')
        {
          $status = $_GET['status'];
          $updateId = $_GET['edit_id'];
          $updateStatus = $this->db->query("UPDATE " . Table::giftTable . " SET status = '$status' WHERE id = '$updateId'   AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

          if($updateStatus)
          {
            $msgArr = [
              'class' => 'success',
              'msg' => 'Gift Status Updated Successfully',
            ];
            $this->session->set_userdata($msgArr);
          }else
          {
            $msgArr = [
              'class' => 'danger',
              'msg' => 'Gift Status Not Updated Due to this Error. ' . $this->db->last_query(),
            ];
            $this->session->set_userdata($msgArr);
          }
          header("Refresh:1 ".base_url()."digicoin/giftMaster");
        }

      }

      if(isset($_POST['submit']))
      {
        $userType = $_POST['user_type'];
        $giftName = $_POST['gift_name'];
        $redeemDigiCoin = $_POST['redeem_digiCoins'];
        $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
  
        if(isset($_FILES['image']))
        {
          $giftImage = $this->CrudModel->uploadImg($_FILES,'GIFT',HelperClass::giftsImagePath);
        }
        
  
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
  
  
        $insertNewDigiCoin = $this->db->query("INSERT INTO " . Table::giftTable . " (schoolUniqueCode,gift_name,gift_image,redeem_digiCoins,user_type) VALUES ('$schoolUniqueCode','$giftName','$giftImage','$redeemDigiCoin','$userType')");
        if($insertNewDigiCoin)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'New Gift Added Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Gift Not Added Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."digicoin/giftMaster");
      }

      if(isset($_POST['update']))
      {
        $userType = $_POST['user_type'];
        $giftName = $_POST['gift_name'];
        $redeemDigiCoin = $_POST['redeem_digiCoins'];
        $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
        $set = '';
        if(isset($_FILES['image']['size']) && $_FILES['image']['size'] > 0)
        {
          $giftImage = $this->CrudModel->uploadImg($_FILES,'GIFT',HelperClass::giftsImagePath);
          $set = " gift_image = '$giftImage', ";
        }else
        {
          $set;
        }
        $monthEditId = $_POST['updateMonthId'];
  
        $updateMonth = $this->db->query("UPDATE " . Table::giftTable . " SET  $set gift_name = '$giftName',redeem_digiCoins = '$redeemDigiCoin' WHERE id = '$monthEditId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
  
        if($updateMonth)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Gifts Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Gifts Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
         header("Refresh:1 ".base_url()."digicoin/giftMaster");
      }

    // }
    

  
   

    // update exiting city
  

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
            <div class="col-md-10 mx-auto">
              <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  DigiCoin Master
                  <!-- <h3 class="card-title">Add / Edit Month</h3> -->
                </div>
                <!-- /.card-header -->
                <!-- form start -->

               <?php  
               //if(HelperClass::checkIfItsACEOAccount()) 
               //{ ?>
                <div class="row">
                <div class="card-body">
                  <form method="post" action="" enctype="multipart/form-data">
                  <?php 
                  if(isset($_GET['action']) && $_GET['action'] == 'edit')
                  {?>
                   <input type="hidden" name="updateMonthId" value="<?=$editId?>">
                  <?php }
                  
                  ?>
                    <div class="row">

                    <div class="col-md-8">
                      <table class="table striped">
                        <tbody>
                        <tr>
                          <td>Gift Name</td>
                          <td>
                          <input type="text" name="gift_name" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editUserData[0]['gift_name'];}?>" class="form-control" required>
                          </td>
                        </tr>
                        <tr>
                          <td> <label for="city">Select Gift Image</label></td>
                            <td>
                              <?php if(isset($editUserData[0]['gift_image']))
                              { ?>
                                <img src="<?= $dir. $editUserData[0]['gift_image']; ?>" alt='100x100' id="img" height='100px' width='100px' class='img-fluid' />
                            <?php  } else
                            { ?>
                                <img src="<?= base_url()?>assets/uploads/gift-default.jpg" alt='100x100' id="img" height='100px' width='100px' class='img-fluid' />
                           <?php } ?>
                            


                            <div class="input-group mt-2">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" name="image" onchange="document.getElementById('img').src = window.URL.createObjectURL(this.files[0])">
                            <label class="custom-file-label" for="img">Choose file</label>
                          </td>
                        </tr>
                        <tr>
                          <td>DigiCoin Value <span class="text-danger">( How much digicoin for this gift )</span></td>
                          <td>
                          <input type="number" name="redeem_digiCoins" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editUserData[0]['redeem_digiCoins'];}?>" class="form-control" required>
                          </td>
                        </tr>
                        <tr>
                          <td>Select User Type </td>
                          <td>
                          <div class="form-group">
                              <select name="user_type" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                                <?php

                                $selectedUserType = '';
                                foreach (HelperClass::userType as $key => $value) {
                                  if (isset($editUserData) && $editUserData[0]['user_type'] == $value) {
                                    $selectedUserType = 'selected';
                                  } else {
                                    $selectedUserType = '';
                                  }
                                ?>

                                  <option <?= $selectedUserType ?> value="<?= $value ?>"><?= $key ?></option>
                                <?php }


                                ?>

                              </select>
                            </div>
                          </td>
                        </tr>
                     
                        <tr>
                          <td>#</td>
                          <td>
                          <button type="submit" name="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo 'update';}else{echo 'submit';}?>" class="btn btn-primary btn-lg btn-block">Add</button>
                          </td>
                        </tr>
                        </tbody>
                       
                      </table>
                   
                        
                     
                    </div>
                    </div>
                  </form>
                </div>
              </div> 
            <?php
              // }
               ?>
            
                <!--/.col (left) -->
                <!-- right column -->
              </div>

              <div class="row">

                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Showing All Gifts</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="MonthDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Gift Id</th>
                            <th>Gift Image</th>
                            <th>Gift Name</th>
                            <th>User Type</th>
                            <th>How Much DigiCoin</th>
                            <?php //if(HelperClass::checkIfItsACEOAccount()) { ?>
                              <th>Status</th>
                            <th>Action</th>
                            <?php  // } ?>
                            
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($setDigiCoinData)) {
                            $i = 0;
                            foreach ($setDigiCoinData as $cn) { ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <td><?= $cn['id'];?></td>
                                <td><img src="<?= $dir.$cn['gift_image'];?>" alt='100x100' height='50px' width='50px' class='img-fluid rounded-circle' /></td>
                                <td><?= $cn['gift_name'];?></td>
                                <td><?= HelperClass::userTypeR[$cn['user_type']];?></td>
                                <td><i class="fa-solid fa-coins"></i> <?= $cn['redeem_digiCoins'];?> Coins </td>
                                <?php //if(HelperClass::checkIfItsACEOAccount()) { ?>
                                  <td>
                                  <a href="?action=status&edit_id=<?= $cn['id'];?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1';?>"
                                      class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger';?>">
                                      <?php  echo ($cn['status'] == '1')? 'Active' : 'Inactive';?>
                                  </td>
                                  <td>
                                    <a href="?action=edit&edit_id=<?= $cn['id'];?>" class="btn btn-warning">Edit</a>
                                    <a href="?action=delete&delete_id=<?= $cn['id'];?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this?');">Delete</a>
                                  </td>
                                <?php   } ?>
                              </tr>
                          <?php  }
                         // } ?>

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