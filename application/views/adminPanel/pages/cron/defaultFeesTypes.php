<?php 
// defaultFees

$schoolUniqueCode = $_SESSION['schoolUniqueCode'];
$sessionId = $_SESSION['currentSession'];


    $i = 0;
    foreach(HelperClass::defaultFeesTypes as $key => $value)
    {
       $alreadyCheck =  $this->db->query("SELECT * FROM ".Table::newfeestypesTable." WHERE status = '1' AND schoolUniqueCode = '$schoolUniqueCode' AND feeTypeName = '$value' ")->result_array();

       if(!empty($alreadyCheck)){
        continue;
       }

       $insert =  $this->db->query("INSERT INTO ".Table::newfeestypesTable." (schoolUniqueCode,feeTypeName,session_table_id)  VALUES ('$schoolUniqueCode', '$value', '$sessionId' )");
        $i++;
    }

    if($i == count(HelperClass::defaultFeesTypes))
    {
        if($insert)
        {
            $msgArr = [
            'class' => 'success',
            'msg' => 'Default Fees Types Added Successfully',
            ];
            $this->session->set_userdata($msgArr);
        }else
        {
            $msgArr = [
            'class' => 'danger',
            'msg' => 'Default Fees Types Updated Due to this Error. ' . $this->db->last_query(),
            ];
            $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."feesManagement/feeTypeMaster");
    }


    header("Refresh:1 ".base_url()."feesManagement/feeTypeMaster");







