<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FeesManagementController extends CI_Controller
{

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";
	public $digiDir = "pages/feeManagement/";

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


	public function feeTypeMaster()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Fee Type Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'feeTypeMaster');
	}
	public function feeGroupMaster()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Fee Group Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'feeGroupMaster');
	}
	public function feeDisctountMaster()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Fee Discount Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'feeDisctountMaster');
	}
	public function feeHeadMaster()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Fee Head Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'feeHeadMaster');
	}
	public function collectFee()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Collect Fee Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'collectFee');
	}
	public function collectStudentFee()
	{
		$this->loginCheck();
		// check permission
		//$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Collect Student Fee Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'collectStudentFee');
	}
	public function showStudentsForFees()
	{
		$this->loginCheck();
		// check permission
		//$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Students Lists For Fees',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'showStudentsForFees');
	}
	public function carryForward()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Fees Carry Forward',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'carryForward');
	}
	public function advanceFeesMaster()
	{
		//$this->loginCheck();
		// check permission
		//$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Fees Carry Forward',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'advanceFeesMaster');
	}




}
