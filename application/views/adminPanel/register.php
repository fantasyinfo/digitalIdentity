<?php
$data = [] ;
$data['pageTitle'] = 'Register';
$data['adminPanelUrl'] = 'assets/adminPanel/';


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $data['pageTitle'] ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
 
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() . $data['adminPanelUrl'] ?>dist/css/adminlte.min.css">
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<?php 

$this->load->library('session');
$this->load->model('CrudModel');



//testing
// echo $this->CrudModel->getUniqueIdForSchool('Deep Vidya Mandir','98375678890');

// die();









// if the user is login then redirect to dashboard
if(!empty($this->session->userdata('name')) && !empty($this->session->userdata('email')) && !empty($this->session->userdata('user_type')) && !empty($this->session->userdata('userData')))
{
  //header("Refresh:0 url=adminPanel");
}


if(isset($_POST['submit']))
{
  $school_name = HelperClass::sanitizeInput($_POST['school_name']);
  $mobile = HelperClass::sanitizeInput($_POST['mobile']);
  $email = HelperClass::sanitizeInput($_POST['email']);
  $school_address = HelperClass::sanitizeInput($_POST['school_address']);
  $classes_up_to = HelperClass::sanitizeInput($_POST['classes_up_to']);
  

  $userData = $this->db->query("SELECT * FROM " . Table::schoolMasterTable . " WHERE email = '$email' OR mobile = '$mobile' AND status = '1'")->result_array();

  if(!empty($userData))
  {
    $msgArr = [
      'class' => 'danger',
      'msg' => 'This Email id OR Mobile Number is Already Register With Us Try Login.',
    ];
    $this->session->set_userdata($msgArr);
    header("Refresh:1 ".base_url()."register");
    exit(0);
  }

  $insertArr = [];
  $insertArr['unique_id'] = $this->CrudModel->getUniqueIdForSchool($school_name,$mobile);
  $insertArr['u_qr_id'] = '';
  $insertArr['user_id'] = $this->CrudModel->getSchoolId(Table::schoolMasterTable);
  $insertArr['school_name'] = $school_name;
  $insertArr['mobile'] = $mobile;
  $insertArr['email'] = $email;
  $insertArr['address'] = $school_address;
  $insertArr['classes_up_to'] = ($classes_up_to) ? $classes_up_to : '0';
  $insertArr['status'] = '2';

  $insertId = $this->CrudModel->insert(Table::schoolMasterTable,$insertArr);


  if($insertId)
  {
    // insert qrcode data
    $qrDataArr = [];
    $qrDataArr['schoolUniqueCode'] = $insertArr['unique_id'];
    $qrDataArr['qrcodeUrl'] = HelperClass::qrcodeUrl . "?schid=" . HelperClass::schoolPrefix . $insertArr['unique_id'];
    $qrDataArr['uniqueValue'] = $insertArr['unique_id'];
    $qrDataArr['type'] = HelperClass::userType['School'];
    $qrDataArr['user_id'] = $insertId;

    $qrInsertId = $this->CrudModel->insert(Table::qrcodeSchoolsTable,$qrDataArr);
    if($qrInsertId)
    {
      $updateArr['u_qr_id'] = $qrInsertId;
      if($this->CrudModel->update(Table::schoolMasterTable,$updateArr,$insertId))
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Your School is successfully registerd with us. we will contact you as soon as possible',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        echo $this->db->last_query();
        die();
        return false;
      }
    }else
    {
      echo $this->db->last_query();
      die();
      return false;
    }
   
  }else
  {
      return false;
  }
  
}



?>



<body class="hold-transition login-page">
  <div class="container">
    <div class="row">

      <div class="mx-auto col-md-4 mt-5">
        <div class="login-box">

          <div class="card card-outline card-primary">
            <div class="card-header text-center">
              <a href="#" class="h1"><b>Digital</b>fied</a>
            </div>
            <div class="card-body">
            <?php 
              if(!empty($this->session->userdata('msg')))
              {?>

              <div class="alert alert-<?=$this->session->userdata('class')?> alert-dismissible fade show" role="alert">
                <?=$this->session->userdata('msg');
                 if($this->session->userdata('class') == 'success')
                 {
                   HelperClass::swalSuccess($this->session->userdata('msg'));
                 }else if($this->session->userdata('class') == 'danger')
                 {
                   HelperClass::swalError($this->session->userdata('msg'));
                 }
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php
              $this->session->unset_userdata('class') ;
              $this->session->unset_userdata('msg') ;
              }
              ?>
              <p class="login-box-msg">Sign up to start your session</p>

              <form action="" method="post">
                <div class="input-group mb-3">
                  <input type="text" name="school_name" class="form-control" placeholder="School Name" required>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fa-solid fa-school"></span>
                    </div>
                  </div>
                </div>
                <div class="input-group mb-3">
                  <input type="number" name="mobile" class="form-control" placeholder="Mobile Number" required>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fa-solid fa-mobile-screen"></span>
                    </div>
                  </div>
                </div>
                <div class="input-group mb-3">
                  <input type="email" name="email" class="form-control" placeholder="Email" required>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-envelope"></span>
                    </div>
                  </div>
                </div>
                <div class="input-group mb-3">
                  <input type="text" name="school_address" class="form-control" placeholder="School Address" required>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fa-solid fa-location-dot"></span>
                    </div>
                  </div>
                </div>
                <div class="input-group mb-3">
                  <input type="text" name="classes_up_to" class="form-control" placeholder="School Classes UpTo 5 / 8 / 12" >
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fa-solid fa-users-rectangle"></span>
                    </div>
                  </div>
                </div>
         
                <div class="row">
            
                  <div class="col-12">
                    <button type="submit" name="submit" class="btn btn-primary btn-block btn-block">Register Now</button>
                  </div>
                  <!-- /.col -->
                </div>
              </form>
         
            </div>

            <p class="login-box-msg"><a href="<?= base_url();?>">Already Registerd ? Login Now</a></p>
            <!-- /.card-body -->
          </div>

        </div>
      </div>



    </div>
  </div>
</body>
</html>
  <!-- /.login-box -->
  <?php
 // include("pages/footer.php");

  ?>