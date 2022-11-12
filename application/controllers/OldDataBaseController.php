<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OldDataBaseController extends CI_Controller
{

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";
	public $digiDir = "pages/oldDataBase/";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CrudModel');
		$this->load->model('FeesManagementModel');
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

	public function studentsLists()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Old Students Lists',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'studentsLists');
	}




}
