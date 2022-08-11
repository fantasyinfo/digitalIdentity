<?php

class StudentModel extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
    $this->load->model('CrudModel');
	}

    public function saveStudent(array $post,array $files = [])
    {
       

      $insertArr = [];
      $insertArr['u_qr_id'] = '';
      $insertArr['name'] = $post['name'];
      $insertArr['user_id'] = $this->CrudModel->getUserId();
      $insertArr['class_id'] = $post['class'];
      $insertArr['section_id'] = $post['section'];
      $insertArr['roll_no'] = $post['roll_no'];
      $insertArr['gender'] = $post['gender'];
      $insertArr['mother_name'] = $post['mother'];
      $insertArr['father_name'] = $post['father'];
      $insertArr['mobile'] = $post['mobile'];
      $insertArr['email'] = $post['email'];
      $insertArr['dob'] = $post['dob'];
      $insertArr['address'] = $post['address'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['city_id'] = $post['city'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['image'] = '';


      $fileName = "";
      if(!empty($files['image']))
      {
        // upload files and get image path
        $fileName = $this->CrudModel->uploadImg($files,$insertArr['user_id']);
        $insertArr['image'] = $fileName;
      }
    

        if($this->CrudModel->insert(Table::studentTable,$insertArr))
        {
            return true;
        }else
        {
            return false;
        }

    }

    public function updateStudent(array $post,array $files = [])
    {
    
      $insertArr = [];
      $insertArr['name'] = $post['name'];
      $insertArr['class_id'] = $post['class'];
      $insertArr['section_id'] = $post['section'];
      $insertArr['roll_no'] = $post['roll_no'];
      $insertArr['gender'] = $post['gender'];
      $insertArr['mother_name'] = $post['mother'];
      $insertArr['father_name'] = $post['father'];
      $insertArr['mobile'] = $post['mobile'];
      $insertArr['email'] = $post['email'];
      $insertArr['dob'] = $post['dob'];
      $insertArr['address'] = $post['address'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['city_id'] = $post['city'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['image'] = @$post['image'];
      $insertArr['user_id'] = $post['user_id'];

      $stuId = $post['stuId'];

      $fileName = "";
      if(!empty($files['image']['tmp_name']))
      {
        // upload files and get image path
        $fileName = $this->CrudModel->uploadImg($files,$insertArr['user_id']);
        $insertArr['image'] = $fileName;
      }
    
        if($this->CrudModel->update(Table::studentTable,$insertArr,$stuId))
        {
            return true;
        }else
        {
            return false;
        }

    }

    public function listStudents($post)
    {
      if(isset($post))
      {
       return $this->CrudModel->showAllStudents(Table::studentTable,$post);
      }
    }

    public function singleStudent($id)
    {
      return $this->CrudModel->singleStudent(Table::studentTable,$id);
    }
    public function viewSingleStudentAllData($id)
    {
      return $this->CrudModel->viewSingleStudentAllData(Table::studentTable,$id);
    }

    public function deleteStudent($id)
    {
      return $this->CrudModel->deleteStudent(Table::studentTable,$id);
    }

    public function allClass(){
      return $this->CrudModel->allClass(Table::classTable);
    }
    public function allSection(){
      return $this->CrudModel->allSection(Table::sectionTable);
    }
    public function allCity(){
      return $this->CrudModel->allCity(Table::cityTable);
    }
    public function allState(){
      return $this->CrudModel->allState(Table::stateTable);
    }


}