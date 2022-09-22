<?php
defined('BASEPATH') or exit('No direct script access allowed');

class APIController extends CI_Controller
{

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";
	public $studentDir = "pages/student/";
	public $apiDir = "apis/";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('StudentModel');
		$this->load->model('TeacherModel');
		$this->load->model('APIModel');
		$this->load->model('CrudModel');
		$this->load->database();
	}

	// app login
	public function login()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['schoolUniqueCode']) || empty($apiData['userId']) || empty($apiData['password']) || empty($apiData['userType']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}

		$schoolUniqueCode = $apiData['schoolUniqueCode'];
		$userId = $apiData['userId'];
		$passWord = $apiData['password'];
		$uerType = $apiData['userType'];
		$fcmToken = (isset($apiData['fcm_token'])) ? @$apiData['fcm_token'] : '';
		$userData = $this->APIModel->login($schoolUniqueCode,$userId, $passWord, $uerType, $fcmToken);
		return HelperClass::APIresponse( 200, 'Login Successfully.', $userData);
	}


	// show list of students for attendence
	public function showStudentsForAttendence()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['className']) || empty($apiData['sectionName']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$uerType = $apiData['userType'];
		$className = $apiData['className'];
		$sectionName = $apiData['sectionName'];
		$loginUser = $this->APIModel->validateLogin($authToken, $uerType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$allStudents = $this->APIModel->showAllStudentForAttendence($loginUser[0]['userType'], $className, $sectionName,$schoolUniqueCode);
		if($allStudents)
		{
			return HelperClass::APIresponse(200, 'All Students Lists For Attendence.', $allStudents , ['totalCounts' => count($allStudents)]);
		}else
		{
			return HelperClass::APIresponse(500, 'Students Not Found. Please Use Correct Details.');
		}
		
	}


	// submit attendence of students
	public function submitAttendence()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['className']) || empty($apiData['sectionName']) || empty($apiData['loginUserId']) || empty($apiData['attendenceData']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}


		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$className = $apiData['className'];
		$sectionName = $apiData['sectionName'];
		$attendenceData = $apiData['attendenceData'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		if($loginUserIdFromDB != $loginUserId)
		{
			return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		}
		$currentDateTime = date('d-m-Y h:i:s');
		$totalAttenData = count($attendenceData);

		// check if total student = total attendanceData
		$totalStudentsInTheClass = $this->APIModel->countStudentViaClassAndSectionName($className, $sectionName, $schoolUniqueCode);

		if($totalStudentsInTheClass != $totalAttenData)
		{
			return HelperClass::APIresponse(500, "Please Mark All Students Attandance, Total Students in this Class $totalStudentsInTheClass and you have submitted only $totalAttenData students attendance." );
		}

		for ($i = 0; $i < $totalAttenData; $i++) {
			$stu_id = $attendenceData[$i]['stu_id'];
			$attendenceStatus = $attendenceData[$i]['attendence'];
			$insertAttendeceRecord = $this->APIModel->submitAttendence($stu_id, $className, $sectionName, $loginUserIdFromDB, $loginuserType, $attendenceStatus,$schoolUniqueCode);
			if (!$insertAttendeceRecord) {
				return HelperClass::APIresponse(500, 'Attendence Not Updated Successfully beacuse ' . $this->db->last_query());
			}
		}

		 // check digiCoin is set for this attendence time for teachers
		 $digiCoinF =  $this->APIModel->checkIsDigiCoinIsSet(HelperClass::actionType['Attendence'],HelperClass::userType['Teacher'],$schoolUniqueCode);

		 if($digiCoinF)
		 {
		  // insert the digicoin
		  $insertDigiCoin = $this->APIModel->insertDigiCoin($loginUserId,HelperClass::userTypeR['2'],HelperClass::actionType['Attendence'],$digiCoinF,$schoolUniqueCode);
		  if($insertDigiCoin)
		  {
			return HelperClass::APIresponse(200, 'Attendence Updated Successfully & DigiCoin Updated at ' . $currentDateTime,'',['coins' =>$digiCoinF]);
		  }else
		  {
			return HelperClass::APIresponse(500, 'DigiCoin Not Inserted For Teachers '. $this->db->last_query());
		  }
		 }

		 
		return HelperClass::APIresponse(200, 'Attendence Updated Successfully at ' . $currentDateTime);
	}

	// showSubmitAttendenceData
	public function showSubmitAttendenceData()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['className']) || empty($apiData['sectionName']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$className = $apiData['className'];
		$sectionName = $apiData['sectionName'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$currentDateTime = date('d-m-Y h:i:s');
		$data = $this->APIModel->showSubmitAttendenceData($className, $sectionName,$schoolUniqueCode);

		return HelperClass::APIresponse(200, 'Attendence Data For today date ' . $currentDateTime, $data);
	}

	// submit departure of students
	public function submitDeparture()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['className']) || empty($apiData['sectionName']) || empty($apiData['loginUserId']) || empty($apiData['departureData']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$className = $apiData['className'];
		$sectionName = $apiData['sectionName'];
		$departureData = $apiData['departureData'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		if($loginUserIdFromDB != $loginUserId)
		{
			return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		}
		$currentDateTime = date('d-m-Y h:i:s');

		$totalDepStu = count($departureData);
		for ($i = 0; $i < $totalDepStu; $i++) {
			// ignore if the student not present today
			if($departureData[$i]['attendenceStatus'] == 0)
			{
				continue;
			}
			
			$attendenceId = $departureData[$i]['attendenceId'];
			 $stu_id = $departureData[$i]['studentId'];
			$departureStatus = '1';
			$insertDepartureRecord = $this->APIModel->submitDeparture($stu_id, $attendenceId,$className, $sectionName, $loginUserIdFromDB, $loginuserType, $departureStatus,$schoolUniqueCode);
			if (!$insertDepartureRecord) {
				return HelperClass::APIresponse(500, 'Departure Not Updated Successfully beacuse ' . $this->db->last_query());
			}
		}

		// check digiCoin is set for this departure time for teachers
		$digiCoinF =  $this->APIModel->checkIsDigiCoinIsSet(HelperClass::actionType['Departure'],HelperClass::userType['Teacher'],$schoolUniqueCode);

		if($digiCoinF)
		{
		 // insert the digicoin
		 $insertDigiCoin = $this->APIModel->insertDigiCoin($loginUserId,HelperClass::userTypeR['2'],HelperClass::actionType['Departure'],$digiCoinF,$schoolUniqueCode);
		 if($insertDigiCoin)
		 {
		   return HelperClass::APIresponse(200, 'Departure Updated Successfully & DigiCoin Updated at ' . $currentDateTime,'',['coins' =>$digiCoinF]);
		 }else
		 {
		   return HelperClass::APIresponse(500, 'DigiCoin Not Inserted For Teachers '. $this->db->last_query());
		 }
		}
		return HelperClass::APIresponse(200, 'Departure Updated Successfully at ' . $currentDateTime);
	}


	// showSubmitDepartureData
	public function showSubmitDepartureData()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['className']) || empty($apiData['sectionName']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
	
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$className = $apiData['className'];
		$sectionName = $apiData['sectionName'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$currentDateTime = date('d-m-Y h:i:s');
		$data = $this->APIModel->showSubmitDepartureData($className, $sectionName,$schoolUniqueCode);

		return HelperClass::APIresponse(200, 'Departure Data For today date ' . $currentDateTime, $data);
	}


	// addExam
	public function addExam()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['classId']) || empty($apiData['sectionId']) || empty($apiData['loginUserId']) || empty($apiData['subjectId']) || empty($apiData['examDate'])  || empty($apiData['examName'])  || empty($apiData['maxMarks']) || empty($apiData['minMarks']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$classId = $apiData['classId'];
		$sectionId = $apiData['sectionId'];
		$subjectId = $apiData['subjectId'];
		$examDate = $apiData['examDate'];
		$examName = $apiData['examName'];
		$maxMarks = $apiData['maxMarks'];
		$minMarks = $apiData['minMarks'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		if($loginUserIdFromDB != $loginUserId)
		{
			return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		}
		$addNewExam = $this->APIModel->addExam($loginUserIdFromDB,$loginuserType,$classId,$sectionId,$subjectId,$examDate,$examName,$maxMarks,$minMarks,$schoolUniqueCode);

		if (!$addNewExam) {
			return HelperClass::APIresponse(500, 'Exam Not Added Successfully beacuse ' . $this->db->last_query());
		}else
		{
			return HelperClass::APIresponse(200, 'New Exam Added Successfully');
		}
		
	}

	
	// show all Exams
	public function showAllExam()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['classId']) || empty($apiData['sectionId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$classId = $apiData['classId'];
		$sectionId = $apiData['sectionId'];
		$subjectId = (@$apiData['subjectId']) ? @$apiData['subjectId'] : ''; // optional
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$allExamList = $this->APIModel->showAllExam($classId,$sectionId,$schoolUniqueCode,$subjectId);

		if (!$allExamList) {
			return HelperClass::APIresponse(500, 'No Exam Found For This Class');
		}else
		{
			return HelperClass::APIresponse(200, 'All Exam List.',$allExamList);
		}
		
	}

	// show all Attendence Data For Student studentId
	public function showAttendanceDataForStudentId()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['studentId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$studentId = $apiData['studentId'];
		$year = (@$apiData['year']) ? @$apiData['year'] : null;
		$month = (@$apiData['month']) ? @$apiData['month'] : null;

		if(isset($year) && isset($month) && $year != null && $month != null)
		{
			$date = $year . "-". $month . "-" . "01";
			$dateWithYear = date($date);
		}else
		{
			$dateWithYear = null;
		}
		
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$allAttendanceList = $this->APIModel->showAttendanceDataForStudentId($studentId,$schoolUniqueCode,$dateWithYear);

		if (!$allAttendanceList) {
			return HelperClass::APIresponse(500, 'No Attendance Found For This Student.' . $this->db->last_query());
		}else
		{
			return HelperClass::APIresponse(200, 'All Attendance List.',$allAttendanceList);
		}
		
	}


	// showSingleExam


	public function showSingleExam()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['classId']) || empty($apiData['sectionId']) || empty($apiData['examId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$classId = $apiData['classId'];
		$sectionId = $apiData['sectionId'];
		$examId = $apiData['examId'];

		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$singleExamData = $this->APIModel->showSingleExam($classId,$sectionId,$examId,$schoolUniqueCode);

		if (!$singleExamData) {
			return HelperClass::APIresponse(500, 'No Exam Found For This Class');
		}else
		{
			return HelperClass::APIresponse(200, 'Exam Data.',$singleExamData);
		}
		
	}


	// upateExam
	public function updateExam()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['classId']) || empty($apiData['sectionId']) || empty($apiData['loginUserId']) || empty($apiData['subjectId']) || empty($apiData['examDate'])  || empty($apiData['examName'])  || empty($apiData['maxMarks']) || empty($apiData['minMarks']) || empty($apiData['examId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$classId = $apiData['classId'];
		$sectionId = $apiData['sectionId'];
		$subjectId = $apiData['subjectId'];
		$examDate = $apiData['examDate'];
		$examName = $apiData['examName'];
		$maxMarks = $apiData['maxMarks'];
		$minMarks = $apiData['minMarks'];
		$examId = $apiData['examId'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		// $schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		if($loginUserIdFromDB != $loginUserId)
		{
			return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		}
		$addNewExam = $this->APIModel->updateExam($loginUserIdFromDB,$loginuserType,$classId,$sectionId,$subjectId,$examDate,$examName,$maxMarks,$minMarks,$examId);

		if (!$addNewExam) {
			return HelperClass::APIresponse(500, 'Exam Not Updated Successfully beacuse ' . $this->db->last_query());
		}else
		{
			return HelperClass::APIresponse(200, 'Exam Updated Successfully');
		}
		
	}

	// addResult
	public function addResult()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['resultDate']) || empty($apiData['results']) || empty($apiData['loginUserId']) || empty($apiData['examId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$resultDate = $apiData['resultDate'];
		$examId = $apiData['examId'];
		$results = $apiData['results'];
		$totalResults = count($results);

		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		if($loginUserIdFromDB != $loginUserId)
		{
			return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		}
		for($i=0;$i<$totalResults;$i++)
		{
			$studentId = $results[$i]['studentId'];
			$marks = $results[$i]['marks'];
			$reMarks = @$results[$i]['reMarks'];
		
			$addExamResult = $this->APIModel->addResult($loginUserIdFromDB,$loginuserType,$resultDate,$studentId,$marks,$reMarks,$examId,$schoolUniqueCode);
		}

		if (!$addExamResult) {
			return HelperClass::APIresponse(500, 'Result Not Added Successfully beacuse ' . $this->db->last_query());
		}else
		{
				// update on exam table result published
				$updateExamPublishedStatus = $this->CrudModel->update(Table::examTable, ['status' => '3'],$examId);

				// check digiCoin is set for this result time for students avg
				$perResultDigiCoin =  $this->APIModel->checkIsDigiCoinIsSet(HelperClass::actionType['Result'], HelperClass::userType['Teacher'], $schoolUniqueCode);

				$dataArrOfExam = $this->APIModel->avgResultOfExam($examId,$schoolUniqueCode);

				
				// calculate digiCoin value as per marks of total student result avg

				if(!empty($dataArrOfExam) && !empty($perResultDigiCoin))
				{
					$digiCoinToInsert =   $this->APIModel->calculateStudentResultDigiCoin($perResultDigiCoin, $dataArrOfExam['obtained_max_marks'], $dataArrOfExam['exam_max_marks']);
				}
				
				if (isset($digiCoinToInsert) && $digiCoinToInsert > 0) {
					// insert the digicoin
					$insertDigiCoin = $this->APIModel->insertDigiCoin($loginUserIdFromDB, HelperClass::userTypeR['2'], HelperClass::actionType['Result'], $digiCoinToInsert, $schoolUniqueCode,$examId);
					if ($insertDigiCoin) {
						return HelperClass::APIresponse(200, 'Result Updated & DigiCoin Inserted For Teacher.','',['coins' =>$perResultDigiCoin]);
					} else {
						return HelperClass::APIresponse(500, 'Result Updated & DigiCoin Not Inserted For Teacher ' . $this->db->last_query());
					}
				}
			return HelperClass::APIresponse(200, 'Result Updated Successfully');
		}
		
	}


	// addHomeWork
	public function addHomeWork()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['classId']) || empty($apiData['sectionId']) || empty($apiData['loginUserId']) || empty($apiData['subjectId'])|| empty($apiData['homeWorkNote'])|| empty($apiData['homeWorkDate'])|| empty($apiData['homeWorkDueDate']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$classId = $apiData['classId'];
		$sectionId = $apiData['sectionId'];
		$subjectId = $apiData['subjectId'];
		$homeWorkNote = $apiData['homeWorkNote'];
		$homeWorkDate = $apiData['homeWorkDate'];
		$homeWorkDueDate = $apiData['homeWorkDueDate'];


		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		if($loginUserIdFromDB != $loginUserId)
		{
			return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		}

		$addHomeWork = $this->APIModel->addHomeWork($loginUserIdFromDB,$loginuserType,$classId,$sectionId,$subjectId,$homeWorkNote,$homeWorkDate,$homeWorkDueDate,$schoolUniqueCode);
		

		if (!$addHomeWork) {
			return HelperClass::APIresponse(500, 'Home Work Not Added Successfully beacuse ' . $this->db->last_query());
		}else
		{
				// check digiCoin is set for this result time for students avg
				// $perResultDigiCoin =  $this->APIModel->checkIsDigiCoinIsSet(HelperClass::actionType['Result'], HelperClass::userType['Teacher'], $schoolUniqueCode);

				// $dataArrOfExam = $this->APIModel->avgResultOfExam($examId,$schoolUniqueCode);

				
				// calculate digiCoin value as per marks of total student result avg

				// if(!empty($dataArrOfExam) && !empty($perResultDigiCoin))
				// {
				// 	$digiCoinToInsert =   $this->APIModel->calculateStudentResultDigiCoin($perResultDigiCoin, $dataArrOfExam['obtained_max_marks'], $dataArrOfExam['exam_max_marks']);
				// }
				
				// if (isset($digiCoinToInsert)) {
				// 	// insert the digicoin
				// 	$insertDigiCoin = $this->APIModel->insertDigiCoin($studentId, HelperClass::userTypeR['2'], HelperClass::actionType['Result'], $digiCoinToInsert, $schoolUniqueCode);
				// 	if ($insertDigiCoin) {
				// 		return HelperClass::APIresponse(200, 'Result Updated & DigiCoin Inserted For Teacher.','',$perResultDigiCoin);
				// 	} else {
				// 		return HelperClass::APIresponse(500, 'Result Updated & DigiCoin Not Inserted For Teacher ' . $this->db->last_query());
				// 	}
				// }
			return HelperClass::APIresponse(200, 'Home Work Updated Successfully');
		}
		
	}

	// showAllHomeWorks
	public function showAllHomeWorks()
	{
		
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['classId']) || empty($apiData['sectionId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$classId = $apiData['classId'];
		$sectionId = $apiData['sectionId'];
		$subjectId = (@$apiData['subjectId']) ? @$apiData['subjectId'] : ''; // optional
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$allHomeWorkList = $this->APIModel->showAllHomeWorks($classId,$sectionId,$schoolUniqueCode,$subjectId);

		if (!$allHomeWorkList) {
			return HelperClass::APIresponse(500, 'No Home Work Found For This Class');
		}else
		{
			return HelperClass::APIresponse(200, 'All Home Work List.',$allHomeWorkList);
		}
	}



// showSingleHomeWork


public function showSingleHomeWork()
{
	$this->checkAPIRequest();
	$apiData = $this->getAPIData();
	if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['classId']) || empty($apiData['sectionId']) || empty($apiData['homeWorkId']))
	{
		return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
	}
	$authToken = $apiData['authToken'];
	$loginuserType = $apiData['userType'];
	$classId = $apiData['classId'];
	$sectionId = $apiData['sectionId'];
	$homeWorkId = $apiData['homeWorkId'];

	$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
	$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
	$singleExamData = $this->APIModel->showSingleHomeWork($classId,$sectionId,$homeWorkId,$schoolUniqueCode);

	if (!$singleExamData) {
		return HelperClass::APIresponse(500, 'No Exam Found For This Class');
	}else
	{
		return HelperClass::APIresponse(200, 'Exam Data.',$singleExamData);
	}
	
}


