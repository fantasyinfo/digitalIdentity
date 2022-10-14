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
      $insertArr['schoolUniqueCode'] = $_SESSION['schoolUniqueCode'];
      $insertArr['u_qr_id'] = '';
      $insertArr['name'] = $post['name'];
      $insertArr['user_id'] = $this->CrudModel->getTeacherId(Table::teacherTable);
      $insertArr['class_id'] = $post['class'];
      $insertArr['section_id'] = $post['section'];
      $insertArr['gender'] = $post['gender'];
      $insertArr['mother_name'] = $post['mother'];
      $insertArr['father_name'] = $post['father'];
      $insertArr['mobile'] = $post['mobile'];
      $insertArr['password'] = HelperClass::makeRandomPassword();
      $insertArr['email'] = $post['email'];
      $insertArr['dob'] = $post['dob'];
      $insertArr['doj'] = $post['doj'];
      $insertArr['address'] = $post['address'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['city_id'] = $post['city'];
      $insertArr['pincode'] = $post['pincode'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['education'] = $post['education'];
      $insertArr['experience'] = $post['experience'];
      $insertArr['image'] = '';

      
      // check if the teacher is already registerd with us
      $already = $this->db->query("SELECT * FROM ".Table::teacherTable." WHERE name = '{$insertArr['name']}' AND mobile = '{$insertArr['mobile']}' AND mother_name = '{$insertArr['mother_name']}' AND father_name = '{$insertArr['father_name']}' AND dob = '{$insertArr['dob']}'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();


      if(!empty($already))
      {
        return false;
      }




      $fileName = "";
      if(!empty($files['image']))
      {
        // upload files and get image path
        $fileName = $this->CrudModel->uploadImg($files,$insertArr['user_id'],HelperClass::teacherImagePath);
        $insertArr['image'] = $fileName;
      }
    
        $insertId = $this->CrudModel->insert(Table::teacherTable,$insertArr);
        if($insertId)
        {
          // return true;
          // die();
          //insert qrcode data
          $qrDataArr = [];
          $qrDataArr['schoolUniqueCode'] = $_SESSION['schoolUniqueCode'];
          $qrDataArr['qrcodeUrl'] = HelperClass::qrcodeUrl . "?tecid=" . HelperClass::schoolPrefix . $insertArr['user_id'];
          $qrDataArr['uniqueValue'] = $insertArr['user_id'];
          $qrDataArr['type'] = HelperClass::userType['Teacher'];
          $qrDataArr['user_id'] = $insertId;

          $qrInsertId = $this->CrudModel->insert(Table::qrcodeTeachersTable,$qrDataArr);
          if($qrInsertId)
          {
            $updateArr['u_qr_id'] = $qrInsertId;
            if($this->CrudModel->update(Table::teacherTable,$updateArr,$insertId))
            {
              return true;
            }else
            {
              echo $this->db->last_query();
              die();
              return false;
            }
          }else
          {
            echo $this->db->last_query();
            die();
            return false;
          }
         
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
      $insertArr['education'] = $post['education'];
      $insertArr['experience'] = $post['experience'];
      $insertArr['image'] = @$post['image'];
      $insertArr['user_id'] = $post['user_id'];

      $tecId = $post['tecId'];

      $fileName = "";
      if(!empty($files['image']['tmp_name']))
      {
        // upload files and get image path
        $fileName = $this->CrudModel->uploadImg($files,$insertArr['user_id'],HelperClass::teacherImagePath);
        $insertArr['*'] = $fileName;
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

    public function teacherReviewsList($post)
    {
      if(isset($post))
      {
       return $this->CrudModel->teacherReviewsList(Table::ratingAndReviewTable,$post);
      }
    }

    public function singleTeacher($id)
    {
      return $this->CrudModel->singleTeacher(Table::teacherTable,$id);
    }
    public function showTeacherProfile()
    {
        if(isset($_GET['tecid']))
        {
          $userId = explode(HelperClass::schoolPrefix,$_GET['tecid']);
          return $this->CrudModel->showTeacherProfile(Table::teacherTable,$userId[1]);
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

    public function allClass($schoolUniqueCode){
      return $this->CrudModel->allClass(Table::classTable,$schoolUniqueCode);
    }
    public function allSection($schoolUniqueCode){
      return $this->CrudModel->allSection(Table::sectionTable,$schoolUniqueCode);
    }
    public function allCity($schoolUniqueCode){
      return $this->CrudModel->allCity(Table::cityTable,$schoolUniqueCode);
    }
    public function allState($schoolUniqueCode){
      return $this->CrudModel->allState(Table::stateTable,$schoolUniqueCode);
    }


}