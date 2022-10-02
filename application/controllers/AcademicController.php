<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AcademicController extends CI_Controller
{

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";
	public $digiDir = "pages/academic/";

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

	public function checkPermission()
	{
		if(!$this->CrudModel->checkPermission())
		{
			header('Location: '.base_url());
		}
	}


	public function allAttendance()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'All Attendance',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'allAttendance');
	}

	public function allTeachersAttendance()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'All Teachers Attendance',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'allTeachersAttendance');
	}

	public function allDeparture()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'All Departure',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'allDeparture');
	}

	public function allComplaints()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'All Complaints',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'allComplaints');
	}


}
