<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CronController extends CI_Controller
{

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";
	public $digiDir = "pages/cron/";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CrudModel');
	}


	// exam alert cron
	public function examAlert()
	{
		
		$this->load->view($this->viewDir . $this->digiDir . 'examAlert');
	}

	// result alert cron
	public function resultAlert()
	{
		
		$this->load->view($this->viewDir . $this->digiDir . 'resultAlert');
	}




}
