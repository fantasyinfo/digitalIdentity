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

    public function allResultList($post)
    {
      if(isset($post))
      {
       return $this->CrudModel->allResultList(Table::resultTable,$post);
      }
    }
    public function showAllSemesterResultsList($post)
    {
      if(isset($post))
      {
       return $this->CrudModel->showAllSemesterResultsList(Table::semExamResults,$post);
      }
    }

}