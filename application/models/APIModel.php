<?php

class APIModel extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
    $this->load->model('CrudModel');
  }

  // login
  public function login($schoolUniqueCode,$id, $password, $type)
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
        $responseData["totalStudentCount"] = ($this->countStudentViaClassAndSection($userData[0]["class_id"],$userData[0]["section_id"])) ? $this->countStudentViaClassAndSection($userData[0]["class_id"],$userData[0]["section_id"]) : null;

        // subjects of teachers
        $responseData["teacherSubjects"] = [];
        $subjectsArr = json_decode(@$userData[0]['subject_ids'],TRUE);
        $totalSub = count(@$subjectsArr);
        if(isset($totalSub))
        {
          for($i=0;$i<$totalSub;$i++)
          {
            $subArr = [];
            $sub = $this->db->query("SELECT id,subjectName from ".Table::subjectTable." WHERE id = '{$subjectsArr[$i]}' AND status = '1'")->result_array();
            if(isset($sub))
            {
              $subArr['subjectId'] = $sub[0]['id'];
              $subArr['subjectName'] = $sub[0]['subjectName'];
            }
            array_push($responseData["teacherSubjects"],$subArr);
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
  public function showAllStudentForAttendence($type, $class, $section,$schoolUniqueCode)
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
        return HelperClass::APIresponse(500, 'Students Not Found. For This Class. '. $class .' - ' .$section);
      }
    } else if ($type == 'Staff') {
      //
    } else if ($type == 'Principal') {
      //
    }
  }

  public function countStudentViaClassAndSection($class_id,$section_id)
  {
   $totalStudents =  $this->db->query("SELECT count(1) as count FROM ". Table::studentTable ." WHERE class_id = '$class_id' AND section_id = '$section_id' AND status = '1'")->result_array();
    if (!empty($totalStudents)) {
      return $totalStudents[0]['count'];
    } else {
      return false;
    }
  }

  // save attendence
  public function submitAttendence($stu_id, $stu_class, $stu_section, $login_user_id, $login_user_type, $attendenceStatus,$schoolUniqueCode)
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
  public function showSubmitAttendenceData($className, $sectionName,$schoolUniqueCode)
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
  public function submitDeparture($stu_id, $attendenceId, $stu_class, $stu_section, $login_user_id, $login_user_type, $departureStatus,$schoolUniqueCode)
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
  public function showSubmitDepartureData($className, $sectionName,$schoolUniqueCode)
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
  public function showStudentDetails($classId,$sectionId,$qrCode,$studentId,$schoolUniqueCode)
  {
    $currentDate = date_create()->format('Y-m-d');
    $dir = base_url() . HelperClass::uploadImgDir;
    $condition = " AND s.schoolUniqueCode = '$schoolUniqueCode' ";
    if(!empty($classId) && !empty($sectionId) && !empty($studentId))
    {
      $condition.=" AND s.id = '{$studentId}' AND ss.id = '{$sectionId}' AND c.id = '{$classId}' ";
    }else
    {
      $condition.= " AND q.qrcodeUrl = '$qrCode' ";
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
    FROM " .Table::studentTable." s
    LEFT JOIN ".Table::qrcodeTable." q ON q.uniqueValue = s.user_id
    LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
    LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
    LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
    LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
    LEFT JOIN ".Table::resultTable." rt ON rt.student_id = s.id
    LEFT JOIN ".Table::examTable." examt ON rt.exam_id = examt.id
    LEFT JOIN ".Table::subjectTable." subt ON subt.id = examt.subject_id
    WHERE s.status = '1' $condition 
    ORDER BY s.id DESC";

    $d = $this->db->query($sql)->result_array();

    if (!empty($d)) {
      $returnArr = [];
      $returnArr['studentId'] = @$d[0]['id'];
      $returnArr['u_qr_id'] =@$d[0]['u_qr_id'];
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
      for($i=0;$i < $totalData; $i++)
      {
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
        array_push($returnArr['resultData'],$subArr);
      }

      return $returnArr;
    } else {
      return HelperClass::APIresponse(500, 'No Data Found for this Student.');
    }
  }


  // add Exam
  public function addExam($loginUserId, $loginuserType, $classId, $sectionId, $subjectId, $examDate, $examName, $maxMarks, $minMarks,$schoolUniqueCode)
  {
    //$currentDate = date_create()->format('Y-m-d');
    if ($loginuserType == 'Teacher') {

      $insertArr = [
        "schoolUniqueCode" => $schoolUniqueCode,
        "class_id" => $classId,
        "section_id" => $sectionId,
        "subject_id" => $subjectId,
        "exam_name" => $examName . " Date: " . $examDate . " Exam Id: " . rand(0000,9999),
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
        "exam_name" => $examName . " Date: " . $examDate . " Exam Id: " . rand(0000,9999),
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
  public function showAllExam($classId, $sectionId, $schoolUniqueCode,$subjectId = '')
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
   public function addResult($loginUserId,$loginuserType,$resultDate,$studentId,$marks,$reMarks,$resultStatus,$examId,$schoolUniqueCode)
   {
     $currentDate = date_create()->format('Y-m-d');
     if ($loginuserType == 'Teacher') {
 
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
