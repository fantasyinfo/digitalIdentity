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
		if(isset($_GET['stuid']))
		{
			$dataArr = [
				'pageTitle' => 'HomePage',
				'studentData' => $this->StudentModel->showStudentProfile(),
				'adminPanelUrl' => $this->adminPanelURL,
			];
			
			if(isset($dataArr['studentData']) && !empty($dataArr['studentData']))
			{
				$this->load->view($this->frontViewDir .'pages/header',['data' => $dataArr]);
				$this->load->view($this->frontViewDir .'index');
				$this->load->view($this->frontViewDir .'pages/footer');
			}else
			{
				$this->load->view($this->frontViewDir .'404');
			}
			
		}else
		{
			//header("Location: ".HelperClass::brandUrl."");
		}
	
		
	}
}
