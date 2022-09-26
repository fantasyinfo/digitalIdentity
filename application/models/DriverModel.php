<?php

class DriverModel extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
    $this->load->model('CrudModel');
	}

    public function saveDriver(array $post,array $files = [])
    {
       
      $insertArr = [];
      $insertArr['schoolUniqueCode'] = $_SESSION['schoolUniqueCode'];
      $insertArr['u_qr_id'] = '';
      $insertArr['name'] = $post['name'];
      $insertArr['user_id'] = $this->CrudModel->getDriverId(Table::driverTable);
      $insertArr['mobile'] = $post['mobile'];
      $insertArr['password'] = HelperClass::makeRandomPassword();
      $insertArr['email'] = $post['email'];
      $insertArr['address'] = $post['address'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['city_id'] = $post['city'];
      $insertArr['pincode'] = $post['pincode'];
      $insertArr['vechicle_type'] = $post['vechicle_type'];
      $insertArr['vechicle_no'] = $post['vechicle_no'];
      $insertArr['total_seats'] = $post['total_seats'];
      $insertArr['image'] = '';

      
      // check if the driver is already registerd with us
      $already = $this->db->query($sql = "SELECT * FROM ".Table::driverTable." WHERE name = '{$insertArr['name']}' AND mobile = '{$insertArr['mobile']}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

      if(!empty($already))
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
    
 
        $insertId = $this->CrudModel->insert(Table::driverTable,$insertArr);
        if($insertId)
        {
          // return true;
          // die();
          //insert qrcode data
          $qrDataArr = [];
          $qrDataArr['schoolUniqueCode'] = $_SESSION['schoolUniqueCode'];
          $qrDataArr['qrcodeUrl'] = HelperClass::qrcodeUrl . "?driid=" . HelperClass::schoolPrefix . $insertArr['user_id'];
          $qrDataArr['uniqueValue'] = $insertArr['user_id'];
          $qrDataArr['type'] = HelperClass::userType['Driver'];
          $qrDataArr['user_id'] = $insertId;

          $qrInsertId = $this->CrudModel->insert(Table::qrcodeDriversTable,$qrDataArr);
          if($qrInsertId)
          {
            $updateArr['u_qr_id'] = $qrInsertId;
            if($this->CrudModel->update(Table::driverTable,$updateArr,$insertId))
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

    public function updateDriver(array $post,array $files = [])
    {
    
      $insertArr = [];
      $insertArr['name'] = $post['name'];
      $insertArr['mobile'] = $post['mobile'];
      $insertArr['email'] = $post['email'];
      $insertArr['address'] = $post['address'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['city_id'] = $post['city'];
      $insertArr['state_id'] = $post['state'];
      $insertArr['pincode'] = $post['pincode'];
      $insertArr['image'] = @$post['image'];
      $insertArr['user_id'] = $post['user_id'];
      $insertArr['vechicle_type'] = $post['vechicle_type'];
      $insertArr['vechicle_no'] = $post['vechicle_no'];
      $insertArr['total_seats'] = $post['total_seats'];
      $driId = $post['driId'];

      $fileName = "";
      if(!empty($files['image']['tmp_name']))
      {
        // upload files and get image path
        $fileName = $this->CrudModel->uploadImg($files,$insertArr['user_id']);
        $insertArr['image'] = $fileName;
      }
    
        if($this->CrudModel->update(Table::driverTable,$insertArr,$driId))
        {
            return true;
        }else
        {
            return false;
        }

    }

    public function listDriver($post)
    {
      if(isset($post))
      {
       return $this->CrudModel->showAllDrivers(Table::driverTable,$post);
      }
    }

    public function teacherReviewsList($post)
    {
      if(isset($post))
      {
       return $this->CrudModel->teacherReviewsList(Table::ratingAndReviewTable,$post);
      }
    }

    public function singleDriver($id)
    {
      return $this->CrudModel->singleDriver(Table::driverTable,$id);
    }
    public function showTeacherProfile()
    {
        if(isset($_GET['tecid']))
        {
          $userId = explode(HelperClass::schoolPrefix,$_GET['tecid']);
          return $this->CrudModel->showTeacherProfile(Table::teacherTable,$userId[1]);
        }
    }
    public function viewSingleDriverAllData($id)
    {
      return $this->CrudModel->viewSingleDriverAllData(Table::driverTable,$id);
    }

    public function deleteDriver($id)
    {
      return $this->CrudModel->deleteStudent(Table::driverTable,$id);
    }


}