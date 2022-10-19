<?php

class StudentModel extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
    $this->load->model('CrudModel');
   
	}

    public function saveStudent(array $post,array $files = [])
    {
       

      $insertArr = [];
      $insertArr['schoolUniqueCode'] = $_SESSION['schoolUniqueCode'];
      $insertArr['u_qr_id'] = '';
      $insertArr['name'] = $post['name'];
      $insertArr['user_id'] = $this->CrudModel->getUserId(Table::studentTable);
      $insertArr['class_id'] = $post['class'];
      $insertArr['section_id'] = $post['section'];
      $insertArr['roll_no'] = $post['roll_no'];
      $insertArr['gender'] = $post['gender'];
      $insertArr['mother_name'] = $post['mother'];
      $insertArr['father_name'] = $post['father'];
      $insertArr['mobile'] = $post['mobile'];
      $insertArr['email'] = $post['email'];
      $insertArr['dob'] = $post['dob'];
      $insertArr['address'] = $post['address'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['city_id'] = $post['city'];
      $insertArr['pincode'] = $post['pincode'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['sr_number'] = $post['sr_number'];
      $insertArr['cast_category'] = $post['cast_category'];
      $insertArr['admission_no'] = $post['admission_no'];
      $insertArr['date_of_admission'] = $post['date_of_admission'];
      $insertArr['image'] = '';



      // check if this mobile number is also using any of the other child of this parent then use the same password
      $alreadyMobile = $this->db->query("SELECT password FROM ".Table::studentTable." WHERE mobile = '{$insertArr['mobile']}'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ")->result_array();

      if(!empty($alreadyMobile[0]))
      {
        $insertArr['password'] = $alreadyMobile[0]['password'];
      }else
      {
        $insertArr['password'] = HelperClass::makeRandomPassword();
      }



    


      // check if the student is already registerd with us
      $already = $this->db->query("SELECT * FROM ".Table::studentTable." WHERE name = '{$insertArr['name']}' AND mobile = '{$insertArr['mobile']}' AND mother_name = '{$insertArr['mother_name']}' AND father_name = '{$insertArr['father_name']}' AND dob = '{$insertArr['dob']}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();


      if(!empty($already[0]))
      {
        return false;
      }


      $fileName = "";
      if(!empty($files['image']))
      {
        // upload files and get image path
        $fileName = $this->CrudModel->uploadImg($files,$insertArr['user_id'],HelperClass::studentImagePath);
        $insertArr['image'] = $fileName;
      }
    
        $insertId = $this->CrudModel->insert(Table::studentTable,$insertArr);
        if($insertId)
        {


          // welcome bonus 10 digicoins

          // insert the digicoin
          $this->load->model('APIModel');

          $insertDigiCoin = $this->APIModel->insertDigiCoin($insertId, HelperClass::userTypeR['1'], '0', '10', $_SESSION['schoolUniqueCode'],'0');




          // insert qrcode data
          $qrDataArr = [];
          $qrDataArr['schoolUniqueCode'] = $_SESSION['schoolUniqueCode'];
          $qrDataArr['qrcodeUrl'] = HelperClass::qrcodeUrl . "?stuid=" . HelperClass::schoolPrefix . $insertArr['user_id'];
          $qrDataArr['uniqueValue'] = $insertArr['user_id'];
          $qrDataArr['type'] = HelperClass::userType['Student'];
          $qrDataArr['user_id'] = $insertId;

          $qrInsertId = $this->CrudModel->insert(Table::qrcodeTable,$qrDataArr);
          if($qrInsertId)
          {
            $updateArr['u_qr_id'] = $qrInsertId;
            if($this->CrudModel->update(Table::studentTable,$updateArr,$insertId))
            {


              
              // add history
              $historyArr = [
                'schoolUniqueCode' => $_SESSION['schoolUniqueCode'],
                'student_id' => $insertId,
                'session_table_id' => ($_SESSION['currentSession']) ? $_SESSION['currentSession'] : '0',
                'class_id' => $post['class'],
                'section_id' => $post['section'],
                'fees_due' => '0'

              ];
              if($this->CrudModel->insert(Table::studentHistoryTable,$historyArr))
              {
                return true;
              }else
              {
                echo $this->db->last_query();
                die();
                return false;
              }
              
            }else
            {
              echo $this->db->last_query();
              die();
              return false;
            }
          }else
          {
            echo $this->db->last_query();
            die();
            return false;
          }
         
        }else
        { echo $this->db->last_query();
          die();
            return false;
        }

    }

    public function updateStudent(array $post,array $files = [])
    {
    
      $insertArr = [];
      $insertArr['name'] = $post['name'];
      $insertArr['class_id'] = $post['class'];
      $insertArr['section_id'] = $post['section'];
      $insertArr['roll_no'] = $post['roll_no'];
      $insertArr['gender'] = $post['gender'];
      $insertArr['mother_name'] = $post['mother'];
      $insertArr['father_name'] = $post['father'];
      $insertArr['mobile'] = $post['mobile'];
      $insertArr['email'] = $post['email'];
      $insertArr['dob'] = $post['dob'];
      $insertArr['address'] = $post['address'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['city_id'] = $post['city'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['pincode'] = $post['pincode'];
      $insertArr['sr_number'] = $post['sr_number'];
      $insertArr['cast_category'] = $post['cast_category'];
      $insertArr['admission_no'] = $post['admission_no'];
      $insertArr['date_of_admission'] = $post['date_of_admission'];
      $insertArr['image'] = @$post['image'];
      $insertArr['user_id'] = $post['user_id'];

      $stuId = $post['stuId'];

      $fileName = "";
      if(!empty($files['image']['tmp_name']))
      {
        // upload files and get image path
        $fileName = $this->CrudModel->uploadImg($files,$insertArr['user_id'],HelperClass::studentImagePath);
        $insertArr['image'] = $fileName;
      }
    
        if($this->CrudModel->update(Table::studentTable,$insertArr,$stuId))
        {
            return true;
        }else
        {
            return false;
        }

    }

    public function listStudents($post)
    {
      if(isset($post))
      {
       return $this->CrudModel->showAllStudents(Table::studentTable,$post);
      }
    }

    public function listStudentsPermote($post)
    {
      if(isset($post))
      {
       return $this->CrudModel->listStudentsPermote(Table::studentTable,$post);
      }
    }

    public function singleStudent($id)
    {
      return $this->CrudModel->singleStudent(Table::studentTable,$id);
    }
    public function showStudentProfile()
    {
        if(isset($_GET['stuid']))
        {
          $userId = explode(HelperClass::schoolPrefix,$_GET['stuid']);
          return $this->CrudModel->showStudentProfile(Table::studentTable,@$userId[1]);
        }
    }
    public function viewSingleStudentAllData($id)
    {
      return $this->CrudModel->viewSingleStudentAllData(Table::studentTable,$id);
    }

    public function deleteStudent($id)
    {
      return $this->CrudModel->deleteStudent(Table::studentTable,$id);
    }

    public function allClass($schoolUniqueCode){
      return $this->CrudModel->allClass(Table::classTable,$schoolUniqueCode);
    }
    public function allSection($schoolUniqueCode){
      return $this->CrudModel->allSection(Table::sectionTable,$schoolUniqueCode);
    }
    public function allCity($schoolUniqueCode){
      return $this->CrudModel->allCity(Table::cityTable,$schoolUniqueCode);
    }
    public function allState($schoolUniqueCode){
      return $this->CrudModel->allState(Table::stateTable,$schoolUniqueCode);
    }


    public function showStudentViaClassAndSectionId(array $p)
    {
      if(!empty($p))
      {
        $d = $this->db->query($sql = "SELECT * FROM ".Table::studentTable." WHERE class_id = '{$p['classId']}' AND section_id = '{$p['sectionId']}' AND status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
        $html = '';

        if(!empty($d))
        {
          foreach($d as $dd)
          {
           $html .= "<option value='{$dd['id']}'>{$dd['name']}</option>";
          }
          return json_encode($html);
        }
        return json_encode($sql);
      }
    }
    // for panel
    public function totalFeesDue(array $p)
    {
      if(!empty($p))
      {
        // sessionStrtingFrom
       $school =  $this->db->query("SELECT sst.session_start_year,sst.session_start_month, sst.session_end_year,sst.session_end_month FROM ".Table::schoolSessionTable." sst JOIN ".Table::schoolMasterTable." smt ON smt.current_session = sst.id AND sst.schoolUniqueCode = smt.unique_id WHERE unique_id = '{$_SESSION['schoolUniqueCode']}' LIMIT 1")->result_array();


       if(!empty($school))
       {
        $sessionStartingFrom = HelperClass::monthsForSchool[$school[0]['session_start_month']]; // month
        $sessionStartingYear = $school[0]['session_start_year'];
        $sessionStartDate = 1;

        $sessionStart = date("$sessionStartingYear-$sessionStartingFrom-$sessionStartDate");
      
        $sessionEndingFrom =  HelperClass::monthsForSchool[$school[0]['session_end_month']]; // month
        $sessionEndingYear =  $school[0]['session_end_year'];
        $sessionEndDate = 31;

        $sessionEnd = date("$sessionEndingYear-$sessionEndingFrom-$sessionEndDate");
    
       }else
       {
        return json_encode(array( 'msg' => 'Please Select The School Session Started From & Ending To on Profile Section.', 'status' => 404));
       }


      
       $currentMonth = date('m'); // current month
       $currentYear = date('Y'); // current Year




        // select fees for this class, and total all months till date

        // where month is greater than session start and current year & month is less then session end next year
        $d = $this->db->query($sql = "SELECT * FROM ".Table::feesTable." WHERE class_id = '{$p['classId']}' AND status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
        if(!empty($d))
        {

        
      
          // current month - session start month

          $totalMonthsForFees = intval($currentMonth) - intval($sessionStartingFrom);

          $totalDueFeesTillThisMonth =  (intval($d[0]['fees_amt']) * intval($totalMonthsForFees));
          $tution_fees_amt =  (intval($d[0]['tution_fees_amt']) * intval($totalMonthsForFees));
          $transport_fees =  (intval($d[0]['transport_fees']) * intval($totalMonthsForFees));

          // check total fees submited by this student

            //           MONTH(fee_deposit_date) > MONTH(CURRENT_DATE())
              // AND YEAR(fee_deposit_date) = YEAR(CURRENT_DATE())

          $check = $this->db->query($sql = "SELECT * FROM ".Table::feesForStudentTable." WHERE 
          class_id = '{$p['classId']}' AND 
          section_id = '{$p['sectionId']}' AND
          student_id = '{$p['studentId']}' AND
          status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
           AND created_at BETWEEN '$sessionStart' AND '$sessionEnd'
           ")->result_array();

          if(!empty($check))
          {
            // offer amt
            // deposit amt
            // total_due_balance
            $totalCountCheck = count($check);

            $totalDepsitAmt = 0;
            $totalOfferAmt = 0;
            $totalDueAmt = 0;
            for($i=0; $i<$totalCountCheck;$i++)
            {
              $totalDepsitAmt = intval($totalDepsitAmt) + intval($check[$i]['deposit_amt']);
              $totalOfferAmt = intval($totalOfferAmt) +  intval($check[$i]['offer_amt']);
              $totalDueAmt = intval($totalDueAmt) + intval($check[$i]['total_due_balance']);
            }

          }

          $totalDepositAmtAfterOfferAddedAndDueSubtracted = (intval(@$totalDepsitAmt) + intval(@$totalOfferAmt)) - intval(@$totalDueAmt);
          // check totalDepositFees

         $totalBalance =  (intval($totalDueFeesTillThisMonth) - intval($totalDepositAmtAfterOfferAddedAndDueSubtracted));

         // calcualte total months fees deposit
        $totalMonthFeesDue =  (intval($totalBalance) / intval($d[0]['fees_amt']));
        $totalMonthFeesDeposit =  (intval($totalBalance) % intval($d[0]['fees_amt']));

        $sendArr = [
          'perMonthFeesForThisClass' => $d[0]['fees_amt'],
          'perMonthTutionFees' => $d[0]['tution_fees_amt'],
          'perMonthTransportFees' => $d[0]['transport_fees'],
          'totalFeesTillThisMonth' => $totalDueFeesTillThisMonth,
          'sessionStartedFrom' => $sessionStartingFrom,
          'currentMonth' => $currentMonth,
          'totalDepositAmount' => @$totalDepsitAmt,
          'totalOfferAmt' => @$totalOfferAmt,
          'totalDueAmt' => @$totalDueAmt,
          'totalDueAmountAfterOfferApplyAndDueSubstract' => @$totalDepositAmtAfterOfferAddedAndDueSubtracted,
          'totalBalanceForDeposit' => $totalBalance,
          'totalMonthFeesDue' => floor($totalMonthFeesDue),
          'totalmonthFeesDeposit' => floor($totalMonthFeesDeposit),
          'totalFeesDueMontyPlusTutionPlusTransport' => $totalBalance + $d[0]['tution_fees_amt']+ $d[0]['transport_fees']

        ];

        return json_encode(array( 'data' => $sendArr, 'status' => 200));
        }else
        {
          return json_encode(array( 'msg' => 'There is no fees showing for this class. please insert it first.', 'status' => 404));

        }
        //return json_encode($sql);
      }
    }

    // for api
    public function totalFeesDueToday($schoolUniqueCode,$classId,$sectionId,$studentId)
    {
      $school =  $this->db->query("SELECT sst.session_start_year,sst.session_start_month, sst.session_end_year,sst.session_end_month FROM ".Table::schoolSessionTable." sst JOIN ".Table::schoolMasterTable." smt ON smt.current_session = sst.id AND sst.schoolUniqueCode = smt.unique_id WHERE unique_id = '{$_SESSION['schoolUniqueCode']}' LIMIT 1")->result_array();

      if(!empty($school))
      {
       $sessionStartingFrom = HelperClass::monthsForSchool[$school[0]['session_start_month']]; // month
       $sessionStartingYear = $school[0]['session_start_year'];
       $sessionStartDate = 1;

       $sessionStart = date("$sessionStartingYear-$sessionStartingFrom-$sessionStartDate");
     
       $sessionEndingFrom =  HelperClass::monthsForSchool[$school[0]['session_end_month']]; // month
       $sessionEndingYear =  $school[0]['session_end_year'];
        $sessionEndDate = 31;

        $sessionEnd = date("$sessionEndingYear-$sessionEndingFrom-$sessionEndDate");
    
       }

       $currentMonth = date('m'); // current month
       $currentYear = date('Y'); // current Year


       $d = $this->db->query("SELECT * FROM ".Table::feesTable." WHERE class_id = '$classId' AND status = '1' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();

            //  echo $this->db->last_query(); die();
       if(!empty($d))
       {
        $totalMonthsForFees = intval($currentMonth) - intval($sessionStartingFrom);

        $totalDueFeesTillThisMonth =  (intval($d[0]['fees_amt']) * intval($totalMonthsForFees));

        $check = $this->db->query($sql = "SELECT * FROM ".Table::feesForStudentTable." WHERE 
          class_id = '$classId' AND 
          section_id = '$sectionId' AND
          student_id = '$studentId' AND
          status = '1' AND schoolUniqueCode = '$schoolUniqueCode'
           AND created_at BETWEEN '$sessionStart' AND '$sessionEnd'
           ")->result_array();
       }
       //echo $this->db->last_query(); die();
       if(!empty($check))
          {
            // offer amt
            // deposit amt
            // total_due_balance
            $totalCountCheck = count($check);

            $totalDepsitAmt = 0;
            $totalOfferAmt = 0;
            $totalDueAmt = 0;
            for($i=0; $i<$totalCountCheck;$i++)
            {
              $totalDepsitAmt = intval($totalDepsitAmt) + intval($check[$i]['deposit_amt']);
              $totalOfferAmt = intval($totalOfferAmt) +  intval($check[$i]['offer_amt']);
              $totalDueAmt = intval($totalDueAmt) + intval($check[$i]['total_due_balance']);
            }

          }
   
          $totalDepositAmtAfterOfferAddedAndDueSubtracted = (intval(@$totalDepsitAmt) + intval(@$totalOfferAmt)) - intval(@$totalDueAmt);
          // check totalDepositFees

         $totalBalance =  (intval(@$totalDueFeesTillThisMonth) - intval(@$totalDepositAmtAfterOfferAddedAndDueSubtracted));

         // calcualte total months fees deposit

         if(@$d[0]['fees_amt'] != '0' || $totalBalance != '0')
         {
          $totalMonthFeesDue =  (intval($totalBalance) / intval(@$d[0]['fees_amt']));
         }else
         {
          $totalMonthFeesDue =  intval($totalBalance);
         }
        
        // $totalMonthFeesDeposit =  (intval($totalBalance) % intval(@$d[0]['fees_amt']));


       return $sendArr = [
          'perMonthFeesForThisClass' => ($d[0]['fees_amt']) ? " ₹ " . number_format($d[0]['fees_amt'],2) : " ₹ " .number_format(0,2),
          'totalFeesTillThisMonth' => (@$totalDueFeesTillThisMonth) ? " ₹ " . number_format(@$totalDueFeesTillThisMonth,2) : " ₹ " . number_format(0,2),
          // 'sessionStartedFrom' => $sessionStartingFrom,
          // 'currentMonth' => $currentMonth,
          'totalDepositAmount' => (@$totalDepsitAmt) ? " ₹ " . number_format(@$totalDepsitAmt,2) : " ₹ " . '0',
          'totalOfferAmt' => (@$totalOfferAmt) ? " ₹ " . number_format(@$totalOfferAmt,2) : " ₹ " . '0',
          'totalDueAmt' => (@$totalDueAmt) ? " ₹ " . number_format(@$totalDueAmt,2) : " ₹ " . number_format(0,2),
          // 'totalDueAmountAfterOfferApplyAndDueSubstract' => @$totalDepositAmtAfterOfferAddedAndDueSubtracted,
          'totalBalanceForDeposit' => (@$totalBalance) ? " ₹ " . number_format(@$totalBalance,2) : " ₹ " . number_format(0,2),
          'totalBalanceForDepositToday' => @$totalBalance ,
          'totalMonthFeesDue' => (@$totalMonthFeesDue) ? floor(@$totalMonthFeesDue) . " Months" : "0" . " Months",
          // 'totalmonthFeesDeposit' => floor($totalMonthFeesDeposit),
        ];
    }


    // for api
    public function checkFeesSubmitDetails($schoolUniqueCode,$classId,$sectionId,$studentId)
    {
      $school =  $this->db->query("SELECT session_started_from,session_started_from_year, session_ended_to,session_ended_to_year FROM ".Table::schoolMasterTable." WHERE unique_id = '$schoolUniqueCode' LIMIT 1")->result_array();

      if(!empty($school))
       {
        $sessionStartingFrom = $school[0]['session_started_from']; // month
        $sessionStartingYear = $school[0]['session_started_from_year'];
        $sessionStartDate = 1;

        $sessionStart = date("$sessionStartingYear-$sessionStartingFrom-$sessionStartDate");
      
        $sessionEndingFrom =  $school[0]['session_ended_to']; // month
        $sessionEndingYear =  $school[0]['session_ended_to_year'];
        $sessionEndDate = 31;

        $sessionEnd = date("$sessionEndingYear-$sessionEndingFrom-$sessionEndDate");
    
       }

       $currentMonth = date('m'); // current month
       $currentYear = date('Y'); // current Year

      $d = $this->db->query("SELECT * FROM ".Table::feesForStudentTable." WHERE 
          class_id = '$classId' AND 
          section_id = '$sectionId' AND
          student_id = '$studentId' AND
          status = '1' AND schoolUniqueCode = '$schoolUniqueCode'
           AND created_at BETWEEN '$sessionStart' AND '$sessionEnd'
           ")->result_array();

        // echo $this->db->last_query(); die();
        $returnArr = [];
           if(!empty($d))
           {
            $totalD = count($d);
              for($i=0; $i<$totalD; $i++)
              {
                $subArr = [];
                $subArr = [
                  'id' => $d[$i]['id'],
                  'invoice_id' => $d[$i]['invoice_id'],
                  'offer_amt' => $d[$i]['offer_amt'],
                  'deposit_amt' => $d[$i]['deposit_amt'],
                  'fee_deposit_date' => $d[$i]['fee_deposit_date'],
                  'depositer_name' => $d[$i]['depositer_name'],
                  'total_old_due_balance' => $d[$i]['total_due_balance'],
                  // 'fee_due_today' => $d[$i]['total_due_balance'],
                  'payment_mode' => ($d[$i]['payment_mode'] =='1') ? 'Online' : 'Offline',
                ];
              
                array_push($returnArr, $subArr);
              }
            
           }

          //  if(empty($returnArr))
          //  {
          //   echo $this->db->last_query();
          //  }
           return $returnArr;
    }

    public function showAttendenceData($studentId,$dateWithYear,$schoolUniqueCode)
    {
      $d = $this->db->query("SELECT * FROM ".Table::attendenceTable." WHERE 
      stu_id = '$studentId' AND
      status = '1' AND 
      schoolUniqueCode = '$schoolUniqueCode' 
      AND MONTH(att_date) = MONTH('$dateWithYear')
      AND YEAR(att_date) = YEAR('$dateWithYear')
      AND attendenceStatus IS NOT NULL 
      AND attendenceStatus != ''
      ")->result_array();

        // echo $this->db->last_query(); die();
        $returnArr = [];
        $returnArr['studentId'] = $studentId;
        $returnArr['month'] = date('M',strtotime($dateWithYear));
        $returnArr['year'] = date('Y',strtotime($dateWithYear));
        $returnArr['attendanceData'] = [];
           if(!empty($d))
           {
            $totalD = count($d);
              for($i=0; $i<$totalD; $i++)
              {
                $subArr = [];
                if($d[$i]['attendenceStatus'] == '0')
                  {
                    // absent
                    $subArr = [
                      'status' => '0',
                      'dateTime' => date('d',strtotime($d[$i]['dateTime']))
                    ];
                  }else if($d[$i]['attendenceStatus'] == '1')
                  {
                    //present
                    $subArr = [
                      'status' => '1',
                      'dateTime' => date('d',strtotime($d[$i]['dateTime']))
                    ];
                  }
                  array_push($returnArr['attendanceData'], $subArr);
              }
            
           }
          
           return $returnArr;
    }

    public function showResultDataWithExam($schoolUniqueCode,$classId,$sectionId,$studentId)
    {
      $d = $this->db->query($sql = "SELECT 
      examt.exam_name, examt.date_of_exam, examt.min_marks,examt.max_marks,
      CONCAT(rt.marks ,' Out of ', examt.max_marks) as examMarks,
      rt.result_date,rt.remarks,rt.marks,if(rt.resultStatus = '1','Pass', 'Fail') as resultStatus,
      subt.subjectName
       FROM ".Table::examTable." examt
      INNER JOIN ".Table::resultTable." rt ON rt.exam_id = examt.id AND examt.schoolUniqueCode = rt.schoolUniqueCode
      LEFT JOIN " . Table::subjectTable . " subt ON subt.id = examt.subject_id
      WHERE rt.student_id = '$studentId' AND examt.class_id = '$classId' AND examt.section_id = '$sectionId'
      AND rt.schoolUniqueCode = '$schoolUniqueCode' AND examt.schoolUniqueCode = '$schoolUniqueCode'
     ")->result_array();

      // echo $sql; die();
         //echo $this->db->last_query(); die();
        $returnArr = [];
           if(!empty($d))
           {
            $totalD = count($d);
              for($i=0; $i<$totalD; $i++)
              {
                $subArr = [];
                // if(empty($d[$i]['exam_name']) || $d[$i]['examMarks'] || $d[$i]['result_date'])
                // {
                //   continue;
                // }
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
                $subArr['currentDate'] = date('d', strtotime(@$d[$i]['result_date']));
                array_push($returnArr, $subArr);
              }
            
           }

           return $returnArr;
    }


  
}