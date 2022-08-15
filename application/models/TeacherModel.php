<?php

class TeacherModel extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
    $this->load->model('CrudModel');
	}

    public function saveTeacher(array $post,array $files = [])
    {
       
      $insertArr = [];
      $insertArr['u_qr_id'] = '';
      $insertArr['name'] = $post['name'];
      $insertArr['user_id'] = $this->CrudModel->getTeacherId(Table::teacherTable);
      $insertArr['class_id'] = $post['class'];
      $insertArr['section_id'] = $post['section'];
      $insertArr['gender'] = $post['gender'];
      $insertArr['mother_name'] = $post['mother'];
      $insertArr['father_name'] = $post['father'];
      $insertArr['mobile'] = $post['mobile'];
      $insertArr['password'] = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ".rand(000000000,999999999)),0,6) ;
      $insertArr['email'] = $post['email'];
      $insertArr['dob'] = $post['dob'];
      $insertArr['doj'] = $post['doj'];
      $insertArr['address'] = $post['address'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['city_id'] = $post['city'];
      $insertArr['pincode'] = $post['pincode'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['image'] = '';


      $fileName = "";
      if(!empty($files['image']))
      {
        // upload files and get image path
        $fileName = $this->CrudModel->uploadImg($files,$insertArr['user_id']);
        $insertArr['image'] = $fileName;
      }
    
        $insertId = $this->CrudModel->insert(Table::teacherTable,$insertArr);
        if($insertId)
        {
          return true;
          die();
          // insert qrcode data
          // $qrDataArr = [];
          // $qrDataArr['qrcodeUrl'] = HelperClass::qrcodeUrl . "?stuid=" . HelperClass::schoolPrefix . $insertArr['user_id'];
          // $qrDataArr['uniqueValue'] = $insertArr['user_id'];
          // $qrDataArr['type'] = HelperClass::userType['Teacher'];
          // $qrDataArr['user_id'] = $insertId;

          // $qrInsertId = $this->CrudModel->insert(Table::qrcodeTable,$qrDataArr);
          // if($qrInsertId)
          // {
          //   $updateArr['u_qr_id'] = $qrInsertId;
          //   if($this->CrudModel->update(Table::teacherTable,$updateArr,$insertId))
          //   {
          //     return true;
          //   }else
          //   {
          //     echo $this->db->last_query();
          //     die();
          //     return false;
          //   }
          // }else
          // {
          //   echo $this->db->last_query();
          //   die();
          //   return false;
          // }
         
        }else
        {
            return false;
        }

    }

    public function updateTeacher(array $post,array $files = [])
    {
    
      $insertArr = [];
      $insertArr['name'] = $post['name'];
      $insertArr['class_id'] = $post['class'];
      $insertArr['section_id'] = $post['section'];
      $insertArr['gender'] = $post['gender'];
      $insertArr['mother_name'] = $post['mother'];
      $insertArr['father_name'] = $post['father'];
      $insertArr['mobile'] = $post['mobile'];
      $insertArr['email'] = $post['email'];
      $insertArr['dob'] = $post['dob'];
      $insertArr['doj'] = $post['doj'];
      $insertArr['address'] = $post['address'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['city_id'] = $post['city'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['pincode'] = $post['pincode'];
      $insertArr['image'] = @$post['image'];
      $insertArr['user_id'] = $post['user_id'];

      $tecId = $post['tecId'];

      $fileName = "";
      if(!empty($files['image']['tmp_name']))
      {
        // upload files and get image path
        $fileName = $this->CrudModel->uploadImg($files,$insertArr['user_id']);
        $insertArr['image'] = $fileName;
      }
    
        if($this->CrudModel->update(Table::teacherTable,$insertArr,$tecId))
        {
            return true;
        }else
        {
            return false;
        }

    }

    public function listTeacher($post)
    {
      if(isset($post))
      {
       return $this->CrudModel->showAllTeachers(Table::teacherTable,$post);
      }
    }

    public function singleTeacher($id)
    {
      return $this->CrudModel->singleTeacher(Table::teacherTable,$id);
    }
    public function showTeacherProfile()
    {
        if(isset($_GET['stuid']))
        {
          $userId = explode(HelperClass::schoolPrefix,$_GET['stuid']);
          return $this->CrudModel->showStudentProfile(Table::teacherTable,$userId[1]);
        }
    }
    public function viewSingleTeacherAllData($id)
    {
      return $this->CrudModel->viewSingleTeacherAllData(Table::teacherTable,$id);
    }

    public function deleteTeacher($id)
    {
      return $this->CrudModel->deleteStudent(Table::teacherTable,$id);
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