<?php

class APIModel extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
    $this->load->model('CrudModel');
  }

  // login
  public function login($id, $password, $type)
  {
    $dir = base_url() . HelperClass::uploadImgDir;
    if ($type == 'Teacher') {
      $sql = "
        SELECT t.id as teacherId, t.name, t.user_id,if(t.gender = 1, 'Male', 'Female') as gender, t.mother_name, t.father_name,t.mobile,t.email,t.address,t.dob,t.doj,t.pincode,CONCAT('$dir',t.image) as image,c.className,ss.sectionName,st.stateName,ct.cityName
        FROM " . Table::teacherTable . " t
        LEFT JOIN " . Table::classTable . " c ON c.id =  t.class_id
        LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  t.section_id
        LEFT JOIN " . Table::stateTable . " st ON st.id =  t.state_id
        LEFT JOIN " . Table::cityTable . " ct ON ct.id =  t.city_id
        WHERE t.user_id = '$id' AND t.password = '$password' AND t.status = '1'
        ";


        $userData = $this->db->query($sql)->result_array();
        if (!empty($userData)) {
          $authToken = HelperClass::generateRandomToken();
          $this->db->query("UPDATE " . Table::teacherTable . " SET auth_token = '$authToken' WHERE id = {$userData[0]['teacherId']} AND user_id = '$id'");
          $responseData = [];
          $responseData["teacherId"] = @$userData[0]["teacherId"];
          $responseData["name"] = @$userData[0]["name"];
          $responseData["user_id"] = @$userData[0]["user_id"];
          $responseData["gender"] = @$userData[0]["gender"];
          $responseData["mother_name"] = @$userData[0]["mother_name"];
          $responseData["father_name"] = @$userData[0]["father_name"];
          $responseData["mobile"] = @$userData[0]["mobile"];
          $responseData["email"] = @$userData[0]["email"];
          $responseData["address"] = @$userData[0]["address"];
          $responseData["dob"] = @$userData[0]["dob"];
          $responseData["doj"] = @$userData[0]["doj"];
          $responseData["pincode"] = @$userData[0]["pincode"];
          $responseData["image"] = @$userData[0]["image"];
          $responseData["className"] = @$userData[0]["className"];
          $responseData["sectionName"] = @$userData[0]["sectionName"];
          $responseData["stateName"] = @$userData[0]["stateName"];
          $responseData["cityName"] = @$userData[0]["cityName"];
          $responseData["authToken"] = @$authToken;
          $responseData["userType"] = @$type;
          return $responseData;
        } else {
          return HelperClass::APIresponse(500, 'User Not Found. Please Use Correct Details.');
        }



    } else if ($type == 'Staff') {
      //
    } else if ($type == 'Principal') {
      //
    }
   
  }

  // validate login
  public function validateLogin($authToken, $type)
  {
    if ($type == 'Teacher') {
      $sql = "SELECT id as login_user_id FROM " . Table::teacherTable . " WHERE auth_token = '$authToken' AND status = '1'";
      $userData = $this->db->query($sql)->result_array();
      if (!empty($userData)) {
        $userData[0]['userType'] = $type;
        return $userData;
      } else {
        return HelperClass::APIresponse(500, 'Auth Token Not Match. Please Use Correct Details.');
      }
    } else if ($type == 'Staff') {
      //
    } else if ($type == 'Principal') {
      //
    }
  }


  // show students for attendence
  public function showAllStudentForAttendence($type, $class, $section)
  {
    if ($type == 'Teacher') {
      $dir = base_url() . HelperClass::uploadImgDir;
      $studentsData = $this->db->query("SELECT stu.name,CONCAT('$dir',stu.image) as image,stu.id, c.className,ss.sectionName 
        FROM " . Table::studentTable . " stu 
        LEFT JOIN " . Table::classTable . " c ON c.id =  stu.class_id
        LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  stu.section_id
        WHERE c.className = '$class' AND ss.sectionName = '$section' AND stu.status = '1'")->result_array();
      if (!empty($studentsData)) {
        return $studentsData;
      } else {
        return HelperClass::APIresponse(500, 'Students Not Found. Please Use Correct Details.');
      }
    } else if ($type == 'Staff') {
      //
    } else if ($type == 'Principal') {
      //
    }
  }


  // save attendence
  public function submitAttendence($stu_id, $stu_class, $stu_section, $login_user_id, $login_user_type, $attendenceStatus)
  {
    $currentDate = date_create()->format('Y-m-d');
    if ($login_user_type == 'Teacher') {


      $d = $this->db->query("SELECT stu_id FROM " . Table::attendenceTable . " WHERE att_date = '$currentDate' AND stu_id = '$stu_id' LIMIT 1")->result_array();

      if (!empty($d)) {
        return HelperClass::APIresponse(500, 'Attendence Already Submited for this Student id today.' . $d[0]['stu_id']);
      }

      $insertArr = [
        "stu_id" => $stu_id,
        "stu_class" => $stu_class,
        "stu_section" => $stu_section,
        "login_user_id" => $login_user_id,
        "login_user_type" => $login_user_type,
        "attendenceStatus" => $attendenceStatus,
        "dateTime" => date_create()->format('Y-m-d h:i:s'),
        "att_date" => date_create()->format('Y-m-d'),
      ];







      $insertId = $this->CrudModel->insert(Table::attendenceTable, $insertArr);
      if (!empty($insertId)) {
        return true;
      } else {
        return false;
      }
    } else if ($login_user_type == 'Staff') {
      //
    } else if ($login_user_type == 'Principal') {
      //
    }
  }

  // showSubmitAttendenceData
  public function showSubmitAttendenceData($className, $sectionName)
  {
    $currentDate = date_create()->format('Y-m-d');
    $dir = base_url() . HelperClass::uploadImgDir;
    $d = $this->db->query("SELECT at.id as attendenceId, at.attendenceStatus, stu.id as studentId, stu.name,CONCAT('$dir',stu.image) as image,cls.className,sec.sectionName FROM " . Table::attendenceTable . " at 
      LEFT JOIN " . Table::studentTable . " stu ON at.stu_id = stu.id
      LEFT JOIN " . Table::classTable . " cls ON stu.class_id = cls.id
      LEFT JOIN " . Table::sectionTable . " sec ON stu.section_id = sec.id
      WHERE at.att_date = '$currentDate' AND at.stu_class = '$className' AND at.stu_section = '$sectionName' ")->result_array();

    if (!empty($d)) {
      return $d;
    } else {
      return HelperClass::APIresponse(500, 'Today Attendence Data Not Found for class ' . $className . ' and section ' . $sectionName);
    }
  }

  // save departure
  public function submitDeparture($stu_id, $attendenceId, $stu_class, $stu_section, $login_user_id, $login_user_type, $departureStatus)
  {
    $currentDate = date_create()->format('Y-m-d');
    if ($login_user_type == 'Teacher') {

      $d = $this->db->query("SELECT stu_id FROM " . Table::departureTable . " WHERE dept_date = '$currentDate' AND stu_id = '$stu_id' LIMIT 1")->result_array();

      if (!empty($d)) {
        return HelperClass::APIresponse(500, 'Departure Already Submited for this Student id today.' . $d[0]['stu_id']);
      }

      $insertArr = [
        "attendence_id" => $attendenceId,
        "stu_id" => $stu_id,
        "stu_class" => $stu_class,
        "stu_section" => $stu_section,
        "login_user_id" => $login_user_id,
        "login_user_type" => $login_user_type,
        "departureStatus" => $departureStatus,
        "dateTime" => date_create()->format('Y-m-d h:i:s'),
        "dept_date" => date_create()->format('Y-m-d'),
      ];

      $insertId = $this->CrudModel->insert(Table::departureTable, $insertArr);
      if (!empty($insertId)) {
        return true;
      } else {
        return false;
      }
    } else if ($login_user_type == 'Staff') {
      //
    } else if ($login_user_type == 'Principal') {
      //
    }
  }

   // showSubmitAttendenceData
   public function showSubmitDepartureData($className, $sectionName)
   {
     $currentDate = date_create()->format('Y-m-d');
     $dir = base_url() . HelperClass::uploadImgDir;
     $d = $this->db->query("SELECT dt.id as departureId, dt.departureStatus, stu.id as studentId, stu.name,CONCAT('$dir',stu.image) as image,cls.className,sec.sectionName FROM " . Table::departureTable . " dt
       LEFT JOIN " . Table::studentTable . " stu ON dt.stu_id = stu.id
       LEFT JOIN " . Table::classTable . " cls ON stu.class_id = cls.id
       LEFT JOIN " . Table::sectionTable . " sec ON stu.section_id = sec.id
       WHERE dt.dept_date = '$currentDate' AND dt.stu_class = '$className' AND dt.stu_section = '$sectionName' ")->result_array();
 
     if (!empty($d)) {
       return $d;
     } else {
       return HelperClass::APIresponse(500, 'Today Departure Data Not Found for class ' . $className . ' and section ' . $sectionName);
     }
   }

  // fetching all classes
  public function allClasses()
  {
    return $this->CrudModel->allClass(Table::classTable);
  }

  // fetching all sections
  public function allSections()
  {
    return  $this->CrudModel->allSection(Table::sectionTable);
  }
  // fetching all subjects
  public function allSubjects()
  {
    return  $this->CrudModel->allSubjects(Table::subjectTable);
  }


}
