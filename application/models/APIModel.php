<?php

class APIModel extends CI_Model
{


  public function __construct()
  {
    $this->load->database();
    $this->load->model('CrudModel');
    $this->load->model('StudentModel');
  }

  // login
  public function login($schoolUniqueCode, $id, $password, $type, $fcmToken)
  {
    $dir = base_url() . HelperClass::schoolLogoImagePath;
    $schoolData = [];
    $schoolData = $this->db->query("SELECT *,CONCAT('$dir',image) as image FROM " . Table::schoolMasterTable . " WHERE unique_id = '$schoolUniqueCode' LIMIT 1")->result_array();


    if ($type == 'Teacher') {
      $dir = base_url() . HelperClass::teacherImagePath;
      $sql = "
        SELECT t.id as teacherId,t.class_id,t.section_id, t.name, t.user_id,if(t.gender = 1, 'Male', 'Female') as gender, t.mother_name, t.father_name,t.mobile,t.email,t.address,t.dob,t.doj,t.pincode,CONCAT('$dir',t.image) as image,c.className,ss.sectionName,st.stateName,ct.cityName,tst.subject_ids
        FROM " . Table::teacherTable . " t
        LEFT JOIN " . Table::classTable . " c ON c.id =  t.class_id
        LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  t.section_id
        LEFT JOIN " . Table::stateTable . " st ON st.id =  t.state_id
        LEFT JOIN " . Table::cityTable . " ct ON ct.id =  t.city_id
        LEFT JOIN " . Table::teacherSubjectsTable . " tst ON tst.teacher_id =  t.id
        WHERE t.schoolUniqueCode = '$schoolUniqueCode' AND t.user_id = '$id' AND t.password = '$password' AND t.status = '1'
        ";


      $userData = $this->db->query($sql)->result_array();
      if (!empty($userData)) {
        $authToken = HelperClass::generateRandomToken();
        $this->db->query($sql = "UPDATE " . Table::teacherTable . " SET auth_token = '$authToken', fcm_token = '$fcmToken' WHERE id = {$userData[0]['teacherId']} AND user_id = '$id' AND schoolUniqueCode = '$schoolUniqueCode'");
        // echo $sql;die();
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
        $responseData["fcm_token"] = @$fcmToken;
        $responseData["userType"] = @$type;
        $responseData["schoolUniqueCode"] = @$schoolUniqueCode;
        $responseData["schoolData"] = $schoolData;

        // total count of students
        $responseData["totalStudentCount"] = ($this->countStudentViaClassAndSection($userData[0]["class_id"], $userData[0]["section_id"], $schoolUniqueCode)) ? $this->countStudentViaClassAndSection($userData[0]["class_id"], $userData[0]["section_id"], $schoolUniqueCode) : null;

        // subjects of teachers
        $responseData["teacherSubjects"] = [];
        $subjectsArr = json_decode(@$userData[0]['subject_ids'], TRUE);
        $totalSub = 0;
        if (isset($subjectsArr)) {
          $totalSub = count(@$subjectsArr);
        }

        if ($totalSub > 0) {
          for ($i = 0; $i < @$totalSub; $i++) {
            $subArr = [];
            $sub = $this->db->query("SELECT id,subjectName from " . Table::subjectTable . " WHERE id = '{$subjectsArr[$i]}' AND status = '1'")->result_array();
            if (isset($sub)) {
              $subArr['subjectId'] = $sub[0]['id'];
              $subArr['subjectName'] = $sub[0]['subjectName'];
            }
            array_push($responseData["teacherSubjects"], $subArr);
          }
        }

        return $responseData;
      } else {
        return HelperClass::APIresponse(500, 'User Not Found. Please Use Correct Details.');
      }
    } else if ($type == 'Staff') {
      $dir = base_url() . HelperClass::staffImagePath;
      $mobile = $id;

      $sql = "SELECT t.* FROM " . Table::userTable . " t 
      WHERE t.schoolUniqueCode = '$schoolUniqueCode' AND t.mobile = '$mobile' AND t.status = '1'";

      $userData = $this->db->query($sql)->result_array();
      $passWord = '';
      if (!empty($userData)) {
        $passWord = HelperClass::decode($userData[0]['password'], $userData[0]['salt']);
      }

      if ($passWord !== $password) {
        return HelperClass::APIresponse(500, 'Password Not Matched. Please Use Correct Details.');
      }


      if (!empty($userData)) {
        $authToken = HelperClass::generateRandomToken();
        // update auth token on driver
        $this->db->query("UPDATE " . Table::userTable . " SET auth_token = '$authToken' , fcm_token = '$fcmToken' WHERE id = {$userData[0]['id']} AND schoolUniqueCode = '$schoolUniqueCode'");

        $responseData = [];
        $responseData["staffId"] = @$userData[0]["id"];
        $responseData["name"] = @$userData[0]["name"];
        $responseData["email"] = @$userData[0]["email"];
        $responseData["mobile"] = @$userData[0]["mobile"];
        $responseData["authToken"] = @$authToken;
        $responseData["userType"] = @$type;
        $responseData["schoolUniqueCode"] = @$schoolUniqueCode;
        $responseData["schoolData"] = $schoolData;

        return $responseData;
      } else {
        return HelperClass::APIresponse(500, 'User Not Found. Please Use Correct Details.');
      }
    } else if ($type == 'Principal') {
      //
    } else if ($type == 'Parent' || $type == 'Student') {
      $dir = base_url() . HelperClass::studentImagePath;
      $mobile = $id;
      $sql = "SELECT t.*, CONCAT('$dir',t.image) as image,c.className,ss.sectionName , ss.id as sectionId,c.id as classId FROM " . Table::studentTable . " t 
      LEFT JOIN " . Table::classTable . " c ON c.id =  t.class_id
      LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  t.section_id
      WHERE t.schoolUniqueCode = '$schoolUniqueCode' AND t.mobile = '$mobile' AND t.password = '$password' AND t.status = '1'";
      $userData = $this->db->query($sql)->result_array();
      if (!empty($userData)) {
        $authToken = HelperClass::generateRandomToken();
        $totalStudentsCount = count($userData);
        $responseData = [];
        $responseData['studentsData'] = [];
        $responseData["schoolData"] = $schoolData;
        for ($i = 0; $i < $totalStudentsCount; $i++) {
          // update auth token on all students
          $this->db->query("UPDATE " . Table::studentTable . " SET auth_token = '$authToken' , fcm_token = '$fcmToken' WHERE id = {$userData[$i]['id']} AND schoolUniqueCode = '$schoolUniqueCode'");


          $subArr = [];
          $subArr["studentId"] = @$userData[$i]["id"];
          $subArr["userId"] = @$userData[$i]["user_id"];
          $subArr["name"] = @$userData[$i]["name"];
          $subArr["image"] = @$userData[$i]["image"];
          $subArr["user_id"] = @$userData[$i]["user_id"];
          $subArr["className"] = @$userData[$i]["className"];
          $subArr["sectionName"] = @$userData[$i]["sectionName"];
          $subArr["class_id"] = @$userData[$i]["classId"];
          $subArr["section_id"] = @$userData[$i]["sectionId"];
          $subArr["authToken"] = @$authToken;
          $subArr["userType"] = @$type;
          $subArr["schoolUniqueCode"] = @$schoolUniqueCode;

          array_push($responseData["studentsData"], $subArr);
        }

        return $responseData;
      } else {
        return HelperClass::APIresponse(500, 'User Not Found. Please Use Correct Details.');
      }
    } else if ($type == 'Driver') {
      $dir = base_url() . HelperClass::driverImagePath;
      $mobile = $id;
      $sql = "SELECT t.*, CONCAT('$dir',t.image) as image FROM " . Table::driverTable . " t 
      WHERE t.schoolUniqueCode = '$schoolUniqueCode' AND t.mobile = '$mobile' AND t.password = '$password' AND t.status = '1'";
      $userData = $this->db->query($sql)->result_array();
      if (!empty($userData)) {
        $authToken = HelperClass::generateRandomToken();
        // update auth token on driver
        $this->db->query("UPDATE " . Table::driverTable . " SET auth_token = '$authToken' , fcm_token = '$fcmToken' WHERE id = {$userData[0]['id']} AND schoolUniqueCode = '$schoolUniqueCode'");

        $responseData = [];
        $responseData["driverId"] = @$userData[0]["id"];
        $responseData["userId"] = @$userData[0]["user_id"];
        $responseData["name"] = @$userData[0]["name"];
        $responseData["image"] = @$userData[0]["image"];
        $responseData["user_id"] = @$userData[0]["user_id"];
        $responseData["mobile"] = @$userData[0]["mobile"];
        $responseData["vechicle_type"] = HelperClass::vehicleType[@$userData[0]["vechicle_type"]];
        $responseData["vechicle_no"] = @$userData[0]["vechicle_no"];
        $responseData["total_seats"] = @$userData[0]["total_seats"];
        $responseData["authToken"] = @$authToken;
        $responseData["userType"] = @$type;
        $responseData["schoolUniqueCode"] = @$schoolUniqueCode;
        $responseData["schoolData"] = $schoolData;
        return $responseData;
      } else {
        return HelperClass::APIresponse(500, 'User Not Found. Please Use Correct Details.');
      }
    }
  }


















  // validate login
  public function validateLogin($authToken, $type)
  {


    if ($type == 'Teacher') {

      $sql = "SELECT id as login_user_id, schoolUniqueCode FROM " . Table::teacherTable . " WHERE auth_token = '$authToken' AND status = '1'";
      $userData = $this->db->query($sql)->result_array();
      if (!empty($userData)) {
        $currentSession = $this->db->query("SELECT current_session FROM " . Table::schoolMasterTable . " WHERE unique_id = '{$userData[0]['schoolUniqueCode']}' LIMIT 1")->result_array()[0]['current_session'];
        $userData[0]['session_table_id'] = (@$currentSession) ? @$currentSession : null;
        $userData[0]['userType'] = $type;
        return $userData;
      } else {
        return HelperClass::APIresponse(500, 'Please Relogin Again. Timeout.');
      }
    } else if ($type == 'Staff') {
      //
      $sql = "SELECT id as login_user_id, schoolUniqueCode FROM " . Table::userTable . " WHERE auth_token = '$authToken' AND status = '1'";
      $userData = $this->db->query($sql)->result_array();
      if (!empty($userData)) {
        $currentSession = $this->db->query("SELECT current_session FROM " . Table::schoolMasterTable . " WHERE unique_id = '{$userData[0]['schoolUniqueCode']}' LIMIT 1")->result_array()[0]['current_session'];
        $userData[0]['session_table_id'] = (@$currentSession) ? @$currentSession : null;

        $userData[0]['userType'] = $type;
        return $userData;
      } else {
        return HelperClass::APIresponse(500, 'Please Relogin Again. Timeout.');
      }
    } else if ($type == 'Principal') {
      //
    } else if ($type == 'Parent' || $type == 'Student') {
      $sql = "SELECT id as login_user_id, schoolUniqueCode FROM " . Table::studentTable . " WHERE auth_token = '$authToken' AND status = '1'";
      $userData = $this->db->query($sql)->result_array();
      if (!empty($userData)) {
        $currentSession = $this->db->query("SELECT current_session FROM " . Table::schoolMasterTable . " WHERE unique_id = '{$userData[0]['schoolUniqueCode']}' LIMIT 1")->result_array()[0]['current_session'];
        $userData[0]['session_table_id'] = (@$currentSession) ? @$currentSession : null;

        $userData[0]['userType'] = $type;
        return $userData;
      } else {
        return HelperClass::APIresponse(500, 'Please Relogin Again. Timeout.');
      }
    } else if ($type == 'Driver') {
      $sql = "SELECT id as login_user_id, schoolUniqueCode FROM " . Table::driverTable . " WHERE auth_token = '$authToken' AND status = '1'";
      $userData = $this->db->query($sql)->result_array();
      if (!empty($userData)) {
        $currentSession = $this->db->query("SELECT current_session FROM " . Table::schoolMasterTable . " WHERE unique_id = '{$userData[0]['schoolUniqueCode']}' LIMIT 1")->result_array()[0]['current_session'];
        $userData[0]['session_table_id'] = (@$currentSession) ? @$currentSession : null;

        $userData[0]['userType'] = $type;
        return $userData;
      } else {
        return HelperClass::APIresponse(500, 'Please Relogin Again. Timeout.');
      }
    }
  }



  // showAllStudentsForSwitchProfile
  public function showAllStudentsForSwitchProfile($schoolUniqueCode, $stuIds)
  {
    $dir = base_url() . HelperClass::studentImagePath;
    $sql = "SELECT t.*, CONCAT('$dir',t.image) as image,c.className,ss.sectionName FROM " . Table::studentTable . " t 
    LEFT JOIN " . Table::classTable . " c ON c.id =  t.class_id
    LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  t.section_id
    WHERE t.schoolUniqueCode = '$schoolUniqueCode' AND t.id in('$stuIds')  AND t.status = '1'";
    $userData = $this->db->query($sql)->result_array();
    if (!empty($userData)) {
      $totalStudentsCount = count($userData);
      $responseData = [];
      for ($i = 0; $i < $totalStudentsCount; $i++) {
        $subArr = [];
        $subArr["studentId"] = @$userData[$i]["id"];
        $subArr["name"] = @$userData[$i]["name"];
        $subArr["image"] = @$userData[$i]["image"];
        $subArr["user_id"] = @$userData[$i]["user_id"];
        $subArr["className"] = @$userData[$i]["className"];
        $subArr["sectionName"] = @$userData[$i]["sectionName"];
        $subArr["schoolUniqueCode"] = @$schoolUniqueCode;
        array_push($responseData, $subArr);
      }
      return $responseData;
    } else {
      return HelperClass::APIresponse(500, 'User Not Found. Please Use Correct Details.');
    }
  }

