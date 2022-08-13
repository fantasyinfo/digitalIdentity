<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrontController extends CI_Controller {

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";
	public $studentDir = "pages/student/";
	public $frontViewDir = 'frontPanel/';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('StudentModel');
	}
	
	public function index()
	{
		$dataArr = [
			'pageTitle' => 'HomePage',
			'studentData' => $this->StudentModel->showStudentProfile(),
			'adminPanelUrl' => $this->adminPanelURL,
		];
		
		$this->load->view($this->frontViewDir .'pages/header',['data' => $dataArr]);
		$this->load->view($this->frontViewDir .'index');
		$this->load->view($this->frontViewDir .'pages/footer');
	}
}
