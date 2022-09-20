<?php

class ExamModel extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
        $this->load->model('CrudModel');

	}

    public function allExamList($post)
    {
      if(isset($post))
      {
       return $this->CrudModel->allExamList(Table::examTable,$post);
      }
    }

}