<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StudentController extends CI_Controller
{

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";
	public $studentDir = "pages/student/";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('StudentModel');
		$this->load->model('CrudModel');
	}

	public function loginCheck()
	{
		if(!$this->CrudModel->checkIsLogin())
		{
			header('Location: '.base_url());
		}
	}

	public function list()
	{
		$this->loginCheck();
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
			'class' => $this->StudentModel->allClass(),
			'section' => $this->StudentModel->allSection(),
			'city' => $this->StudentModel->allCity(),
			'state' => $this->StudentModel->allState(),
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->studentDir . 'add');
		$this->load->view($this->viewDir . 'pages/footer');
	}

	public function editStudent($id)
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Edit Student',
			'studentData' => $this->StudentModel->singleStudent($id),
			'adminPanelUrl' => $this->adminPanelURL,
			'submitFormUrl' => base_url('student/updateStudent'),
			'class' => $this->StudentModel->allClass(),
			'section' => $this->StudentModel->allSection(),
			'city' => $this->StudentModel->allCity(),
			'state' => $this->StudentModel->allState(),
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->studentDir . 'edit');
		$this->load->view($this->viewDir . 'pages/footer');
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
				$this->list();
			} else {
				$this->addStudent();
			}
		}
	}
	public function updateStudent()
	{
		$this->loginCheck();
		if (isset($_POST['submit'])) {
			if ($this->StudentModel->updateStudent($_POST, $_FILES)) {
				$this->list();
			} else {
				$this->addStudent();
			}
		}
	}
	public function deleteStudent($id)
	{
		$this->loginCheck();
		$studentData = $this->StudentModel->singleStudent($id);
		unlink(@$studentData[0]['image']);
		if ($this->StudentModel->deleteStudent($id)) {
			$this->list();
		} else {
			$this->addStudent();
		}
		
	}


}
