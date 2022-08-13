<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TeacherController extends CI_Controller
{

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";
	public $teacherDir = "pages/teacher/";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('StudentModel');
		$this->load->model('TeacherModel');
	}

	public function list()
	{
		$dataArr = [
			'pageTitle' => 'Teacher List',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->teacherDir . 'list');
		//$this->load->view($this->viewDir . 'pages/footer');
	}

	public function addTeacher()
	{
		
		$dataArr = [
			'pageTitle' => 'Add New Teacher',
			'adminPanelUrl' => $this->adminPanelURL,
			'submitFormUrl' => base_url('teacher/saveTeacher'),
			'class' => $this->TeacherModel->allClass(),
			'section' => $this->TeacherModel->allSection(),
			'city' => $this->TeacherModel->allCity(),
			'state' => $this->TeacherModel->allState(),
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->teacherDir . 'add');
		$this->load->view($this->viewDir . 'pages/footer');
	}

	public function editTeacher($id)
	{

		$dataArr = [
			'pageTitle' => 'Edit Teacher',
			'teacherData' => $this->TeacherModel->singleTeacher($id),
			'adminPanelUrl' => $this->adminPanelURL,
			'submitFormUrl' => base_url('teacher/updateTeacher'),
			'class' => $this->TeacherModel->allClass(),
			'section' => $this->TeacherModel->allSection(),
			'city' => $this->TeacherModel->allCity(),
			'state' => $this->TeacherModel->allState(),
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->teacherDir . 'edit');
		$this->load->view($this->viewDir . 'pages/footer');
	}

	public function viewTeacher($id)
	{

		$dataArr = [
			'pageTitle' => 'View Teacher',
			'teacherData' => $this->TeacherModel->viewSingleTeacherAllData($id),
			'adminPanelUrl' => $this->adminPanelURL,
			// 'submitFormUrl' => base_url('Teacher/updateTeacher'),
			// 'class' => $this->TeacherModel->allClass(),
			// 'section' => $this->TeacherModel->allSection(),
			// 'city' => $this->TeacherModel->allCity(),
			// 'state' => $this->TeacherModel->allState(),
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->teacherDir . 'profile');
		$this->load->view($this->viewDir . 'pages/footer');
	}

	

	public function saveTeacher()
	{
		if (isset($_POST['submit'])) {
			if ($this->TeacherModel->saveTeacher($_POST, $_FILES)) {
				$this->list();
			} else {
				$this->addTeacher();
			}
		}
	}
	public function updateTeacher()
	{
		if (isset($_POST['submit'])) {
			if ($this->TeacherModel->updateTeacher($_POST, $_FILES)) {
				$this->list();
			} else {
				$this->addTeacher();
			}
		}
	}
	public function deleteTeacher($id)
	{
		$TeacherData = $this->TeacherModel->singleTeacher($id);
		if ($this->TeacherModel->deleteTeacher($id)) {
			$dir = HelperClass::uploadImgDir . @$TeacherData[0]['image'];
			unlink(@$dir);
			$this->list();
		} else {
			$this->addTeacher();
		}
		
	}


}
