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

	public function login()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		$userId = $apiData['userId'];
		$passWord = $apiData['password'];
		$uerType = $apiData['userType'];
		$userData = $this->APIModel->login($userId,$passWord,$uerType);
		return HelperClass::APIresponse($status = 200, $msg = 'Login Successfully.',$userData);
	}



	public function showStudentsForAttendence()
	{
		$this->checkAPIRequest();
		$apiData = $this->getAPIData();
		$authToken = $apiData['authToken'];
		$uerType = $apiData['userType'];
		$className = $apiData['className'];
		$sectionName = $apiData['sectionName'];
		$loginUser = $this->APIModel->validateLogin($authToken,$uerType);
		$allStudents = $this->APIModel->showAllStudentForAttendence($loginUser[0]['userType'],$className,$sectionName);
		return HelperClass::APIresponse($status = 200, $msg = 'Login Successfully.',$allStudents);

	}







	public function checkAPIRequest()
	{
		// errors
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		// headers
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: multipart/form-data');
		header("Access-Control-Allow-Methods: POST");
		header("Allow: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Headers: Authentication, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");
		// check method is post
		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			return HelperClass::APIresponse($status = 500, $msg = 'Only POST Method is Allowed');
	   	}
	}

	public function getAPIData()
	{
		$d = file_get_contents('php://input');
		return json_decode($d,TRUE);
	}

}
