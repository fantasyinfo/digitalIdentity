<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SchoolController extends CI_Controller {

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";
	public $schoolDir = "pages/school/";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('SchoolModel');
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


	public function schoolProfile()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();

		$dataArr = [
			'pageTitle' => 'School Profile',
			'adminPanelUrl' => $this->adminPanelURL,
			'schoolData' => $this->SchoolModel->showSchoolData(),
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->schoolDir . 'schoolProfile');
		
	}


}
