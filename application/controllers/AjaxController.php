<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AjaxController extends CI_Controller {

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('StudentModel');
		$this->load->model('TeacherModel');
		$this->load->model('CrudModel');
		$this->load->model('QRModel');
		$this->load->model('ExamModel');
	}

	public function listStudentsAjax()
	{
		if(isset($_POST))
		{
			return $this->StudentModel->listStudents($_POST);
		}
	}
	public function listTeachersAjax()
	{
		if(isset($_POST))
		{
			return $this->TeacherModel->listTeacher($_POST);
		}
	}
	public function allExamList()
	{
		if(isset($_POST))
		{
			return $this->ExamModel->allExamList($_POST);
		}
	}


	public function showStudentViaClassAndSectionId()
	{
		if(isset($_POST))
		{
			//HelperClass::prePrintR($_POST);
			echo $this->StudentModel->showStudentViaClassAndSectionId($_POST);
		}
	}

	public function totalFeesDue()
	{
		if(isset($_POST))
		{
			//HelperClass::prePrintR($_POST);
			echo $this->StudentModel->totalFeesDue($_POST);
		}
	}


	public function listDigiCoinAjax()
	{
		if(isset($_POST))
		{
			return $this->CrudModel->listDigiCoin(Table::getDigiCoinTable,$_POST['user_type'], $_POST);
		}
	}

	public function showCityViaStateId()
	{
		if(isset($_POST['stateId']))
		{
			echo $this->CrudModel->showCityViaStateId($_POST['stateId']);
		}
	}



	public function listQR()
	{
		$dataArr = [
			'pageTitle' => 'QR Code List',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view('adminPanel/pages/header', ['data' => $dataArr]);
		$this->load->view('adminPanel/pages/qrcode/list');
	}
	public function listQRCodeAjax()
	{
		if(isset($_POST))
		{
			return $this->QRModel->listQR($_POST);
		}
	}
}