<?php

(HelperClass::checkIfItsACEOAccount()) ?: redirect(base_url());


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

    // edit and delete action
    if (isset($_GET['action'])) {

      // delete the city
      if ($_GET['action'] == 'delete') {
        $deleteId = $_GET['delete_id'];

        $deleteUser = $this->db->query("UPDATE " . Table::schoolMasterTable . " SET status = '4' WHERE id='$deleteId'");
        if ($deleteUser) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'School Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'School Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "master/givePermissionMaster");
      }


      if ($_GET['action'] == 'status') {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];

        $schoolUniqueCode = $_GET['schoolUniqueCode'];

        if ($status == '1') {
          $alreadyUser = $this->db->query($sql = "SELECT count(1) as count FROM " . Table::userTable . " ut 
          INNER JOIN " . Table::panelMenuPermissionTable . " pmp ON ut.schoolUniqueCode = pmp.schoolUniqueCode AND ut.id = pmp.user_id
          WHERE ut.schoolUniqueCode = '$schoolUniqueCode' AND pmp.is_head = '1'")->result_array();

          if ($alreadyUser[0]['count'] != '0') {
            $msgArr = [
              'class' => 'danger',
              'msg' => 'This School Default Users & Permission is already inserted, Please Edit That',
            ];
            $this->session->set_userdata($msgArr);
            header("Refresh:0 " . base_url() . "master/givePermissionMaster");
            exit(0);
          }
          // echo $sql;
          // die();
        }

        $schoolDetails = $this->db->query($sql = "SELECT * FROM " . Table::schoolMasterTable . " WHERE id = '$updateId'")->result_array();


        $updateStatus = $this->db->query("UPDATE " . Table::schoolMasterTable . " SET status = '$status' WHERE id = '$updateId'");

        // insert default userPermissions

        // fetch default permissions


        $userDefaultPermissionArr = [
          'Admin' => [ "2", "3", "5", "6", "13", "14", "47", "55", "16", "17", "18", "19", "20", "22", "23", "24", "25", "62", "28", "39", "43", "32", "34", "35", "36", "37", "38", "29", "31", "41", "45", "46", "49", "50", "51", "53", "54", "56", "57", "59", "60", "61"
        ],
          'Staff' => [ "2", "3", "5", "6", "13", "14", "47", "55", "16", "17", "18", "19", "20", "22", "23", "24", "25", "62", "28", "39", "43", "32", "34", "35", "36", "37", "38", "29", "31", "41", "45", "46", "49", "50", "51", "53", "54", "56", "57", "59", "60", "61"
        ],
          'Principal' => [ "2", "3", "5", "6", "13", "14", "47", "55", "16", "17", "18", "19", "20", "22", "23", "24", "25", "62", "28", "39", "43", "32", "34", "35", "36", "37", "38", "29", "31", "41", "45", "46", "49", "50", "51", "53", "54", "56", "57", "59", "60", "61"
          ]
        ];

        foreach ($userDefaultPermissionArr as $key => $value) {

          $password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" . rand(000000000, 999999999)), 0, 6);
          $salt = HelperClass::generateRandomToken();
          $passWordForSave = HelperClass::encode($password, $salt);

          // inserting default users
          $userArr = [];
          $userArr['schoolUniqueCode'] = $schoolUniqueCode;
          $userArr['name'] = $key;
          $userArr['email'] = $key . '@email.com';
          $userArr['password'] = $passWordForSave;
          $userArr['user_type'] = $key;
          $userArr['salt'] = $salt;
          $lastInserId = $this->CrudModel->insert(Table::userTable, $userArr);

          // inserting permmison
          $insertNewArr = [];
          $insertNewArr['schoolUniqueCode'] = $schoolUniqueCode;
          $insertNewArr['user_id'] = $lastInserId;
          $insertNewArr['user_type'] = $key;
          $insertNewArr['permissions'] = json_encode($value);
          $insertNewArr['is_head'] = '1';
          $insertNewArr['status'] = '1';
          $this->CrudModel->insert(Table::panelMenuPermissionTable, $insertNewArr);
        }





        // update default notifications
        foreach(HelperClass::setNotificationForWhat as $key => $value)
        {
          $insert =  $this->db->query("INSERT INTO ".Table::setNotificationTable." (schoolUniqueCode,for_what,title,body) 
            VALUES ('$schoolUniqueCode', $key,'".htmlspecialchars(HelperClass::defaultNotifications[$key]['title'])."', '".htmlspecialchars(HelperClass::defaultNotifications[$key]['body'])."' )");
          
        }

        // insert a user for login to panel
        $pass = 'abc012';
        $saltY = HelperClass::generateRandomToken();
        $passWordForSaveY = HelperClass::encode($pass, $saltY);
        $loginUser = [];
        $loginUser['schoolUniqueCode'] = $schoolUniqueCode;
        $loginUser['name'] = $schoolDetails[0]['school_name'] . ' Admin';
        $loginUser['email'] = $schoolDetails[0]['email'];
        $loginUser['password'] = $passWordForSaveY;
        $loginUser['user_type'] = 'Admin';
        $loginUser['salt'] = $saltY;
        $loginDefaultSchool = $this->CrudModel->insert(Table::userTable, $loginUser);

        if ($loginDefaultSchool) {

          
         $permissions = $this->db->query("SELECT permissions FROM " . Table::panelMenuPermissionTable . " WHERE user_type = 'Admin' AND schoolUniqueCode = '$schoolUniqueCode' AND is_head = '1' AND status = '1'")->result_array();
          
          $p = $this->db->query("INSERT INTO " . Table::panelMenuPermissionTable . " (schoolUniqueCode,user_id, user_type,permissions) VALUES ('$schoolUniqueCode','$loginDefaultSchool','Admin','{$permissions[0]['permissions']}')");

          if($p)
          {

            $to = $schoolDetails[0]['email'];
            $subject = 'Login Details for ' . HelperClass::brandName . ' on '. date('d-m-y h:i:A');
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
                                                                <h3 style="color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 22px; font-weight: 400; line-height: 25px;">School Name : '.$schoolDetails[0]['school_name'].'</h3>
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
                                                                <h3 style="color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 22px; font-weight: 400; line-height: 25px;">School Code : '.$schoolDetails[0]['unique_id'].'</h3>
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
                                                                <h3 style="color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 22px; font-weight: 400; line-height: 25px;">Email : '.$schoolDetails[0]['email'].'</h3>
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
                                                                <h3 style="color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 22px; font-weight: 400; line-height: 25px;">Password : '.$pass.'</h3>
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

// update status now
        if ($updateStatus) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'School Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'School Status Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        //header("Refresh:30 " . base_url() . "master/givePermissionMaster");
      }
    }



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
                
                if($this->session->userdata('class') == 'success')
                 {
                   HelperClass::swalSuccess($this->session->userdata('msg'));
                 }else if($this->session->userdata('class') == 'danger')
                 {
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
                            <th>Classes Up To</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($registerdSchoolData)) {
                            $i = 0;
                            foreach ($registerdSchoolData as $cn) { ?>
                              <tr>
                                <td><?= ++$i; ?></td>
                                <td><?= $cn['unique_id']; ?></td>
                                <td><?= $cn['school_name']; ?></td>
                                <td><?= $cn['email']; ?></td>
                                <td><?= $cn['mobile']; ?></td>
                                <td><?= $cn['address']; ?></td>
                                <td><?= $cn['classes_up_to']; ?></td>
                                <td>
                                  <a href="?action=status&schoolUniqueCode=<?= $cn['unique_id']; ?>&edit_id=<?= $cn['id']; ?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1'; ?>" class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger'; ?>">
                                    <?php echo ($cn['status'] == '1') ? 'Active' : 'Inactive'; ?> </a>
                                </td>
                                <td>
                                  <a href="?action=delete&delete_id=<?= $cn['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this?');">Delete</a>
                                </td>
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
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->
  <script>
    $("#hourDataTable").DataTable();
  </script>