<?php 

if(isset($_POST['qrCode'])){

    $qrCode = trim($_POST['qrCode']);
    $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
    $loginuserType = 'Staff';
   $identity =  validateQRCode($qrCode,$loginuserType, $schoolUniqueCode, $this->db, $this->CrudModel);
    //print_r($identity);

    header('Refresh:0');


}



function validateQRCode($qrCode,$loginuserType, $schoolUniqueCode,$db,$cM)
{
  $currentDate = date_create()->format('Y-m-d');
  
  $condition = " AND e.schoolUniqueCode = '$schoolUniqueCode' ";

  $identityType = $cM->extractQrCodeAndReturnUserType($qrCode);
  if ($identityType == HelperClass::userTypeR[1]) {
    $table = Table::qrcodeTable;
  }else if ($identityType == HelperClass::userTypeR[2])
  {
    $table = Table::qrcodeTeachersTable;
  }else if ($identityType == HelperClass::userTypeR[7])
  {
    $table = Table::qrcodeDriversTable;
  }

  if(empty($table)){
    return;
    // HelperClass::APIresponse(500, 'QR Code Table Not Found. Please Check QR Code Again.');
  }

  $d = $db->query("SELECT * FROM " . $table . " WHERE qrcodeUrl = '$qrCode' AND schoolUniqueCode = '$schoolUniqueCode' AND status = '1' LIMIT 1")->result_array();

  if (!empty($d)) {

    if ($identityType == HelperClass::userTypeR[1]) {
      $tableB = Table::studentTable;
      $dir = base_url() . HelperClass::studentImagePath;
    }else if ($identityType == HelperClass::userTypeR[2])
    {
      $tableB = Table::teacherTable;
      $dir = base_url() . HelperClass::teacherImagePath;
    }else if ($identityType == HelperClass::userTypeR[7])
    {
      $tableB = Table::driverTable;
      $dir = base_url() . HelperClass::driverImagePath;
    }



    $details = $db->query("SELECT id, name,email,mobile,CONCAT('$dir',image) as image FROM " . $tableB . " WHERE user_id = '{$d[0]['uniqueValue']}' AND u_qr_id = '{$d[0]['id']}' AND schoolUniqueCode = '$schoolUniqueCode' AND status = '1' LIMIT 1")->result_array();


    $insertData = $db->query("INSERT INTO ".Table::qrScanHistory." (schoolUniqueCode,qrcode,user_id,user_type_id) VALUES ('$schoolUniqueCode','$qrCode','{$details[0]['id']}', '".HelperClass::userType[$identityType]."')");
   
    $details[0]['userType'] = $identityType;

    $idForUser = (String) $details[0]['id'];

    if(!empty($details))
    {
      // fetch token
      $tokensFromDB =  $db->query("SELECT fcm_token FROM " . $tableB . " WHERE id = '$idForUser' AND schoolUniqueCode = '$schoolUniqueCode'  AND status = '1' LIMIT 1")->result_array();

        if($loginuserType == HelperClass::userTypeR[3])
        {
          // staff 

          if(!empty($tokensFromDB))
          {
            $tokenArr = [$tokensFromDB[0]['fcm_token']];
            // fetch notification from db
            $notificationFromDB = $db->query("SELECT title, body FROM ".Table::setNotificationTable." WHERE status = '1' AND schoolUniqueCode = '$schoolUniqueCode' AND for_what = '4' LIMIT 1")->result_array();

            if(!empty($notificationFromDB))
            {
              $title = $cM->replaceNotificationsWords((String)$notificationFromDB[0]['title'],['identity'=>$identityType]);
              $body =  $cM->replaceNotificationsWords((String)$notificationFromDB[0]['body'],['identity'=>$identityType]);
            }else
            {
              $title = "$identityType Entry On 🏫 School.";
              $body = "Hey 👋 Dear $identityType, We Welcome You On 🏫 School, You Have Entered Into The 🏫 School, Entry Gate.";
            }
          }
        }
        // else if($loginuserType == HelperClass::userTypeR[7])
        // {
        //   // driver

        //   if(!empty($tokensFromDB))
        //   {
        //     $tokenArr = [$tokensFromDB[0]['fcm_token']];

        //         // fetch notification from db
        //         $notificationFromDB = $db->query("SELECT title, body FROM ".Table::setNotificationTable." WHERE status = '1' AND schoolUniqueCode = '$schoolUniqueCode' AND for_what = '3' LIMIT 1")->result_array();

        //         if(!empty($notificationFromDB))
        //         {
        //           $title = $cM->replaceNotificationsWords((String)$notificationFromDB[0]['title'],['identity'=>$identityType]);
        //           $body =  $cM->replaceNotificationsWords((String)$notificationFromDB[0]['body'],['identity'=>$identityType]);
        //         }else
        //         {
        //           $title = "$identityType Entry On 🚌 Transport.";
        //           $body = "Hey 👋 Dear $identityType, We Welcome You On 🚌 Bus / Rikshaw. Parents Can Check 📍 Track Location On App 📱 Now!!";
        //         }


            
        //   }
        // }



        $image = null;
        $sound = null;
        $sendPushSMS= json_decode($cM->sendFireBaseNotificationWithDeviceId($tokenArr, $title,$body,$image,$sound), TRUE);
      return; 
      HelperClass::APIresponse(200, 'Identity Verified Successfully.',$details);
    }else
    {
      return;
      //HelperClass::APIresponse(500, 'Identity Not Verified');
    }


    
  } else {
    return HelperClass::APIresponse(500, 'QrCode Not found.', '', ['query' => $db->last_query()]);
  }
}

