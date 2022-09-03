<?php
$data = [] ;
$data['pageTitle'] = 'Login';
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


// if the user is login then redirect to dashboard
if(!empty($this->session->userdata('name')) && !empty($this->session->userdata('email')) && !empty($this->session->userdata('user_type')) && !empty($this->session->userdata('userData')))
{
  //header("Refresh:0 url=adminPanel");
}


if(isset($_POST['submit']))
{
  $email = HelperClass::sanitizeInput($_POST['email']);
  $schoolUniqueCode = HelperClass::sanitizeInput($_POST['schoolUniqueCode']);
  $password = HelperClass::sanitizeInput($_POST['password']);

  $userData = $this->db->query("SELECT * FROM " . Table::userTable . " WHERE email = '$email' AND schoolUniqueCode = '$schoolUniqueCode' AND status = 1 LIMIT 1")->result_array();

  

  if($userData)
  {
    $pass = $userData[0]['password'];
    $salt = $userData[0]['salt'];

    $dbPass = HelperClass::decode($pass,$salt);

    if($dbPass == $password)
    {

      $userArr = [];
      $userArr['id'] = $userData[0]['id'];
      $userArr['name'] = $userData[0]['name'];
      $userArr['email'] = $userData[0]['email'];
      $userArr['user_type'] = $userData[0]['user_type'];
      $userArr['schoolUniqueCode'] = $userData[0]['schoolUniqueCode'];

      $userArr['userData'] = [
        'id' => $userData[0]['id'],
        'name' => $userData[0]['name'],
        'email' => $userData[0]['email'],
        'user_type' => $userData[0]['user_type'],
        'schoolUniqueCode' => $userData[0]['schoolUniqueCode']
       
      ];
      $this->session->set_userdata($userArr);

      $msgArr = [
        'class' => 'success',
        'msg' => 'Login successfull, redirect you to dashboard.',
      ];
      $this->session->set_userdata($msgArr);

      header("Refresh:1 url=adminPanel");
    }else
    {
      $msgArr = [
        'class' => 'danger',
        'msg' => 'Password does\'t matched, please use correct password.',
      ];
      $this->session->set_userdata($msgArr);
    }

  }else
  {
    $msgArr = [
      'class' => 'danger',
      'msg' => 'Email id and School Code does\'t matched, please use correct email.',
    ];
    $this->session->set_userdata($msgArr);
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
                 }else 
                 
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
              <p class="login-box-msg">Sign in to start your session</p>

              <form action="" method="post" >
              <div class="input-group mb-3">
                  <input type="text" name="schoolUniqueCode" class="form-control" placeholder="School Code" required>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fa-solid fa-address-card"></span>
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
                  <input type="password" name="password" class="form-control" placeholder="Password" required>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>
                </div>
                <div class="row">
            
                  <div class="col-4">
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Sign In</button>
                  </div>
                  <!-- /.col -->
                </div>
              </form>
         
            </div>

            <p class="login-box-msg"><a href="<?= base_url() . 'register';?>">Register Your School Now!! Enjoy All Benefits</a></p>
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