  // student dashboard
  public function studentDashboard($schoolUniqueCode, $studentId)
  {
    $dir = base_url() . HelperClass::studentImagePath;

    $sql = "SELECT t.*, CONCAT('$dir',t.image) as image,c.className,ss.sectionName, ss.id as sectionId,c.id as classId FROM " . Table::studentTable . " t 
    LEFT JOIN " . Table::classTable . " c ON c.id =  t.class_id
    LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  t.section_id
    WHERE t.schoolUniqueCode = '$schoolUniqueCode' AND t.id = '$studentId' AND t.status = '1' LIMIT 1";
    $userData = $this->db->query($sql)->result_array();



    $schoolData = [];
    $schoolData = $this->db->query("SELECT *,CONCAT('$dir',image) as image FROM " . Table::schoolMasterTable . " WHERE unique_id = '$schoolUniqueCode' LIMIT 1")->result_array();

    $type = 'Parent';
    if (!empty($userData)) {
      $responseData = [];
      $responseData["schoolData"] = @$schoolData;
      $responseData["studentId"] = @$userData[0]["id"];
      $responseData["userId"] = @$userData[0]["user_id"];
      $responseData["name"] = @$userData[0]["name"];
      $responseData["image"] = @$userData[0]["image"];
      $responseData["user_id"] = @$userData[0]["user_id"];
      $responseData["className"] = @$userData[0]["className"];
      $responseData["sectionName"] = @$userData[0]["sectionName"];
      $responseData["section_id"] = @$userData[0]["sectionId"];
      $responseData["class_id"] = @$userData[0]["classId"];
      $responseData["schoolUniqueCode"] = @$schoolUniqueCode;
      $responseData["authToken"] = @$userData[0]["auth_token"];
      $responseData["userType"] = @$type;
      return $responseData;
    } else {
      return HelperClass::APIresponse(500, 'User Not Found. Please Use Correct Details.');
    }
  }

  // show students for attendence
  public function showAllStudentForAttendence($type, $class, $section, $schoolUniqueCode)
  {



    if ($type == 'Teacher') {
      $dir = base_url() . HelperClass::studentImagePath;
      $sql = "SELECT stu.name,CONCAT('$dir',stu.image) as image,stu.id, stu.roll_no, c.id as classId, c.className,ss.sectionName , ss.id as sectionId
      FROM " . Table::studentTable . " stu 
      LEFT JOIN " . Table::classTable . " c ON c.id =  stu.class_id
      LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  stu.section_id
      WHERE c.className = '$class' AND ss.sectionName = '$section' AND stu.status = '1' AND stu.schoolUniqueCode = '$schoolUniqueCode'";
      $studentsData = $this->db->query($sql)->result_array();
      if (!empty($studentsData)) {
        return $studentsData;
      } else {
        return HelperClass::APIresponse(500, 'Students Not Found. For This Class. ' . $class . ' - ' . $section);
      }
    } else if ($type == 'Staff') {
      //
    } else if ($type == 'Principal') {
      //
    }
  }

