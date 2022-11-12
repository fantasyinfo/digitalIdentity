<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StudentController extends CI_Controller
{

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";
	public $studentDir = "pages/student/";
	public $schoolUniqueCode = '';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('StudentModel');
		$this->load->model('CrudModel');
		$this->load->library('session');
		$this->schoolUniqueCode = $_SESSION['schoolUniqueCode'];
	}

	public function loginCheck()
	{
		if(!$this->CrudModel->checkIsLogin())
		{
			header('Location: '.base_url());
		}
	}

	public function checkPermission()
	{
		if(!$this->CrudModel->checkPermission())
		{
			header('Location: '.base_url());
		}
	}

	public function list()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Student List',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->studentDir . 'list');
		//$this->load->view($this->viewDir . 'pages/footer');
	}

	public function addStudent()
	{
		$this->loginCheck();
	
		$dataArr = [
			'pageTitle' => 'Add New Student',
			'adminPanelUrl' => $this->adminPanelURL,
			'submitFormUrl' => base_url('student/saveStudent'),
			'class' => $this->StudentModel->allClass($this->schoolUniqueCode),
			'section' => $this->StudentModel->allSection($this->schoolUniqueCode),
			// 'city' => $this->StudentModel->allCity($this->schoolUniqueCode),
			'state' => $this->StudentModel->allState($this->schoolUniqueCode),
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->studentDir . 'add');
		// $this->load->view($this->viewDir . 'pages/footer');
	}

	public function editStudent($id)
	{
		$this->loginCheck();

		$dataArr = [
			'pageTitle' => 'Edit Student',
			'studentData' => $this->StudentModel->singleStudent($id),
			'adminPanelUrl' => $this->adminPanelURL,
			'submitFormUrl' => base_url('student/updateStudent'),
			'class' => $this->StudentModel->allClass($this->schoolUniqueCode),
			'section' => $this->StudentModel->allSection($this->schoolUniqueCode),
			// 'city' => $this->StudentModel->allCity($this->schoolUniqueCode),
			'state' => $this->StudentModel->allState($this->schoolUniqueCode),
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->studentDir . 'edit');
		// $this->load->view($this->viewDir . 'pages/footer');
	}

	public function viewStudent($id)
	{
		$this->loginCheck();

		$dataArr = [
			'pageTitle' => 'View Student',
			'studentData' => $this->StudentModel->viewSingleStudentAllData($id),
			'adminPanelUrl' => $this->adminPanelURL,
			// 'submitFormUrl' => base_url('student/updateStudent'),
			// 'class' => $this->StudentModel->allClass(),
			// 'section' => $this->StudentModel->allSection(),
			// 'city' => $this->StudentModel->allCity(),
			// 'state' => $this->StudentModel->allState(),
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->studentDir . 'profile');
		$this->load->view($this->viewDir . 'pages/footer');
	}

	

	public function saveStudent()
	{
		$this->loginCheck();

		if (isset($_POST['submit'])) {
			if ($this->StudentModel->saveStudent($_POST, $_FILES)) {
				$msgArr = [
					'class' => 'success',
					'msg' => 'New Student Added Successfully.',
				  ];
				 $this->session->set_userdata($msgArr);
				redirect(base_url('student/list'));
			} else {
				$msgArr = [
					'class' => 'danger',
					'msg' => 'There is some issue, maybe this student is already register or some technical issue.',
				  ];
				 $this->session->set_userdata($msgArr);
				 redirect(base_url('student/add'));
			}
		}
	}
	public function updateStudent()
	{
		$this->loginCheck();
	
		if (isset($_POST['submit'])) {
			if ($this->StudentModel->updateStudent($_POST, $_FILES)) {
				$msgArr = [
					'class' => 'success',
					'msg' => 'Student Updated Added Successfully.',
				  ];
				 $this->session->set_userdata($msgArr);
				redirect(base_url('student/list'));
			} else {
				$msgArr = [
					'class' => 'danger',
					'msg' => 'There is some issue, student not updated or some technical issue.',
				  ];
				 $this->session->set_userdata($msgArr);
				 redirect(base_url('student/list'));
			}
		}
	}

	public function permoteStudent()
	{
		$this->loginCheck();
		$this->checkPermission();
	
		$dataArr = [
			'pageTitle' => 'Permote Student',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->studentDir . 'permoteStudent');
	}

	public function generateTC()
	{
		$this->loginCheck();
		$this->checkPermission();
	
		$dataArr = [
			'pageTitle' => 'Generate TC',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->studentDir . 'generateTC');
	}
	public function editTC()
	{
		$this->loginCheck();
		//$this->checkPermission();
	
		$dataArr = [
			'pageTitle' => 'Edit TC',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->studentDir . 'editTC');
	}
	public function getCharacterCertificate()
	{
		$this->loginCheck();
		$this->checkPermission();
	
		$dataArr = [
			'pageTitle' => 'Character Certificate',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->studentDir . 'getCharacterCertificate');
	}

	public function deleteStudent($id)
	{
		$this->loginCheck();

		if ($this->StudentModel->deleteStudent($id)) {
			$msgArr = [
				'class' => 'success',
				'msg' => 'Student Deleted Successfully.',
			  ];
			 $this->session->set_userdata($msgArr);
			redirect(base_url('student/list'));
		} else {
			$msgArr = [
				'class' => 'danger',
				'msg' => 'There is some issue, student not deleted or some technical issue.',
			  ];
			 $this->session->set_userdata($msgArr);
			redirect(base_url('student/list'));
		}
		
	}


}
