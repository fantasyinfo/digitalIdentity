<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MasterController extends CI_Controller
{

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";
	public $masterDir = "pages/master/";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CrudModel');
	}

	public function loginCheck()
	{
		if(!$this->CrudModel->checkIsLogin())
		{
			header('Location: '.base_url());
		}
	}

	public function cityMaster()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'City Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'cityMaster');
	}

	public function stateMaster()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'State Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'stateMaster');
	}

	public function classMaster()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Class Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'classMaster');
	}

	public function sectionMaster()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Section Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'sectionMaster');
	}

	public function subjectMaster()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Subject Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'subjectMaster');
	}

	public function weekMaster()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Week Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'weekMaster');
	}

	public function hourMaster()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Hour Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'hourMaster');
	}

	public function teacherSubjectsMaster()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Teacher Subjects Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'teacherSubjectsMaster');
	}


	public function timeTableSheduleMaster()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Time Table Shedule Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'timeTableSheduleMaster');
	}

	public function panelUserMaster()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Panel User Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'panelUserMaster');
	}

	public function editPermission($id,$userType)
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Panel User Master',
			'adminPanelUrl' => $this->adminPanelURL,
			'id' => $id,
			'userType' => $userType
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'editPermission');
	}

	public function notificationMaster()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Notification Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'notificationMaster');
	}

	public function feesMaster()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Fees Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'feesMaster');
	}

	public function submitFeesMaster()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Fees Submit Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'submitFeesMaster');
	}

	public function monthMaster()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Months Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'monthMaster');
	}



	// this is only for super admin
	public function givePermissionMaster()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Permision Provider Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'givePermissionMaster');
	}
}
