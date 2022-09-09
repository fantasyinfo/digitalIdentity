<?php

class APIModel extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
    $this->load->model('CrudModel');
  }

  // login
  public function login($schoolUniqueCode, $id, $password, $type)
  {
    $dir = base_url() . HelperClass::uploadImgDir;
    if ($type == 'Teacher') {
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
        $this->db->query("UPDATE " . Table::teacherTable . " SET auth_token = '$authToken' WHERE id = {$userData[0]['teacherId']} AND user_id = '$id' AND schoolUniqueCode = '$schoolUniqueCode'");
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
        $responseData["schoolUniqueCode"] = @$schoolUniqueCode;

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
      //
    } else if ($type == 'Principal') {
      //
    }else if($type == 'Parent')
    {
      $mobile = $id;
      $sql = "SELECT t.*, CONCAT('$dir',t.image) as image,c.className,ss.sectionName FROM " . Table::studentTable . " t 
      LEFT JOIN " . Table::classTable . " c ON c.id =  t.class_id
      LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  t.section_id
      WHERE t.schoolUniqueCode = '$schoolUniqueCode' AND t.mobile = '$mobile' AND t.password = '$password' AND t.status = '1'";
      $userData = $this->db->query($sql)->result_array();
      if(!empty($userData))
      {
        $authToken = HelperClass::generateRandomToken();
        $this->db->query("UPDATE " . Table::studentTable . " SET auth_token = '$authToken' WHERE id = {$userData[0]['id']} AND schoolUniqueCode = '$schoolUniqueCode'");

        $totalStudentsCount = count($userData);
        $responseData = [];
        $responseData['studentsData'] = [];
        for($i = 0 ; $i < $totalStudentsCount; $i++)
        {
          $subArr = [];
          $subArr["userId"] = @$userData[0]["user_id"];
          $subArr["name"] = @$userData[0]["name"];
          $subArr["image"] = @$userData[0]["image"];
          $subArr["user_id"] = @$userData[0]["user_id"];
          $subArr["className"] = @$userData[0]["className"];
          $subArr["sectionName"] = @$userData[0]["sectionName"];
          $subArr["authToken"] = @$authToken;
          $subArr["userType"] = @$type;
          $subArr["schoolUniqueCode"] = @$schoolUniqueCode;
          array_push($responseData["studentsData"], $subArr);
        }
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
  public function showAllStudentForAttendence($type, $class, $section, $schoolUniqueCode)
  {

    if ($type == 'Teacher') {
      $dir = base_url() . HelperClass::uploadImgDir;
      $sql = "SELECT stu.name,CONCAT('$dir',stu.image) as image,stu.id, c.id as classId, c.className,ss.sectionName , ss.id as sectionId
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

  // save attendence
  public function submitAttendence($stu_id, $stu_class, $stu_section, $login_user_id, $login_user_type, $attendenceStatus, $schoolUniqueCode)
  {
    $currentDate = date_create()->format('Y-m-d');
    if ($login_user_type == 'Teacher') {


      $d = $this->db->query("SELECT stu_id FROM " . Table::attendenceTable . " WHERE att_date = '$currentDate' AND stu_id = '$stu_id' AND schoolUniqueCode = '$schoolUniqueCode' LIMIT 1")->result_array();

      if (!empty($d)) {
        return HelperClass::APIresponse(500, 'Attendence Already Submited for this Student id today.' . $d[0]['stu_id']);
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
          $insertDigiCoin = $this->insertDigiCoin($stu_id, HelperClass::userTypeR['1'], HelperClass::actionType['Attendence'], $digiCoinF, $schoolUniqueCode);
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
    $dir = base_url() . HelperClass::uploadImgDir;
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

  // save departure
  public function submitDeparture($stu_id, $attendenceId, $stu_class, $stu_section, $login_user_id, $login_user_type, $departureStatus, $schoolUniqueCode)
  {
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
      ];

      $insertId = $this->CrudModel->insert(Table::departureTable, $insertArr);
      if (!empty($insertId)) {


        // check digiCoin is set for this departure time for students
        $digiCoinF =  $this->checkIsDigiCoinIsSet(HelperClass::actionType['Departure'], HelperClass::userType['Student'], $schoolUniqueCode);

        if ($digiCoinF) {
          // insert the digicoin
          $insertDigiCoin = $this->insertDigiCoin($stu_id, HelperClass::userTypeR['1'], HelperClass::actionType['Departure'], $digiCoinF, $schoolUniqueCode);
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
    $dir = base_url() . HelperClass::uploadImgDir;
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
  public function showStudentDetails($classId, $sectionId, $qrCode, $studentId, $schoolUniqueCode)
  {
    $currentDate = date_create()->format('Y-m-d');
    $dir = base_url() . HelperClass::uploadImgDir;
    $condition = " AND s.schoolUniqueCode = '$schoolUniqueCode' ";
    if (!empty($classId) && !empty($sectionId) && !empty($studentId)) {
      $condition .= " AND s.id = '{$studentId}' AND ss.id = '{$sectionId}' AND c.id = '{$classId}' ";
    } else {
      $condition .= " AND (q.qrcodeUrl = '$qrCode' OR q.uniqueValue = '$qrCode') ";
    }

    $sql = "SELECT s.*, 
    CONCAT('$dir',s.image) as image,
    if(s.status = '1', 'Active','InActive')as status,
    c.className,
    ss.sectionName,
    st.stateName,
    ct.cityName,
    q.uniqueValue, q.qrcodeUrl,
    examt.exam_name, examt.date_of_exam, examt.min_marks,examt.max_marks,
    CONCAT(rt.marks ,' Out of ', examt.max_marks) as examMarks,
    rt.result_date,rt.remarks,rt.marks,if(rt.status = '1','Pass', 'Fail') as resultStatus,
    subt.subjectName
    FROM " . Table::studentTable . " s
    LEFT JOIN " . Table::qrcodeTable . " q ON q.uniqueValue = s.user_id
    LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
    LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
    LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
    LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
    LEFT JOIN " . Table::resultTable . " rt ON rt.student_id = s.id
    LEFT JOIN " . Table::examTable . " examt ON rt.exam_id = examt.id
    LEFT JOIN " . Table::subjectTable . " subt ON subt.id = examt.subject_id
    WHERE s.status = '1' $condition 
    ORDER BY s.id DESC LIMIT 1";

    $d = $this->db->query($sql)->result_array();

    if (!empty($d)) {
      $returnArr = [];
      $returnArr['studentId'] = @$d[0]['id'];
      $returnArr['u_qr_id'] = @$d[0]['u_qr_id'];
      $returnArr['uniqueValue'] = @$d[0]['uniqueValue'];
      $returnArr['qrcodeUrl'] = @$d[0]['qrcodeUrl'];
      $returnArr['name'] = @$d[0]['name'];
      $returnArr['user_id'] = @$d[0]['user_id'];
      $returnArr['class_id'] = @$d[0]['className'];
      $returnArr['section_id'] = @$d[0]['sectionName'];
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

      $returnArr['resultData'] = [];
      $totalData = count($d);
      for ($i = 0; $i < $totalData; $i++) {
        $subArr = [];
        $subArr['exam_name'] = @$d[$i]['exam_name'];
        $subArr['exam_date'] = @$d[$i]['date_of_exam'];
        $subArr['subjectName'] = @$d[$i]['subjectName'];
        $subArr['max_marks'] = @$d[$i]['max_marks'];
        $subArr['min_marks'] = @$d[$i]['min_marks'];
        $subArr['exam_name'] = @$d[$i]['exam_name'];
        $subArr['result_date'] = @$d[$i]['result_date'];
        $subArr['marksRecived'] = @$d[$i]['marks'];
        $subArr['examMarks'] = @$d[$i]['examMarks'];
        $subArr['resultStatus'] = @$d[$i]['resultStatus'];
        $subArr['remarks'] = @$d[$i]['remarks'];
        array_push($returnArr['resultData'], $subArr);
      }

      return $returnArr;
    } else {
      return HelperClass::APIresponse(500, 'No Data Found for this Student.');
    }
  }


  // add Exam
  public function addExam($loginUserId, $loginuserType, $classId, $sectionId, $subjectId, $examDate, $examName, $maxMarks, $minMarks, $schoolUniqueCode)
  {
    //$currentDate = date_create()->format('Y-m-d');
    if ($loginuserType == 'Teacher') {

      $insertArr = [
        "schoolUniqueCode" => $schoolUniqueCode,
        "class_id" => $classId,
        "section_id" => $sectionId,
        "subject_id" => $subjectId,
        "exam_name" => $examName . " Date: " . $examDate . " Exam Id: " . rand(0000, 9999),
        "date_of_exam" => $examDate,
        "max_marks" => $maxMarks,
        "min_marks" => $minMarks,
        "login_user_id" => $loginUserId,
        "login_user_type" => $loginuserType,
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
        "exam_name" => $examName . " Date: " . $examDate . " Exam Id: " . rand(0000, 9999),
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
    $dir = base_url() . HelperClass::uploadImgDir;
    $condition = " AND e.schoolUniqueCode = '$schoolUniqueCode' ";
    if (!empty($subjectId)) {
      $condition .= " AND e.subject_id = $subjectId ";
    }

    $d = $this->db->query("SELECT e.id as examId,e.exam_name,e.max_marks,e.min_marks,e.date_of_exam,ct.className,st.sectionName,subt.subjectName FROM " . Table::examTable . " e
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
  public function addHomeWork($loginUserId, $loginuserType, $classId, $sectionId, $subjectId, $homeWorkNote, $homeWorkDate, $homeWorkDueDate, $schoolUniqueCode)
  {
    //$currentDate = date_create()->format('Y-m-d');
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
      ];

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
  public function showAllHomeWorks($classId, $sectionId, $schoolUniqueCode, $subjectId = '')
  {
    $currentDate = date_create()->format('Y-m-d');
    $dir = base_url() . HelperClass::uploadImgDir;
    $condition = " AND e.schoolUniqueCode = '$schoolUniqueCode' ";
    if (!empty($subjectId)) {
      $condition .= " AND e.subject_id = $subjectId ";
    }

    $d = $this->db->query("SELECT e.id as homeWorkId,e.home_work_note,e.home_work_date,e.home_work_finish_date,ct.className,st.sectionName,subt.subjectName FROM " . Table::homeWorkTable . " e
        INNER JOIN " . Table::classTable . " ct ON e.class_id = ct.id 
        INNER JOIN " . Table::sectionTable . " st ON e.section_id = st.id 
        INNER JOIN " . Table::subjectTable . " subt ON e.subject_id = subt.id 
        WHERE e.class_id = '$classId' AND e.section_id = '$sectionId' $condition AND e.status = '1'")->result_array();

    if (!empty($d)) {
      return $d;
    } else {
      return HelperClass::APIresponse(500, 'No Home Work found for this class');
    }
  }



  // showSingleHomeWork
  public function showSingleHomeWork($classId, $sectionId,  $homeWorkId, $schoolUniqueCode)
  {
    $currentDate = date_create()->format('Y-m-d');
    $dir = base_url() . HelperClass::uploadImgDir;
    $condition = " AND e.schoolUniqueCode = '$schoolUniqueCode' ";

    $d = $this->db->query("SELECT e.id as homeWorkId,e.home_work_note,e.home_work_date,e.home_work_finish_date,ct.className,st.sectionName,subt.subjectName FROM " . Table::homeWorkTable . " e
      INNER JOIN " . Table::classTable . " ct ON e.class_id = ct.id 
      INNER JOIN " . Table::sectionTable . " st ON e.section_id = st.id 
      INNER JOIN " . Table::subjectTable . " subt ON e.subject_id = subt.id 
      WHERE e.class_id = '$classId' AND e.section_id = '$sectionId' $condition AND e.id = '$homeWorkId' AND e.status = '1'")->result_array();

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
    $dir = base_url() . HelperClass::uploadImgDir;
    $condition = " AND e.schoolUniqueCode = '$schoolUniqueCode' ";

    $d = $this->db->query("SELECT e.id as examId,e.exam_name,e.max_marks,e.min_marks,e.date_of_exam,ct.className,st.sectionName,subt.subjectName FROM " . Table::examTable . " e
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
  public function addResult($loginUserId, $loginuserType, $resultDate, $studentId, $marks, $reMarks, $examId, $schoolUniqueCode)
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



      $d = $this->db->query("SELECT student_id,exam_id,resultStatus FROM " . Table::resultTable . " WHERE student_id = '$studentId' AND exam_id = '$examId' AND schoolUniqueCode = '$schoolUniqueCode' LIMIT 1")->result_array();

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
      ];


      $insertId = $this->CrudModel->insert(Table::resultTable, $insertArr);
      if (!empty($insertId)) {



        if ($resultStatus == '1' && !empty($e)) {
          // check digiCoin is set for this result time for students
          $perResultDigiCoin =  $this->checkIsDigiCoinIsSet(HelperClass::actionType['Result'], HelperClass::userType['Student'], $schoolUniqueCode);

          // calculate digiCoin value as per marks of student

          $digiCoinToInsert =   $this->calculateStudentResultDigiCoin($perResultDigiCoin, $marks, $e[0]['max_marks']);



          if ($digiCoinToInsert) {
            // insert the digicoin
            $insertDigiCoin = $this->insertDigiCoin($studentId, HelperClass::userTypeR['1'], HelperClass::actionType['Result'], $digiCoinToInsert, $schoolUniqueCode);
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
  public function getAlreadyDigiCoinCount($user_id, $user_type_id, $user_type_key,$schoolUniqueCode)
  {
    
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
      $dir = base_url() . HelperClass::uploadImgDir;
      $userTypeId = HelperClass::userType[$user_type];
      $d = $this->db->query("SELECT gift_name,CONCAT('$dir',gift_image) as image,redeem_digiCoins FROM " . Table::giftTable . " WHERE user_type = '$userTypeId' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();
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
        
        $dir = base_url() . HelperClass::uploadImgDir;
        $d = $this->db->query("SELECT id as gift_id, gift_name,CONCAT('$dir',gift_image) as image,redeem_digiCoins FROM " . Table::giftTable . " WHERE user_type = '$userType' AND redeem_digiCoins <= '{$totalDigiCoinInWallet['balanceDigiCoin']}' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();
      }

      if (!empty($d)) {
        return $d;
      } else {
        return 0;
      }
    } else if ($loginuserType == 'Student') {
      //
    }
  }


  // redeemGifts
  public function redeemGifts($giftId, $loginUserId, $loginuserType, $schoolUniqueCode)
  {
    if ($loginuserType == 'Teacher') {
      $giftValueDigiCoin = 0;
      // check sum of there digicoins first
      $userType = HelperClass::userType[$loginuserType];

    
      $giftValue = $this->db->query("SELECT redeem_digiCoins FROM " . Table::giftTable . " WHERE user_type = '$userType' AND schoolUniqueCode = '$schoolUniqueCode' and id = '$giftId'")->result_array();

      if (!empty($giftValue)) {
        $giftValueDigiCoin = $giftValue[0]['redeem_digiCoins'];
      }else
      {
        return HelperClass::APIresponse(500, 'This Gift Id\'s Not found, Please Check Another Gift ' . $this->db->last_query());
      }


      // total digiCoins in wallet
      $totalDigiCoinInWallet = $this->getAlreadyDigiCoinCount($loginUserId, $userType,$loginuserType, $schoolUniqueCode);

      if ($totalDigiCoinInWallet['balanceDigiCoin'] >= $giftValueDigiCoin) {
        $insertRedeem = $this->db->query("INSERT INTO " . Table::giftRedeemTable . " (schoolUniqueCode,login_user_id,login_user_type,gift_id,digiCoin_used) VALUES ('$schoolUniqueCode','$loginUserId','$userType','$giftId','$giftValueDigiCoin')");
      }else
      {
        return HelperClass::APIresponse(500, 'This Gifts Not Redeem, Because Your DigiCoins are too low. Gift Id is ' . $giftId . ' if you have redeem 1 or more gifts then check all other is redeem successfully.');
      }

      if (!empty($insertRedeem)) {
        return true;
      } else {
        return false;
      }
    } else if ($loginuserType == 'Student') {
      //
    }
  }


// gift redeem status
  public function giftRedeemStatus($loginUserId, $loginuserType, $schoolUniqueCode)
  {
    $dir = base_url() . HelperClass::uploadImgDir;
      if ($loginuserType == 'Teacher') {
        $userType = HelperClass::userType[$loginuserType];

        $sql = "SELECT grt.*, gt.id as giftId, gt.gift_name, CONCAT('$dir',gt.gift_image) as image FROM " . Table::giftRedeemTable . " grt
        LEFT JOIN ".Table::giftTable." gt ON grt.gift_id = gt.id AND gt.user_type = '$userType'
         WHERE grt.login_user_id = '$loginUserId' AND grt.login_user_type = '$userType' AND grt.schoolUniqueCode = '$schoolUniqueCode'";

        $allGiftsRedeemData = $this->db->query($sql)->result_array();
        if($allGiftsRedeemData){

          $totalC = count($allGiftsRedeemData);
          $sendArr = [];
          $sendArr['giftStatus'] = [];
          for($i=0; $i< $totalC; $i++)
          {
            $subArr = [];
            $subArr['giftRedeemId'] = $allGiftsRedeemData[$i]['id'];
            $subArr['giftId'] = $allGiftsRedeemData[$i]['giftId'];
            $subArr['giftName'] = $allGiftsRedeemData[$i]['gift_name'];
            $subArr['giftImage'] = $allGiftsRedeemData[$i]['image'];
            $subArr['digiCoinUsed'] = $allGiftsRedeemData[$i]['digiCoin_used'];
            $subArr['redeemStatus'] = HelperClass::giftStatus[$allGiftsRedeemData[$i]['status']];
            $subArr['redeemDate'] = date('d-m-Y h:i:A', strtotime($allGiftsRedeemData[$i]['created_at']));
            array_push($sendArr['giftStatus'],$subArr);
          }
          return $sendArr;
        }else
        {
          return HelperClass::APIresponse(500, 'There is no gift redeem found for this user.');
        }

      } else if ($loginuserType == 'Student') {
        //
      }
  }

  // wallet History
  public function walletHistory($loginUserId, $loginuserType, $schoolUniqueCode)
  {
    if ($loginuserType == 'Teacher') {
      $userType = HelperClass::userType[$loginuserType];
      $d = $this->db->query("
    SELECT id as tId, CASE WHEN for_what = '1' THEN 'Attendence'  WHEN for_what = '2' THEN 'Departure' WHEN for_what = '3' THEN 'Result' END as for_what,
    digiCoin, 'Earning' as tStatus, created_at as whatDate FROM " . Table::getDigiCoinTable . " gdc WHERE gdc.user_type = '$loginuserType' AND gdc.user_id = '$loginUserId' AND gdc.schoolUniqueCode = '$schoolUniqueCode' 
    UNION ALL
    SELECT grt.id as tId, gift.gift_name as for_what, grt.digiCoin_used as digiCoin, 'Redeem' as tStatus, grt.created_at as whatDate FROM " . Table::giftRedeemTable . " grt 
    LEFT JOIN " . Table::giftTable . " ON gift.id = grt.gift_id
    WHERE grt.login_user_type = '$userType' AND grt.login_user_id = '$loginUserId' AND grt.schoolUniqueCode = '$schoolUniqueCode' ")->result_array();
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
  public function leaderBoard($loginuserType, $schoolUniqueCode)
  {
    $dir = base_url().HelperClass::uploadImgDir;
    if ($loginuserType == 'Teacher') {
      $tableName = Table::teacherTable;
      $d = $this->db->query("SELECT SUM(gdc.digiCoin) as totalDigiCoinsEarn, gdc.user_id,gdc.user_type, (SELECT name FROM students WHERE id = gdc.user_id) as userName FROM " . Table::getDigiCoinTable . " gdc WHERE gdc.schoolUniqueCode = '$schoolUniqueCode' AND user_type = '$loginuserType' AND MONTH(gdc.created_at)=MONTH(now()) AND YEAR(gdc.created_at)=YEAR(now()) GROUP BY gdc.user_id ORDER BY SUM(gdc.digiCoin) DESC")->result_array();
      
      if (!empty($d)) {

        $sendArr = [];
        $totalCount = count($d);
        $a = 1;
        for ($i = 0; $i < $totalCount; $i++) {
         $userDetails =  $this->db->query("SELECT s.name,s.user_id,s.image,c.className,sc.sectionName FROM ".$tableName." s LEFT JOIN ".Table::classTable." c ON c.id = s.class_id LEFT JOIN ".Table::sectionTable." sc ON sc.id = s.section_id WHERE s.id = '{$d[$i]['user_id']}'")->result_array();

          $subArr = [];
          $subArr['position'] = $a++;
          $subArr['userType'] = $d[$i]['user_type'];
          $subArr['totalDigiCoinsEarn'] = $d[$i]['totalDigiCoinsEarn'];
          $subArr['name'] = @$userDetails[0]['name'];
          $subArr['uniqueId'] = @$userDetails[0]['user_id'];
          $subArr['className'] = @$userDetails[0]['className'];
          $subArr['sectionName'] = @$userDetails[0]['sectionName'];
          $subArr['image'] = $dir.@$userDetails[0]['image'];
          array_push($sendArr, $subArr);
        }
        
        return $sendArr;
      } else {
        return 0;
      }
    }else if($loginuserType == 'Student')
    {
      $tableName = Table::studentTable;
      $d = $this->db->query("SELECT SUM(gdc.digiCoin) as totalDigiCoinsEarn, gdc.user_id,gdc.user_type, (SELECT name FROM students WHERE id = gdc.user_id) as userName FROM " . Table::getDigiCoinTable . " gdc WHERE gdc.schoolUniqueCode = '$schoolUniqueCode' AND user_type = '$loginuserType' AND MONTH(gdc.created_at)=MONTH(now()) AND YEAR(gdc.created_at)=YEAR(now()) GROUP BY gdc.user_id ORDER BY SUM(gdc.digiCoin) DESC")->result_array();
      
      if (!empty($d)) {

        $sendArr = [];
        $totalCount = count($d);
        $a = 1;
        for ($i = 0; $i < $totalCount; $i++) {
         $userDetails =  $this->db->query("SELECT s.name,s.user_id,s.image,c.className,sc.sectionName FROM ".$tableName." s LEFT JOIN ".Table::classTable." c ON c.id = s.class_id LEFT JOIN ".Table::sectionTable." sc ON sc.id = s.section_id WHERE s.id = '{$d[$i]['user_id']}'")->result_array();

          $subArr = [];
          $subArr['position'] = $a++;
          $subArr['userType'] = $d[$i]['user_type'];
          $subArr['totalDigiCoinsEarn'] = $d[$i]['totalDigiCoinsEarn'];
          $subArr['name'] = $userDetails[0]['name'];
          $subArr['uniqueId'] = $userDetails[0]['user_id'];
          $subArr['className'] = $userDetails[0]['className'];
          $subArr['sectionName'] = $userDetails[0]['sectionName'];
          $subArr['image'] = $dir.$userDetails[0]['image'];
          array_push($sendArr, $subArr);
        }
        
        return $sendArr;
      } else {
        return 0;
      }
    }
  }


  // leaderBoard
  public function visitorEntry($visit_date,$visit_time,$visitor_name,$person_to_meet,$purpose_to_meet,$visitor_mobile_no,$file,$schoolUniqueCode)
  {
      if(!empty($file))
      {
        $fileName = $this->CrudModel->uploadImg($file,'VISITOR-');
      }else
      {
        $fileName = '';
      }

      $d = $this->db->query("INSERT INTO ".Table::visitorTable." (schoolUniqueCode,visit_date,visit_time,visitor_name,person_to_meet,purpose_to_meet,visitor_mobile_no,visitor_image) VALUES ('$schoolUniqueCode','$visit_date','$visit_time','$visitor_name','$person_to_meet','$purpose_to_meet','$visitor_mobile_no','$fileName')");
      // echo $this->db->last_query();
      
      if (($d)) {
        return true;
      } else {
        return false;
      }
    
  }
  




  // check if the digiCoin is set 
  public function checkIsDigiCoinIsSet($for_what, $user_type, $schoolUniqueCode)
  {
    $d = $this->db->query("SELECT digiCoin FROM " . Table::setDigiCoinTable . " WHERE user_type = '$user_type' AND for_what = '$for_what' AND schoolUniqueCode = '$schoolUniqueCode' AND status ='1' LIMIT 1")->result_array();
    if (!empty($d)) {
      return $d[0]['digiCoin'];
    } else {
      return false;
    }
  }

  // insert digiCoin
  public function insertDigiCoin($user_id, $user_type, $for_what, $digiCoin, $schoolUniqueCode)
  {
    $d = $this->db->query("INSERT INTO " . Table::getDigiCoinTable . " (schoolUniqueCode,user_type,user_id,for_what,digiCoin) VALUES ('$schoolUniqueCode','$user_type','$user_id',$for_what,'$digiCoin')");
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
}
