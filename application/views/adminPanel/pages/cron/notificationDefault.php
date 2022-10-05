<?php 
// defaultNotifications

$schoolUniqueCode = $_SESSION['schoolUniqueCode'];

$alreadyCheck = $this->db->query("SELECT * FROM ".Table::setNotificationTable." WHERE status = '1' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();

if(!empty($alreadyCheck))
{
    // update
    $totalC = count($alreadyCheck);

    $j = 1;
    for($i=0; $i < $totalC; $i++)
    {
       $update =  $this->db->query("UPDATE  ".Table::setNotificationTable." SET title = '".htmlspecialchars(HelperClass::defaultNotifications[$j]['title'])."',body = '".htmlspecialchars(HelperClass::defaultNotifications[$j]['body'])."' WHERE schoolUniqueCode = '{$alreadyCheck[$i]['schoolUniqueCode']}' AND id = '{$alreadyCheck[$i]['id']}' ");
       $j++;
    }


    if($i == $totalC)
    {
        if($update)
        {
            $msgArr = [
            'class' => 'success',
            'msg' => 'Default Notifications Added Successfully',
            ];
            $this->session->set_userdata($msgArr);
        }else
        {
            $msgArr = [
            'class' => 'danger',
            'msg' => 'Default Notifications Updated Due to this Error. ' . $this->db->last_query(),
            ];
            $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/setNotificationMaster");
    }


}else
{
    // insert


    $i = 0;
    foreach(HelperClass::setNotificationForWhat as $key => $value)
    {
       $insert =  $this->db->query("INSERT INTO ".Table::setNotificationTable." (schoolUniqueCode,for_what,title,body) 
        VALUES ('$schoolUniqueCode', $key,'".htmlspecialchars(HelperClass::defaultNotifications[$key]['title'])."', '".htmlspecialchars(HelperClass::defaultNotifications[$key]['body'])."' )");
        $i++;
    }


    if($i == count(HelperClass::setNotificationForWhat))
    {
        if($insert)
        {
            $msgArr = [
            'class' => 'success',
            'msg' => 'Default Notifications Added Successfully',
            ];
            $this->session->set_userdata($msgArr);
        }else
        {
            $msgArr = [
            'class' => 'danger',
            'msg' => 'Default Notifications Updated Due to this Error. ' . $this->db->last_query(),
            ];
            $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/setNotificationMaster");
    }
}









