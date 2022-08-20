<?php

class APIModel extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
    $this->load->model('CrudModel');
	}

  // login
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
        return HelperClass::APIresponse(500, 'User Not Found. Please Use Correct Details.');
      }
    }

    // validate login
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
            return HelperClass::APIresponse(500, 'Auth Token Not Match. Please Use Correct Details.');
          }
      }else if($type == 'Staff')
      {
        //
      }else if ($type == 'Principal')
      {
        //
      }

      

    }


    // show students for attendence
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
          return HelperClass::APIresponse(500, 'Students Not Found. Please Use Correct Details.');
        }
      }else if($type == 'Staff')
      {
        //
      }else if ($type == 'Principal')
      {
        //
      }
    }


    // save attendence
    public function submitAttendence($stu_id,$stu_class,$stu_section,$login_user_id,$login_user_type,$attendenceStatus,$dateTime)
    {
      $currentDate = date_create()->format('Y-m-d');
      if($login_user_type == 'Teacher')
      {
       

        $d = $this->db->query("SELECT stu_id FROM ".Table::attendenceTable." WHERE att_date = '$currentDate' AND stu_id = '$stu_id' LIMIT 1")->result_array();

        if(!empty($d))
        {
          return HelperClass::APIresponse(500, 'Attendence Already Submited for this Student id today.' . $d[0]['stu_id']);
        }

        $insertArr = [
          "stu_id" => $stu_id,
          "stu_class" => $stu_class,
          "stu_section" => $stu_section,
          "login_user_id" => $login_user_id,
          "login_user_type" => $login_user_type,
          "attendenceStatus" => $attendenceStatus,
          "dateTime" =>date_create()->format('Y-m-d h:i:s'),
          "att_date" =>date_create()->format('Y-m-d'),
        ];
        






        $insertId = $this->CrudModel->insert(Table::attendenceTable,$insertArr);
        if(!empty($insertId))
        {
          return true;
        }else
        {
          return false;
        }
      }else if($login_user_type == 'Staff')
      {
        //
      }else if ($login_user_type == 'Principal')
      {
        //
      }
    }


    // showSubmitAttendenceData
    public function showSubmitAttendenceData($className, $sectionName)
    {
      $currentDate = date_create()->format('Y-m-d');
      $dir = base_url().HelperClass::uploadImgDir;
      $d = $this->db->query("SELECT at.attendenceStatus, stu.name,CONCAT('$dir',stu.image) as image,cls.className,sec.sectionName FROM ".Table::attendenceTable." at 
      LEFT JOIN ".Table::studentTable." stu ON at.stu_id = stu.id
      LEFT JOIN ".Table::classTable." cls ON stu.class_id = cls.id
      LEFT JOIN ".Table::sectionTable." sec ON stu.section_id = sec.id
      WHERE at.att_date = '$currentDate' AND at.stu_class = '$className' AND at.stu_section = '$sectionName' ")->result_array();

        if(!empty($d))
        {
          return $d;
        }else
        {
          return HelperClass::APIresponse(500, 'Today Attendence Data Not Found for class ' . $className . ' and section ' . $sectionName);
        }
     
    }

}