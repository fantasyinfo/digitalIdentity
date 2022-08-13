<?php

class CrudModel extends CI_Model
{
    private $tableName  = '';
    private $student_id = '';
    private $messageArr = array();

	public function __construct()
	{
		$this->load->database();
	}


    public function insert($tableName = "", $params = array())
    {
        if (!empty($tableName) && !empty($params)) {


            $this->tableName = $tableName;

            $keys   = array_keys($params);
            $values = array_values($params);

            // $data = array();

            $keys   =  implode(', ', $keys);
            $values =  implode("','", $values);

            $sql    = 'INSERT INTO ' . $this->tableName . ' (' . $keys . ') VALUES (' . "'$values'" . ') ';
          
            $this->db->query($sql);
            if($this->db->insert_id() != '')
            {
                return $this->db->insert_id();
            }else 
            {
                return false;
            }
        }
    }

    public function update($tableName, $params,$stuId)
    {
        if (!empty($tableName) && !empty($params) && !empty($stuId)) {

            $this->tableName    = $tableName;
            $this->student_id   = $stuId;

            $keys               = array_keys($params);
            $values             = array_values($params);

            $combine = "";
            for ($i = 0; $i < count($params); $i++) {
                $combine .= "$keys[$i] = " . "'$values[$i]'" . " , ";
            }
            $combine = rtrim($combine, ' , ');

            $sql = 'UPDATE ' . $this->tableName . ' SET ' . $combine . ' WHERE id = ' . $this->student_id . ' ';
            if($this->db->query($sql))
            {
                return true;
            }else 
            {
                return false;
            }
            
        }
    }

    public function getUserId($tableName)
    {
        $this->tableName    = $tableName;
        $dd = $this->db->query("SELECT user_id FROM " .$this->tableName ." WHERE user_id IS NOT NULL ORDER BY id DESC LIMIT 1")->result_array();
        $id = explode(HelperClass::prefix,$dd[0]['user_id']);
        return HelperClass::prefix . ($id[1] + 1);
    }
    public function getTeacherId($tableName)
    {
        $this->tableName    = $tableName;
        $dd = $this->db->query("SELECT user_id FROM " .$this->tableName ." WHERE user_id IS NOT NULL ORDER BY id DESC LIMIT 1")->result_array();
        $id = explode(HelperClass::tecPrefix,$dd[0]['user_id']);
        return HelperClass::tecPrefix . ($id[1] + 1);
    }

    public function uploadImg(array $file,$id = '')
    {
       
        $errors= array();
        $file_name = HelperClass::imgPrefix . $id ."-".$file['image']['name'];
        $file_size =$file['image']['size'];
        $file_tmp =$file['image']['tmp_name'];
        $file_type=$file['image']['type'];
        $explode = explode('.',$file['image']['name']);
        $end = end($explode);
        $file_ext=strtolower($end);
        
        $extensions= array("jpeg","jpg","png","webp","svg");
        
        if(in_array($file_ext,$extensions)=== false){
           $errors[]="extension not allowed, please choose a JPEG or PNG file.";
        }
        
        if($file_size > 2097152){
           $errors[]='File size must be excately 2 MB';
        }
        
        if(empty($errors)==true){
           move_uploaded_file($file_tmp,HelperClass::uploadImgDir.$file_name);
           return $file_name;
        }else{
          return false;
        }
      
    }


