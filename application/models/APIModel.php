<?php

class APIModel extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
    $this->load->model('CrudModel');
	}

    public function login($id,$password,$type)
    {
      $dir = base_url().HelperClass::uploadImgDir;
      if($type == 'Teacher')
      {
        $sql = "
        SELECT t.id as teacherId, t.name, t.user_id,if(t.gender = 1, 'Male', 'Female') as gender, t.mother_name, t.father_name,t.mobile,t.email,t.address,t.dob,t.doj,t.pincode,CONCAT('$dir',t.image) as image,c.className,ss.sectionName,st.stateName,ct.cityName
        FROM ".Table::teacherTable." t
        LEFT JOIN ".Table::classTable." c ON c.id =  t.class_id
        LEFT JOIN ".Table::sectionTable." ss ON ss.id =  t.section_id
        LEFT JOIN ".Table::stateTable." st ON st.id =  t.state_id
        LEFT JOIN ".Table::cityTable." ct ON ct.id =  t.city_id
        WHERE t.user_id = '$id' AND t.password = '$password' AND t.status = '1'
        ";
      }else if($type == 'Staff')
      {
        //
      }else if ($type == 'Principal')
      {
        //
      }
      $userData = $this->db->query($sql)->result_array();
      if(!empty($userData))
      {
        $authToken = HelperClass::generateRandomToken();
        $this->db->query("UPDATE ".Table::teacherTable." SET auth_token = '$authToken' WHERE id = {$userData[0]['teacherId']} AND user_id = '$id'");
        $userData[0]['auth_token'] = $authToken;
        return $userData;
      }else
      {
        return HelperClass::APIresponse($status = 500, $msg = 'User Not Found. Please Use Correct Details.');
      }
    }

    public function validateLogin($authToken,$type)
    {
      if($type == 'Teacher')
      {
        $sql = "SELECT id as login_user_id FROM ".Table::teacherTable." WHERE auth_token = '$authToken' AND status = '1'";
        $userData = $this->db->query($sql)->result_array();
          if(!empty($userData))
          {
            $userData[0]['userType'] = $type;
            return $userData;
          }else
          {
            return HelperClass::APIresponse($status = 500, $msg = 'Auth Token Not Match. Please Use Correct Details.');
          }
      }else if($type == 'Staff')
      {
        //
      }else if ($type == 'Principal')
      {
        //
      }

      

    }


    public function showAllStudentForAttendence($type,$class,$section)
    {
      if($type == 'Teacher')
      {
        $dir = base_url().HelperClass::uploadImgDir;
        $studentsData = $this->db->query("SELECT stu.name,CONCAT('$dir',stu.image) as image,stu.id, c.className,ss.sectionName 
        FROM ".Table::studentTable." stu 
        LEFT JOIN ".Table::classTable." c ON c.id =  stu.class_id
        LEFT JOIN ".Table::sectionTable." ss ON ss.id =  stu.section_id
        WHERE c.className = '$class' AND ss.sectionName = '$section' AND stu.status = '1'")->result_array();
        if(!empty($studentsData))
        {
          return $studentsData;
        }else
        {
          return HelperClass::APIresponse($status = 500, $msg = 'Students Not Found. Please Use Correct Details.');
        }
      }else if($type == 'Staff')
      {
        //
      }else if ($type == 'Principal')
      {
        //
      }
  }

}