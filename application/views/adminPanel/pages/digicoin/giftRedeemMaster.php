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

    if(HelperClass::checkIfItsACEOAccount()) {
      $schoolCodeCheck = '';
    }else
    {
      $schoolCodeCheck = " WHERE grt.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ";
    }
  // fetching city data
    $giftRedeemData = $this->db->query("SELECT grt.*, gt.id as gift_id, gt.gift_image, gt.gift_name, gt.redeem_digiCoins FROM " . Table::giftRedeemTable . " grt 
    LEFT JOIN ".Table::giftTable." gt ON gt.id = grt.gift_id
   $schoolCodeCheck ORDER BY grt.id DESC")->result_array();
    


    // $teacherD = $this->db->query("SELECT SUM(digiCoin), user_id FROM get_digi_coin 
    // WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND user_type = 'Teacher'
    // GROUP BY user_id ORDER BY SUM(digiCoin) DESC")->result_array();







    // $studentD = $this->db->query("SELECT SUM(digiCoin), user_id FROM get_digi_coin 
    // WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND user_type = 'Student'
    // GROUP BY user_id ORDER BY SUM(digiCoin) DESC")->result_array();



    // $tCount = count($l);
    // $teacherC = 0;
    // $studentC = 0;

    // $teacherArr = [];
    // $studentArr = [];
    // for($i=0; $i < $tCount; $i++)
    // {
    //   if($l[$i]['user_type'] == 'Teacher')
    //   {
    //     $subArr = [];
    //     $subArr['user_id'] = $l[$i]['user_id'];
    //     $subArr['digiCoin'] = $l[$i]['digiCoin'];
    //     array_push($teacherArr,$subArr);
    //   }else if($l[$i]['user_type'] == 'Student')
    //   {
    //     $subArr = [];
    //     $subArr['user_id'] = $l[$i]['user_id'];
    //     $subArr['digiCoin'] = $l[$i]['digiCoin'];
    //     array_push($studentArr,$subArr);
    //   }
    // }
    // HelperClass::prePrintR($teacherArr);









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
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Showing All Gifts Rdeem Requests</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="MonthDataTable" class="table table-bordered table-striped table-responsive">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Gift Redeem Id</th>
                            <th>Gift Id</th>
                            <th>Gift Name</th>
                            <th>Gift Image</th>
                            <th>User Name</th>
                            <th>User Unique Id</th>
                            <th>User Type</th>
                            <th>Gift DigiCoin Cost</th>
                            <th>DigiCoin Used</th>
                            <th>School Code</th>
                            <th>Change Status</th>
                            <th>Gift Redeem Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($giftRedeemData)) {
                            $i = 0;
                            foreach ($giftRedeemData as $cn) { ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <td><?= $cn['id'];?></td>
                                <td><?= $cn['gift_id'];?></td>
                                <td><?= $cn['gift_name'];?></td>
                                <td><img src="<?= $dir.$cn['gift_image'];?>" alt='100x100' height='50px' width='50px' class='img-fluid rounded-circle' /></td>


                                <?php 
                                if(HelperClass::userTypeR[$cn['login_user_type']] == 'Student' || HelperClass::userTypeR[$cn['login_user_type']] == 'Parent' )
                                {
                                  $tableName = Table::studentTable;
                                }else if (HelperClass::userTypeR[$cn['login_user_type']] == 'Teacher')
                                {
                                  $tableName = Table::teacherTable;
                                }
                                // if(HelperClass::checkIfItsACEOAccount()) { 
                                //   $condition = "";
                                // }else
                                // {
                                  $condition = " AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ";
                                // }
                                $s = $this->db->query("SELECT name, id, user_id FROM ".$tableName." WHERE id='{$cn['login_user_id']}' $condition LIMIT 1")->result_array();
                                
                                ?>

                                <td><?= $s[0]['name'];?></td>
                                <td><?= $s[0]['user_id'];?></td>
                                <td><?= HelperClass::userTypeR[$cn['login_user_type']];?></td>
                                <td><i class="fa-solid fa-coins"></i>  <?= $cn['redeem_digiCoins'];?> Coins</td>
                                <td><i class="fa-solid fa-coins"></i>  <?= $cn['digiCoin_used'];?> Coins</td>
                               <?php 
                               //if(HelperClass::checkIfItsACEOAccount()) { ?>
                               <td>
                                <?= $cn['schoolUniqueCode'];?>
                               </td>
                                <td>
                                  <select class="form-control" name="status" onchange="changeStatus(this,'<?= $cn['id']?>', '<?= $cn['schoolUniqueCode']?>')">
                                    <?php  foreach(HelperClass::giftStatus as $g => $v)
                                    { 
                                      if($cn['status'] == $g)
                                      {
                                        $selected = 'selected';
                                      }else
                                      {
                                        $selected = '';
                                      }
                                      
                                      ?>
                                        <option <?= $selected?> value="<?= $g?>"><?= $v?></option>
                                   <?php }
                                  
                                    ?>
                                   
                                  </select>
                                </td>
                                
                              <?php //}  else
                                 //  { ?>
                                    <!-- <td> -->
                                      <?php // echo HelperClass::giftStatus[$cn['status']]; ?>
                                    <!-- </td> -->
                                  <?php //}?>
                              
                                <td><?= date('d-m-Y h:i:A', strtotime($cn['created_at']));?></td>
                              </tr>
                          <?php  }
                          } 
                          ?>

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
    // var ajaxUrl = '<?= base_url() . 'ajax/listStudentsAjax' ?>';


    $("#MonthDataTable").DataTable();

    function changeStatus(x,y,schoolCode)
    {
      console.log(x.value);
      console.log(y);
      $.ajax({
        url: '<?= base_url('digicoin/changeGiftStatus')?>',
        method: 'post',
        data: {
          status: x.value,
          editId: y,
          schoolCode: schoolCode
        },
        success: function(response)
        {
          response = $.parseJSON(response);
          if(response.status == true)
          {
            location.reload();
          }
          console.log(response);

        },
        error: function(e)
        {
          console.log(e);
        }
      })
    }
  </script>