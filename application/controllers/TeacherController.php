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
		$this->load->model('CrudModel');
		$this->load->library('session');
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
			'pageTitle' => 'Teacher List',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->teacherDir . 'list');
		//$this->load->view($this->viewDir . 'pages/footer');
	}

	public function addTeacher()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
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
		$this->loginCheck();

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
		$this->loginCheck();

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
		$this->loginCheck();
	
		if (isset($_POST['submit'])) {
			if ($this->TeacherModel->saveTeacher($_POST, $_FILES)) {
				$msgArr = [
					'class' => 'success',
					'msg' => 'New Teacher Added Successfully.',
				  ];
				 $this->session->set_userdata($msgArr);
				redirect(base_url('teacher/list'));
			} else {
				$msgArr = [
					'class' => 'danger',
					'msg' => 'There is some issue, maybe this teacher is already register or some technical issue.',
				  ];
				 $this->session->set_userdata($msgArr);
				 redirect(base_url('teacher/list'));
			}
		}
	}
	public function updateTeacher()
	{
		$this->loginCheck();

		if (isset($_POST['submit'])) {
			if ($this->TeacherModel->updateTeacher($_POST, $_FILES)) {
				$msgArr = [
					'class' => 'success',
					'msg' => 'Teacher Updated Successfully.',
				  ];
				 $this->session->set_userdata($msgArr);
				redirect(base_url('teacher/list'));
			} else {
				$msgArr = [
					'class' => 'danger',
					'msg' => 'There is some issue, teacher not updated or some technical issue.',
				  ];
				 $this->session->set_userdata($msgArr);
				redirect(base_url('teacher/list'));
			}
		}
	}
	public function deleteTeacher($id)
	{
		$this->loginCheck();

		if ($this->TeacherModel->deleteTeacher($id)) {
			$msgArr = [
				'class' => 'success',
				'msg' => 'Teacher Deleted Successfully.',
			  ];
			 $this->session->set_userdata($msgArr);
			redirect(base_url('teacher/list'));
		} else {
			$msgArr = [
				'class' => 'danger',
				'msg' => 'There is some issue, teacher not deleted or some technical issue.',
			  ];
			 $this->session->set_userdata($msgArr);
			redirect(base_url('teacher/list'));
		}
	}


}