    public function showAllStudents($tableName,$data = '')
    {
        $this->tableName = $tableName;
        $dir = base_url().HelperClass::uploadImgDir;
        if(!empty($data))
        {
            $condition = '';

            if(isset($data['studentName']) || isset($data['studentClass']) || isset($data['studentMobile']) || isset($data['studentUserId']) || isset($data['studentFromDate']) || isset($data['studentToDate']))
            {
                if(!empty($data['studentName']))
                {
                    $condition .= " AND s.name LIKE '%{$data['studentName']}%' ";
                }
                if(!empty($data['studentClass']))
                {
                    $condition .= " AND c.className LIKE '%{$data['studentClass']}%' ";
                }
                if(!empty($data['studentMobile']))
                {
                    $condition .= " AND s.mobile = '{$data['studentMobile']}' ";
                }
                if(!empty($data['studentUserId']))
                {
                    $condition .= " AND s.user_id = '{$data['studentUserId']}' ";
                }
                if(!empty($data['studentFromDate']) && !empty($data['studentToDate']))
                {
                    $condition .= " AND s.created_at BETWEEN '{$data['studentFromDate']}' AND  '{$data['studentToDate']}' ";
                }
            
                if(!empty($data['search']['value']))
                {
                    $condition .= " 
                    OR s.name LIKE '%{$data['search']['value']}%' 
                    OR c.className LIKE '%{$data['search']['value']}%' 
                    OR s.mobile LIKE '%{$data['search']['value']}%' 
                    OR s.user_id LIKE '%{$data['search']['value']}%' 
                    ";
                }
            }
      
                $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,if(s.status = '1', 'Active','InActive')as status,s.name,s.user_id,s.mobile,s.dob,s.pincode,c.className,ss.sectionName,st.stateName,ct.cityName FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 0 $condition ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $countSql = "SELECT count(s.id) as count  FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 0 $condition ORDER BY s.id DESC";
            }else
            {
                $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,if(s.status = '1', 'Active','InActive')as status,s.name,s.user_id,s.mobile,s.dob,s.pincode,c.className,ss.sectionName,st.stateName,ct.cityName FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 0 ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $countSql = "SELECT count(s.id) as count FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 0 ORDER BY s.id DESC";
            }


            $tCount = $this->db->query($countSql)->result_array();

            $sendArr = [];
            for($i=0;$i<count($d);$i++)
            {
                $subArr = [];
               
                $subArr[] = ($j = $i + 1);
                $subArr[] = "<img src='{$d[$i]['image']}' alt='100x100' height='50px' width='50px' class='img-fluid rounded-circle' />";
                $subArr[] = $d[$i]['name'];
                $subArr[] = $d[$i]['user_id'];
                $subArr[] = $d[$i]['mobile'];
                $subArr[] = $d[$i]['className']. " - ".$d[$i]['sectionName'];
                $subArr[] = $d[$i]['stateName']. " - ".$d[$i]['cityName'] . " - " . $d[$i]['pincode'];

                  if($d[$i]['status'] == 'Active')
                    {
                        $subArr[] = '<span class="badge badge-success">'.$d[$i]['status'].'</span>';
                    }else{
                        $subArr[] = '<span class="badge badge-success">'.$d[$i]['status'].'</span>';
                    };

                $subArr[] = date('d-m-Y', strtotime($d[$i]['dob']));
                $subArr[] = '
                <a href="viewStudent/'.$d[$i]['id'].'" class="btn btn-primary" ><i class="fas fa-eye"></i></a>  
                <a href="editStudent/'.$d[$i]['id'].'" class="btn btn-warning" ><i class="fas fa-edit"></i></a>  
                <a href="deleteStudent/'.$d[$i]['id'].'" class="btn btn-danger" 
                onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fas fa-trash"></i></a>';

                $sendArr[] = $subArr;
            }

        $dataTableArr = [
            "draw"=> $data['draw'],
            "recordsTotal"=> $tCount[0]['count'],
            "recordsFiltered"=> $tCount[0]['count'],
            "data"=>$sendArr
        ];

        echo json_encode($dataTableArr);
        
    }
    public function showAllTeachers($tableName,$data = '')
    {
        $this->tableName = $tableName;
        $dir = base_url().HelperClass::uploadImgDir;
        if(!empty($data))
        {
            $condition = '';

            if(isset($data['teacherName']) || isset($data['teacherClass']) || isset($data['teacherMobile']) || isset($data['teacherUserId']) || isset($data['teacherFromDate']) || isset($data['teacherToDate']))
            {
                if(!empty($data['teacherName']))
                {
                    $condition .= " AND s.name LIKE '%{$data['teacherName']}%' ";
                }
                if(!empty($data['teacherClass']))
                {
                    $condition .= " AND c.className LIKE '%{$data['teacherClass']}%' ";
                }
                if(!empty($data['teacherMobile']))
                {
                    $condition .= " AND s.mobile = '{$data['teacherMobile']}' ";
                }
                if(!empty($data['teacherUserId']))
                {
                    $condition .= " AND s.user_id = '{$data['teacherUserId']}' ";
                }
                if(!empty($data['teacherFromDate']) && !empty($data['teacherToDate']))
                {
                    $condition .= " AND s.created_at BETWEEN '{$data['teacherFromDate']}' AND  '{$data['teacherToDate']}' ";
                }
            
                if(!empty($data['search']['value']))
                {
                    $condition .= " 
                    OR s.name LIKE '%{$data['search']['value']}%' 
                    OR c.className LIKE '%{$data['search']['value']}%' 
                    OR s.mobile LIKE '%{$data['search']['value']}%' 
                    OR s.user_id LIKE '%{$data['search']['value']}%' 
                    ";
                }
            }
      
                $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,if(s.status = '1', 'Active','InActive')as status,s.name,s.user_id,s.mobile,s.dob,s.pincode,c.className,ss.sectionName,st.stateName,ct.cityName FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 0 $condition ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $countSql = "SELECT count(s.id) as count  FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 0 $condition ORDER BY s.id DESC";
            }else
            {
                $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,if(s.status = '1', 'Active','InActive')as status,s.name,s.user_id,s.mobile,s.dob,s.pincode,c.className,ss.sectionName,st.stateName,ct.cityName FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 0 ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $countSql = "SELECT count(s.id) as count FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 0 ORDER BY s.id DESC";
            }


            $tCount = $this->db->query($countSql)->result_array();

            $sendArr = [];
            for($i=0;$i<count($d);$i++)
            {
                $subArr = [];
               
                $subArr[] = ($j = $i + 1);
                $subArr[] = "<img src='{$d[$i]['image']}' alt='100x100' height='50px' width='50px' class='img-fluid rounded-circle' />";
                $subArr[] = $d[$i]['name'];
                $subArr[] = $d[$i]['user_id'];
                $subArr[] = $d[$i]['mobile'];
                $subArr[] = $d[$i]['className']. " - ".$d[$i]['sectionName'];
                $subArr[] = $d[$i]['stateName']. " - ".$d[$i]['cityName'] . " - " . $d[$i]['pincode'];

                  if($d[$i]['status'] == 'Active')
                    {
                        $subArr[] = '<span class="badge badge-success">'.$d[$i]['status'].'</span>';
                    }else{
                        $subArr[] = '<span class="badge badge-success">'.$d[$i]['status'].'</span>';
                    };

                $subArr[] = date('d-m-Y', strtotime($d[$i]['dob']));
                $subArr[] = '
                <a href="viewTeacher/'.$d[$i]['id'].'" class="btn btn-primary" ><i class="fas fa-eye"></i></a>  
                <a href="editTeacher/'.$d[$i]['id'].'" class="btn btn-warning" ><i class="fas fa-edit"></i></a>  
                <a href="deleteTeacher/'.$d[$i]['id'].'" class="btn btn-danger" 
                onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fas fa-trash"></i></a>';

                $sendArr[] = $subArr;
            }

        $dataTableArr = [
            "draw"=> $data['draw'],
            "recordsTotal"=> $tCount[0]['count'],
            "recordsFiltered"=> $tCount[0]['count'],
            "data"=>$sendArr
        ];

        echo json_encode($dataTableArr);
        
    }


