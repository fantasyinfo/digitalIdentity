<?php

class SchoolModel extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
    $this->load->model('CrudModel');
   
	}

    public function showSchoolData()
    {
      $s = $this->db->query("SELECT * FROM ".Table::schoolMasterTable." WHERE unique_id = '{$_SESSION['schoolUniqueCode']}' ORDER BY id DESC LIMIT 1 ")->result_array();
      return $s;
    }


}