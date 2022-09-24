<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DriverController extends CI_Controller
{

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";
	public $driverDir = "pages/driver/";
	public $schoolUniqueCode = '';


	public function __construct()
	{
		parent::__construct();
		$this->load->model('StudentModel');
		$this->load->model('TeacherModel');
		$this->load->model('DriverModel');
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
			'pageTitle' => 'Driver List',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->driverDir . 'list');
		//$this->load->view($this->viewDir . 'pages/footer');
	}

	public function addDriver()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Add New Driver',
			'adminPanelUrl' => $this->adminPanelURL,
			'submitFormUrl' => base_url('driver/saveDriver'),
			'state' => $this->TeacherModel->allState($this->schoolUniqueCode),
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->driverDir . 'add');
		$this->load->view($this->viewDir . 'pages/footer');
	}

	public function editDriver($id)
	{
		$this->loginCheck();

		$dataArr = [
			'pageTitle' => 'Edit Driver',
			'driverData' => $this->DriverModel->singleDriver($id),
			'adminPanelUrl' => $this->adminPanelURL,
			'submitFormUrl' => base_url('driver/updateDriver'),
			'state' => $this->TeacherModel->allState($this->schoolUniqueCode),
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->driverDir . 'edit');
		$this->load->view($this->viewDir . 'pages/footer');
	}

	public function viewDriver($id)
	{
		$this->loginCheck();

		$dataArr = [
			'pageTitle' => 'View Driver',
			'driverData' => $this->DriverModel->viewSingleDriverAllData($id),
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->driverDir . 'profile');
		$this->load->view($this->viewDir . 'pages/footer');
	}

	// public function teacherReviews()
	// {
	// 	$this->loginCheck();

	// 	$dataArr = [
	// 		'pageTitle' => 'Teacher Reviews',
	// 		'adminPanelUrl' => $this->adminPanelURL,
	// 	];
	// 	$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
	// 	$this->load->view($this->viewDir . $this->teacherDir . 'teacherReviews');
	// 	// $this->load->view($this->viewDir . 'pages/footer');
	// }

	public function saveDriver()
	{
		$this->loginCheck();
	
		if (isset($_POST['submit'])) {
			if ($this->DriverModel->saveDriver($_POST, $_FILES)) {
				$msgArr = [
					'class' => 'success',
					'msg' => 'New Driver Added Successfully.',
				  ];
				 $this->session->set_userdata($msgArr);
				redirect(base_url('driver/list'));
			} else {
				$msgArr = [
					'class' => 'danger',
					'msg' => 'There is some issue, maybe this driver is already register or some technical issue.',
				  ];
				 $this->session->set_userdata($msgArr);
				 redirect(base_url('driver/list'));
			}
		}
	}
	public function updateDriver()
	{
		$this->loginCheck();

		if (isset($_POST['submit'])) {
			if ($this->DriverModel->updateDriver($_POST, $_FILES)) {
				$msgArr = [
					'class' => 'success',
					'msg' => 'Driver Updated Successfully.',
				  ];
				 $this->session->set_userdata($msgArr);
				redirect(base_url('driver/list'));
			} else {
				$msgArr = [
					'class' => 'danger',
					'msg' => 'There is some issue, driver not updated or some technical issue.',
				  ];
				 $this->session->set_userdata($msgArr);
				redirect(base_url('driver/list'));
			}
		}
	}
	public function deleteDriver($id)
	{
		$this->loginCheck();

		if ($this->DriverModel->deleteDriver($id)) {
			$msgArr = [
				'class' => 'success',
				'msg' => 'Driver Deleted Successfully.',
			  ];
			 $this->session->set_userdata($msgArr);
			redirect(base_url('driver/list'));
		} else {
			$msgArr = [
				'class' => 'danger',
				'msg' => 'There is some issue, driver not deleted or some technical issue.',
			  ];
			 $this->session->set_userdata($msgArr);
			redirect(base_url('driver/list'));
		}
	}


}
