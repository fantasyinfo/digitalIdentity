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
	}

	public function cityMaster()
	{
		$dataArr = [
			'pageTitle' => 'City Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'cityMaster');
	}

	public function stateMaster()
	{
		$dataArr = [
			'pageTitle' => 'State Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'stateMaster');
	}

	public function classMaster()
	{
		$dataArr = [
			'pageTitle' => 'Class Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'classMaster');
	}

	public function sectionMaster()
	{
		$dataArr = [
			'pageTitle' => 'Section Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'sectionMaster');
	}

	public function subjectMaster()
	{
		$dataArr = [
			'pageTitle' => 'Subject Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->masterDir . 'subjectMaster');
	}
}
