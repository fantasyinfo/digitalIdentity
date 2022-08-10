<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StudentController extends CI_Controller {

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";
	public $studentDir = "pages/student/";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('StudentModel');
	}

	public function list()
	{
		$dataArr = [
			'pageTitle' => 'Student List',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view($this->viewDir .'pages/header',['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->studentDir .'list');
		$this->load->view($this->viewDir .'pages/footer');
	}

	public function addStudent()
	{
		$dataArr = [
			'pageTitle' => 'Add New Student',
			'adminPanelUrl' => $this->adminPanelURL,
			'submitFormUrl' => base_url('student/saveStudent'),
		];
		$this->load->view($this->viewDir .'pages/header',['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->studentDir .'add');
		$this->load->view($this->viewDir .'pages/footer');
	}

	public function saveStudent()
	{
		//print_r($_POST);print_r($_FILES);die();
		if(isset($_POST['submit']))
		{
			if($this->StudentModel->saveStudent($_POST,$_FILES))
			{
				$this->list();
			}else
			{
				$this->addStudent();
			}
		}
		
	}
}
