<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AjaxController extends CI_Controller {

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";

	public function listStudentsAjax($request = '')
	{
		print_r($_POST);
        die();
		// $this->load->view($this->viewDir .'pages/header',['data' => $dataArr]);
		// $this->load->view($this->viewDir .'index');
		// $this->load->view($this->viewDir .'pages/footer');
	}
}