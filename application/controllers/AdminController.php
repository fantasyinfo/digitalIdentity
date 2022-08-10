<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminController extends CI_Controller {

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";

	public function index()
	{
		$dataArr = [
			'pageTitle' => 'HomePage',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view($this->viewDir .'pages/header',['data' => $dataArr]);
		$this->load->view($this->viewDir .'index');
		$this->load->view($this->viewDir .'pages/footer');
	}
}
