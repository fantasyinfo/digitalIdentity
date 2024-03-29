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
		if(!empty($userData))
		{
			return HelperClass::APIresponse( 200, 'Login Successfully.', $userData);
		}else
		{
			return HelperClass::APIresponse(500, 'User Not Found. Please Use Correct Details.');
		}
		
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
		$session_table_id = $loginUser[0]['session_table_id'];
		// if($loginUserIdFromDB != $loginUserId)
		// {
		// 	return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		// }
		$currentDateTime = date('d-m-Y h:i:s');
		$totalAttenData = count($attendenceData);


		if($totalAttenData == '0')
		{
			$msg = "Please Mark All Students Attandance.";
			$totalAttenData = 0;
			return HelperClass::APIresponse(500, $msg);
		}


		// check if total student = total attendanceData
		$totalStudentsInTheClass = $this->APIModel->countStudentViaClassAndSectionName($className, $sectionName, $schoolUniqueCode);

		if($totalStudentsInTheClass != $totalAttenData)
		{
			$msg = "Please Mark All Students Attandance, Total Students in this Class $totalStudentsInTheClass and you have submitted only $totalAttenData students attendance." ;
			$totalAttenData = 0;
			return HelperClass::APIresponse(500, $msg);
		}

		$studentIds = [];
		for ($i = 0; $i < $totalAttenData; $i++) {
			$stu_id = $attendenceData[$i]['stu_id'];
			$attendenceStatus = $attendenceData[$i]['attendence'];
			// if(empty($attendenceStatus))
			// {
			// 	continue;
			// }
			array_push($studentIds,$stu_id);
			$insertAttendeceRecord = $this->APIModel->submitAttendence($stu_id, $className, $sectionName, $loginUserIdFromDB, $loginuserType, $attendenceStatus,$schoolUniqueCode,$session_table_id);
			if (!$insertAttendeceRecord) {
				return HelperClass::APIresponse(500, 'Attendence Not Updated Successfully beacuse ' . $this->db->last_query());
			}
		}

		// send notification now
		$studentIdsInString = implode("','",$studentIds);
		$sql = "SELECT fcm_token FROM " . Table::studentTable . " WHERE id IN ('$studentIdsInString') AND schoolUniqueCode = '$schoolUniqueCode'  AND status = '1' ";
		// die();
		$tokensFromDB =  $this->db->query($sql)->result_array();

		// echo $this->db->last_query();
		if(!empty($tokensFromDB))
		{
			$totalTokens = count($tokensFromDB);
			$tokenArr = [];
			if(!empty( $tokensFromDB))
			{
				if($totalTokens < 500)
				{
					for($i=0; $i < $totalTokens; $i++)
					{
						if(empty($tokensFromDB[$i]['fcm_token']) || $tokensFromDB[$i]['fcm_token'] == null)
						{
							continue;
						}
						array_push($tokenArr,$tokensFromDB[$i]['fcm_token']);
					}
				}
				
			}


			// fetch notification from db
			$notificationFromDB = $this->db->query("SELECT title, body FROM ".Table::setNotificationTable." WHERE status = '1' AND schoolUniqueCode = '$schoolUniqueCode' AND for_what = '1' LIMIT 1")->result_array();

			if(!empty($notificationFromDB))
			{
				$title = $this->CrudModel->replaceNotificationsWords((String)$notificationFromDB[0]['title']);
				$body =  $this->CrudModel->replaceNotificationsWords((String)$notificationFromDB[0]['body']);
			}else
			{
				$title = "Attendance Update ✅";
				$body = "Hey 👋 Dear Parents, Our 🏫 School Attendance Updated, Please Check The App Now!!";
			}

			
			$image = null;
			$sound = null;
		
			$sendPushSMS= json_decode($this->CrudModel->sendFireBaseNotificationWithDeviceId($tokenArr, $title,$body,$image,$sound), TRUE);
			$isNotificationSend = false;
			if(!empty($sendPushSMS))
			{
				if($sendPushSMS['success'])
				{
					$isNotificationSend = true;
				}
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
			return HelperClass::APIresponse(200, 'Attendence Updated Successfully & DigiCoin Updated at ' . $currentDateTime. ' and Notification Send Status ' . $isNotificationSend,'',['coins' =>$digiCoinF]);
		  }else
		  {
			return HelperClass::APIresponse(500, 'DigiCoin Not Inserted For Teachers '. $this->db->last_query());
		  }
		 }

		 
		return HelperClass::APIresponse(200, 'Attendence Updated Successfully at ' . $currentDateTime . ' and Notification Send Status ' . $isNotificationSend);
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

	// upate attendance data
	public function submitUpdatedAttendanceData()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if (empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['className']) || empty($apiData['sectionName']) || empty($apiData['loginUserId']) || empty($apiData['attendenceData'])) {
			return HelperClass::APIresponse(404, 'Please Enter All Parameters.');
		}

		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$className = $apiData['className'];
		$sectionName = $apiData['sectionName'];
		$attendenceData = $apiData['attendenceData'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode = $loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		$session_table_id = $loginUser[0]['session_table_id'];

		$currentDateTime = date('d-m-Y h:i:s');
		$totalAttenData = count($attendenceData);


		if ($totalAttenData == '0') {
			$msg = "Please Mark All Students Attandance.";
			$totalAttenData = 0;
			return HelperClass::APIresponse(500, $msg);
		}


		// check if total student = total attendanceData
		$totalStudentsInTheClass = $this->APIModel->countStudentViaClassAndSectionName($className, $sectionName, $schoolUniqueCode);

		if ($totalStudentsInTheClass != $totalAttenData) {
			$msg = "Please Mark All Students Attandance, Total Students in this Class $totalStudentsInTheClass and you have submitted only $totalAttenData students attendance.";
			$totalAttenData = 0;
			return HelperClass::APIresponse(500, $msg);
		}

		$studentIds = [];
		for ($i = 0; $i < $totalAttenData; $i++) {
			$stu_id = $attendenceData[$i]['stu_id'];
			$attendenceStatus = $attendenceData[$i]['attendence'];
			$updateId = $attendenceData[$i]['updateId'];

			array_push($studentIds, $stu_id);
			$insertAttendeceRecord = $this->APIModel->submitUpdatedAttendanceData($stu_id, $className, $sectionName, $loginUserIdFromDB, $loginuserType, $attendenceStatus, $schoolUniqueCode, $session_table_id, $updateId);
			if (!$insertAttendeceRecord) {
				return HelperClass::APIresponse(500, 'Attendence Not Updated Successfully beacuse ' . $this->db->last_query());
			}
		}

		// send notification now
		$studentIdsInString = implode("','", $studentIds);
		$sql = "SELECT fcm_token FROM " . Table::studentTable . " WHERE id IN ('$studentIdsInString') AND schoolUniqueCode = '$schoolUniqueCode'  AND status = '1' ";
		// die();
		$tokensFromDB = $this->db->query($sql)->result_array();

		// echo $this->db->last_query();
		if (!empty($tokensFromDB)) {
			$totalTokens = count($tokensFromDB);
			$tokenArr = [];
			if (!empty($tokensFromDB)) {
				if ($totalTokens < 500) {
					for ($i = 0; $i < $totalTokens; $i++) {
						if (empty($tokensFromDB[$i]['fcm_token']) || $tokensFromDB[$i]['fcm_token'] == null) {
							continue;
						}
						array_push($tokenArr, $tokensFromDB[$i]['fcm_token']);
					}
				}

			}


			// fetch notification from db
			$notificationFromDB = $this->db->query("SELECT title, body FROM " . Table::setNotificationTable . " WHERE status = '1' AND schoolUniqueCode = '$schoolUniqueCode' AND for_what = '1' LIMIT 1")->result_array();

			if (!empty($notificationFromDB)) {
				$title = $this->CrudModel->replaceNotificationsWords((String) $notificationFromDB[0]['title']);
				$body = $this->CrudModel->replaceNotificationsWords((String) $notificationFromDB[0]['body']);
			} else {
				$title = "Attendance Update ✅";
				$body = "Hey 👋 Dear Parents, Our 🏫 School Attendance Updated, Please Check The App Now!!";
			}


			$image = null;
			$sound = null;

			$sendPushSMS = json_decode($this->CrudModel->sendFireBaseNotificationWithDeviceId($tokenArr, $title, $body, $image, $sound), TRUE);
			$isNotificationSend = false;
			if (!empty($sendPushSMS)) {
				if ($sendPushSMS['success']) {
					$isNotificationSend = true;
				}
			}

		}


		return HelperClass::APIresponse(200, 'Attendence Updated Successfully at ' . $currentDateTime . ' and Notification Send Status ' . $isNotificationSend);
	}
	// attendanceLists
	public function attendanceLists()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if (empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['className']) || empty($apiData['sectionName']) || empty($apiData['date'])) {
			return HelperClass::APIresponse(404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$className = $apiData['className'];
		$sectionName = $apiData['sectionName'];
		$date = $apiData['date'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode = $loginUser[0]['schoolUniqueCode'];
		$currentDateTime = date('d-m-Y h:i:s');
		$data = $this->APIModel->attendanceLists($className, $sectionName, $date, $schoolUniqueCode);

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
		$session_table_id = $loginUser[0]['session_table_id'];
		// if($loginUserIdFromDB != $loginUserId)
		// {
		// 	return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		// }
		$currentDateTime = date('d-m-Y h:i:s');

		$totalDepStu = count($departureData);
		$studentIds = [];
		for ($i = 0; $i < $totalDepStu; $i++) {
			// ignore if the student not present today
			if($departureData[$i]['attendenceStatus'] == 0)
			{
				continue;
			}
			
			$attendenceId = $departureData[$i]['attendenceId'];
			 $stu_id = $departureData[$i]['studentId'];
			 array_push($studentIds,$stu_id);
			$departureStatus = '1';
			$insertDepartureRecord = $this->APIModel->submitDeparture($stu_id, $attendenceId,$className, $sectionName, $loginUserIdFromDB, $loginuserType, $departureStatus,$schoolUniqueCode,$session_table_id);
			if (!$insertDepartureRecord) {
				return HelperClass::APIresponse(500, 'Departure Not Updated Successfully beacuse ' . $this->db->last_query());
			}
		}

		// send notification now
		$studentIdsInString = implode("','",$studentIds);
		$sql = "SELECT fcm_token FROM " . Table::studentTable . " WHERE id IN ('$studentIdsInString') AND schoolUniqueCode = '$schoolUniqueCode'  AND status = '1' ";
		// die();
		$tokensFromDB =  $this->db->query($sql)->result_array();

		// echo $this->db->last_query();
		if(!empty($tokensFromDB))
		{
			$totalTokens = count($tokensFromDB);
			$tokenArr = [];
			if(!empty( $tokensFromDB))
			{
				if($totalTokens < 500)
				{
					for($i=0; $i < $totalTokens; $i++)
					{
						if(empty($tokensFromDB[$i]['fcm_token']) || $tokensFromDB[$i]['fcm_token'] == null)
						{
							continue;
						}
						array_push($tokenArr,$tokensFromDB[$i]['fcm_token']);
					}
				}
				
			}


			// fetch notification from db
			$notificationFromDB = $this->db->query("SELECT title, body FROM ".Table::setNotificationTable." WHERE status = '1' AND schoolUniqueCode = '$schoolUniqueCode' AND for_what = '2' LIMIT 1")->result_array();

			if(!empty($notificationFromDB))
			{
				$title = $this->CrudModel->replaceNotificationsWords((String)$notificationFromDB[0]['title']);
				$body =  $this->CrudModel->replaceNotificationsWords((String)$notificationFromDB[0]['body']);
			}else
			{
				$title = "Departure Update ✅";
			$body = "Hey 👋 Dear Parents, Our 🏫 School Departure Updated, Please Check The App Now!!";
			}



			
			$image = null;
			$sound = null;
     
			$sendPushSMS= json_decode($this->CrudModel->sendFireBaseNotificationWithDeviceId($tokenArr, $title,$body,$image,$sound), TRUE);
			$isNotificationSend = false;
			if(!empty($sendPushSMS))
			{
				if($sendPushSMS['success'])
				{
					$isNotificationSend = true;
				}
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
		   return HelperClass::APIresponse(200, 'Departure Updated Successfully & DigiCoin Updated at ' . $currentDateTime  . ' and Notification Send Status ' . $isNotificationSend,'',['coins' =>$digiCoinF]);
		 }else
		 {
		   return HelperClass::APIresponse(500, 'DigiCoin Not Inserted For Teachers '. $this->db->last_query());
		 }
		}
		return HelperClass::APIresponse(200, 'Departure Updated Successfully at ' . $currentDateTime .  ' and Notification Send Status ' . $isNotificationSend);
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
		$session_table_id = $loginUser[0]['session_table_id'];
		// if($loginUserIdFromDB != $loginUserId)
		// {
		// 	return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		// }
		$addNewExam = $this->APIModel->addExam($loginUserIdFromDB,$loginuserType,$classId,$sectionId,$subjectId,$examDate,$examName,$maxMarks,$minMarks,$schoolUniqueCode,$session_table_id);

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
		// if($loginUserIdFromDB != $loginUserId)
		// {
		// 	return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		// }
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
		$session_table_id = $loginUser[0]['session_table_id'];
		// if($loginUserIdFromDB != $loginUserId)
		// {
		// 	return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		// }
		$studentIds = [];
		for($i=0;$i<$totalResults;$i++)
		{
			$studentId = $results[$i]['studentId'];
			array_push($studentIds,$studentId);
			$marks = $results[$i]['marks'];
			$reMarks = @$results[$i]['reMarks'];
		
			$addExamResult = $this->APIModel->addResult($loginUserIdFromDB,$loginuserType,$resultDate,$studentId,$marks,$reMarks,$examId,$schoolUniqueCode,$session_table_id);
		}

		if (!$addExamResult) {
			return HelperClass::APIresponse(500, 'Result Not Added Successfully beacuse ' . $this->db->last_query());
		}else
		{
			// send notification now
			$studentIdsInString = implode("','",$studentIds);
			$sql = "SELECT fcm_token FROM " . Table::studentTable . " WHERE id IN ('$studentIdsInString') AND schoolUniqueCode = '$schoolUniqueCode'  AND status = '1' ";
			// die();
			$tokensFromDB =  $this->db->query($sql)->result_array();

			// echo $this->db->last_query();
			if(!empty($tokensFromDB))
			{
				$totalTokens = count($tokensFromDB);
				$tokenArr = [];
				if(!empty( $tokensFromDB))
				{
					if($totalTokens < 500)
					{
						for($i=0; $i < $totalTokens; $i++)
						{
							if(empty($tokensFromDB[$i]['fcm_token']) || $tokensFromDB[$i]['fcm_token'] == null)
							{
								continue;
							}
							array_push($tokenArr,$tokensFromDB[$i]['fcm_token']);
						}
					}
					
				}
				$title = "Result Published ✅";
				$body = "Hey 👋 Dear Parents, Result Has Been Published For Exam Id $examId, Please Check Result In The App Now!!";
				$image = null;
				$sound = null;
		
				$sendPushSMS= json_decode($this->CrudModel->sendFireBaseNotificationWithDeviceId($tokenArr, $title,$body,$image,$sound), TRUE);
				$isNotificationSend = false;
				if(!empty($sendPushSMS))
				{
					if($sendPushSMS['success'])
					{
						$isNotificationSend = true;
					}
				}
			}
			

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
						return HelperClass::APIresponse(200, 'Result Updated & DigiCoin Inserted For Teacher.' . ' and Notification Send Status ' . $isNotificationSend,'',['coins' =>$perResultDigiCoin]);
					} else {
						return HelperClass::APIresponse(200, 'Result Updated & DigiCoin Not Inserted For Teacher ' . $this->db->last_query());
					}
				}
			return HelperClass::APIresponse(200, 'Result Updated Successfully' . ' and Notification Send Status ' . $isNotificationSend);
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
		$document_image_name = '';
		
		if(isset($apiData['image']) && !empty(@$apiData['image']))
		{
			$documentType=@$apiData['image'];
			$document = base64_decode(@$apiData['image']);
			$document_image_name='homework_img'.time().'.png';
			$document_file = $_SERVER['DOCUMENT_ROOT']."/assets/uploads/homework/".$document_image_name;
			$success = file_put_contents($document_file, $document);
		}
		


		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		// if($loginUserIdFromDB != $loginUserId)
		// {
		// 	return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		// }

		$addHomeWork = $this->APIModel->addHomeWork($loginUserIdFromDB,$loginuserType,$classId,$sectionId,$subjectId,$homeWorkNote,$homeWorkDate,$homeWorkDueDate,$schoolUniqueCode,$document_image_name);
		

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
		if(empty($apiData['authToken']) || empty($apiData['userType']) 
		|| empty($apiData['classId']) || empty($apiData['sectionId']) 
		|| empty($apiData['date']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$classId = $apiData['classId'];
		$sectionId = $apiData['sectionId'];
		$date = $apiData['date'];

		if(empty($date))
		{
			$date = date('Y-m-d');
		}

		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$allHomeWorkList = $this->APIModel->showAllHomeWorks($classId,$sectionId,$schoolUniqueCode,$date);

		if (!$allHomeWorkList) {
			return HelperClass::APIresponse(500, 'No Home Work Found For ' .$date. ' & This Class');
		}else
		{
			return HelperClass::APIresponse(200, 'All Home Work List.',$allHomeWorkList);
		}
	}


		// showHoliday Claender
		public function holidayCalender()
		{
			
			$this->checkAPIRequest();
			$apiData = $this->getAPIData();
			if(empty($apiData['authToken']) || empty($apiData['userType']) )
			{
				return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
			}
			$authToken = $apiData['authToken'];
			$loginuserType = $apiData['userType'];
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
			$holidayList = $this->APIModel->holidayCalender($schoolUniqueCode,$dateWithYear);
	
			if (!$holidayList) {
				return HelperClass::APIresponse(500, 'No Holiday\'s Found.');
			}else
			{
				return HelperClass::APIresponse(200, 'All Holidays List.',$holidayList);
			}
		}

	// show result with exam 
	public function showResultDataWithExam()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) 
		|| empty($apiData['classId']) || empty($apiData['sectionId']) 
		|| empty($apiData['studentId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$classId = $apiData['classId'];
		$sectionId = $apiData['sectionId'];
		$studentId = $apiData['studentId'];

	
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$showResultDataWithExam = $this->APIModel->showResultDataWithExam($classId,$sectionId,$studentId,$schoolUniqueCode);

		if (!$showResultDataWithExam) {
			return HelperClass::APIresponse(500, 'No Result Found For This Student.');
		}else
		{
			return HelperClass::APIresponse(200, 'All Result Data With Exam.',$showResultDataWithExam);
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
		$session_table_id = $loginUser[0]['session_table_id'];
		$data = $this->APIModel->showStudentDetails($classId,$sectionId,$qrCode,$studentId,$schoolUniqueCode,$session_table_id);
		return HelperClass::APIresponse(200, 'Student Details.', $data);
	}
	public function showStudentFeesDetails()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['classId']) || empty($apiData['sectionId']) || empty($apiData['loginUserId']) || empty($apiData['studentId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}

		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$classId = $apiData['classId'];
		$sectionId = $apiData['sectionId'];
		$studentId = $apiData['studentId'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$session_table_id = $loginUser[0]['session_table_id'];
		$data = $this->APIModel->showStudentFeesDetails($studentId, $classId, $sectionId, $schoolUniqueCode, $session_table_id);
		return HelperClass::APIresponse(200, 'Student Details.', $data);
	}






	public function studentFeesSubmitData()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['classId'])|| empty($apiData['sectionId'])|| empty($apiData['studentId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		// $loginUserId = $apiData['loginUserId'];
		$classId = $apiData['classId'];
		$sectionId = $apiData['sectionId'];
		$studentId = $apiData['studentId'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
	
		$feesDetails = $this->APIModel->studentFeesSubmitData($classId,$sectionId,$studentId,$schoolUniqueCode);

		if (!$feesDetails) {
			return HelperClass::APIresponse(500, 'No Fees Found For This User');
		}else
		{
			return HelperClass::APIresponse(200, 'Fees Data.',$feesDetails);
		}
	}

	// wallet history
	public function walletHistory()
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
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		// if($loginUserIdFromDB != $loginUserId)
		// {
		// 	return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		// }
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
		$loginUserId = $apiData['loginUserId'];


	
		$leaderBoardData = $this->APIModel->leaderBoard($loginuserType,$schoolUniqueCode,$loginUserId);

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
		if(empty($apiData['authToken']) || empty($apiData['userType'])  || empty($apiData['visit_date'])|| empty($apiData['visit_time'])|| empty($apiData['visitor_name']) || empty($apiData['person_to_meet'])|| empty($apiData['purpose_to_meet'])|| empty($apiData['visitor_mobile_no']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.', $apiData);
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = @$apiData['loginUserId'];
		$visit_date = $apiData['visit_date'];
		$visit_time = $apiData['visit_time'];
		$visitor_name = $apiData['visitor_name'];
		$person_to_meet = $apiData['person_to_meet'];
		$purpose_to_meet = $apiData['purpose_to_meet'];
		$visitor_mobile_no = $apiData['visitor_mobile_no'];
		$document_image_name = '';
		
		if(isset($apiData['image']) && !empty(@$apiData['image']))
		{
			$documentType=@$apiData['image'];
			$document = base64_decode(@$apiData['image']);
			$document_image_name='visitor_img_'.time().'.png';
			$document_file = $_SERVER['DOCUMENT_ROOT']."/assets/uploads/visitorentry/".$document_image_name;
			$success = file_put_contents($document_file, $document);
		}
		
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		$session_table_id = $loginUser[0]['session_table_id'];
		// if($loginUserIdFromDB != $loginUserId)
		// {
		// 	return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		// }
		$visitorEntry = $this->APIModel->visitorEntry($visit_date,$visit_time,$visitor_name,$person_to_meet,$purpose_to_meet,$visitor_mobile_no,$document_image_name,$schoolUniqueCode,$session_table_id);

		if (!$visitorEntry) {
			return HelperClass::APIresponse(500, 'There is some technical issue, visitor entry not inserted ' . $this->db->last_query());
		}else
		{
			return HelperClass::APIresponse(200, 'New Visitor Entry Added Successfully.','',['visitorEntry' => $visitorEntry]);
		}
	}


	// gatePass
	public function gatePass()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType'])  || empty($apiData['studentId'])|| empty($apiData['classId'])|| empty($apiData['sectionId']) || empty($apiData['guardian_name'])|| empty($apiData['mobile'])|| empty($apiData['address']) || empty($apiData['time']) || empty($apiData['date']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.', $apiData);
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = @$apiData['loginUserId'];
		$studentId = $apiData['studentId'];
		$class_id = $apiData['classId'];
		$section_id = $apiData['sectionId'];
		$guardian_name = $apiData['guardian_name'];
		$mobile = $apiData['mobile'];
		$address = $apiData['address'];
		$time = $apiData['time'];
		$date = $apiData['date'];
		
		$document_image_name = '';
		
		if(isset($apiData['image']) && !empty(@$apiData['image']))
		{
			$documentType=@$apiData['image'];
			$document = base64_decode(@$apiData['image']);
			$document_image_name='gate_pass_img_'.time().'.png';
			$document_file = $_SERVER['DOCUMENT_ROOT']."/assets/uploads/gatepass/".$document_image_name;
			$success = file_put_contents($document_file, $document);
		}
		
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];

		$gatePassId = $this->APIModel->gatePass($schoolUniqueCode,$studentId,$class_id,$section_id,$guardian_name,$mobile,$address,$time,$date,$document_image_name);

		if (!$gatePassId) {
			return HelperClass::APIresponse(500, 'There is some technical issue, gate Pass not created ' . $this->db->last_query());
		}else
		{
			return HelperClass::APIresponse(200, 'New Gate Pass Generated Successfully.','',['gatepassId' => "https://dvm.digitalfied.in/gatePass?gatePass=$gatePassId"]);
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

	// gatePassList
	public function gatePassList()
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

		$bannerImgs = $this->APIModel->gatePassList($schoolUniqueCode);

		if (!$bannerImgs) {
			return HelperClass::APIresponse(500, 'No Gate Pass Found ' . $this->db->last_query());
		}else
		{
			return HelperClass::APIresponse(200, 'All Gate Pass Lists.', $bannerImgs);
		}
	}

	// studentNamesViaClassAndSectionId
	public function studentNamesViaClassAndSectionId()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$class_id = $apiData['classId'];
		$section_id = $apiData['sectionId'];

		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];

		$studentsLists = $this->APIModel->studentNamesViaClassAndSectionId($class_id,$section_id,$schoolUniqueCode);

		if (!$studentsLists) {
			return HelperClass::APIresponse(500, 'No Students Found ' . $this->db->last_query());
		}else
		{
			return HelperClass::APIresponse(200, 'All Students Lists.', $studentsLists);
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

	public function notificationsForAll()
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

		$notificationsData = $this->APIModel->notificationsForAll($schoolUniqueCode);

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
		// if($loginUserIdFromDB != $loginUserId)
		// {
		// 	return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		// }
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
		if(empty($apiData['authToken']) || empty($apiData['userType']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
	
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		// if($loginUserIdFromDB != $loginUserId)
		// {
		// 	return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		// }
		$digiCoinCountData = $this->APIModel->checkAllGifts($loginuserType,$schoolUniqueCode);

		if (!$digiCoinCountData) {
			return HelperClass::APIresponse(500, 'No Gifts Found For This User Type' . $loginuserType);
		}else
		{
			return HelperClass::APIresponse(200, 'All Gifts Data.',$digiCoinCountData);
		}
	}

	// validateQRCode
	public function validateQRCode()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['qrCode']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$qrCode = $apiData['qrCode'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
	
		$validateQR = $this->APIModel->validateQRCode($qrCode,$loginuserType,$schoolUniqueCode);

		if (!$validateQR) {
			return HelperClass::APIresponse(500, 'Identity Not Verified');
		}else
		{
			return HelperClass::APIresponse(200, 'Identity Verified Successfully.',$validateQR);
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
		// if($loginUserIdFromDB != $loginUserId)
		// {
		// 	return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		// }
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
		// if($loginUserIdFromDB != $loginUserId)
		// {
		// 	return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		// }
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
		// if($loginUserIdFromDB != $loginUserId)
		// {
		// 	return HelperClass::APIresponse(500, "Login User Id And Auth Token Not Matched, Please Use Correct Login User Id. " );
		// }
		$giftStatus = $this->APIModel->giftRedeemStatus($loginUserIdFromDB,$loginuserType,$schoolUniqueCode);
		if (!$giftStatus) {
			return HelperClass::APIresponse(500, 'Gifts Redeem Status Not Found.');
		}else
		{
			return HelperClass::APIresponse(200, 'All Your Gifts Redeem Status. ',$giftStatus);
		}
	}

	// upateDriverLatLng
	public function upateDriverLatLng()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['loginUserId']) || empty($apiData['lat']) || empty($apiData['lng']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$loginUserId = $apiData['loginUserId'];
		$lat = $apiData['lat'];
		$lng = $apiData['lng'];
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
	
		$driverLatLng = $this->APIModel->upateDriverLatLng($loginUserIdFromDB,$loginuserType,$lat,$lng,$schoolUniqueCode);
		if (!$driverLatLng) {
			return HelperClass::APIresponse(500, 'Driver Lat Lng Not Added.');
		}else
		{
			return HelperClass::APIresponse(200, 'Driver Lat Lng Updated Successfully.');
		}
	}


	// getDriverLatLng

	public function getDriverLatLng()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$studentId = @$apiData['studentId'];
		$teacherId = @$apiData['teacherId'];
	
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		
		$isTeaher = false;
		if(isset($teacherId)){
		    $isTeaher = true;
		    $studentId = $teacherId;
		}
	
		$driverLatLng = $this->APIModel->getDriverLatLng($studentId,$schoolUniqueCode,$isTeaher);
		if (!$driverLatLng) {
			return HelperClass::APIresponse(500, 'Driver Lat Lng Not Found.');
		}else
		{
			return HelperClass::APIresponse(200, 'Driver Lat Lng Data.',$driverLatLng);
		}
	}

	// add complaint
	public function addComplaint()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['guiltyPersonName']) || empty($apiData['guiltyPersonPosition']) || empty($apiData['subject'])  || empty($apiData['issue']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$guiltyPersonName = $apiData['guiltyPersonName'];
		$guiltyPersonPosition = $apiData['guiltyPersonPosition'];
		$subject = $apiData['subject'];
		$issue = $apiData['issue'];
	
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
	
		$addComplaint = $this->APIModel->addComplaint($loginUserIdFromDB,$loginuserType,$guiltyPersonName,$guiltyPersonPosition,$subject,$issue,$schoolUniqueCode);
		if (!$addComplaint) {
			return HelperClass::APIresponse(500, 'Complaint Not Registerd.');
		}else
		{
			return HelperClass::APIresponse(200, 'Complaint Register Successfully.',$addComplaint);
		}
	}

	// add complaint
	public function markAttendance()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['qrCode']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$qrCode = $apiData['qrCode'];

		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$loginUserIdFromDB = $loginUser[0]['login_user_id'];
		$session_table_id = $loginUser[0]['session_table_id'];
	
		$markAtt = $this->APIModel->markAttendance($loginuserType,$qrCode,$schoolUniqueCode,$session_table_id);

		
		if (!$markAtt) {
			return HelperClass::APIresponse(500, 'Attendance Not Registerd.');
		}else
		{
			return HelperClass::APIresponse(200, 'API Working Check Response.',[],['responseCode' =>$markAtt]);
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


	// show semester Exam Names

	public function showSemesterExamNames()
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
		$session_table_id = $loginUser[0]['session_table_id'];
		$allSemesterExamNames = $this->APIModel->showSemesterExamNames($schoolUniqueCode,$session_table_id);

		if (!$allSemesterExamNames) {
			return HelperClass::APIresponse(500, 'No Semester Exam Found');
		}else
		{
			return HelperClass::APIresponse(200, 'All Semester Exams.',$allSemesterExamNames);
		}
		
	}

// show all semester Exams
	
	public function showAllSemesterExam()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['classId']) || empty($apiData['sectionId']) || empty($apiData['subjectId']) || empty($apiData['semExamNameId']))
		{
			return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
		}
		$authToken = $apiData['authToken'];
		$loginuserType = $apiData['userType'];
		$classId = $apiData['classId'];
		$sectionId = $apiData['sectionId'];
		$subjectId = $apiData['subjectId']; 
		$semExamId = $apiData['semExamNameId']; 
		$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
		$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
		$session_table_id = $loginUser[0]['session_table_id'];
		$allSemesterExamList = $this->APIModel->showAllSemesterExam($semExamId,$classId,$sectionId,$subjectId,$schoolUniqueCode,$session_table_id);

		if (!$allSemesterExamList) {
			return HelperClass::APIresponse(500, 'No Semester Exam Found For This Class And Section And Subject');
		}else
		{
			return HelperClass::APIresponse(200, 'All Semester Exam List.',$allSemesterExamList);
		}
		
	}



// add Semster Exam Result
public function addSemesterExamResult()
{
	$this->checkAPIRequest();
	$apiData = $this->getAPIData();
	if(empty($apiData['authToken']) || empty($apiData['userType']) || empty($apiData['results']) || empty($apiData['loginUserId']) || empty($apiData['semExamId']))
	{
		return HelperClass::APIresponse( 404, 'Please Enter All Parameters.');
	}
	$authToken = $apiData['authToken'];
	$loginuserType = $apiData['userType'];
	$loginUserId = $apiData['loginUserId'];
	$examId = $apiData['semExamId'];
	$semExamNameId = $apiData['semExamNameId'];
	$results = $apiData['results'];
	$totalResults = count($results);

	$loginUser = $this->APIModel->validateLogin($authToken, $loginuserType);
	$schoolUniqueCode =	$loginUser[0]['schoolUniqueCode'];
	$loginUserIdFromDB = $loginUser[0]['login_user_id'];
	$session_table_id = $loginUser[0]['session_table_id'];

	$studentIds = [];
	for($i=0;$i<$totalResults;$i++)
	{
		$studentId = $results[$i]['studentId'];
		array_push($studentIds,$studentId);
		$marks = $results[$i]['marks'];

		$addExamResult = $this->APIModel->addSemesterExamResult($studentId,$marks,$examId,$semExamNameId,$schoolUniqueCode,$session_table_id);
	}

	if (!$addExamResult) {
		return HelperClass::APIresponse(500, 'Result Not Added Successfully beacuse ' . $this->db->last_query());
	}else
	{
		// update on exam table result published
		$updateExamPublishedStatus = $this->CrudModel->update(Table::secExamTable, ['status' => '3'],$examId);
		return HelperClass::APIresponse(200, 'Result Updated Successfully');
	}
	
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

	// get headers from API
	public function getHeaderDataFromRequest()
	{
		return apache_request_headers();
	}



	// check App Update
	public function checkAppUpdate()
	{
		$this->load->config('app_version');
        $this->checkAPIRequest();
        $apiData = $this->getAPIData();

		$userVersionName = $apiData['data']['app_version_name'];
		$userVersionCode = $apiData['data']['app_version_code'];
		$appVersion = $this->config->item('app')['connect'];

		if (stripos($userVersionName, 'beta'))
		{
			$app = $appVersion['beta'];
		}
		else
		{
			$app = $appVersion['live'];
			$app['link'] = $app['playstore'];
		}
	
		$versionCode = $app['version_code'];
		$versionName = $app['version_name'];
		$link = $app['link'];
		if (!in_array($userVersionName, $versionName) || !in_array($userVersionCode, $versionCode))
		{
			return HelperClass::APIresponse(500, 'Update Available.' .end($versionName) ,[],['url' => $link,'is_compulsory' => '1']);
		}
			return HelperClass::APIresponse(200, 'You are using latest version.');
		
	} 



	
}
