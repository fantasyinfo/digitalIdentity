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
		$this->load->model('DriverModel');
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
	public function listDriversAjax()
	{
		if(isset($_POST))
		{
			return $this->DriverModel->listDriver($_POST);
		}
	}
	public function allExamList()
	{
		if(isset($_POST))
		{
			return $this->ExamModel->allExamList($_POST);
		}
	}

	public function allResultList()
	{
		if(isset($_POST))
		{
			return $this->ExamModel->allResultList($_POST);
		}
	}
	
	public function teacherReviewsList()
	{
		if(isset($_POST))
		{
			return $this->TeacherModel->teacherReviewsList($_POST);
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


	public function showDriverListViaVechicleType()
	{
		if(isset($_POST))
		{
			//HelperClass::prePrintR($_POST);
			echo $this->DriverModel->showDriverListViaVechicleType($_POST);
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


	public function getLatLng()
	{
		header('Access-Control-Allow-Origin: *');
		$latLng = $this->db->query("SELECT * FROM ".Table::driverTable." WHERE status != '4' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND lat IS NOT NULL AND lng IS NOT NULL")->result_array();
  
		if(!empty($latLng))
		{
			$totalC = count($latLng);
			$latLngArr = [];
			for($i=0; $i < $totalC; $i++)
			{
			$subArr = [
				'lat' => (float) $latLng[$i]['lat'],
				'lng' => (float) $latLng[$i]['lng'],
				'name' =>  $latLng[$i]['name'],
				'mobile' =>  $latLng[$i]['mobile'],
				'vechicle_type' =>  HelperClass::vehicleType[$latLng[$i]['vechicle_type']],
				'vechicle_no' =>  $latLng[$i]['vechicle_no'],
				'total_seats' =>  $latLng[$i]['total_seats'],
			];
			array_push($latLngArr,$subArr);
			}
			echo json_encode($latLngArr);
			die();
		}
	}
}