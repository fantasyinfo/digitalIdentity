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
		$this->load->model('TeacherModel');
		$this->load->library('session');
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
				$dataArr = [
					'pageTitle' => 'Login',
					'adminPanelUrl' => $this->adminPanelURL,
				];
				$this->load->view('adminPanel/index',['data' => $dataArr]);
			
			}
			
		
		}else if(isset($_GET['tecid']))
		{
			$dataArr = [
				'pageTitle' => 'HomePage',
				'studentData' => $this->TeacherModel->showTeacherProfile(),
				'adminPanelUrl' => $this->adminPanelURL,
			];
			
			if(isset($dataArr['studentData']) && !empty($dataArr['studentData']))
			{
				$this->load->view($this->frontViewDir .'pages/header',['data' => $dataArr]);
				$this->load->view($this->frontViewDir .'index');
				$this->load->view($this->frontViewDir .'pages/footer');
			}else
			{
				$dataArr = [
					'pageTitle' => 'Login',
					'adminPanelUrl' => $this->adminPanelURL,
				];
				$this->load->view('adminPanel/index',['data' => $dataArr]);
			
			}
		}
		
		else
		{
			$dataArr = [
				'pageTitle' => 'Login',
				'adminPanelUrl' => $this->adminPanelURL,
			];
			$this->load->view('adminPanel/index',['data' => $dataArr]);
			
			//header("Location: ".HelperClass::brandUrl."");
		}
	
		
	}

	public function register()
	{

		$dataArr = [
			'pageTitle' => 'Register',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view('adminPanel/register',['data' => $dataArr]);
		
	}

	public function logout()
	{
		$this->session->unset_userdata('id');
		$this->session->unset_userdata('name');
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('user_type');
		$this->session->unset_userdata('schoolUniqueCode');
		$this->session->unset_userdata('userData');

		redirect(base_url());
	}
}
