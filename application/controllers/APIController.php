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
	}

	// app login
	public function login()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		$userId = $apiData['userId'];
		$passWord = $apiData['password'];
		$uerType = $apiData['userType'];
		$userData = $this->APIModel->login($userId, $passWord, $uerType);
		return HelperClass::APIresponse( 200, 'Login Successfully.', $userData);
	}


	// show list of students for attendence
	public function showStudentsForAttendence()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		$authToken = $apiData['authToken'];
		$uerType = $apiData['userType'];
		$className = $apiData['className'];
		$sectionName = $apiData['sectionName'];
		$loginUser = $this->APIModel->validateLogin($authToken, $uerType);
		$allStudents = $this->APIModel->showAllStudentForAttendence($loginUser[0]['userType'], $className, $sectionName);
		return HelperClass::APIresponse(200, 'All Students Lists For Attendence.', $allStudents);
	}


	// submit attendence of students
	public function submitAttendence()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$className = $apiData['className'];
		$sectionName = $apiData['sectionName'];
		$attendenceData = $apiData['attendenceData'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$currentDateTime = date('d-m-Y h:i:s');
		$totalAttenData = count($attendenceData);
		for ($i = 0; $i < $totalAttenData; $i++) {
			$stu_id = $attendenceData[$i]['stu_id'];
			$attendenceStatus = $attendenceData[$i]['attendence'];
			$insertAttendeceRecord = $this->APIModel->submitAttendence($stu_id, $className, $sectionName, $loginUserId, $loginuserType, $attendenceStatus);
			if (!$insertAttendeceRecord) {
				return HelperClass::APIresponse(500, 'Attendence Not Updated Successfully beacuse ' . $this->db->last_query());
			}
		}

		return HelperClass::APIresponse(200, 'Attendence Updated Successfully at ' . $currentDateTime);
	}

	// showSubmitAttendenceData
	public function showSubmitAttendenceData()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$className = $apiData['className'];
		$sectionName = $apiData['sectionName'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$currentDateTime = date('d-m-Y h:i:s');
		$data = $this->APIModel->showSubmitAttendenceData($className, $sectionName);

		return HelperClass::APIresponse(200, 'Attendence Data For today date ' . $currentDateTime, $data);
	}

	// submit departure of students
	public function submitDeparture()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$className = $apiData['className'];
		$sectionName = $apiData['sectionName'];
		$departureData = $apiData['departureData'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
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
			$insertDepartureRecord = $this->APIModel->submitDeparture($stu_id, $attendenceId,$className, $sectionName, $loginUserId, $loginuserType, $departureStatus);
			if (!$insertDepartureRecord) {
				return HelperClass::APIresponse(500, 'Departure Not Updated Successfully beacuse ' . $this->db->last_query());
			}
		}

		return HelperClass::APIresponse(200, 'Departure Updated Successfully at ' . $currentDateTime);
	}


	// showSubmitDepartureData
	public function showSubmitDepartureData()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$className = $apiData['className'];
		$sectionName = $apiData['sectionName'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$currentDateTime = date('d-m-Y h:i:s');
		$data = $this->APIModel->showSubmitDepartureData($className, $sectionName);

		return HelperClass::APIresponse(200, 'Departure Data For today date ' . $currentDateTime, $data);
	}


	// addExam
	public function addExam()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
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

		$addNewExam = $this->APIModel->addExam($loginUserId,$loginuserType,$classId,$sectionId,$subjectId,$examDate,$examName,$maxMarks,$minMarks);

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
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		// $loginUserId = $apiData['loginUserId'];
		$classId = $apiData['classId'];
		$sectionId = $apiData['sectionId'];
		$subjectId = (@$apiData['subjectId']) ? @$apiData['subjectId'] : ''; // optional
		// $examDate = $apiData['examDate'];
		// $examName = $apiData['examName'];
		// $maxMarks = $apiData['maxMarks'];
		// $minMarks = $apiData['minMarks'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);

		$allExamList = $this->APIModel->showAllExam($classId,$sectionId,$subjectId);

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
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$classId = $apiData['classId'];
		$sectionId = $apiData['sectionId'];
		$subjectId = ($apiData['subjectId']) ? $apiData['subjectId'] : ''; // optional
		$examId = $apiData['examId'];

		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);

		$singleExamData = $this->APIModel->showSingleExam($classId,$sectionId,$subjectId,$examId);

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
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$resultDate = $apiData['resultDate'];
		$examId = $apiData['examId'];
		$results = $apiData['results'];
		$totalResults = count($results);

		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);

		for($i=0;$i<$totalResults;$i++)
		{
			$studentId = $results[$i]['studentId'];
			$marks = $results[$i]['marks'];
			$reMarks = @$results[$i]['reMarks'];
			$resultStatus = @$results[$i]['resultStatus'];

			$addExamResult = $this->APIModel->addResult($loginUserId,$loginuserType,$resultDate,$studentId,$marks,$reMarks,$resultStatus,$examId);
		}

		if (!$addExamResult) {
			return HelperClass::APIresponse(500, 'Result Not Added Successfully beacuse ' . $this->db->last_query());
		}else
		{
			return HelperClass::APIresponse(200, 'Result Updated Successfully');
		}
		
	}



	// showStudentDetails
	public function showStudentDetails()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$classId = @$apiData['classId'];
		$sectionId = @$apiData['sectionId'];
		$qrCode = @$apiData['qrCode'];
		$studentId = @$apiData['studentId'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$data = $this->APIModel->showStudentDetails($classId,$sectionId,$qrCode,$studentId);
		return HelperClass::APIresponse(200, 'Student Details.', $data);
	}




	// fetching all classes
	public function allClasses()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$data = $this->APIModel->allClasses();
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
		$data = $this->APIModel->allSections();
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
		$data = $this->APIModel->allSubjects();
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