  public function countStudentViaClassAndSection($class_id, $section_id, $schoolUniqueCode)
  {
    $totalStudents =  $this->db->query("SELECT count(1) as count FROM " . Table::studentTable . " WHERE class_id = '$class_id' AND section_id = '$section_id' AND status = '1' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();
    if (!empty($totalStudents)) {
      return $totalStudents[0]['count'];
    } else {
      return false;
    }
  }

  public function countStudentViaClassAndSectionName($className, $sectionName, $schoolUniqueCode)
  {
    $totalStudents =  $this->db->query("SELECT count(1) as count FROM " . Table::studentTable . " s
    INNER JOIN " . Table::classTable . " c ON c.id = s.class_id 
    INNER JOIN " . Table::sectionTable . " ss ON ss.id = s.section_id
    WHERE c.className = '$className' AND ss.sectionName = '$sectionName' AND s.status = '1' AND s.schoolUniqueCode = '$schoolUniqueCode'")->result_array();
    if (!empty($totalStudents)) {
      return $totalStudents[0]['count'];
    } else {
      return false;
    }
  }


  // save attendence
  public function submitAttendence($stu_id, $stu_class, $stu_section, $login_user_id, $login_user_type, $attendenceStatus, $schoolUniqueCode, $session_table_id)
  {


    $currentDate = date_create()->format('Y-m-d');



    if (date('D') == 'Sun') {
      return HelperClass::APIresponse(500, 'Today is Sunday. Please Try in Between Monday to Saturday');
    }


    //check if today is holiday


    $holiday = $this->db->query("SELECT title FROM " . Table::holidayCalendarTable . " WHERE event_date = '$currentDate' AND schoolUniqueCode = '$schoolUniqueCode' LIMIT 1")->result_array();

    if (!empty($holiday)) {
      $h = (string) $holiday[0]['title'];
      return HelperClass::APIresponse(500, "Today is $h Holiday. Try on School Working Days.");
    }





    if ($login_user_type == 'Teacher') {


      $d = $this->db->query("SELECT stu_id FROM " . Table::attendenceTable . " WHERE att_date = '$currentDate' AND stu_id = '$stu_id' AND schoolUniqueCode = '$schoolUniqueCode' LIMIT 1")->result_array();

      if (!empty($d)) {
        return HelperClass::APIresponse(500, 'Attendence Already Submited for this Class.');
      }

      $insertArr = [
        "schoolUniqueCode" => $schoolUniqueCode,
        "stu_id" => $stu_id,
        "stu_class" => $stu_class,
        "stu_section" => $stu_section,
        "login_user_id" => $login_user_id,
        "login_user_type" => $login_user_type,
        "attendenceStatus" => $attendenceStatus,
        "dateTime" => date_create()->format('Y-m-d h:i:s'),
        "att_date" => date_create()->format('Y-m-d'),
        "session_table_id" => $session_table_id
      ];
      $insertId = $this->CrudModel->insert(Table::attendenceTable, $insertArr);
      if (!empty($insertId)) {


        // return true if student is absent today do not enter there digicoins
        if ($attendenceStatus == '0') {
          return true;
        }


        // check digiCoin is set for this attendence time for students
        $digiCoinF =  $this->checkIsDigiCoinIsSet(HelperClass::actionType['Attendence'], HelperClass::userType['Student'], $schoolUniqueCode);

        if ($digiCoinF) {
          // insert the digicoin
          $insertDigiCoin = $this->insertDigiCoin($stu_id, HelperClass::userTypeR['1'], HelperClass::actionType['Attendence'], $digiCoinF, $schoolUniqueCode, $insertId);
          if ($insertDigiCoin) {
            return true;
          } else {
            return HelperClass::APIresponse(500, 'DigiCoin Not Inserted For Student ' . $this->db->last_query());
          }
        }

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
  public function showSubmitAttendenceData($className, $sectionName, $schoolUniqueCode)
  {

    $currentDate = date_create()->format('Y-m-d');
    $dir = base_url() . HelperClass::studentImagePath;
    $d = $this->db->query("SELECT at.id as attendenceId, at.attendenceStatus, stu.id as studentId, stu.name,CONCAT('$dir',stu.image) as image,cls.className,sec.sectionName FROM " . Table::attendenceTable . " at 
      LEFT JOIN " . Table::studentTable . " stu ON at.stu_id = stu.id
      LEFT JOIN " . Table::classTable . " cls ON stu.class_id = cls.id
      LEFT JOIN " . Table::sectionTable . " sec ON stu.section_id = sec.id
      WHERE at.att_date = '$currentDate' AND at.stu_class = '$className' AND at.stu_section = '$sectionName' AND at.schoolUniqueCode = '$schoolUniqueCode'")->result_array();

    if (!empty($d)) {
      return $d;
    } else {
      return HelperClass::APIresponse(500, 'Today Attendence Data Not Found for class ' . $className . ' and section ' . $sectionName);
    }
  }

  // update attendance
  public function submitUpdatedAttendanceData($stu_id, $stu_class, $stu_section, $login_user_id, $login_user_type, $attendenceStatus, $schoolUniqueCode, $session_table_id, $updateId)
  {


    $currentDate = date_create()->format('Y-m-d');



    if (date('D') == 'Sun') {
      return HelperClass::APIresponse(500, 'Today is Sunday. Please Try in Between Monday to Saturday');
    }


    //check if today is holiday


    $holiday = $this->db->query("SELECT title FROM " . Table::holidayCalendarTable . " WHERE event_date = '$currentDate' AND schoolUniqueCode = '$schoolUniqueCode' LIMIT 1")->result_array();

    if (!empty($holiday)) {
      $h = (string) $holiday[0]['title'];
      return HelperClass::APIresponse(500, "Today is $h Holiday. Try on School Working Days.");
    }





    if ($login_user_type == 'Teacher') {



      $updateArr = [
        "schoolUniqueCode" => $schoolUniqueCode,
        "stu_id" => $stu_id,
        "stu_class" => $stu_class,
        "stu_section" => $stu_section,
        "login_user_id" => $login_user_id,
        "login_user_type" => $login_user_type,
        "attendenceStatus" => $attendenceStatus,
        "dateTime" => date_create()->format('Y-m-d h:i:s'),
        "att_date" => date_create()->format('Y-m-d'),
        "session_table_id" => $session_table_id
      ];

      $insertId = $this->CrudModel->update(Table::attendenceTable, $updateArr, $updateId);
      if (!empty($insertId)) {


        // return true if student is absent today do not enter there digicoins
        if ($attendenceStatus == '0') {
          return true;
        }


        return true;
      } else {
        return false;
      }
    }
  }

  public function attendanceLists($className, $sectionName, $date, $schoolUniqueCode)
  {


    $dir = base_url() . HelperClass::studentImagePath;
    $d = $this->db->query("SELECT at.id as attendenceId, if(at.attendenceStatus = '1' , 'Present', 'Absent') as attendenceStatus, stu.id as studentId, stu.name,CONCAT('$dir',stu.image) as image,cls.className,sec.sectionName FROM " . Table::attendenceTable . " at 
      LEFT JOIN " . Table::studentTable . " stu ON at.stu_id = stu.id
      LEFT JOIN " . Table::classTable . " cls ON stu.class_id = cls.id
      LEFT JOIN " . Table::sectionTable . " sec ON stu.section_id = sec.id
      WHERE at.att_date = '$date' AND at.stu_class = '$className' AND at.stu_section = '$sectionName' AND at.schoolUniqueCode = '$schoolUniqueCode'")->result_array();

    if (!empty($d)) {
      return $d;
    } else {
      return HelperClass::APIresponse(500, 'Attendence Data Not Found for class ' . $className . ' and section ' . $sectionName);
    }
  }

  // save departure
  public function submitDeparture($stu_id, $attendenceId, $stu_class, $stu_section, $login_user_id, $login_user_type, $departureStatus, $schoolUniqueCode, $session_table_id)
  {
    if (date('D') == 'Sun') {
      return HelperClass::APIresponse(500, 'Today is Sunday. Please Try in Between Monday to Saturday');
    }
    $currentDate = date_create()->format('Y-m-d');
    if ($login_user_type == 'Teacher') {

      $d = $this->db->query("SELECT stu_id FROM " . Table::departureTable . " WHERE dept_date = '$currentDate' AND stu_id = '$stu_id' AND schoolUniqueCode = '$schoolUniqueCode' LIMIT 1")->result_array();

      if (!empty($d)) {
        return HelperClass::APIresponse(500, 'Departure Already Submited for this Student id today.' . $d[0]['stu_id']);
      }

      $insertArr = [
        "schoolUniqueCode" => $schoolUniqueCode,
        "attendence_id" => $attendenceId,
        "stu_id" => $stu_id,
        "stu_class" => $stu_class,
        "stu_section" => $stu_section,
        "login_user_id" => $login_user_id,
        "login_user_type" => $login_user_type,
        "departureStatus" => $departureStatus,
        "dateTime" => date_create()->format('Y-m-d h:i:s'),
        "dept_date" => date_create()->format('Y-m-d'),
        "session_table_id" => $session_table_id
      ];

      $insertId = $this->CrudModel->insert(Table::departureTable, $insertArr);
      if (!empty($insertId)) {


        // check digiCoin is set for this departure time for students
        $digiCoinF =  $this->checkIsDigiCoinIsSet(HelperClass::actionType['Departure'], HelperClass::userType['Student'], $schoolUniqueCode);

        if ($digiCoinF) {
          // insert the digicoin
          $insertDigiCoin = $this->insertDigiCoin($stu_id, HelperClass::userTypeR['1'], HelperClass::actionType['Departure'], $digiCoinF, $schoolUniqueCode, $insertId);
          if ($insertDigiCoin) {
            return true;
          } else {
            return HelperClass::APIresponse(500, 'DigiCoin Not Inserted For Student ' . $this->db->last_query());
          }
        }


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
  public function showSubmitDepartureData($className, $sectionName, $schoolUniqueCode)
  {
    $currentDate = date_create()->format('Y-m-d');
    $dir = base_url() . HelperClass::studentImagePath;
    $d = $this->db->query("SELECT dt.id as departureId, dt.departureStatus, stu.id as studentId, stu.name,CONCAT('$dir',stu.image) as image,cls.className,sec.sectionName FROM " . Table::departureTable . " dt
       LEFT JOIN " . Table::studentTable . " stu ON dt.stu_id = stu.id
       LEFT JOIN " . Table::classTable . " cls ON stu.class_id = cls.id
       LEFT JOIN " . Table::sectionTable . " sec ON stu.section_id = sec.id
       WHERE dt.dept_date = '$currentDate' AND dt.stu_class = '$className' AND dt.stu_section = '$sectionName' AND dt.schoolUniqueCode = '$schoolUniqueCode'")->result_array();

    if (!empty($d)) {
      return $d;
    } else {
      return HelperClass::APIresponse(500, 'Today Departure Data Not Found for class ' . $className . ' and section ' . $sectionName);
    }
  }

  // showStudentDetils
  public function showStudentDetails($classId, $sectionId, $qrCode, $studentId, $schoolUniqueCode,$session_table_id)
  {
    $currentDate = date_create()->format('Y-m-d');
    $dir = base_url() . HelperClass::studentImagePath;
    $condition = " AND s.schoolUniqueCode = '$schoolUniqueCode' ";
    if (!empty($classId) && !empty($sectionId) && !empty($studentId)) {
      $condition .= " AND s.id = '{$studentId}' AND ss.id = '{$sectionId}' AND c.id = '{$classId}' ";
    } else {
      $condition .= " AND (q.qrcodeUrl = '$qrCode' OR q.uniqueValue = '$qrCode') ";
    }

    $sql = "SELECT s.*, 
    CONCAT('$dir',s.image) as image,
    if(s.status = '1', 'Active','InActive')as status,
    c.className, c.id as classId,
    ss.sectionName, ss.id as sectionId,
    st.stateName,
    ct.cityName,
    q.uniqueValue, q.qrcodeUrl
    FROM " . Table::studentTable . " s
    LEFT JOIN " . Table::qrcodeTable . " q ON q.uniqueValue = s.user_id
    LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
    LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
    LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
    LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
    WHERE s.status = '1' 
    $condition 
    ORDER BY s.id DESC";

    $d = $this->db->query($sql)->result_array();

    if (!empty($d)) {
      $returnArr = [];
      $returnArr['studentId'] = @$d[0]['id'];
      $returnArr['u_qr_id'] = @$d[0]['u_qr_id'];
      $returnArr['uniqueValue'] = @$d[0]['uniqueValue'];
      $returnArr['qrcodeUrl'] = @$d[0]['qrcodeUrl'];
      $returnArr['name'] = @$d[0]['name'];
      $returnArr['user_id'] = @$d[0]['user_id'];
      $returnArr['class_id'] = @$d[0]['classId'];
      $returnArr['section_id'] = @$d[0]['sectionId'];
      $returnArr['class_name'] = @$d[0]['className'];
      $returnArr['section_name'] = @$d[0]['sectionName'];
      $returnArr['roll_no'] = @$d[0]['roll_no'];
      $returnArr['gender'] = (@$d[0]['gender'] == '1') ? 'Male' : 'Female';
      $returnArr['mother_name'] = @$d[0]['mother_name'];
      $returnArr['father_name'] = @$d[0]['father_name'];
      $returnArr['mobile'] = @$d[0]['mobile'];
      $returnArr['email'] = @$d[0]['email'];
      $returnArr['dob'] = @$d[0]['dob'];
      $returnArr['address'] = @$d[0]['address'];
      $returnArr['cityName'] = @$d[0]['cityName'];
      $returnArr['stateName'] = @$d[0]['stateName'];
      $returnArr['pincode'] = @$d[0]['pincode'];
      $returnArr['status'] = @$d[0]['status'];
      $returnArr['image'] = @$d[0]['image'];
      $returnArr['roll_no'] = @$d[0]['roll_no'];

      // results with exam
      $returnArr['resultData'] = $this->StudentModel->showResultDataWithExam($schoolUniqueCode, $d[0]['classId'], $d[0]['sectionId'], $d[0]['id']);



      // fees data
      $returnArr['feesData'] = $this->StudentModel->checkFeesSubmitDetails($schoolUniqueCode, $d[0]['classId'], $d[0]['sectionId'], $d[0]['id']);

      $returnArr['feesDetails'] = $this->StudentModel->totalFeesDueToday($schoolUniqueCode, $d[0]['classId'], $d[0]['sectionId'], $d[0]['id']);

      // $stuId, $classId, $sectionId, $schoolCode, $sessionId
      $sessionId = $session_table_id;
      // $returnArr['newFeesDetails'] = $this->CrudModel-->showStudentFeesViaIdClassAndSection($d[0]['id'], $d[0]['classId'], $d[0]['sectionId'], $schoolUniqueCode,$sessionId);

      return $returnArr;
    } else {
      return HelperClass::APIresponse(500, 'No Data Found for this Student.');
    }
  }


  public function showStudentFeesDetails($stuId, $classId, $sectionId, $schoolCode, $sessionId)
  {
    $sendArr = [];
    $dir = base_url() . HelperClass::studentImagePath;

    $studentData = @$this->db->query("SELECT s.*,CONCAT('$dir',s.image) as image,cl.className,se.sectionName FROM " . Table::studentTable . " s
    JOIN " . Table::classTable . " cl ON cl.id = s.class_id
    JOIN " . Table::sectionTable . " se ON se.id = s.section_id
    WHERE s.status = '1' AND s.schoolUniqueCode = '$schoolCode' AND s.id = '$stuId'")->result_array()[0];

    $feesDetails = $this->db->query("SELECT DISTINCT(fee_group_id) FROM " . Table::newfeeclasswiseTable . " WHERE class_id = '$classId' AND section_id = '$sectionId' AND schoolUniqueCode = '$schoolCode' AND student_id = '$stuId'  GROUP BY fee_group_id")->result_array();

    $gAmount = 0.00;
    $gFine = 0.00;
    $gdiscount = 0.00;
    $gFine = 0.00;
    $gFineD = 0.00;
    $gPaid = 0.00;
    $gBalance = 0.00;
    $sendArr = [
        'gAmount' => $gAmount,
        'gFine' =>  $gFine,
        'gdiscount' => $gdiscount,
        'gFineD' => $gFineD,
        'gPaid' => $gPaid,
        'gBalance' => $gBalance
    ];

    $sendArr['deposits'] = [];
    $todayDate = date('Y-m-d');

    $j = 1;
    $a = 1;
    $b = 1;


    foreach ($feesDetails as $f) {
        $sqln = "SELECT nfm.id as fmtId, nfm.amount, nfm.fineType,nfm.finePercentage,nfm.fineFixAmount, nfm.dueDate,
        nft.id as nftId, nft.feeTypeName, nft.shortCode, nfg.id as nfgId, nfg.feeGroupName FROM
        " . Table::newfeemasterTable . " nfm 
        JOIN " . Table::newfeestypesTable . " nft ON nft.id = nfm.newFeeType
        JOIN " . Table::newfeesgroupsTable . " nfg ON nfg.id = nfm.newFeeGroupId
        WHERE nfm.newFeeGroupId = '{$f['fee_group_id']}' ";

        $groupWiseFeeDetails = $this->db->query($sqln)->result_array();
        $fGN = @$groupWiseFeeDetails[0]['feeGroupName'];


        foreach ($groupWiseFeeDetails as $gwf) {
            // search student all depoists
            $fineAmount = 0.00;
            if ($todayDate > $gwf['dueDate']) {
                if ($gwf['fineType'] == '1') {
                    $fineAmount = 0.00;
                } else if ($gwf['fineType'] == '2') {
                    // percenrtage
                    $fineAmount = ceil($gwf['amount'] * @$gwf['finePercentage'] / 100);
                } else if ($gwf['fineType'] == '3') {
                    // fixed amount
                    $fineAmount = @$gwf['fineFixAmount'];
                }
            } else {
                $fineAmount = 0.00;
            }

            if ($fineAmount == 0) {
                $fShow = false;
            } else {
                $fShow = true;
            }



            $feesDeposits = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeessubmitmasterTable . " WHERE stuId = '$stuId' AND classId = '$classId' AND sectionId = '$sectionId' AND fmtId = '{$gwf['fmtId']}' AND nftId = '{$gwf['nftId']}' AND nfgId = '{$gwf['nfgId']}' AND status = '1' AND session_table_id = '$sessionId'");


            $depositAmt = 0.00;
            $fineAmt = 0.00;
            $discountAmt = 0.00;
            if (!empty($feesDeposits)) {


                foreach ($feesDeposits as $fd) {
                    $depositAmt = $depositAmt + $fd['depositAmount'];
                    $fineAmt = $fineAmt + $fd['fine'];
                    $discountAmt = $discountAmt + $fd['discount'];
                    $b++;
                }
            }

            $amountNow = $gwf['amount'] - $depositAmt;

            $bstatusBalance = ($gwf['amount'] - $depositAmt) - $discountAmt;

            $gAmount = $gAmount + $gwf['amount'];
            $gFine = $gFine + $fineAmount;

            $gdiscount = $gdiscount + $discountAmt;
            $gFineD = $gFineD + $fineAmt;
            $gPaid = $gPaid + $depositAmt;
            if ($amountNow > 0) {
                $bblance = $amountNow - $discountAmt;
            } else {
                $bblance = ($gwf['amount'] - $depositAmt) - $discountAmt;
            }


            $a = 1;
            $depositAmt = 0.00;
            $fineAmt = 0.00;
            $discountAmt = 0.00;
            $subArr = [];
            if (!empty($feesDeposits)) {

                foreach ($feesDeposits as $fd) {



                    $depositAmt = @$depositAmt + $fd['depositAmount'];
                    $fineAmt = @$fineAmt + $fd['fine'];
                    $discountAmt = @$discountAmt + $fd['discount'];

                    $paymentMode = ($fd['paymentMode'] == '1') ? 'Offline' : 'Online';

                    $depositDate =  date('d-m-y', strtotime($fd['depositDate']));
                    $invoiceId = $fd['invoiceId'];

                    // $discount = $fd['discount'];
                    // $fine = $fd['fine'];
                    // $depositamount = $fd['depositAmount'];

                    $subArr['depositAmount'] = $depositAmt;
                    $subArr['fine'] = $fineAmt;
                    $subArr['discount'] = $discountAmt;
                    $subArr['paymentMode'] =  $paymentMode;
                    $subArr['depositDate'] = $depositDate;
                    $subArr['invoiceId'] = $invoiceId;
                    array_push($sendArr['deposits'], $subArr);
                }
            }

            $j++;
        }
    }
    $sendArr['gAmount'] = $gAmount;
    $sendArr['gFine'] =  $gFine;
    $sendArr['gdiscount'] = $gdiscount;
    $sendArr['gFineD'] = $gFineD;
    $sendArr['gPaid'] = $gPaid;
    $sendArr['gBalance'] = $gBalance;
    $sendArr['totalPaidIncludingDiscounts'] = $sendArr['gPaid'] + $sendArr['gdiscount'];
    $sendArr['totalDueNow'] = $sendArr['gAmount'] - $sendArr['totalPaidIncludingDiscounts'];



    return $sendArr;
}



  public function studentFeesSubmitData($classId, $sectionId, $studentId, $schoolUniqueCode)
  {

    $sendArr = [
      'studentFeesDepositData' => $this->StudentModel->checkFeesSubmitDetails($schoolUniqueCode, $classId, $sectionId, $studentId),
      'totalFeesDueDetails' => $this->StudentModel->totalFeesDueToday($schoolUniqueCode, $classId, $sectionId, $studentId),
    ];
    return $sendArr;
  }



  public function showResultDataWithExam($classId, $sectionId, $studentId, $schoolUniqueCode)
  {

    return $this->StudentModel->showResultDataWithExam($schoolUniqueCode, $classId, $sectionId, $studentId);
  }



  // showAttendanceDataForStudentId
  public function showAttendanceDataForStudentId($studentId, $schoolUniqueCode, $dateWithYear = null)
  {
    // attendence
    if ($dateWithYear == null) {
      $dateWithYear = date('Y-m-01');
    }
    return $returnArr = $this->StudentModel->showAttendenceData($studentId, $dateWithYear, $schoolUniqueCode,'showAll');
  }
  // add Exam
  public function addExam($loginUserId, $loginuserType, $classId, $sectionId, $subjectId, $examDate, $examName, $maxMarks, $minMarks, $schoolUniqueCode, $session_table_id)
  {
    //$currentDate = date_create()->format('Y-m-d');
    if ($loginuserType == 'Teacher') {

      $insertArr = [
        "schoolUniqueCode" => $schoolUniqueCode,
        "class_id" => $classId,
        "section_id" => $sectionId,
        "subject_id" => $subjectId,
        "exam_name" => $examName,
        "date_of_exam" => $examDate,
        "max_marks" => $maxMarks,
        "min_marks" => $minMarks,
        "login_user_id" => $loginUserId,
        "login_user_type" => $loginuserType,
        "session_table_id" => $session_table_id
      ];

      $insertId = $this->CrudModel->insert(Table::examTable, $insertArr);
      if (!empty($insertId)) {
        return true;
      } else {
        return false;
      }
    } else if ($loginuserType == 'Staff') {
      //
    } else if ($loginuserType == 'Principal') {
      //
    }
  }
  // updateExam
  public function updateExam($loginUserId, $loginuserType, $classId, $sectionId, $subjectId, $examDate, $examName, $maxMarks, $minMarks, $examId)
  {
    //$currentDate = date_create()->format('Y-m-d');
    if ($loginuserType == 'Teacher') {

      $updateArr = [
        "class_id" => $classId,
        "section_id" => $sectionId,
        "subject_id" => $subjectId,
        "exam_name" => $examName,
        "date_of_exam" => $examDate,
        "max_marks" => $maxMarks,
        "min_marks" => $minMarks,
        "login_user_id" => $loginUserId,
        "login_user_type" => $loginuserType,
      ];

      $update = $this->CrudModel->update(Table::examTable, $updateArr, $examId);
      if (!empty($update)) {
        return true;
      } else {
        return false;
      }
    } else if ($loginuserType == 'Staff') {
      //
    } else if ($loginuserType == 'Principal') {
      //
    }
  }



  // showAllExam
  public function showAllExam($classId, $sectionId, $schoolUniqueCode, $subjectId = '')
  {
    $currentDate = date_create()->format('Y-m-d');
    // $dir = base_url() . HelperClass::uploadImgDir;
    $condition = " AND e.schoolUniqueCode = '$schoolUniqueCode' ";
    if (!empty($subjectId)) {
      $condition .= " AND e.subject_id = $subjectId ";
    }

    $d = $this->db->query("SELECT e.id as examId,e.exam_name,e.max_marks,e.min_marks,e.date_of_exam,ct.className,ct.id as classId,st.sectionName,st.id as sectionId, subt.id as subjectId, subt.subjectName FROM " . Table::examTable . " e
      INNER JOIN " . Table::classTable . " ct ON e.class_id = ct.id 
      INNER JOIN " . Table::sectionTable . " st ON e.section_id = st.id 
      INNER JOIN " . Table::subjectTable . " subt ON e.subject_id = subt.id 
      WHERE e.class_id = '$classId' AND e.section_id = '$sectionId' $condition AND e.status = '1'")->result_array();

    if (!empty($d)) {
      return $d;
    } else {
      return HelperClass::APIresponse(500, 'No Exams found for this class');
    }
  }


  // add homeWork
  public function addHomeWork($loginUserId, $loginuserType, $classId, $sectionId, $subjectId, $homeWorkNote, $homeWorkDate, $homeWorkDueDate, $schoolUniqueCode, $document_image_name)
  {
    //$currentDate = date_create()->format('Y-m-d');
    // check if homework is already added or not

    if ($loginuserType == 'Teacher') {

      $insertArr = [
        "schoolUniqueCode" => $schoolUniqueCode,
        "login_user_id" => $loginUserId,
        "login_user_type" => $loginuserType,
        "class_id" => $classId,
        "section_id" => $sectionId,
        "subject_id" => $subjectId,
        "home_work_note" => $homeWorkNote,
        "home_work_date" => $homeWorkDate,
        "home_work_finish_date" => $homeWorkDueDate,
        "image" => $document_image_name
      ];

      $sql = "SELECT h.id, s.subjectName FROM ".Table::homeWorkTable." h LEFT JOIN ".Table::subjectTable." s ON s.id = h.subject_id 
      WHERE h.subject_id = '$subjectId' AND h.class_id = '$classId' AND h.section_id = '$sectionId' AND h.home_work_date = '$homeWorkDate' LIMIT 1 ";
      $alreadyAdded = $this->db->query($sql)->result_array();

   
      if(isset($alreadyAdded[0])){
        $msg = @$alreadyAdded[0]['subjectName'] . " Homework is already added for this class on date $homeWorkDate , please update that.";
        return HelperClass::APIresponse(500,$msg);
      }




      $insertId = $this->CrudModel->insert(Table::homeWorkTable, $insertArr);
      if (!empty($insertId)) {
        return true;
      } else {
        return false;
      }
    } else if ($loginuserType == 'Staff') {
      //
    } else if ($loginuserType == 'Principal') {
      //
    }
  }

  // updateHomeWork
  public function updateHomeWork($loginUserId, $loginuserType, $classId, $sectionId, $subjectId, $homeWorkNote, $homeWorkDate, $homeWorkDueDate, $homeWorkId)
  {
    //$currentDate = date_create()->format('Y-m-d');
    if ($loginuserType == 'Teacher') {

      $updateArr = [
        "login_user_id" => $loginUserId,
        "login_user_type" => $loginuserType,
        "class_id" => $classId,
        "section_id" => $sectionId,
        "subject_id" => $subjectId,
        "home_work_note" => $homeWorkNote,
        "home_work_date" => $homeWorkDate,
        "home_work_finish_date" => $homeWorkDueDate,
      ];

      $sql = "SELECT h.id, s.subjectName FROM ".Table::homeWorkTable." h LEFT JOIN ".Table::subjectTable." s ON s.id = h.subject_id 
      WHERE h.subject_id = '$subjectId' AND h.class_id = '$classId' AND h.section_id = '$sectionId' AND h.home_work_date = '$homeWorkDate' AND h.id != '$homeWorkId' LIMIT 1 ";
      $alreadyAdded = $this->db->query($sql)->result_array();

   
      if(isset($alreadyAdded[0])){
        $msg = @$alreadyAdded[0]['subjectName'] . " Homework is already added for this class on date $homeWorkDate , please update that.";
        return HelperClass::APIresponse(500,$msg);
      }




      $update = $this->CrudModel->update(Table::homeWorkTable, $updateArr, $homeWorkId);
      if (!empty($update)) {
        return true;
      } else {
        return false;
      }
    } else if ($loginuserType == 'Staff') {
      //
    } else if ($loginuserType == 'Principal') {
      //
    }
  }



  // showAllExam
  public function showAllHomeWorks($classId, $sectionId, $schoolUniqueCode, $date)
  {
    $currentDate = date_create()->format('Y-m-d');
    $dir = base_url() . HelperClass::homeworkImagePath;
    $condition = " AND e.schoolUniqueCode = '$schoolUniqueCode' ";

    $sql = "SELECT e.id as homeWorkId,
    e.home_work_note,
    DATE_FORMAT(e.home_work_date,'".HelperClass::dateFormatForAPIWithoutTime."') as home_work_date,
    DATE_FORMAT(e.home_work_finish_date,'".HelperClass::dateFormatForAPIWithoutTime."') as home_work_finish_date,
    ct.className,st.sectionName,subt.subjectName,CONCAT('$dir',e.image) as image FROM " . Table::homeWorkTable . " e
        INNER JOIN " . Table::classTable . " ct ON e.class_id = ct.id 
        INNER JOIN " . Table::sectionTable . " st ON e.section_id = st.id 
        INNER JOIN " . Table::subjectTable . " subt ON e.subject_id = subt.id 
        WHERE e.class_id = '$classId' AND e.section_id = '$sectionId'  AND e.status = '1'
        AND e.home_work_date = '$date'
        ";

    $d = $this->db->query($sql)->result_array();


    if (!empty($d)) {
      return $d;
    } else {
      return HelperClass::APIresponse(500, 'No Home Work Found For ' . $date . ' & This Class');
    }
  }


  // holiday calender
  // showAllExam
  public function holidayCalender($schoolUniqueCode, $dateWithYear = null)
  {

    if ($dateWithYear == null) {
      $dateWithYear = date('Y-m-01');
    }
    $d = $this->db->query($sql = "SELECT * FROM " . Table::holidayCalendarTable . " e
          WHERE e.status = '1' AND e.schoolUniqueCode = '$schoolUniqueCode'
          AND YEAR(e.event_date) = YEAR('$dateWithYear')
          ")->result_array();


    $totalC =  count($d);

    $sendArr = [];
    for ($i = 0; $i < $totalC; $i++) {
      $subArr = [];
      $subArr['date'] = date('d', strtotime($d[$i]['event_date']));
      $subArr['event_date'] = date('Y-m-d', strtotime($d[$i]['event_date']));
      $subArr['title'] = $d[$i]['title'];
      array_push($sendArr, $subArr);
    }


    if (!empty($sendArr)) {
      return $sendArr;
    } else {
      return HelperClass::APIresponse(500, 'No Holiday\'s Found.');
    }
  }

  // validateQRCode

  public function validateQRCode($qrCode, $loginuserType, $schoolUniqueCode)
  {
    $currentDate = date_create()->format('Y-m-d');

    $condition = " AND e.schoolUniqueCode = '$schoolUniqueCode' ";

    $identityType = $this->CrudModel->extractQrCodeAndReturnUserType($qrCode);
    if ($identityType == HelperClass::userTypeR[1]) {
      $table = Table::qrcodeTable;
    } else if ($identityType == HelperClass::userTypeR[2]) {
      $table = Table::qrcodeTeachersTable;
    } else if ($identityType == HelperClass::userTypeR[7]) {
      $table = Table::qrcodeDriversTable;
    }

    $d = $this->db->query("SELECT * FROM " . $table . " WHERE qrcodeUrl = '$qrCode' AND schoolUniqueCode = '$schoolUniqueCode' AND status = '1' LIMIT 1")->result_array();

    if (!empty($d)) {

      if ($identityType == HelperClass::userTypeR[1]) {
        $tableB = Table::studentTable;
        $dir = base_url() . HelperClass::studentImagePath;
      } else if ($identityType == HelperClass::userTypeR[2]) {
        $tableB = Table::teacherTable;
        $dir = base_url() . HelperClass::teacherImagePath;
      } else if ($identityType == HelperClass::userTypeR[7]) {
        $tableB = Table::driverTable;
        $dir = base_url() . HelperClass::driverImagePath;
      }



      $details = $this->db->query("SELECT id, name,email,mobile,CONCAT('$dir',image) as image FROM " . $tableB . " WHERE user_id = '{$d[0]['uniqueValue']}' AND u_qr_id = '{$d[0]['id']}' AND schoolUniqueCode = '$schoolUniqueCode' AND status = '1' LIMIT 1")->result_array();

      $details[0]['userType'] = $identityType;

      $idForUser = (string) $details[0]['id'];

      if (!empty($details)) {
        // fetch token
        $tokensFromDB =  $this->db->query("SELECT fcm_token FROM " . $tableB . " WHERE id = '$idForUser' AND schoolUniqueCode = '$schoolUniqueCode'  AND status = '1' LIMIT 1")->result_array();

        if ($loginuserType == HelperClass::userTypeR[3]) {
          // staff 

          if (!empty($tokensFromDB)) {
            $tokenArr = [$tokensFromDB[0]['fcm_token']];
            // fetch notification from db
            $notificationFromDB = $this->db->query("SELECT title, body FROM " . Table::setNotificationTable . " WHERE status = '1' AND schoolUniqueCode = '$schoolUniqueCode' AND for_what = '4' LIMIT 1")->result_array();

            if (!empty($notificationFromDB)) {
              $title = $this->CrudModel->replaceNotificationsWords((string)$notificationFromDB[0]['title'], ['identity' => $identityType]);
              $body =  $this->CrudModel->replaceNotificationsWords((string)$notificationFromDB[0]['body'], ['identity' => $identityType]);
            } else {
              $title = "$identityType Entry On 🏫 School.";
              $body = "Hey 👋 Dear $identityType, We Welcome You On 🏫 School, You Have Entered Into The 🏫 School, Entry Gate.";
            }
          }
        } else if ($loginuserType == HelperClass::userTypeR[7]) {
          // driver

          if (!empty($tokensFromDB)) {
            $tokenArr = [$tokensFromDB[0]['fcm_token']];

            // fetch notification from db
            $notificationFromDB = $this->db->query("SELECT title, body FROM " . Table::setNotificationTable . " WHERE status = '1' AND schoolUniqueCode = '$schoolUniqueCode' AND for_what = '3' LIMIT 1")->result_array();

            if (!empty($notificationFromDB)) {
              $title = $this->CrudModel->replaceNotificationsWords((string)$notificationFromDB[0]['title'], ['identity' => $identityType]);
              $body =  $this->CrudModel->replaceNotificationsWords((string)$notificationFromDB[0]['body'], ['identity' => $identityType]);
            } else {
              $title = "$identityType Entry On 🚌 Transport.";
              $body = "Hey 👋 Dear $identityType, We Welcome You On 🚌 Bus / Rikshaw. Parents Can Check 📍 Track Location On App 📱 Now!!";
            }
          }
        }



        $image = null;
        $sound = null;
        $sendPushSMS = json_decode($this->CrudModel->sendFireBaseNotificationWithDeviceId($tokenArr, $title, $body, $image, $sound), TRUE);
        return HelperClass::APIresponse(200, 'Identity Verified Successfully.', $details);
      } else {
        return HelperClass::APIresponse(500, 'Identity Not Verified');
      }
    } else {
      return HelperClass::APIresponse(500, 'QrCode Not found.', '', ['query' => $this->db->last_query()]);
    }
  }



  // showSingleHomeWork
  public function showSingleHomeWork($classId, $sectionId,  $homeWorkId, $schoolUniqueCode)
  {
    $currentDate = date_create()->format('Y-m-d');
    $dir = base_url() . HelperClass::homeworkImagePath;
    $condition = " AND e.schoolUniqueCode = '$schoolUniqueCode' ";


    $d = $this->db->query("SELECT e.id as examId,e.exam_name,e.max_marks,e.min_marks,e.date_of_exam,ct.className,st.sectionName,subt.subjectName, subt.id as subjectId FROM " . Table::examTable . " e
    INNER JOIN " . Table::classTable . " ct ON e.class_id = ct.id 
    INNER JOIN " . Table::sectionTable . " st ON e.section_id = st.id 
    INNER JOIN " . Table::subjectTable . " subt ON e.subject_id = subt.id 
    WHERE e.class_id = '$classId' AND e.section_id = '$sectionId' $condition AND e.id = '$examId' AND e.status = '1'")->result_array();

    if (!empty($d)) {
      return $d;
    } else {
      return HelperClass::APIresponse(500, 'No Home Work found for this class');
    }
  }







  // showSingleExam
  public function showSingleExam($classId, $sectionId,  $examId, $schoolUniqueCode)
  {
    $currentDate = date_create()->format('Y-m-d');
    // $dir = base_url() . HelperClass::uploadImgDir;
    $condition = " AND e.schoolUniqueCode = '$schoolUniqueCode' ";

    $d = $this->db->query("SELECT e.id as examId,e.exam_name,e.max_marks,e.min_marks,e.date_of_exam,ct.className,st.sectionName,subt.subjectName, subt.id as subjectId FROM " . Table::examTable . " e
      INNER JOIN " . Table::classTable . " ct ON e.class_id = ct.id 
      INNER JOIN " . Table::sectionTable . " st ON e.section_id = st.id 
      INNER JOIN " . Table::subjectTable . " subt ON e.subject_id = subt.id 
      WHERE e.class_id = '$classId' AND e.section_id = '$sectionId' $condition AND e.id = '$examId' AND e.status = '1'")->result_array();

    if (!empty($d)) {
      return $d;
    } else {
      return HelperClass::APIresponse(500, 'No Exams found for this class');
    }
  }


  // save attendence
  public function addResult($loginUserId, $loginuserType, $resultDate, $studentId, $marks, $reMarks, $examId, $schoolUniqueCode, $session_table_id)
  {
    $currentDate = date_create()->format('Y-m-d');
    if ($loginuserType == 'Teacher') {

      // check if student is pass or fail


      $e = $this->db->query("SELECT min_marks,max_marks FROM " . Table::examTable . " WHERE 
      id = '$examId' AND schoolUniqueCode = '$schoolUniqueCode' LIMIT 1")->result_array();

      if (!empty($e)) {
        if ($marks >= $e[0]['min_marks'] && $marks <= $e[0]['max_marks']) {
          $resultStatus = '1'; // pass
        } else {
          $resultStatus = '2'; // fail
        }
      }



      $d = $this->db->query($sql = "SELECT student_id,exam_id,resultStatus FROM " . Table::resultTable . " WHERE student_id = '$studentId' AND exam_id = '$examId' AND schoolUniqueCode = '$schoolUniqueCode' LIMIT 1")->result_array();

      if (!empty($d)) {
        return HelperClass::APIresponse(500, 'Result For This Student is Already Submited.' . $d[0]['student_id']);
      }

      $insertArr = [
        "schoolUniqueCode" => $schoolUniqueCode,
        "exam_id" => $examId,
        "marks" => $marks,
        "remarks" => ($reMarks) ? $reMarks : "",
        "resultStatus" => $resultStatus,
        "student_id" => $studentId,
        "login_user_type" => $loginuserType,
        "login_user_id" => $loginUserId,
        "result_date" => $resultDate,
        "session_table_id" => $session_table_id
      ];



      $insertId = $this->CrudModel->insert(Table::resultTable, $insertArr);
      if (!empty($insertId)) {





        if ($resultStatus == '1' && !empty($e)) {
          // check digiCoin is set for this result time for students
          $perResultDigiCoin =  $this->checkIsDigiCoinIsSet(HelperClass::actionType['Result'], HelperClass::userType['Student'], $schoolUniqueCode);

          // calculate digiCoin value as per marks of student

          $digiCoinToInsert =   $this->calculateStudentResultDigiCoin($perResultDigiCoin, $marks, $e[0]['max_marks']);



          if ($digiCoinToInsert > 0) {
            // insert the digicoin
            $insertDigiCoin = $this->insertDigiCoin($studentId, HelperClass::userTypeR['1'], HelperClass::actionType['Result'], $digiCoinToInsert, $schoolUniqueCode, $examId);
            if ($insertDigiCoin) {
              return true;
            } else {
              return HelperClass::APIresponse(500, 'DigiCoin Not Inserted For Student ' . $this->db->last_query());
            }
          }
        }


        return true;
      } else {
        return false;
      }
    } else if ($loginuserType == 'Staff') {
      //
    } else if ($loginuserType == 'Principal') {
      //
    }
  }


  // fetching all classes
  public function allClasses($schoolUniqueCode)
  {
    return $this->CrudModel->allClass(Table::classTable, $schoolUniqueCode);
  }

  // fetching all sections
  public function allSections($schoolUniqueCode)
  {
    return  $this->CrudModel->allSection(Table::sectionTable, $schoolUniqueCode);
  }
  // fetching all subjects
  public function allSubjects($schoolUniqueCode)
  {
    return  $this->CrudModel->allSubjects(Table::subjectTable, $schoolUniqueCode);
  }

  // calculate digicoin
  public function getAlreadyDigiCoinCount($user_id, $user_type_id, $user_type_key, $schoolUniqueCode)
  {

    if ($user_type_key == 'Parent' || $user_type_key == 'Student') {
      $user_type_key = 'Student';
      $user_type_id = '1';
    }
    // check total digicoin earn
    $d = $this->db->query("SELECT SUM(digiCoin) as digiCoin FROM " . Table::getDigiCoinTable . " WHERE user_type = '$user_type_key' AND user_id = '$user_id' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();

    if (!empty($d)) {
      $totalDigiCoinEarn = $d[0]['digiCoin'];
    }

    // check total digicoin redeem

    $c = $this->db->query("SELECT SUM(digiCoin_used) as digiCoinUsed FROM " . Table::giftRedeemTable . " WHERE login_user_type = '$user_type_id' AND login_user_id = '$user_id' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();


    // get diffrence both
    if (!empty($c)) {
      $redeemCoins = @$c[0]['digiCoinUsed'];
    }

    $r = [];
    $r['earnDigiCoin'] = @$totalDigiCoinEarn;
    $r['redeemDigiCoin'] = (@$redeemCoins) ? @$redeemCoins : '0';
    $r['balanceDigiCoin'] = strval(@$totalDigiCoinEarn - @$redeemCoins);

    if (!empty($r)) {
      return $r;
    } else {
      return 0;
    }
  }


  // checkAllGifts
  public function checkAllGifts($user_type, $schoolUniqueCode)
  {
    if ($user_type == 'Teacher') {
      $dir = base_url() . HelperClass::giftsImagePath;
      $userTypeId = HelperClass::userType[$user_type];
      $d = $this->db->query("SELECT gift_name,CONCAT('$dir',gift_image) as image,redeem_digiCoins FROM " . Table::giftTable . " WHERE user_type = '$userTypeId' AND status = '1'")->result_array();
      if (!empty($d)) {
        return $d;
      } else {
        return 0;
      }
    } else if ($user_type == 'Student' || $user_type == 'Parent') {
      $user_type = 'Student';
      $dir = base_url() . HelperClass::giftsImagePath;
      $userTypeId = HelperClass::userType[$user_type];
      $d = $this->db->query("SELECT gift_name,CONCAT('$dir',gift_image) as image,redeem_digiCoins FROM " . Table::giftTable . " WHERE user_type = '$userTypeId'  AND status = '1'")->result_array();
      if (!empty($d)) {
        return $d;
      } else {
        return 0;
      }
    }
  }
  // checkAllGifts
  public function showGiftsForRedeem($loginUserId, $loginuserType, $schoolUniqueCode)
  {
    if ($loginuserType == 'Teacher') {
      // check sum of there digicoins first
      $userType = HelperClass::userType[$loginuserType];
      $totalDigiCoinInWallet = $this->getAlreadyDigiCoinCount($loginUserId, $userType, $loginuserType, $schoolUniqueCode);
      if (!empty($totalDigiCoinInWallet)) {

        $dir = base_url() . HelperClass::giftsImagePath;
        $d = $this->db->query("SELECT id as gift_id, gift_name,CONCAT('$dir',gift_image) as image,redeem_digiCoins FROM " . Table::giftTable . " WHERE user_type = '$userType' AND redeem_digiCoins <= '{$totalDigiCoinInWallet['balanceDigiCoin']}'  AND status = '1'")->result_array();
      }

      if (!empty($d)) {
        return $d;
      } else {
        return 0;
      }
    } else if ($loginuserType == 'Student' || $loginuserType == 'Parent') {
      $loginuserType = 'Student';
      $userType = HelperClass::userType[$loginuserType];
      $totalDigiCoinInWallet = $this->getAlreadyDigiCoinCount($loginUserId, $userType, $loginuserType, $schoolUniqueCode);
      if (!empty($totalDigiCoinInWallet)) {

        $dir = base_url() . HelperClass::giftsImagePath;
        $d = $this->db->query("SELECT id as gift_id, gift_name,CONCAT('$dir',gift_image) as image,redeem_digiCoins FROM " . Table::giftTable . " WHERE user_type = '$userType' AND redeem_digiCoins <= '{$totalDigiCoinInWallet['balanceDigiCoin']}'   AND status = '1'")->result_array();
      }

      if (!empty($d)) {
        return $d;
      } else {
        return 0;
      }
    }
  }


  // redeemGifts
  public function redeemGifts($giftId, $loginUserId, $loginuserType, $schoolUniqueCode)
  {
    if ($loginuserType == 'Teacher') {
      $giftValueDigiCoin = 0;
      // check sum of there digicoins first
      $userType = HelperClass::userType[$loginuserType];


      $giftValue = $this->db->query("SELECT redeem_digiCoins FROM " . Table::giftTable . " WHERE user_type = '$userType' and id = '$giftId'   AND status = '1'")->result_array();

      if (!empty($giftValue)) {
        $giftValueDigiCoin = $giftValue[0]['redeem_digiCoins'];
      } else {
        return HelperClass::APIresponse(500, 'This Gift Id\'s Not found, Please Check Another Gift ' . $this->db->last_query());
      }


      // send notification now

      $tokensFromDB =  $this->db->query("SELECT fcm_token FROM " . Table::teacherTable . " WHERE id = '$loginUserId' AND schoolUniqueCode = '$schoolUniqueCode'  AND status = '1' LIMIT 1")->result_array();

      if (!empty($tokensFromDB)) {
        $tokenArr = [$tokensFromDB[0]['fcm_token']];
        $title = "🎁 Gift Redeem Successfully.";
        $body = "Hey 👋 Dear Teacher, We Have Successfully Recived Your Gift Redeem Request You Will Get Your Gift Soon.";
        $image = null;
        $sound = null;
        $sendPushSMS = json_decode($this->CrudModel->sendFireBaseNotificationWithDeviceId($tokenArr, $title, $body, $image, $sound), TRUE);
      }





      // total digiCoins in wallet
      $totalDigiCoinInWallet = $this->getAlreadyDigiCoinCount($loginUserId, $userType, $loginuserType, $schoolUniqueCode);

      if ($totalDigiCoinInWallet['balanceDigiCoin'] >= $giftValueDigiCoin) {
        $insertRedeem = $this->db->query("INSERT INTO " . Table::giftRedeemTable . " (schoolUniqueCode,login_user_id,login_user_type,gift_id,digiCoin_used) VALUES ('$schoolUniqueCode','$loginUserId','$userType','$giftId','$giftValueDigiCoin')");
      } else {
        return HelperClass::APIresponse(500, 'This Gifts Not Redeem, Because Your DigiCoins are too low. Gift Id is ' . $giftId . ' if you have redeem 1 or more gifts then check all other is redeem successfully.');
      }

      if (!empty($insertRedeem)) {
        return true;
      } else {
        return false;
      }
    } else if ($loginuserType == 'Student' || $loginuserType == 'Parent') {
      $giftValueDigiCoin = 0;
      // check sum of there digicoins first
      $loginuserType = 'Student';
      $userType = HelperClass::userType[$loginuserType];


      $giftValue = $this->db->query("SELECT redeem_digiCoins FROM " . Table::giftTable . " WHERE user_type = '$userType' and id = '$giftId'  AND schoolUniqueCode = '$schoolUniqueCode' AND status = '1'")->result_array();

      if (!empty($giftValue)) {
        $giftValueDigiCoin = $giftValue[0]['redeem_digiCoins'];
      } else {
        return HelperClass::APIresponse(500, 'This Gift Id\'s Not found, Please Check Another Gift ' . $this->db->last_query());
      }


      // send notification now

      $tokensFromDB =  $this->db->query("SELECT fcm_token FROM " . Table::teacherTable . " WHERE id = '$loginUserId' AND schoolUniqueCode = '$schoolUniqueCode'  AND status = '1' LIMIT 1")->result_array();

      if (!empty($tokensFromDB)) {
        $tokenArr = [$tokensFromDB[0]['fcm_token']];
        $title = "🎁 Gift Redeem Successfully.";
        $body = "Hey 👋 Dear Teacher, We Have Successfully Recived Your Gift Redeem Request You Will Get Your Gift Soon.";
        $image = null;
        $sound = null;
        $sendPushSMS = json_decode($this->CrudModel->sendFireBaseNotificationWithDeviceId($tokenArr, $title, $body, $image, $sound), TRUE);
      }





      // total digiCoins in wallet
      $totalDigiCoinInWallet = $this->getAlreadyDigiCoinCount($loginUserId, $userType, $loginuserType, $schoolUniqueCode);

      if ($totalDigiCoinInWallet['balanceDigiCoin'] >= $giftValueDigiCoin) {
        $insertRedeem = $this->db->query("INSERT INTO " . Table::giftRedeemTable . " (schoolUniqueCode,login_user_id,login_user_type,gift_id,digiCoin_used) VALUES ('$schoolUniqueCode','$loginUserId','$userType','$giftId','$giftValueDigiCoin')");
      } else {
        return HelperClass::APIresponse(500, 'This Gifts Not Redeem, Because Your DigiCoins are too low. Gift Id is ' . $giftId . ' if you have redeem 1 or more gifts then check all other is redeem successfully.');
      }

      if (!empty($insertRedeem)) {
        return true;
      } else {
        return false;
      }
    }
  }


  // gift redeem status
  public function giftRedeemStatus($loginUserId, $loginuserType, $schoolUniqueCode)
  {
    $dir = base_url() . HelperClass::giftsImagePath;
    if ($loginuserType == 'Teacher') {
      $userType = HelperClass::userType[$loginuserType];

      $sql = "SELECT grt.*, gt.id as giftId, gt.gift_name, CONCAT('$dir',gt.gift_image) as image FROM " . Table::giftRedeemTable . " grt
        LEFT JOIN " . Table::giftTable . " gt ON grt.gift_id = gt.id AND gt.user_type = '$userType'
         WHERE grt.login_user_id = '$loginUserId' AND grt.login_user_type = '$userType' AND grt.schoolUniqueCode = '$schoolUniqueCode'";

      $allGiftsRedeemData = $this->db->query($sql)->result_array();
      if ($allGiftsRedeemData) {

        $totalC = count($allGiftsRedeemData);
        $sendArr = [];
        $sendArr['giftStatus'] = [];
        for ($i = 0; $i < $totalC; $i++) {
          $subArr = [];
          $subArr['giftRedeemId'] = $allGiftsRedeemData[$i]['id'];
          $subArr['giftId'] = $allGiftsRedeemData[$i]['giftId'];
          $subArr['giftName'] = $allGiftsRedeemData[$i]['gift_name'];
          $subArr['giftImage'] = $allGiftsRedeemData[$i]['image'];
          $subArr['digiCoinUsed'] = $allGiftsRedeemData[$i]['digiCoin_used'];
          $subArr['redeemStatus'] = HelperClass::giftStatus[$allGiftsRedeemData[$i]['status']];
          $subArr['redeemDate'] = date('d-m-Y h:i:A', strtotime($allGiftsRedeemData[$i]['created_at']));
          array_push($sendArr['giftStatus'], $subArr);
        }
        return $sendArr;
      } else {
        return HelperClass::APIresponse(500, 'There is no gift redeem found for this user.');
      }
    } else if ($loginuserType == 'Student') {
      //
    }
  }

  // wallet History
  public function walletHistory($loginUserId, $loginuserType, $schoolUniqueCode)
  {
    $dir = base_url() . HelperClass::giftsImagePath;
    if ($loginuserType == 'Teacher') {
      $userType = HelperClass::userType[$loginuserType];

      $sql = "SELECT * FROM ( SELECT gdc.id as tId,  
      CASE 
      WHEN gdc.for_what = '1' THEN 'Attendence'  
      WHEN gdc.for_what = '2' THEN 'Departure'
      WHEN gdc.for_what = '3' THEN 'Result'  
      WHEN gdc.for_what = '0' THEN 'Welcome Bonus'  
      END as for_what, gdc.digiCoin,  'Earning' as tStatus, 
      gdc.created_at as whatDate,
       CASE 
       WHEN gdc.for_what = '1' THEN CONCAT('$dir','attendance.png')
       WHEN gdc.for_what = '2' THEN CONCAT('$dir','clock.png')
       WHEN gdc.for_what = '3' THEN CONCAT('$dir','exam.png')
       END as image

        FROM " . Table::getDigiCoinTable . " gdc  WHERE gdc.user_type = '$loginuserType' AND gdc.user_id = '$loginUserId' AND gdc.schoolUniqueCode = '$schoolUniqueCode' ) as a  
      UNION ALL
     SELECT * FROM (SELECT grt.id as tId,gift.gift_name as for_what, grt.digiCoin_used as digiCoin, 'Redeem' as tStatus,grt.created_at as whatDate, CONCAT('$dir',gift.gift_image)  as image   FROM " . Table::giftRedeemTable . " grt  LEFT JOIN " . Table::giftTable . "  ON gift.id = grt.gift_id WHERE grt.login_user_type = '$userType' AND grt.login_user_id = '$loginUserId' AND grt.schoolUniqueCode = '$schoolUniqueCode' ) as b
     ORDER BY whatDate DESC
     ";

      $d = $this->db->query($sql)->result_array();

      if (!empty($d)) {

        $sendArr = [];
        $totalCount = count($d);

        $sendArr['totalDigiCoins'] = 0;
        $sendArr['transactions'] = [];
        $totalCoins = 0;
        for ($i = 0; $i < $totalCount; $i++) {
          $subArr = [];
          $subArr['tId'] = $d[$i]['tId'];
          $subArr['digiCoin'] = $d[$i]['digiCoin'];
          $subArr['for_what'] = $d[$i]['for_what'];
          $subArr['tStatus'] = $d[$i]['tStatus'];
          $subArr['whatDate'] = $d[$i]['whatDate'];
          $subArr['image'] = $d[$i]['image'];
          array_push($sendArr['transactions'], $subArr);
        }
        $sendArr['totalDigiCoins'] = $this->getAlreadyDigiCoinCount($loginUserId, $userType, $loginuserType, $schoolUniqueCode);

        return $sendArr;
      } else {
        return 0;
      }
    } else if ($loginuserType == 'Parent' || $loginuserType == 'Student') {
      $userType = '1';
      //$userType = HelperClass::userType[$loginuserType];

      $loginuserType = 'Student';

      $sql = "SELECT * FROM ( SELECT gdc.id as tId,  
      CASE 
      WHEN gdc.for_what = '1' THEN 'Attendence'  
      WHEN gdc.for_what = '2' THEN 'Departure'
      WHEN gdc.for_what = '3' THEN 'Result'  
      WHEN gdc.for_what = '0' THEN 'Welcome Bonus'  
      END as for_what, gdc.digiCoin,  'Earning' as tStatus, 
      gdc.created_at as whatDate,
       CASE 
       WHEN gdc.for_what = '1' THEN CONCAT('$dir','attendance.png')
       WHEN gdc.for_what = '2' THEN CONCAT('$dir','clock.png')
       WHEN gdc.for_what = '3' THEN CONCAT('$dir','exam.png')
       END as image

        FROM " . Table::getDigiCoinTable . " gdc  WHERE gdc.user_type = '$loginuserType' AND gdc.user_id = '$loginUserId' AND gdc.schoolUniqueCode = '$schoolUniqueCode' ) as a  
      UNION ALL
     SELECT * FROM (SELECT grt.id as tId,gift.gift_name as for_what, grt.digiCoin_used as digiCoin, 'Redeem' as tStatus,grt.created_at as whatDate, CONCAT('$dir',gift.gift_image)  as image   FROM " . Table::giftRedeemTable . " grt  LEFT JOIN " . Table::giftTable . "  ON gift.id = grt.gift_id WHERE grt.login_user_type = '$userType' AND grt.login_user_id = '$loginUserId' AND grt.schoolUniqueCode = '$schoolUniqueCode' ) as b
     ORDER BY whatDate DESC
     ";

      // echo $sql;
      $d = $this->db->query($sql)->result_array();

      if (!empty($d)) {

        $sendArr = [];
        $totalCount = count($d);

        $sendArr['totalDigiCoins'] = 0;
        $sendArr['transactions'] = [];
        $totalCoins = 0;
        for ($i = 0; $i < $totalCount; $i++) {
          $subArr = [];
          $subArr['tId'] = $d[$i]['tId'];
          $subArr['digiCoin'] = $d[$i]['digiCoin'];
          $subArr['for_what'] = $d[$i]['for_what'];
          $subArr['tStatus'] = $d[$i]['tStatus'];
          $subArr['whatDate'] = $d[$i]['whatDate'];
          $subArr['image'] = $d[$i]['image'];
          array_push($sendArr['transactions'], $subArr);
        }
        $sendArr['totalDigiCoins'] = $this->getAlreadyDigiCoinCount($loginUserId, $userType, $loginuserType, $schoolUniqueCode);

        return $sendArr;
      } else {
        return 0;
      }
    }
  }


  // leaderBoard
  public function leaderBoard($loginuserType, $schoolUniqueCode, $loginUserId)
  {

    if ($loginuserType == 'Teacher') {
      $dir = base_url() . HelperClass::teacherImagePath;
      $tableName = Table::teacherTable;
      // $d = $this->db->query($sql1 = "SELECT SUM(gdc.digiCoin) as totalDigiCoinsEarn, gdc.user_id,gdc.user_type, (SELECT name FROM students WHERE id = gdc.user_id) as userName FROM " . Table::getDigiCoinTable . " gdc WHERE gdc.schoolUniqueCode = '$schoolUniqueCode' AND user_type = '$loginuserType' AND MONTH(gdc.created_at)=MONTH(now()) AND YEAR(gdc.created_at)=YEAR(now()) GROUP BY gdc.user_id ORDER BY SUM(gdc.digiCoin) DESC")->result_array();
      $d = $this->db->query($sql1 = "SELECT SUM(gdc.digiCoin) as totalDigiCoinsEarn, gdc.user_id,gdc.user_type, (SELECT name FROM students WHERE id = gdc.user_id AND schoolUniqueCode = '$schoolUniqueCode') as userName FROM " . Table::getDigiCoinTable . " gdc WHERE gdc.schoolUniqueCode = '$schoolUniqueCode' AND user_type = '$loginuserType'  GROUP BY gdc.user_id ORDER BY SUM(gdc.digiCoin) DESC")->result_array();


      if (!empty($d)) {

        $sendArr = [];
        $sendArr['topOne'] = [];
        $sendArr['topTwo'] = [];
        $sendArr['topThree'] = [];
        $sendArr['restAll'] = [];

        $totalCount = count($d);
        $a = 1;
        for ($i = 0; $i < $totalCount; $i++) {
          $userDetails =  $this->db->query($sql2 = "SELECT s.id, s.name,s.user_id,s.image,c.className,sc.sectionName FROM " . $tableName . " s LEFT JOIN " . Table::classTable . " c ON c.id = s.class_id LEFT JOIN " . Table::sectionTable . " sc ON sc.id = s.section_id WHERE s.id = '{$d[$i]['user_id']}' AND s.schoolUniqueCode = '$schoolUniqueCode'")->result_array();

          //  if(empty($userDetails)) 
          //  {
          //   continue;
          //  }

          // for first, second & third position
          if ($i == 0 || $i == 1 || $i == 2) {
            if ($i == 0) {
              $topSubArr = [];
              $topSubArr['myPosition'] = FALSE;
              if ($loginUserId == @$userDetails[0]['id']) {
                $topSubArr['myPosition'] = TRUE;
              }
              $topSubArr['position'] = $a++;
              $topSubArr['userType'] = @$d[$i]['user_type'];
              $topSubArr['totalDigiCoinsEarn'] = @$d[$i]['totalDigiCoinsEarn'];
              $topSubArr['id'] = @$userDetails[0]['id'];
              $topSubArr['name'] = @$userDetails[0]['name'];
              $topSubArr['uniqueId'] = @$userDetails[0]['user_id'];
              $topSubArr['className'] = @$userDetails[0]['className'];
              $topSubArr['sectionName'] = @$userDetails[0]['sectionName'];
              $topSubArr['image'] = @$dir . @$userDetails[0]['image'];
              $sendArr['topOne'] = $topSubArr;
            }
            if ($i == 1) {
              $topSubArr = [];
              $topSubArr['myPosition'] = FALSE;
              if ($loginUserId == @$userDetails[0]['id']) {
                $topSubArr['myPosition'] = TRUE;
              }
              $topSubArr['position'] = $a++;
              $topSubArr['userType'] = @$d[$i]['user_type'];
              $topSubArr['totalDigiCoinsEarn'] = @$d[$i]['totalDigiCoinsEarn'];
              $topSubArr['id'] = @$userDetails[0]['id'];
              $topSubArr['name'] = @$userDetails[0]['name'];
              $topSubArr['uniqueId'] = @$userDetails[0]['user_id'];
              $topSubArr['className'] = @$userDetails[0]['className'];
              $topSubArr['sectionName'] = @$userDetails[0]['sectionName'];
              $topSubArr['image'] = @$dir . @$userDetails[0]['image'];
              $sendArr['topTwo'] = $topSubArr;
            }
            if ($i == 2) {
              $topSubArr = [];
              $topSubArr['myPosition'] = FALSE;
              if ($loginUserId == @$userDetails[0]['id']) {
                $topSubArr['myPosition'] = TRUE;
              }
              $topSubArr['position'] = $a++;
              $topSubArr['userType'] = @$d[$i]['user_type'];
              $topSubArr['totalDigiCoinsEarn'] = @$d[$i]['totalDigiCoinsEarn'];
              $topSubArr['id'] = @$userDetails[0]['id'];
              $topSubArr['name'] = @$userDetails[0]['name'];
              $topSubArr['uniqueId'] = @$userDetails[0]['user_id'];
              $topSubArr['className'] = @$userDetails[0]['className'];
              $topSubArr['sectionName'] = @$userDetails[0]['sectionName'];
              $topSubArr['image'] = @$dir . @$userDetails[0]['image'];
              $sendArr['topThree'] = $topSubArr;
            }

            continue;
          }


          $subArr = [];
          $subArr['myPosition'] = FALSE;
          if ($loginUserId == @$userDetails[0]['id']) {
            $subArr['myPosition'] = TRUE;
          }
          $subArr['position'] = $a++;
          $subArr['userType'] = @$d[$i]['user_type'];
          $subArr['totalDigiCoinsEarn'] = @$d[$i]['totalDigiCoinsEarn'];
          $subArr['id'] = @$userDetails[0]['id'];
          $subArr['name'] = @$userDetails[0]['name'];
          $subArr['uniqueId'] = @$userDetails[0]['user_id'];
          $subArr['className'] = @$userDetails[0]['className'];
          $subArr['sectionName'] = @$userDetails[0]['sectionName'];
          $subArr['image'] = @$dir . @$userDetails[0]['image'];
          array_push($sendArr['restAll'], $subArr);
        }

        return $sendArr;
      } else {
        return 0;
      }
    } else if ($loginuserType == 'Student' || $loginuserType == 'Parent') {
      $dir = base_url() . HelperClass::studentImagePath;
      $tableName = Table::studentTable;
      $loginuserType = 'Student';
      // $d = $this->db->query("SELECT SUM(gdc.digiCoin) as totalDigiCoinsEarn, gdc.user_id,gdc.user_type, (SELECT name FROM students WHERE id = gdc.user_id) as userName FROM " . Table::getDigiCoinTable . " gdc WHERE gdc.schoolUniqueCode = '$schoolUniqueCode' AND user_type = '$loginuserType' AND MONTH(gdc.created_at)=MONTH(now()) AND YEAR(gdc.created_at)=YEAR(now()) GROUP BY gdc.user_id ORDER BY SUM(gdc.digiCoin) DESC")->result_array();
      $d = $this->db->query("SELECT SUM(gdc.digiCoin) as totalDigiCoinsEarn, gdc.user_id,gdc.user_type, (SELECT name FROM students WHERE id = gdc.user_id AND schoolUniqueCode = '$schoolUniqueCode') as userName FROM " . Table::getDigiCoinTable . " gdc WHERE gdc.schoolUniqueCode = '$schoolUniqueCode' AND user_type = '$loginuserType'  GROUP BY gdc.user_id ORDER BY SUM(gdc.digiCoin) DESC")->result_array();

      if (!empty($d)) {

        $sendArr = [];
        $sendArr['topOne'] = [];
        $sendArr['topTwo'] = [];
        $sendArr['topThree'] = [];
        $sendArr['restAll'] = [];

        $totalCount = count($d);
        $a = 1;
        for ($i = 0; $i < $totalCount; $i++) {
          $userDetails =  $this->db->query("SELECT s.id, s.name,s.user_id,s.image,c.className,sc.sectionName FROM " . $tableName . " s LEFT JOIN " . Table::classTable . " c ON c.id = s.class_id LEFT JOIN " . Table::sectionTable . " sc ON sc.id = s.section_id WHERE s.id = '{$d[$i]['user_id']}' AND s.schoolUniqueCode = '$schoolUniqueCode'")->result_array();

          //  if(empty($userDetails)) 
          //  {
          //   continue;
          //  }
          if ($i == 0 || $i == 1 || $i == 2) {
            if ($i == 0) {
              $topSubArr = [];
              $topSubArr['myPosition'] = FALSE;
              if ($loginUserId == @$userDetails[0]['id']) {
                $topSubArr['myPosition'] = TRUE;
              }
              $topSubArr['position'] = $a++;
              $topSubArr['userType'] = @$d[$i]['user_type'];
              $topSubArr['totalDigiCoinsEarn'] = @$d[$i]['totalDigiCoinsEarn'];
              $topSubArr['id'] = @$userDetails[0]['id'];
              $topSubArr['name'] = @$userDetails[0]['name'];
              $topSubArr['uniqueId'] = @$userDetails[0]['user_id'];
              $topSubArr['className'] = @$userDetails[0]['className'];
              $topSubArr['sectionName'] = @$userDetails[0]['sectionName'];
              $topSubArr['image'] = @$dir . @$userDetails[0]['image'];
              $sendArr['topOne'] =  $topSubArr;
            }
            if ($i == 1) {
              $topSubArr = [];
              $topSubArr['myPosition'] = FALSE;
              if ($loginUserId == @$userDetails[0]['id']) {
                $topSubArr['myPosition'] = TRUE;
              }
              $topSubArr['position'] = $a++;
              $topSubArr['userType'] = @$d[$i]['user_type'];
              $topSubArr['totalDigiCoinsEarn'] = @$d[$i]['totalDigiCoinsEarn'];
              $topSubArr['id'] = @$userDetails[0]['id'];
              $topSubArr['name'] = @$userDetails[0]['name'];
              $topSubArr['uniqueId'] = @$userDetails[0]['user_id'];
              $topSubArr['className'] = @$userDetails[0]['className'];
              $topSubArr['sectionName'] = @$userDetails[0]['sectionName'];
              $topSubArr['image'] = @$dir . @$userDetails[0]['image'];
              $sendArr['topTwo'] = $topSubArr;
            }
            if ($i == 2) {
              $topSubArr = [];
              $topSubArr['myPosition'] = FALSE;
              if ($loginUserId == @$userDetails[0]['id']) {
                $topSubArr['myPosition'] = TRUE;
              }
              $topSubArr['position'] = $a++;
              $topSubArr['userType'] = @$d[$i]['user_type'];
              $topSubArr['totalDigiCoinsEarn'] = @$d[$i]['totalDigiCoinsEarn'];
              $topSubArr['id'] = @$userDetails[0]['id'];
              $topSubArr['name'] = @$userDetails[0]['name'];
              $topSubArr['uniqueId'] = @$userDetails[0]['user_id'];
              $topSubArr['className'] = @$userDetails[0]['className'];
              $topSubArr['sectionName'] = @$userDetails[0]['sectionName'];
              $topSubArr['image'] = @$dir . @$userDetails[0]['image'];
              $sendArr['topThree'] =  $topSubArr;
            }
            continue;
          }


          $subArr = [];
          $subArr['myPosition'] = FALSE;
          if ($loginUserId == @$userDetails[0]['id']) {
            $subArr['myPosition'] = TRUE;
          }
          $subArr['position'] = $a++;
          $subArr['userType'] = @$d[$i]['user_type'];
          $subArr['totalDigiCoinsEarn'] = @$d[$i]['totalDigiCoinsEarn'];
          $subArr['id'] = @$userDetails[0]['id'];
          $subArr['name'] = @$userDetails[0]['name'];
          $subArr['uniqueId'] = @$userDetails[0]['user_id'];
          $subArr['className'] = @$userDetails[0]['className'];
          $subArr['sectionName'] = @$userDetails[0]['sectionName'];
          $subArr['image'] = @$dir . @$userDetails[0]['image'];
          array_push($sendArr['restAll'], $subArr);
        }

        return $sendArr;
      } else {
        return 0;
      }
    }
  }

  public function bannerForApp($schoolUniqueCode)
  {

    $dir = base_url() . HelperClass::schoolBannerImagePath;

    return $this->db->query("SELECT *, CONCAT('$dir',image) as image FROM " . Table::bannerTable . " WHERE status = '1' AND schoolUniqueCode = '$schoolUniqueCode' ")->result_array();
  }
  public function gatePassList($schoolUniqueCode)
  {

    $dir = base_url() . HelperClass::gatePassImagePath;

    return $this->db->query("SELECT g.*, CONCAT('$dir',g.image) as image, CONCAT('https://dvm.digitalfied.in/gatePass?gatePass=', g.id) as link ,c.className, sec.sectionName, s.name
    FROM " . Table::gatePassTable . " g 
    INNER JOIN " . Table::studentTable . " s ON s.id = g.student_id
    INNER JOIN " . Table::classTable . " c ON c.id = g.class_id
    INNER JOIN " . Table::sectionTable . " sec ON sec.id = g.section_id
    WHERE g.status = '1' AND g.schoolUniqueCode = '$schoolUniqueCode' ")->result_array();
  }

  public function notificationsForParent($schoolUniqueCode)
  {
    return $this->db->query("SELECT * FROM " . Table::pushNotificationTable . " WHERE status = '1' AND schoolUniqueCode = '$schoolUniqueCode' ORDER BY id DESC ")->result_array();
  }
  public function notificationsForAll($schoolUniqueCode)
  {
    return $this->db->query("SELECT *,DATE_FORMAT(created_at, '".HelperClass::dateFormatForAPI."') as created_at FROM " . Table::pushNotificationTable . " WHERE status = '1' AND schoolUniqueCode = '$schoolUniqueCode' ORDER BY id DESC LIMIT 10")->result_array();
  }

  // leaderBoard
  public function visitorEntry($visit_date, $visit_time, $visitor_name, $person_to_meet, $purpose_to_meet, $visitor_mobile_no, $file, $schoolUniqueCode, $session_table_id)
  {

    $d = $this->db->query("INSERT INTO " . Table::visitorTable . " (schoolUniqueCode,visit_date,visit_time,visitor_name,person_to_meet,purpose_to_meet,visitor_mobile_no,visitor_image,session_table_id) VALUES ('$schoolUniqueCode','$visit_date','$visit_time','$visitor_name','$person_to_meet','$purpose_to_meet','$visitor_mobile_no','$file','$session_table_id')");
    // echo $this->db->last_query();

    if (($d)) {
      return $this->db->insert_id();
    } else {
      return false;
    }
  }


  public function gatePass($schoolUniqueCode, $studentId, $class_id, $section_id, $guardian_name, $mobile, $address, $time, $date, $document_image_name)
  {
    $d = $this->db->query("INSERT INTO " . Table::gatePassTable . " (schoolUniqueCode,student_id,class_id,section_id,guardian_name,mobile,address,time,date,image) VALUES ('$schoolUniqueCode','$studentId','$class_id','$section_id','$guardian_name','$mobile','$address','$time','$date','$document_image_name')");
    // echo $this->db->last_query();

    if (($d)) {
      return $this->db->insert_id();
    } else {
      return false;
    }
  }

  // upateDriverLatLng
  public function upateDriverLatLng($loginUserIdFromDB, $loginuserType, $lat, $lng, $schoolUniqueCode)
  {
    //$currentDate = date_create()->format('Y-m-d');
    if ($loginuserType == 'Driver') {

      $updateArr = [
        "lat" => $lat,
        "lng" => $lng,
      ];

      $update = $this->CrudModel->update(Table::driverTable, $updateArr, $loginUserIdFromDB);
      if (!empty($update)) {
        return true;
      } else {
        return false;
      }
    } else if ($loginuserType == 'Staff') {
      //
    } else if ($loginuserType == 'Principal') {
      //
    }
  }

  // add Compalint
  public function addComplaint($loginUserIdFromDB, $loginuserType, $guiltyPersonName, $guiltyPersonPosition, $subject, $issue, $schoolUniqueCode)
  {
    //$currentDate = date_create()->format('Y-m-d');
    $insertArr = [
      "schoolUniqueCode" => $schoolUniqueCode,
      "login_user_id" => $loginUserIdFromDB,
      "login_user_type" => HelperClass::userType[$loginuserType],
      "guilty_person_name" => $guiltyPersonName,
      "guilty_person_position" => $guiltyPersonPosition,
      "subject" => $subject,
      "issue" => $issue,
      "complaint_id" => "COMPLAINT-ID-" . rand(0000, 9999),
    ];

    $insertId = $this->CrudModel->insert(Table::complaintTable, $insertArr);
    if (!empty($insertId)) {
      return HelperClass::APIresponse(200, 'Complaint Register Successfully, And Your Complaint Id Is ' . $insertArr['complaint_id'] . " Use This Id For Future Refrence.");
    } else {
      return false;
    }
  }

  // add Compalint
  public function markAttendance($loginuserType, $qrCode, $schoolUniqueCode, $session_table_id)
  {
    $currentDate = date_create()->format('Y-m-d');
    $dataStatus = '';
    $condition = " AND e.schoolUniqueCode = '$schoolUniqueCode' ";

    $identityType = $this->CrudModel->extractQrCodeAndReturnUserType($qrCode);
    if ($identityType == HelperClass::userTypeR[1]) {
      $table = Table::qrcodeTable;
    } else if ($identityType == HelperClass::userTypeR[2]) {
      $table = Table::qrcodeTeachersTable;
    } else if ($identityType == HelperClass::userTypeR[7]) {
      $table = Table::qrcodeDriversTable;
    }

    if (empty($table)) {
      $dataStatus = '5'; // qr code is not valid
      return $dataStatus;
      // HelperClass::APIresponse(500, 'QR Code Table Not Found. Please Check QR Code Again.');
    }



    $d = $this->db->query("SELECT * FROM " . $table . " WHERE qrcodeUrl = '$qrCode' AND schoolUniqueCode = '$schoolUniqueCode' AND status = '1' LIMIT 1")->result_array();

 

    if (!empty($d)) {

      if ($identityType == HelperClass::userTypeR[1]) {
        $tableB = Table::studentTable;
        $dir = base_url() . HelperClass::studentImagePath;
      } else if ($identityType == HelperClass::userTypeR[2]) {
        $tableB = Table::teacherTable;
        $dir = base_url() . HelperClass::teacherImagePath;
      } else if ($identityType == HelperClass::userTypeR[7]) {
        $tableB = Table::driverTable;
        $dir = base_url() . HelperClass::driverImagePath;
      }




      $details = $this->db->query("SELECT id, name,email,mobile,CONCAT('$dir',image) as image FROM " . $tableB . " WHERE user_id = '{$d[0]['uniqueValue']}' AND u_qr_id = '{$d[0]['id']}' AND schoolUniqueCode = '$schoolUniqueCode' AND status = '1' LIMIT 1")->result_array();


      // mark attendance now 

      if ($tableB == Table::studentTable) {
        // mark student attendance
        $currentDate = date_create()->format('Y-m-d');

        $stu_id = $details[0]['id'];
        $d = $this->db->query("SELECT stu_id FROM " . Table::attendenceTable . " WHERE att_date = '$currentDate' AND stu_id = '$stu_id' AND schoolUniqueCode = '$schoolUniqueCode' LIMIT 1")->result_array();

        if (!empty($d)) {
          $dataStatus = '1'; // already mark
          return $dataStatus;
        }

        $de = $this->db->query("SELECT c.className,sec.sectionName FROM " . Table::studentTable . " s 
     JOIN " . Table::classTable . " c ON c.id = s.class_id
     JOIN " . Table::sectionTable . " sec ON sec.id = s.section_id
      WHERE s.id = '$stu_id' AND s.schoolUniqueCode = '$schoolUniqueCode' LIMIT 1")->result_array();

        $insertArr = [
          "schoolUniqueCode" => $schoolUniqueCode,
          "stu_id" => $stu_id,
          "stu_class" => $de[0]['className'],
          "stu_section" => $de[0]['sectionName'],
          "login_user_id" => '1',
          "login_user_type" => 'Staff',
          "attendenceStatus" => '1',
          "dateTime" => date_create()->format('Y-m-d h:i:s'),
          "att_date" => date_create()->format('Y-m-d'),
          "session_table_id" => $session_table_id
        ];
        $insertId = $this->CrudModel->insert(Table::attendenceTable, $insertArr);
        if (!empty($insertId)) {

          // insert scan history
          $insertData = $this->db->query("INSERT INTO " . Table::qrScanHistory . " (schoolUniqueCode,qrcode,user_id,user_type_id) VALUES ('$schoolUniqueCode','$qrCode','{$details[0]['id']}', '" . HelperClass::userType[$identityType] . "')");

          $details[0]['userType'] = $identityType;

          $idForUser = (string) $details[0]['id'];


          // send  firebase notification
          if (!empty($details)) {
            $tokensFromDB =  $this->db->query("SELECT fcm_token FROM " . $tableB . " WHERE id = '$idForUser' AND schoolUniqueCode = '$schoolUniqueCode'  AND status = '1' LIMIT 1")->result_array();

            if ($loginuserType == HelperClass::userTypeR[3]) {


              if (!empty($tokensFromDB)) {
                $tokenArr = [$tokensFromDB[0]['fcm_token']];

                $notificationFromDB = $this->db->query("SELECT title, body FROM " . Table::setNotificationTable . " WHERE status = '1' AND schoolUniqueCode = '$schoolUniqueCode' AND for_what = '4' LIMIT 1")->result_array();

                if (!empty($notificationFromDB)) {
                  $title = $this->CrudModel->replaceNotificationsWords((string)$notificationFromDB[0]['title'], ['identity' => $identityType]);
                  $body =  $this->CrudModel->replaceNotificationsWords((string)$notificationFromDB[0]['body'], ['identity' => $identityType]);
                } else {
                  $title = "$identityType Entry On 🏫 School.";
                  $body = "Hey 👋 Dear $identityType, We Welcome You On 🏫 School, You Have Entered Into The 🏫 School, Entry Gate.";
                }
              }
            }

            $image = null;
            $sound = null;
            $sendPushSMS = json_decode($this->CrudModel->sendFireBaseNotificationWithDeviceId($tokenArr, $title, $body, $image, $sound), TRUE);
          }

          // check digiCoin is set for this attendence time for students
          $digiCoinF =  $this->APIModel->checkIsDigiCoinIsSet(HelperClass::actionType['Attendence'], HelperClass::userType['Student'], $schoolUniqueCode);

          if ($digiCoinF) {
            // insert the digicoin
            $insertDigiCoin = $this->APIModel->insertDigiCoin($stu_id, HelperClass::userTypeR['1'], HelperClass::actionType['Attendence'], $digiCoinF, $schoolUniqueCode, $insertId);
            if ($insertDigiCoin) {
              $dataStatus = '2'; // attendance Marked
              return $dataStatus;
            } else {
              $dataStatus = '2'; // attendance Marked but coin not added
              return $dataStatus;
            }
          }

          $dataStatus = '2'; // attendance Marked successfully
          return $dataStatus;
        }
      }

      return $dataStatus;
    } else {
      $dataStatus = '5'; // qrcode is not valid or not found
      return $dataStatus;
    }
  }


  // get driver lat lng
  public function getDriverLatLng($studentId, $schoolUniqueCode,$isTeaher)
  {
      
      
      if($isTeaher)
      {
            $sql= "SELECT d.* FROM " . Table::driverTable . " d
    INNER JOIN " . Table::teacherTable . " s ON s.driver_id = d.id AND s.vechicle_type = d.vechicle_type
    WHERE d.status = '1' AND d.schoolUniqueCode = '$schoolUniqueCode' AND s.id = '$studentId'";
    
      }else{
          $sql = "SELECT d.* FROM " . Table::driverTable . " d
    INNER JOIN " . Table::studentTable . " s ON s.driver_id = d.id AND s.vechicle_type = d.vechicle_type
    WHERE d.status = '1' AND d.schoolUniqueCode = '$schoolUniqueCode' AND s.id = '$studentId'";
         
      }
      
    
        $d = $this->db->query($sql)->result_array();
   

    if (!empty($d)) {
      return $d[0];
    } else {
      return HelperClass::APIresponse(500, 'Driver Details Not Found Linking With The Student ');
    }
  }


  // check if the digiCoin is set 
  public function checkIsDigiCoinIsSet($for_what, $user_type)
  {
    $d = $this->db->query("SELECT digiCoin FROM " . Table::setDigiCoinTable . " WHERE user_type = '$user_type' AND for_what = '$for_what' AND status ='1' LIMIT 1")->result_array();
    if (!empty($d)) {
      return $d[0]['digiCoin'];
    } else {
      return false;
    }
  }

  // insert digiCoin
  public function insertDigiCoin($user_id, $user_type, $for_what, $digiCoin, $schoolUniqueCode, $for_what_id = null)
  {
    $d = $this->db->query("INSERT INTO " . Table::getDigiCoinTable . " (schoolUniqueCode,user_type,user_id,for_what,for_what_id,digiCoin) VALUES ('$schoolUniqueCode','$user_type','$user_id',$for_what,'$for_what_id','$digiCoin')");
    if (!empty($d)) {
      return true;
    } else {
      return false;
    }
  }


  // formula for student digiCoin
  public function calculateStudentResultDigiCoin($perResultDigiCoin, $marksObtained, $maxMarks)
  {

    $percentageCal = $marksObtained / $maxMarks * 100; // 35 / 50 * 100 = 70%

    $returnDigiCoin = 0;
    if ($percentageCal >= 90 && $percentageCal <= 100) {
      // 90 - 100 %tage = return 100% digiCoin
      return $returnDigiCoin = $perResultDigiCoin;
    } else if ($percentageCal >= 80 && $percentageCal <= 90) {
      // 80 - 90 %tage = return 90% digiCoin
      return $returnDigiCoin = ($perResultDigiCoin / 100) * 90;
    } else if ($percentageCal >= 70 && $percentageCal <= 80) {
      // 70 - 80 %tage = return 80% digiCoin
      return $returnDigiCoin = ($perResultDigiCoin / 100) * 80;
    } else if ($percentageCal >= 60 && $percentageCal <= 70) {
      // 60 - 70 %tage = return 70% digiCoin
      return $returnDigiCoin = ($perResultDigiCoin / 100) * 70;
    } else if ($percentageCal >= 50 && $percentageCal <= 60) {
      // 50 - 60 %tage = return 60% digiCoin
      return $returnDigiCoin = ($perResultDigiCoin / 100) * 60;
    } else if ($percentageCal >= 40 && $percentageCal <= 50) {
      // 40 - 50 %tage = return 50% digiCoin
      return $returnDigiCoin = ($perResultDigiCoin / 100) * 50;
    } else if ($percentageCal >= 33 && $percentageCal <= 40) {
      // 33 - 40 %tage = return 40% digiCoin
      return $returnDigiCoin = ($perResultDigiCoin / 100) * 40;
    } else {
      return $returnDigiCoin;
    }
  }

  // formula for average percentage of result of a exam / class
  public function avgResultOfExam($examId, $schoolUniqueCode)
  {

    $s = $this->db->query("SELECT SUM(marks) as obtainedMarks, COUNT(1) as count FROM " . Table::resultTable . " WHERE exam_id = '$examId' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();

    if (!empty($s)) {
      $studentMarksObtained = $s[0]['obtainedMarks'];
      $totalCountOfStudents = $s[0]['count'];
    }


    $e = $this->db->query("SELECT max_marks FROM " . Table::examTable . " WHERE id = '$examId' AND schoolUniqueCode = '$schoolUniqueCode' LIMIT 1")->result_array();

    if (!empty($e)) {
      $maxMarksOfExam = $e[0]['max_marks'] * $totalCountOfStudents;
    }

    return array('exam_max_marks' => $maxMarksOfExam, 'obtained_max_marks' => $studentMarksObtained);
  }



  // showAll Semester Exam Name
  public function showSemesterExamNames($schoolUniqueCode, $session_table_id)
  {

    $d = $this->db->query("SELECT sem.* , sem.id as semExamNameId FROM " . Table::semExamNameTable . " sem
        WHERE  sem.schoolUniqueCode = '$schoolUniqueCode' AND sem.status = '1' AND session_table_id = '$session_table_id'")->result_array();

    if (!empty($d)) {
      return $d;
    } else {
      return HelperClass::APIresponse(500, 'No Semesters Exams found for this school');
    }
  }


  // showAll Semester Exam
  public function showAllSemesterExam($semExamId, $classId, $sectionId, $subjectId, $schoolUniqueCode, $session_table_id)
  {
    $d = $this->db->query("SELECT 
    sec.id as semExamId, sec.sem_exam_id as semExamNameId, sec.exam_date,sec.exam_day,sec.min_marks,sec.max_marks,
    sem.sem_exam_name, sem.exam_year,
    c.className,se.sectionName,sub.subjectName
     FROM " . Table::secExamTable . " sec
    JOIN " . Table::classTable . " c ON c.id = sec.class_id
    JOIN " . Table::sectionTable . " se ON se.id = sec.section_id
    JOIN " . Table::subjectTable . " sub ON sub.id = sec.subject_id
    JOIN " . Table::semExamNameTable . " sem ON sem.id = sec.sem_exam_id
      WHERE sec.class_id = '$classId' 
      AND sec.section_id = '$sectionId' 
      AND sec.subject_id = '$subjectId' 
      AND sec.sem_exam_id = '$semExamId' 
      AND sec.schoolUniqueCode = '$schoolUniqueCode' 
      AND sec.status = '1'
      AND sec.session_table_id = '$session_table_id'
      ")->result_array();

    if (!empty($d)) {
      return $d;
    } else {
      return HelperClass::APIresponse(500, 'No Semester Exams found for this class & subject');
    }
  }



  // add semester exam result

  public function addSemesterExamResult($studentId, $marks, $examId, $semExamNameId, $schoolUniqueCode, $session_table_id)
  {

    $e = $this->db->query($s = "SELECT min_marks,max_marks,class_id,section_id,subject_id FROM " . Table::secExamTable . " WHERE 
      id = '$examId' AND schoolUniqueCode = '$schoolUniqueCode' AND session_table_id = '$session_table_id' AND sem_exam_id = '$semExamNameId'  LIMIT 1")->result_array()[0];

    if (!empty($e)) {
      if ($marks >= $e['min_marks'] && $marks <= $e['max_marks']) {
        $resultStatus = '1'; // pass
      } else {
        $resultStatus = '2'; // fail
      }
    }


    $d = $this->db->query($sql = "SELECT * FROM " . Table::semExamResults . " WHERE sem_id = '$semExamNameId' AND sec_exam_id = '$examId' AND student_id = '$studentId' AND session_table_id = '$session_table_id' AND schoolUniqueCode = '$schoolUniqueCode' LIMIT 1")->result_array();

    if (!empty($d)) {
      return HelperClass::APIresponse(500, 'Result For This Student is Already Submited.' . $d[0]['student_id']);
    }

    $insertArr = [
      "schoolUniqueCode" => $schoolUniqueCode,
      "sem_id" => $semExamNameId,
      "sec_exam_id" => $examId,
      "class_id" => $e['class_id'],
      "section_id" => $e['section_id'],
      "subject_id" => $e['subject_id'],
      "student_id" => $studentId,
      "marks" => $marks,
      "result_status" => $resultStatus,
      "session_table_id" => $session_table_id
    ];


    $insertId = $this->CrudModel->insert(Table::semExamResults, $insertArr);
    return ($insertId) ? true : false;
  }


  public function studentNamesViaClassAndSectionId($class_id, $section_id, $schoolUniqueCode)
  {

    return $e = $this->db->query($s = "SELECT id, name FROM " . Table::studentTable . " WHERE 
      class_id = '$class_id' AND section_id = '$section_id' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();
  }
}
