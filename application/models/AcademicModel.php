<?php

class AcademicModel extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
        $this->load->model('CrudModel');

	}

    public function allAttendanceList($post)
    {
      if(isset($post))
      {
       return $this->CrudModel->allAttendanceList(Table::attendenceTable,$post);
      }
    }
    public function allTeachersAttendanceList($post)
    {
      if(isset($post))
      {
       return $this->CrudModel->allTeachersAttendanceList(Table::attendenceTeachersTable,$post);
      }
    }

    public function allResultList($post)
    {
      if(isset($post))
      {
       return $this->CrudModel->allResultList(Table::resultTable,$post);
      }
    }

    public function allComplaintList($post)
    {
      if(isset($post))
      {
       return $this->CrudModel->allComplaintList(Table::complaintTable,$post);
      }
    }

}