?>

<!DOCTYPE html>
<!-- [if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif] -->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Scan QR Code</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">
      
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <div class="container ">
            <div class="row mt-5">
                <form method="POST">
                <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" name="qrCode" id="qrValue" class="form-control" autofocus />
                        </div>
                </div>
                </form>
            </div>


            <div class="row">
              <div class="col-md-12">
                <?php 
                
                $d = $this->db->query("SELECT qr.* FROM ".Table::qrScanHistory." qr
                 WHERE qr.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ORDER BY qr.id DESC")->result_array(); 
                
                if(!empty($d)){ 
                  
                 
                  
                  ?>

                    <div class="table-responsive">
                      <table id="cityDataTable" class="table mb-0 align-middle bg-white">
                        <thead class="bg-light">
                          <tr>
                            <th>Id</th>
                            <th>Id</th>
                            <!-- <th>QR Code</th> -->
                            <th>Name</th>
                            <th>User Type</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($d)) {
                            $i = 0;
                            foreach ($d as $dd) { 
                              
                              if($dd['user_type_id'] == '1'){
                                $tableName = Table::studentTable;
                              }else if($dd['user_type_id'] == '2'){
                                $tableName = Table::teacherTable;
                              }
                              $name = @$this->db->query("SELECT name FROM ".$tableName." WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND id = '{$dd['user_id']}' ")->result_array()[0]['name']; 
                              
                              
                              ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <td><?= $dd['id'];?></td>
                                <!-- <td><?= $dd['qrcode'];?></td> -->
                                <td><?= $name;?></td>
                                <td><?= HelperClass::userTypeR[$dd['user_type_id']];?></td>
                                <td><?= date('F d Y ', strtotime($dd['created_at']));?></td>
                              </tr>
                          <?php  }
                          } ?>

                        </tbody>

                      </table>
                      </div>
              <?php  }
                
                
                ?>
              </div>
            </div>
        </div>







        <script src="https://code.jquery.com/jquery-1.8.2.js"></script> 
        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <script >
         $( document ).ready(function() {
          $("#qrValue").val("");
      });


      $("#cityDataTable").DataTable();

        </script>
    </body>
</html>