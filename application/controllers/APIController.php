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
		$userData = $this->APIModel->login($schoolUniqueCode,$userId, $passWord, $uerType);
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
			return HelperClass::APIresponse(200, 'All Students Lists For Attendence.', $allStudents);
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
		$currentDateTime = date('d-m-Y h:i:s');
		$totalAttenData = count($attendenceData);
		for ($i = 0; $i < $totalAttenData; $i++) {
			$stu_id = $attendenceData[$i]['stu_id'];
			$attendenceStatus = $attendenceData[$i]['attendence'];
			$insertAttendeceRecord = $this->APIModel->submitAttendence($stu_id, $className, $sectionName, $loginUserId, $loginuserType, $attendenceStatus,$schoolUniqueCode);
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
			return HelperClass::APIresponse(200, 'Attendence Updated Successfully & DigiCoin Updated at ' . $currentDateTime,'',$digiCoinF);
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
			$insertDepartureRecord = $this->APIModel->submitDeparture($stu_id, $attendenceId,$className, $sectionName, $loginUserId, $loginuserType, $departureStatus,$schoolUniqueCode);
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
		   return HelperClass::APIresponse(200, 'Departure Updated Successfully & DigiCoin Updated at ' . $currentDateTime,'',$digiCoinF);
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

		$addNewExam = $this->APIModel->addExam($loginUserId,$loginuserType,$classId,$sectionId,$subjectId,$examDate,$examName,$maxMarks,$minMarks,$schoolUniqueCode);

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
		$addNewExam = $this->APIModel->updateExam($loginUserId,$loginuserType,$classId,$sectionId,$subjectId,$examDate,$examName,$maxMarks,$minMarks,$examId);

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
		for($i=0;$i<$totalResults;$i++)
		{
			$studentId = $results[$i]['studentId'];
			$marks = $results[$i]['marks'];
			$reMarks = @$results[$i]['reMarks'];
		
			$addExamResult = $this->APIModel->addResult($loginUserId,$loginuserType,$resultDate,$studentId,$marks,$reMarks,$examId,$schoolUniqueCode);
		}

		if (!$addExamResult) {
			return HelperClass::APIresponse(500, 'Result Not Added Successfully beacuse ' . $this->db->last_query());
		}else
		{
				// check digiCoin is set for this result time for students avg
				$perResultDigiCoin =  $this->APIModel->checkIsDigiCoinIsSet(HelperClass::actionType['Result'], HelperClass::userType['Teacher'], $schoolUniqueCode);

				$dataArrOfExam = $this->APIModel->avgResultOfExam($examId,$schoolUniqueCode);

				
				// calculate digiCoin value as per marks of total student result avg

				if(!empty($dataArrOfExam) && !empty($perResultDigiCoin))
				{
					$digiCoinToInsert =   $this->APIModel->calculateStudentResultDigiCoin($perResultDigiCoin, $dataArrOfExam['obtained_max_marks'], $dataArrOfExam['exam_max_marks']);
				}
				
				if (isset($digiCoinToInsert)) {
					// insert the digicoin
					$insertDigiCoin = $this->APIModel->insertDigiCoin($studentId, HelperClass::userTypeR['2'], HelperClass::actionType['Result'], $digiCoinToInsert, $schoolUniqueCode);
					if ($insertDigiCoin) {
						return HelperClass::APIresponse(200, 'Result Updated & DigiCoin Inserted For Teacher.','',$perResultDigiCoin);
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

		$addHomeWork = $this->APIModel->addHomeWork($loginUserId,$loginuserType,$classId,$sectionId,$subjectId,$homeWorkNote,$homeWorkDate,$homeWorkDueDate,$schoolUniqueCode);
		

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
		$walletHistoryData = $this->APIModel->walletHistory($loginUserId,$loginuserType,$schoolUniqueCode);

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
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['loginUserId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
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
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['loginUserId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
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
		$userTypeId = HelperClass::userType[$loginuserType];
		$digiCoinCountData = $this->APIModel->getAlreadyDigiCoinCount($loginUserId,$userTypeId,$loginuserType,$schoolUniqueCode);

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
		$digiCoinCountData = $this->APIModel->showGiftsForRedeem($loginUserId,$loginuserType,$schoolUniqueCode);

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

		$totalCountOfGifts = count($giftsIds);

		for($i=0; $i < $totalCountOfGifts; $i++){
			$giftId = $giftsIds[$i]['gift_id'];
			$redeemGifts = $this->APIModel->redeemGifts($giftId,$loginUserId,$loginuserType,$schoolUniqueCode);
		}

		if (!$redeemGifts) {
			return HelperClass::APIresponse(500, 'Gifts Not Redeem, there is some issue contact support.');
		}else
		{
			return HelperClass::APIresponse(200, 'You have successfully redeem your gift, digiCoin balance is updated & you can check your gift status anytime.');
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