//updateHomeWork

public function updateHomeWork()
{
	$this->checkAPIRequest();
	$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['classId']) || empty($apiData['sectionId']) || empty($apiData['loginUserId']) || empty($apiData['subjectId'])|| empty($apiData['homeWorkNote'])|| empty($apiData['homeWorkDate'])|| empty($apiData['homeWorkDueDate'])|| empty($apiData['homeWorkId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$classId = $apiData['classId'];
		$sectionId = $apiData['sectionId'];
		$subjectId = $apiData['subjectId'];
		$homeWorkNote = $apiData['homeWorkNote'];
		$homeWorkDate = $apiData['homeWorkDate'];
		$homeWorkDueDate = $apiData['homeWorkDueDate'];
		$homeWorkId = $apiData['homeWorkId'];
	$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
	// $schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
	$addNewHomeWork = $this->APIModel->updateHomeWork($loginUserId,$loginuserType,$classId,$sectionId,$subjectId,$homeWorkNote,$homeWorkDate,$homeWorkDueDate,$homeWorkId);

	if (!$addNewHomeWork) {
		return HelperClass::APIresponse(500, 'Home Work Not Updated Successfully beacuse ' . $this->db->last_query());
	}else
	{
		return HelperClass::APIresponse(200, 'Home Work Updated Successfully');
	}
	
}




	// showStudentDetails
	public function showStudentDetails()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$classId = (@$apiData['classId'])?$apiData['classId']:'';
		$sectionId = (@$apiData['sectionId'])?$apiData['sectionId']:'';
		$qrCode = (@$apiData['qrCode'])?$apiData['qrCode']:"";
		$studentId = (@$apiData['studentId'])?$apiData['studentId']:"";
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$data = $this->APIModel->showStudentDetails($classId,$sectionId,$qrCode,$studentId,$schoolUniqueCode);
		return HelperClass::APIresponse(200, 'Student Details.', $data);
	}



	// wallet history
	public function walletHistory()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['loginUserId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		if($loginUserIdFromDB != $loginUserId)
		{
			return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		}
		$walletHistoryData = $this->APIModel->walletHistory($loginUserIdFromDB,$loginuserType,$schoolUniqueCode);

		if (!$walletHistoryData) {
			return HelperClass::APIresponse(500, 'No Wallet Found For This User');
		}else
		{
			return HelperClass::APIresponse(200, 'Wallet Data.',$walletHistoryData);
		}
	}

	// leaderBoard
	public function leaderBoard()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];

	
		$leaderBoardData = $this->APIModel->leaderBoard($loginuserType,$schoolUniqueCode);

		if (!$leaderBoardData) {
			return HelperClass::APIresponse(500, 'No LeaderBoard Found For This User ' . $this->db->last_query());
		}else
		{
			return HelperClass::APIresponse(200, 'LeaderBoard Data.',$leaderBoardData);
		}
	}

	// visitorEntry
	public function visitorEntry()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['loginUserId']) || empty($apiData['visit_date'])|| empty($apiData['visit_time'])|| empty($apiData['visitor_name']) || empty($apiData['person_to_meet'])|| empty($apiData['purpose_to_meet'])|| empty($apiData['visitor_mobile_no']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$visit_date = $apiData['visit_date'];
		$visit_time = $apiData['visit_time'];
		$visitor_name = $apiData['visitor_name'];
		$person_to_meet = $apiData['person_to_meet'];
		$purpose_to_meet = $apiData['purpose_to_meet'];
		$visitor_mobile_no = $apiData['visitor_mobile_no'];
		$document_image_name = '';
		
		$documentType=$apiData['image'];
	    $document = base64_decode($apiData['image']);
        $document_image_name='visitor_img_'.time().'.png';
    	$document_file = $_SERVER['DOCUMENT_ROOT']."/assets/uploads/".$document_image_name;
        $success = file_put_contents($document_file, $document);

		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		if($loginUserIdFromDB != $loginUserId)
		{
			return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		}
		$visitorEntry = $this->APIModel->visitorEntry($visit_date,$visit_time,$visitor_name,$person_to_meet,$purpose_to_meet,$visitor_mobile_no,$document_image_name,$schoolUniqueCode);

		if (!$visitorEntry) {
			return HelperClass::APIresponse(500, 'There is some technical issue, visitor entry not inserted ' . $this->db->last_query());
		}else
		{
			return HelperClass::APIresponse(200, 'New Visitor Entry Added Successfully.');
		}
	}

	// bannerForApp
	public function bannerForApp()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];

		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];

		$bannerImgs = $this->APIModel->bannerForApp($schoolUniqueCode);

		if (!$bannerImgs) {
			return HelperClass::APIresponse(500, 'No Banner\'s Added From Panel ' . $this->db->last_query());
		}else
		{
			return HelperClass::APIresponse(200, 'All Banners Images.', $bannerImgs);
		}
	}

	// notificationsForParent
	public function notificationsForParent()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];

		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];

		$notificationsData = $this->APIModel->notificationsForParent($schoolUniqueCode);

		if (!$notificationsData) {
			return HelperClass::APIresponse(500, 'No New Notification Found. ' . $this->db->last_query());
		}else
		{
			return HelperClass::APIresponse(200, 'All Notifications Data.', $notificationsData);
		}
	}

	// studentDashboard
	public function studentDashboard()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['studentId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$studentId = $apiData['studentId'];

		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];

		$notificationsData = $this->APIModel->studentDashboard($schoolUniqueCode,$studentId);

		if (!$notificationsData) {
			return HelperClass::APIresponse(500, 'No Student Found. ' . $this->db->last_query());
		}else
		{
			return HelperClass::APIresponse(200, 'Student Dashboard Data.', $notificationsData);
		}
	}

	// showAllStudentsForSwitchProfile
	public function showAllStudentsForSwitchProfile()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];

		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);

		$stuIds = [];
		$totalStu = count($loginUser);
		for($i=0;$i<$totalStu; $i++)
		{
			$stuIds[] = $loginUser[$i]['login_user_id'];
		}

		$sString = implode("','",$stuIds);

		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];

		$notificationsData = $this->APIModel->showAllStudentsForSwitchProfile($schoolUniqueCode,$sString);

		if (!$notificationsData) {
			return HelperClass::APIresponse(500, 'No Student Found. ' . $this->db->last_query());
		}else
		{
			return HelperClass::APIresponse(200, 'Student Dashboard Data.', $notificationsData);
		}
	}




	// count digicoin
	public function getAlreadyDigiCoinCount()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['loginUserId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		if($loginUserIdFromDB != $loginUserId)
		{
			return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		}
		$userTypeId = HelperClass::userType[$loginuserType];
		$digiCoinCountData = $this->APIModel->getAlreadyDigiCoinCount($loginUserIdFromDB,$userTypeId,$loginuserType,$schoolUniqueCode);

		if (!$digiCoinCountData) {
			return HelperClass::APIresponse(500, 'No DigiCoin Found For This User');
		}else
		{
			return HelperClass::APIresponse(200, 'DigiCoin Count Data.',$digiCoinCountData);
		}
	}

	// check all gifts
	public function checkAllGifts()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['loginUserId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		if($loginUserIdFromDB != $loginUserId)
		{
			return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		}
		$digiCoinCountData = $this->APIModel->checkAllGifts($loginuserType,$schoolUniqueCode);

		if (!$digiCoinCountData) {
			return HelperClass::APIresponse(500, 'No Gifts Found For This User Type' . $loginuserType);
		}else
		{
			return HelperClass::APIresponse(200, 'All Gifts Data.',$digiCoinCountData);
		}
	}

	// check all gifts
	public function showGiftsForRedeem()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['loginUserId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		if($loginUserIdFromDB != $loginUserId)
		{
			return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		}
		$digiCoinCountData = $this->APIModel->showGiftsForRedeem($loginUserIdFromDB,$loginuserType,$schoolUniqueCode);

		if (!$digiCoinCountData) {
			return HelperClass::APIresponse(500, 'Your DigiCoins are too low, there is no gifts for your total digicoins. increase them & try again.' . $loginuserType);
		}else
		{
			return HelperClass::APIresponse(200, 'You are eligible to redeem those Gifts Data.',$digiCoinCountData);
		}
	}


	// redeem gifts
	public function redeemGifts()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['loginUserId']) || empty($apiData['giftsIds']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$giftsIds = $apiData['giftsIds'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		if($loginUserIdFromDB != $loginUserId)
		{
			return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		}
		$totalCountOfGifts = count($giftsIds);



		// echo json_encode($giftsIds);die();




		for($i=0; $i < $totalCountOfGifts; $i++){
			$giftId = $giftsIds[$i];
			$redeemGifts = $this->APIModel->redeemGifts($giftId,$loginUserIdFromDB,$loginuserType,$schoolUniqueCode);
		}

		if (!$redeemGifts) {
			return HelperClass::APIresponse(500, 'Gifts Not Redeem, there is some issue contact support.');
		}else
		{
			return HelperClass::APIresponse(200, 'You have successfully redeem your gift, digiCoin balance is updated & you can check your gift status anytime.');
		}
	}

	// gift redeem status
	public function giftRedeemStatus()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['loginUserId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		if($loginUserIdFromDB != $loginUserId)
		{
			return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		}
		$giftStatus = $this->APIModel->giftRedeemStatus($loginUserIdFromDB,$loginuserType,$schoolUniqueCode);
		if (!$giftStatus) {
			return HelperClass::APIresponse(500, 'Gifts Redeem Status Not Found.');
		}else
		{
			return HelperClass::APIresponse(200, 'All Your Gifts Redeem Status. ',$giftStatus);
		}
	}

	// fetching all classes
	public function allClasses()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$data = $this->APIModel->allClasses($schoolUniqueCode);
		return HelperClass::APIresponse(200, 'All Classes Data.', $data);
	}

	// fetching all sections
	public function allSections()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$data = $this->APIModel->allSections($schoolUniqueCode);
		return HelperClass::APIresponse(200, 'All Sections Data', $data);
	}


	// fetching all subjects
	public function allSubjects()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$data = $this->APIModel->allSubjects($schoolUniqueCode);
		return HelperClass::APIresponse(200, 'All Subjects Data', $data);
	}



	// check api request is post method
	public function checkAPIRequest()
	{
		// errors
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		// headers
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: multipart/form-data');
		header('Content-Type: application/json');
		header("Access-Control-Allow-Methods: POST");
		header("Allow: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Headers: Authentication, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");
		// check method is post
		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			return HelperClass::APIresponse($status = 500, $msg = 'Only POST Method is Allowed');
		}
	}

	// collect all api data 
	public function getAPIData()
	{
		$d = file_get_contents('php://input');
		return json_decode($d, TRUE);
	}
}
