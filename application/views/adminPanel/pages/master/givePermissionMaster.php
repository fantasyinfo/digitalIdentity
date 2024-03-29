<?php

(HelperClass::checkIfItsACEOAccount()) ?: redirect(base_url());



$parentMenu = $this->db->query("SELECT * FROM " . Table::adminPanelMenuTable . " WHERE is_parent = 1 AND status = '1'")->result_array();

$childMenu = $this->db->query("SELECT * FROM " . Table::adminPanelMenuTable . " WHERE is_child = 1 AND status = '1'")->result_array();

if (isset($_POST['providePermissions'])) {
    $totalParents = count($_POST['pMenu']);
    $permessions = [];
    $menusSingle = [];
    for ($i = 0; $i < $totalParents; $i++) {

        $menus = $this->db->query("SELECT id FROM " . Table::adminPanelMenuTable . " WHERE is_child = 1 AND status = '1' AND parent_id = '{$_POST['pMenu'][$i]}' ")->result_array();

        foreach ($menus as $m) {
            array_push($menusSingle, (string)$m['id']);
        }



        //  array_push($permessions,$menus);

    }

    // $menusSingle = implode(',',$menusSingle);
    // $menusSingle = rtrim($menusSingle,',');
    //echo $menusSingle;
    // echo $menusSingle;die();
    $schoolUniqueCodeViaModal = $_POST['unique_id'];

    $schoolDetails = $this->db->query($sql = "SELECT * FROM " . Table::schoolMasterTable . " WHERE unique_id = '$schoolUniqueCodeViaModal'")->result_array();


    $updateStatus = $this->db->query("UPDATE " . Table::schoolMasterTable . " SET status = '2' WHERE unique_id = '$schoolUniqueCodeViaModal'");


    $userDefaultPermissionArr = [
        'Admin' => $menusSingle,
        'Staff' => $menusSingle,
        'Principal' => $menusSingle
    ];

    // first check if already has perssmission then update
    $alp = $this->db->query($sql = "SELECT * FROM " . Table::schoolModulesTable . " WHERE schoolUniqueCode = '$schoolUniqueCodeViaModal' ORDER BY id DESC LIMIT 1")->result_array();

    if (empty($alp)) {
        // insert
        $p = $this->db->query("INSERT INTO " . Table::schoolModulesTable . " (schoolUniqueCode,modules) VALUES ('$schoolUniqueCodeViaModal','" . json_encode($menusSingle) . "')");
    } else {
        // update
        $updateStatus = $this->db->query("UPDATE " . Table::schoolModulesTable . " SET modules = '" . json_encode($menusSingle) . "' WHERE schoolUniqueCode = '$schoolUniqueCodeViaModal' AND id = '{$alp[0]['id']}' ");
    }





    foreach ($userDefaultPermissionArr as $key => $value) {

        $al = $this->db->query($sql = "SELECT * FROM " . Table::userTable . " WHERE schoolUniqueCode = '$schoolUniqueCodeViaModal' AND email = '" . $key . '@email.com' . "' AND user_type = '$key' ORDER BY id DESC LIMIT 1")->result_array();

        if (!empty($al)) {
            $pa = $this->db->query($sql = "SELECT * FROM " . Table::panelMenuPermissionTable . " WHERE schoolUniqueCode = '$schoolUniqueCodeViaModal' AND user_id = '{$al[0]['id']}' AND user_type = '$key' ORDER BY id DESC LIMIT 1")->result_array();

            if (!empty($pa)) {
                // update permission
                $updateStatus = $this->db->query("UPDATE " . Table::panelMenuPermissionTable . " SET permissions = '" . json_encode($value) . "' WHERE schoolUniqueCode = '$schoolUniqueCodeViaModal' AND user_id = '{$alp[0]['id']}' AND id = '{$pa[0]['id']}' ");
                continue;
            }
        }



        $password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" . rand(000000000, 999999999)), 0, 6);
        $salt = HelperClass::generateRandomToken();
        $passWordForSave = HelperClass::encode($password, $salt);

        // inserting default users
        $userArr = [];
        $userArr['schoolUniqueCode'] = $schoolUniqueCodeViaModal;
        $userArr['name'] = $key;
        $userArr['email'] = $key . '@email.com';
        $userArr['password'] = $passWordForSave;
        $userArr['user_type'] = $key;
        $userArr['salt'] = $salt;
        $lastInserId = $this->CrudModel->insert(Table::userTable, $userArr);

        // inserting permmison
        $insertNewArr = [];
        $insertNewArr['schoolUniqueCode'] = $schoolUniqueCodeViaModal;
        $insertNewArr['user_id'] = $lastInserId;
        $insertNewArr['user_type'] = $key;
        $insertNewArr['permissions'] = json_encode($value);
        $insertNewArr['is_head'] = '1';
        $insertNewArr['status'] = '1';
        $this->CrudModel->insert(Table::panelMenuPermissionTable, $insertNewArr);
    }

    // update default notifications
    foreach (HelperClass::setNotificationForWhat as $key => $value) {
        // if already inserrted then continue
        $aal = $this->db->query($sql = "SELECT * FROM " . Table::setNotificationTable . " WHERE schoolUniqueCode = '$schoolUniqueCodeViaModal' AND for_what = '$key' AND title = '" . htmlspecialchars(HelperClass::defaultNotifications[$key]['title']) . "' AND body = '" . htmlspecialchars(HelperClass::defaultNotifications[$key]['body']) . "' ORDER BY id DESC LIMIT 1")->result_array();

        if (!empty($aal)) {
            continue;
        }

        $insert =  $this->db->query("INSERT INTO " . Table::setNotificationTable . " (schoolUniqueCode,for_what,title,body) 
      VALUES ('$schoolUniqueCodeViaModal', $key,'" . htmlspecialchars(HelperClass::defaultNotifications[$key]['title']) . "', '" . htmlspecialchars(HelperClass::defaultNotifications[$key]['body']) . "' )");
    }


    $alreadyUser = $this->db->query($sql = "SELECT * FROM " . Table::userTable . " WHERE schoolUniqueCode = '$schoolUniqueCodeViaModal' AND email = '{$schoolDetails[0]['email']}' AND user_type = 'Admin' ORDER BY id DESC LIMIT 1")->result_array();

    if (!empty($alreadyUser)) {
        $msgArr = [
            'class' => 'success',
            'msg' => 'School Status Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
        header("Refresh:0" . base_url() . "master/givePermissionMaster");
        exit(0);
    }


    // insert a user for login to panel
    $pass = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ" . rand(000000000, 999999999)), 0, 6);
    $saltY = HelperClass::generateRandomToken();
    $passWordForSaveY = HelperClass::encode($pass, $saltY);
    $loginUser = [];
    $loginUser['schoolUniqueCode'] = $schoolUniqueCodeViaModal;
    $loginUser['name'] = $schoolDetails[0]['school_name'] . ' Admin';
    $loginUser['email'] = $schoolDetails[0]['email'];
    $loginUser['password'] = $passWordForSaveY;
    $loginUser['user_type'] = 'Admin';
    $loginUser['salt'] = $saltY;
    $loginDefaultSchool = $this->CrudModel->insert(Table::userTable, $loginUser);

    if ($loginDefaultSchool) {


        $permissions = $this->db->query("SELECT permissions FROM " . Table::panelMenuPermissionTable . " WHERE user_type = 'Admin' AND schoolUniqueCode = '$schoolUniqueCodeViaModal' AND is_head = '1' AND status = '1' ORDER BY id DESC LIMIT 1")->result_array();

        $p = $this->db->query("INSERT INTO " . Table::panelMenuPermissionTable . " (schoolUniqueCode,user_id, user_type,permissions) VALUES ('$schoolUniqueCodeViaModal','$loginDefaultSchool','Admin','{$permissions[0]['permissions']}')");

        $py = $this->db->query("INSERT INTO " . Table::preLoader . " (schoolUniqueCode,isRun) VALUES ('$schoolUniqueCodeViaModal','1')");



        if ($p) {

            $to = $schoolDetails[0]['email'];
            $subject = 'Login Details for ' . HelperClass::brandName . ' on ' . date('d-m-y h:i:A');
            // $body = 
            //     "<table style='border 1px solid black;'>
            //       <tr>
            //         <th>School Name</th>
            //         <th>School Unique Code</th>
            //         <th>Email</th>
            //         <th>Password</th>
            //       </tr>
            //       <tr>
            //         <td>{$schoolDetails[0]['school_name']}</td>
            //         <td>{$schoolDetails[0]['unique_id']}</td>
            //         <td>{$schoolDetails[0]['email']}</td>
            //         <td>$pass</td>
            //       </tr>
            //     </table>"
            // ;



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
                  <td bgcolor="#FFA73B" align="center" style="padding: 0px 10px 0px 10px;">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                          <tr>
                              <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                                  <h1 style="font-size: 48px; font-weight: 400; margin: 2;">Welcome!</h1> <img src=" https://img.icons8.com/clouds/100/000000/handshake.png" width="125" height="120" style="display: block; border: 0px;" />
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
                                  <p style="margin: 0;">We"re excited to have you get started. Find Your Login Details Below.</p>
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
                                                          <h3 style="color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 22px; font-weight: 400; line-height: 25px;">School Name : ' . $schoolDetails[0]['school_name'] . '</h3>
                                                      </td>
                                                  </tr>
                                              </table>
                                          </td>
                                      </tr>
                                  </table>
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
                                                          <h3 style="color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 22px; font-weight: 400; line-height: 25px;">School Code : ' . $schoolDetails[0]['unique_id'] . '</h3>
                                                      </td>
                                                  </tr>
                                              </table>
                                          </td>
                                      </tr>
                                  </table>
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
                                                          <h3 style="color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 22px; font-weight: 400; line-height: 25px;">Email : ' . $schoolDetails[0]['email'] . '</h3>
                                                      </td>
                                                  </tr>
                                              </table>
                                          </td>
                                      </tr>
                                  </table>
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
                                                          <h3 style="color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 22px; font-weight: 400; line-height: 25px;">Password : ' . $pass . '</h3>
                                                      </td>
                                                  </tr>
                                              </table>
                                          </td>
                                      </tr>
                                  </table>
                              </td>
                          </tr> 
          
      
      
      
                          <!-- COPY -->
                          <tr>
                              <td bgcolor="#ffffff" align="left">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                          <td bgcolor="#ffffff" align="center" style="padding: 20px 30px 60px 30px;">
                                              <table border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                      <td align="center" style="border-radius: 3px;" bgcolor="#FFA73B"><a href="https://dvm.digitalfied.in" target="_blank" style="font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; border: 1px solid #FFA73B; display: inline-block;">Login Now!!</a></td>
                                                  </tr>
                                              </table>
                                          </td>
                                      </tr>
                                  </table>
                              </td>
                          </tr> <!-- COPY -->
                          <tr>
                              <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                                  <p style="margin: 0;">If you don\'t found your login details please email us back:</p>
                              </td>
                          </tr> <!-- COPY -->
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

            HelperClass::sendEmail($to, $subject, $body);
        }
    }
}


// HelperClass::sendEmail('gs27349gs@gmail.com', 'User Login Details', 'Hey, You have sucefully login now!!');
?>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->

        <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php $this->load->view("adminPanel/pages/sidebar.php");

        $this->load->library('session');

        // fetching city data
        $registerdSchoolData = $this->db->query("SELECT * FROM " . Table::schoolMasterTable . " WHERE status != '4' ORDER BY id DESC")->result_array();

    


        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
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
                                    <h3 class="card-title">Permission Master</h3>

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Showing All Registerd School Data</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="hourDataTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>School Id</th>
                                                        <th>School Name</th>
                                                        <th>Email</th>
                                                        <th>Mobile</th>
                                                        <th>Address</th>
                                                        <th>Already Modules</th>
                                                        <th>Action</th>
                                                        <!-- <th>Status</th>
                            <th>Action</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (isset($registerdSchoolData)) {
                                                        $i = 0;
                                                        foreach ($registerdSchoolData as $cn) {

                                                            $alp = $this->db->query($sql = "SELECT * FROM " . Table::schoolModulesTable . " WHERE schoolUniqueCode = '{$cn['unique_id']}' ORDER BY id DESC LIMIT 1")->result_array();

                                                    ?>
                                                            <tr>
                                                                <td><?= ++$i; ?></td>
                                                                <td><?= $cn['unique_id']; ?></td>
                                                                <td><?= $cn['school_name']; ?></td>
                                                                <td><?= $cn['email']; ?></td>
                                                                <td><?= $cn['mobile']; ?></td>
                                                                <td><?= $cn['address']; ?></td>
                                                                <td><?= @$alp[0]['modules']; ?></td>
                                                                <td><button onclick="showOptions('<?= $cn['unique_id']; ?>')" class="btn btn-block mybtnColor">Give Permissions</button></td>
                                                                <!-- <td>
                                  <a href="?action=status&schoolUniqueCode=<?= $cn['unique_id']; ?>&edit_id=<?= $cn['id']; ?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1'; ?>" class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger'; ?>">
                                    <?php echo ($cn['status'] == '1') ? 'Active' : 'Inactive'; ?> </a>
                                </td> -->
                                                                <!-- <td>
                                  <a href="?action=delete&delete_id=<?= $cn['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this?');">Delete</a>
                                </td> -->
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

        <!-- Modal -->

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Provide Permissions</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="unique_id" id="unique_id" />
                            <?php if (isset($parentMenu)) {
                                foreach ($parentMenu as $p) { ?>

                                    <input type="checkbox" name="pMenu[]" value="<?= $p['id'] ?>" />&nbsp;&nbsp;&nbsp;<?= $p['name'] ?></br>

                            <?php  } // parent foreach 
                            } // isset  
                            ?>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="providePermissions" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
    </div>
    <?php $this->load->view("adminPanel/pages/footer.php"); ?>
    <!-- ./wrapper -->
    <script>
        $("#hourDataTable").DataTable();

        function showOptions(e) {
            $("#unique_id").val(e);
            $("#exampleModal").modal('show');
        }
    </script>