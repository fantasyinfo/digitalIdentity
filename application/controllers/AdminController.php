<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminController extends CI_Controller {

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CrudModel');
	}

	public function index()
	{
		// login check
		$this->loginCheck();
		// check permission
		$this->checkPermission();


		$dataArr = [
			'pageTitle' => 'Dashboard',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view($this->viewDir .'pages/header',['data' => $dataArr]);
		$this->load->view($this->viewDir .'dashboard');
		//$this->load->view($this->viewDir .'pages/footer');
	}
	public function newOptions()
	{
		// login check
		$this->loginCheck();
		// check permission
		//$this->checkPermission();


		$dataArr = [
			'pageTitle' => 'Menus',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view($this->viewDir .'pages/header',['data' => $dataArr]);
		$this->load->view($this->viewDir .'newOptions');
		//$this->load->view($this->viewDir .'pages/footer');
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


}