    public function singleStudent($tableName,$id)
    {
        $dir = base_url().HelperClass::uploadImgDir;
        $this->tableName = $tableName;
       return $d = $this->db->query("SELECT *,CONCAT('$dir',image) as image FROM " . $this->tableName ." WHERE id=$id AND status != 0 LIMIT 1")->result_array();
    }

    public function singleTeacher($tableName,$id)
    {
        $dir = base_url().HelperClass::uploadImgDir;
        $this->tableName = $tableName;
       return $d = $this->db->query("SELECT *,CONCAT('$dir',image) as image FROM " . $this->tableName ." WHERE id=$id AND status != 0 LIMIT 1")->result_array();
    }

    public function showStudentProfile($tableName,$userId)
    {
        $dir = base_url().HelperClass::uploadImgDir;
        $this->tableName = $tableName;
        return $d = $this->db->query("SELECT s.*, CONCAT('$dir',s.image) as image,if(s.status = '1', 'Active','InActive')as status,c.className,ss.sectionName,st.stateName,ct.cityName FROM " .$this->tableName." s
        LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
        LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
        LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
        LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
        WHERE s.status != 0 AND s.user_id = '{$userId}' ORDER BY s.id DESC LIMIT 1")->result_array();
    }

    public function viewSingleStudentAllData($tableName,$id)
    {
        $dir = base_url().HelperClass::uploadImgDir;
        $this->tableName = $tableName;
        return $d = $this->db->query("SELECT s.*, CONCAT('$dir',s.image) as image,if(s.status = '1', 'Active','InActive')as status,c.className,ss.sectionName,st.stateName,ct.cityName FROM " .$this->tableName." s
        LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
        LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
        LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
        LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
        WHERE s.status != 0 AND s.id = {$id} ORDER BY s.id DESC LIMIT 1")->result_array();
    }

    public function allClass($tableName)
    {
        $this->tableName = $tableName;
       return $d = $this->db->query("SELECT id,className FROM " . $this->tableName)->result_array();
    }
    public function allSection($tableName)
    {
        $this->tableName = $tableName;
       return $d = $this->db->query("SELECT id,sectionName FROM " . $this->tableName)->result_array();
    }
    public function allCity($tableName)
    {
        $this->tableName = $tableName;
       return $d = $this->db->query("SELECT id,cityName FROM " . $this->tableName)->result_array();
    }
    public function allState($tableName)
    {
        $this->tableName = $tableName;
       return $d = $this->db->query("SELECT id,stateName FROM " . $this->tableName)->result_array();
    }

    public function deleteStudent($tableName = "", $student_id = "")
    {
        if (!empty($tableName)) {
            $this->tableName    = $tableName;
            $this->student_id   = $this->sanitizeInput($student_id);
            $sql    = 'DELETE FROM ' . $this->tableName . ' WHERE id = ' . $this->student_id . '';
            if($this->db->query($sql))
            {
                return true;
            }else 
            {
                return false;
            }
        }
    }


    public function showMessage()
    {
        echo json_encode($this->messageArr);
    }

 
    public function sanitizeInput($input)
    {
        return htmlspecialchars(strip_tags(trim($input)));
    }


    public function pushArr($code = "", $msg = "", $data = array())
    {

        $pushArr =  array('status' => $code, 'message' => $msg);

        if (!empty($data)) {
            $pushArr['data'] = $data;
        }
        return $pushArr;
    }
}