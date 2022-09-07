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
      $insertArr['schoolUniqueCode'] = $_SESSION['schoolUniqueCode'];
      $insertArr['u_qr_id'] = '';
      $insertArr['name'] = $post['name'];
      $insertArr['user_id'] = $this->CrudModel->getUserId(Table::studentTable);
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
      $insertArr['pincode'] = $post['pincode'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['image'] = '';



      // check if this mobile number is also using any of the other child of this parent then use the same password
      $alreadyMobile = $this->db->query("SELECT password FROM ".Table::studentTable." WHERE mobile = '{$insertArr['mobile']}'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ")->result_array();

      if(!empty($alreadyMobile[0]))
      {
        $insertArr['password'] = $alreadyMobile[0]['password'];
      }else
      {
        $insertArr['password'] = HelperClass::makeRandomPassword();
      }



    


      // check if the student is already registerd with us
      $already = $this->db->query("SELECT * FROM ".Table::studentTable." WHERE name = '{$insertArr['name']}' AND mobile = '{$insertArr['mobile']}' AND mother_name = '{$insertArr['mother_name']}' AND father_name = '{$insertArr['father_name']}' AND dob = '{$insertArr['dob']}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();


      if(!empty($already[0]))
      {
        return false;
      }


      $fileName = "";
      if(!empty($files['image']))
      {
        // upload files and get image path
        $fileName = $this->CrudModel->uploadImg($files,$insertArr['user_id']);
        $insertArr['image'] = $fileName;
      }
    
        $insertId = $this->CrudModel->insert(Table::studentTable,$insertArr);
        if($insertId)
        {
          // insert qrcode data
          $qrDataArr = [];
          $qrDataArr['schoolUniqueCode'] = $_SESSION['schoolUniqueCode'];
          $qrDataArr['qrcodeUrl'] = HelperClass::qrcodeUrl . "?stuid=" . HelperClass::schoolPrefix . $insertArr['user_id'];
          $qrDataArr['uniqueValue'] = $insertArr['user_id'];
          $qrDataArr['type'] = HelperClass::userType['Student'];
          $qrDataArr['user_id'] = $insertId;

          $qrInsertId = $this->CrudModel->insert(Table::qrcodeTable,$qrDataArr);
          if($qrInsertId)
          {
            $updateArr['u_qr_id'] = $qrInsertId;
            if($this->CrudModel->update(Table::studentTable,$updateArr,$insertId))
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
        { echo $this->db->last_query();
          die();
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
      $insertArr['pincode'] = $post['pincode'];
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
    public function showStudentProfile()
    {
        if(isset($_GET['stuid']))
        {
          $userId = explode(HelperClass::schoolPrefix,$_GET['stuid']);
          return $this->CrudModel->showStudentProfile(Table::studentTable,@$userId[1]);
        }
    }
    public function viewSingleStudentAllData($id)
    {
      return $this->CrudModel->viewSingleStudentAllData(Table::studentTable,$id);
    }

    public function deleteStudent($id)
    {
      return $this->CrudModel->deleteStudent(Table::studentTable,$id);
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


    public function showStudentViaClassAndSectionId(array $p)
    {
      if(!empty($p))
      {
        $d = $this->db->query($sql = "SELECT * FROM ".Table::studentTable." WHERE class_id = '{$p['classId']}' AND section_id = '{$p['sectionId']}' AND status = '1'")->result_array();
        $html = '';
        if(!empty($d))
        {
          foreach($d as $dd)
          {
           $html .= "<option value='{$dd['id']}'>{$dd['name']}</option>";
          }
          return json_encode($html);
        }
        return json_encode($sql);
      }
    }

}