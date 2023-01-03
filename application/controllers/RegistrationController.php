<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RegistrationController extends CI_Controller
{

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";
	public $digiDir = "pages/registration/";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CrudModel');
		$this->load->model('StudentModel');
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


	public function newRegistration()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Registration',
			'adminPanelUrl' => $this->adminPanelURL,
			'class' => $this->StudentModel->allClass($this->schoolUniqueCode),
			'section' => $this->StudentModel->allSection($this->schoolUniqueCode),
			// 'city' => $this->StudentModel->allCity($this->schoolUniqueCode),
			'state' => $this->StudentModel->allState($this->schoolUniqueCode),
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'newRegistration');
	}

	public function registrationLists()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Registration',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'registrationLists');
	}




}
