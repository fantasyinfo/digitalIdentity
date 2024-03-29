<?php
$data = [] ;
$data['pageTitle'] = 'Register';
$data['adminPanelUrl'] = 'assets/adminPanel/';


$body = '<!DOCTYPE html>
<html>

<head>
    <title>Account Confirmation Email From - Digitalfied</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">
        @media screen {
            @font-face {
                font-family: "Lato";
                font-style: normal;
                font-weight: 400;
                src: local("Lato Regular"), local("Lato-Regular"), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format("woff");
            }

            @font-face {
                font-family: "Lato";
                font-style: normal;
                font-weight: 700;
                src: local("Lato Bold"), local("Lato-Bold"), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format("woff");
            }

            @font-face {
                font-family: "Lato";
                font-style: italic;
                font-weight: 400;
                src: local("Lato Italic"), local("Lato-Italic"), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format("woff");
            }

            @font-face {
                font-family: "Lato";
                font-style: italic;
                font-weight: 700;
                src: local("Lato Bold Italic"), local("Lato-BoldItalic"), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format("woff");
            }
        }

        /* CLIENT-SPECIFIC STYLES */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        /* RESET STYLES */
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* MOBILE STYLES */
        @media screen and (max-width:600px) {
            h1 {
                font-size: 32px !important;
                line-height: 32px !important;
            }
        }

        /* ANDROID CENTER FIX */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
</head>

<body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
    <!-- HIDDEN PREHEADER TEXT -->
    <div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: "Lato", Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;"> We"re thrilled to have you here! Get ready to dive into your new account.
    </div>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <!-- LOGO -->
        <tr>
            <td bgcolor="#FFA73B" align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td align="center" valign="top" style="padding: 40px 10px 40px 10px;"> </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#FFA73B" align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                            <img src=" https://digitalfied.com/digitalfied_logo.png" width="300" height="120" style="display: block; border: 0px;" />
                         
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" align="center" style=" color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;">Digital School Management Software - ERP</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
 
        <tr>
            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="center" style="padding: 20px 30px 40px 30px; color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;">We"re excited to have you get started.</p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" align="left">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td bgcolor="#ffffff" align="center" >
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center" style="border-radius: 3px;">
                                                    <h3 style="color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 22px; font-weight: 400; line-height: 25px;">You have successfully Register With Us. You Will Recived Your Login Details Via New Email After Approval !!</h3>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr> 
              

             
                  
                    <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;"><a href="#" target="_blank" style="color: #FFA73B;">https://digitalfied.com</a></p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 20px 30px; color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;">If you have any questions, just reply to this email&mdash;we"re always happy to help out.</p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 40px 30px; border-radius: 0px 0px 4px 4px; color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;">Cheers,<br>Digitalfied Team</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#f4f4f4" align="center" style="padding: 30px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#FFECD1" align="center" style="padding: 30px 30px 30px 30px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <h2 style="font-size: 20px; font-weight: 400; color: #111111; margin: 0;">Need more help?</h2>
                            <p style="margin: 0;"><a href="https://digitalfied.com" target="_blank" style="color: #FFA73B;">We&rsquo;re here to help you out</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>';



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




  $already = $this->db->query("SELECT unique_id FROM ".Table::schoolMasterTable." WHERE unique_id = '{$insertArr['unique_id']}'")->result_array();

  if(!empty($already))
  {
    $insertArr['unique_id'] = $already[0]['unique_id'] + 1;
  }


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


        HelperClass::sendEmail($email, "$school_name Register Successfully on Digitalfied", $body);
        HelperClass::sendEmail("digitalfied@gmail.com", "$school_name Register Successfully on Digitalfied", $body);

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