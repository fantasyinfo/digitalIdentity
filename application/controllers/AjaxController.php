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
		$this->load->model('QRModel');
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