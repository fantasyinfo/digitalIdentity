<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DigiCoinController extends CI_Controller
{

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";
	public $digiDir = "pages/digicoin/";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CrudModel');
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


	public function setDigiCoinMaster()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'DigiCoin Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'setDigiCoinMaster');
	}

	public function giftMaster()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Gift Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'giftMaster');
	}

	public function giftRedeemMaster()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Gift Redeem Master',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'giftRedeemMaster');
	}

	public function studentDigiCoin()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Students DigiCoin',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'studentDigiCoinList');
	}

	public function teacherDigiCoin()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Teachers DigiCoin',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'teacherDigiCoinList');
	}

	public function leaderBoard()
	{
		$this->loginCheck();
		// check permission
		$this->checkPermission();
		$dataArr = [
			'pageTitle' => 'Leader Board',
			'adminPanelUrl' => $this->adminPanelURL
		];
		$this->load->view($this->viewDir . 'pages/header', ['data' => $dataArr]);
		$this->load->view($this->viewDir . $this->digiDir . 'leaderBoard');
	}


	public function changeGiftStatus()
	{
		if(isset($_POST['status']) && isset($_POST['editId']))
		{
			$status = $_POST['status'];
			$updateId = $_POST['editId'];
			$schoolCode = $_POST['schoolCode'];
			$updateStatus = $this->db->query("UPDATE " . Table::giftRedeemTable . " SET status = '$status' WHERE id = '$updateId'  AND schoolUniqueCode = '$schoolCode' ");

			if($updateStatus)
			{
				$msgArr = [
					'class' => 'success',
					'msg' => 'Gift Redeem Status Updated Successfully',
				];
				$this->session->set_userdata($msgArr);
			}else
			{
				$msgArr = [
					'class' => 'danger',
					'msg' => 'Gift Redeem Status Not Updated Due to this Error. ' . $this->db->last_query(),
				];
				$this->session->set_userdata($msgArr);
			}
			echo json_encode(array('status' => true));
		}
	}

}
