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
	}

	public function list()
	{
		$dataArr = [
			'pageTitle' => 'Student List',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->studentDir . 'list');
		$this->load->view($this->viewDir . 'pages/footer');
	}

	public function addStudent()
	{
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
		$studentData = $this->StudentModel->singleStudent($id);
		if ($this->StudentModel->deleteStudent($id)) {
			$dir = HelperClass::uploadImgDir . @$studentData[0]['image'];
			unlink(@$dir);
			$this->list();
		} else {
			$this->addStudent();
		}
		
	}


}
