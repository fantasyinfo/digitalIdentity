<?php

class CrudModel extends CI_Model
{
    private $tableName  = '';
    private $student_id = '';
    private $messageArr = array();

	public function __construct()
	{
		$this->load->database();
        $this->load->library('session');
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

    public function getSchoolId($tableName)
    {
        $this->tableName    = $tableName;
        $dd = $this->db->query("SELECT user_id FROM " .$this->tableName ." WHERE user_id IS NOT NULL ORDER BY id DESC LIMIT 1")->result_array();
        $id = explode(HelperClass::schoolIDPrefix,$dd[0]['user_id']);
        return HelperClass::schoolIDPrefix . ($id[1] + 1);
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
        $file_name = HelperClass::imgPrefix . $id ."-". time() .$file['image']['name'];
        $file_size =$file['image']['size'];
        $file_tmp =$file['image']['tmp_name'];
        $file_type=$file['image']['type'];
        $explode = explode('.',$file['image']['name']);
        $end = end($explode);
        $file_ext=strtolower($end);
        
        $extensions= array("jpeg","jpg","png","gif");
        
        if(in_array($file_ext,$extensions)=== false){
           $errors[]="extension not allowed, please choose a JPEG or PNG file.";
        }
        
        if($file_size > 2097152){
           $errors[]='File size must be excately 2 MB';
        }
        
        if(empty($errors)==true){
           
           //move_uploaded_file($file_tmp,HelperClass::uploadImgDir.$file_name);
        //    return $file_name;
           
           $destUrl =  $this->compressImg($file_tmp,HelperClass::uploadImgDir.$file_name,80);
           $exp = explode(HelperClass::uploadImgDir,$destUrl);
           return $exp[1]; // return upload url
        }else{
          return false;
        }
      
    }

    public function compressImg($source_url, $destination_url, $quality)
    {
        $info = getimagesize($source_url);
     
        if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source_url);
        elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source_url);
        elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source_url);
        elseif ($info['mime'] == 'image/jpg') $image = imagecreatefromjpeg($source_url);
        
        //save it
        imagejpeg($image, $destination_url, $quality);
            
        //return destination file url
        return $destination_url;    
    }

    public function showAllStudents($tableName,$data = '')
    {
        $this->tableName = $tableName;
        $dir = base_url().HelperClass::uploadImgDir;
        if(!empty($data))
        {
            // print_r($data);
            $condition = " 
             AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
             AND c.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
             AND ss.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
             AND st.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
             AND ct.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            ";

            if(isset($data['studentName']) || isset($data['studentClass']) || isset($data['studentMobile']) || isset($data['studentUserId']) || isset($data['studentFromDate']) || isset($data['studentToDate']) || isset($data['studentSection']))
            {
                if(!empty($data['studentName']))
                {
                    $condition .= " AND s.name LIKE '%{$data['studentName']}%' ";
                }
                if(!empty($data['studentClass']))
                {
                    $condition .= " AND c.id = '{$data['studentClass']}' ";
                }
                if(!empty($data['studentSection']))
                {
                    $condition .= " AND ss.id = '{$data['studentSection']}' ";
                }
                if(!empty($data['studentMobile']))
                {
                    $condition .= " AND s.mobile LIKE '%{$data['studentMobile']}%' ";
                }
                if(!empty($data['studentUserId']))
                {
                    $condition .= " AND s.user_id LIKE '%{$data['studentUserId']}%' ";
                }
                if(!empty($data['studentFromDate']) && !empty($data['studentToDate']))
                {
                    $condition .= " AND s.created_at BETWEEN '{$data['studentFromDate']}' AND  '{$data['studentToDate']}' ";
                }
            
                if(!empty($data['search']['value']))
                {
                    $condition .= " 
                    OR s.name LIKE '%{$data['search']['value']}%' 
                    OR c.id LIKE '%{$data['search']['value']}%' 
                    OR s.mobile LIKE '%{$data['search']['value']}%' 
                    OR s.user_id LIKE '%{$data['search']['value']}%' 
                    ";
                }
            }
      
                $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,s.status,s.name,s.user_id,s.mobile,s.dob,s.pincode,c.className,ss.sectionName,st.stateName,ct.cityName FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 4 $condition ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $countSql = "SELECT count(s.id) as count  FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 4 $condition ORDER BY s.id DESC";
            }else
            {
                $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,s.status,s.name,s.user_id,s.mobile,s.dob,s.pincode,c.className,ss.sectionName,st.stateName,ct.cityName FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 4 ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $countSql = "SELECT count(s.id) as count FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 4 ORDER BY s.id DESC";
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

                if($d[$i]['status'] == '1') 
                {
                    $ns =  '2';
                }
                else{
                    $ns = '1';
                }

           
                if($d[$i]['status'] == '1') 
                {
                    $cclas = 'success';
                    $ssus = 'Active';
                }
                else{
                    $cclas = 'danger';
                    $ssus = 'Inactive';
                }

                

                $subArr[] = "<a href='?action=status&edit_id=".$d[$i]['id']."&status=".$ns." ' class='badge badge-".$cclas ."'> ".$ssus."</a>";



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
            $condition = "
            AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND c.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND ss.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND st.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND ct.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
             ";

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
                    $condition .= " AND s.mobile LIKE '%{$data['teacherMobile']}%' ";
                }
                if(!empty($data['teacherUserId']))
                {
                    $condition .= " AND s.user_id LIKE '%{$data['teacherUserId']}%' ";
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
      
                $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,s.status,s.name,s.user_id,s.mobile,s.dob,s.pincode,s.address,s.experience, s.education,c.className,ss.sectionName,st.stateName,ct.cityName FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 4 $condition ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $countSql = "SELECT count(s.id) as count  FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 4 $condition ORDER BY s.id DESC";
            }else
            {
                $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,s.status,s.name,s.user_id,s.mobile,s.dob,s.pincode,s.address,s.experience, s.education,c.className,ss.sectionName,st.stateName,ct.cityName FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 4 ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $countSql = "SELECT count(s.id) as count FROM " .$this->tableName." s
                LEFT JOIN ".Table::classTable." c ON c.id =  s.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  s.section_id
                LEFT JOIN ".Table::stateTable." st ON st.id =  s.state_id
                LEFT JOIN ".Table::cityTable." ct ON ct.id =  s.city_id
                WHERE s.status != 4 ORDER BY s.id DESC";
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
                $subArr[] = $d[$i]['education'];
                $subArr[] = @HelperClass::experience[$d[$i]['experience']];
                $subArr[] = $d[$i]['stateName']. " - ".$d[$i]['cityName'] . " - " . $d[$i]['pincode'];

                if($d[$i]['status'] == '1') 
                {
                    $ns =  '2';
                }
                else{
                    $ns = '1';
                }

           
                if($d[$i]['status'] == '1') 
                {
                    $cclas = 'success';
                    $ssus = 'Active';
                }
                else{
                    $cclas = 'danger';
                    $ssus = 'Inactive';
                }

                

                $subArr[] = "<a href='?action=status&edit_id=".$d[$i]['id']."&status=".$ns." ' class='badge badge-".$cclas ."'> ".$ssus."</a>";

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

    public function listDigiCoin($tableName,$userType,$data = '')
    {
        $this->tableName = $tableName;
        $joins = '';
        if(!empty($userType))
        {
            if($userType == 'Student')
            {
                $joins .= ' LEFT JOIN '.Table::studentTable.' u ON u.id = g.user_id ';
            }else if($userType == 'Teacher')
            {
                $joins .= ' LEFT JOIN '.Table::teacherTable.' u ON u.id = g.user_id ';
            }
            
        }


        if(!empty($data))
        {
           
            $condition = " AND g.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND u.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ";

            if(!empty($userType))
            {
                $condition .= " AND user_type = '$userType' ";
            }
            
            if(isset($data['name']) || isset($data['userId']) || isset($data['fromDate']) || isset($data['toDate']))
            {
                if(!empty($data['name']))
                {
                    $condition .= " AND u.name LIKE '%{$data['name']}%' ";
                }
                if(!empty($data['userId']))
                {
                    $condition .= " AND u.user_id LIKE '%{$data['userId']}%' ";
                }
                if(!empty($data['fromDate']) && !empty($data['toDate']))
                {
                    $condition .= " AND g.created_at BETWEEN '{$data['fromDate']}' AND  '{$data['toDate']}' ";
                }
          
            }
      
                $d = $this->db->query("SELECT g.user_type,g.for_what,g.digiCoin,g.status,u.name,u.user_id as uniqueId FROM " .$this->tableName." g $joins WHERE g.status != 4 $condition ORDER BY g.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $countSql = "SELECT count(1) as count  FROM " .$this->tableName." g $joins WHERE g.status != 4 $condition ORDER BY g.id DESC";
            }else
            {
                $d = $this->db->query("SELECT g.user_type,g.for_what,g.digiCoin,g.status,u.name,u.user_id as uniqueId FROM " .$this->tableName." g $joins WHERE g.status != 4 ORDER BY g.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $countSql = "SELECT count(1) as count  FROM " .$this->tableName." g $joins WHERE g.status != 4 ORDER BY g.id DESC";
            }


            $tCount = $this->db->query($countSql)->result_array();

            $sendArr = [];
            for($i=0;$i<count($d);$i++)
            {
                $subArr = [];
               
                $subArr[] = ($j = $i + 1);
                $subArr[] = $d[$i]['user_type'];
                $subArr[] = $d[$i]['name'];
                $subArr[] = $d[$i]['uniqueId'];
                $subArr[] = HelperClass::actionTypeR[$d[$i]['for_what']];
                $subArr[] = $d[$i]['digiCoin'];
                  if($d[$i]['status'] == '1')
                    {
                        $subArr[] = '<span class="badge badge-success">Active</span>';
                    }else{
                        $subArr[] = '<span class="badge badge-success">DeActive</span>';
                    };
                //  $subArr[] = '
                //  <a href="viewTeacher/'.$d[$i]['id'].'" class="btn btn-primary" ><i class="fas fa-eye"></i></a>  
                //  <a href="editTeacher/'.$d[$i]['id'].'" class="btn btn-warning" ><i class="fas fa-edit"></i></a>  
                //  <a href="deleteTeacher/'.$d[$i]['id'].'" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fas fa-trash"></i></a>';

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

    public function allExamList($tableName,$data = '')
    {
        $this->tableName = $tableName;
        $dir = base_url().HelperClass::uploadImgDir;
        if(!empty($data))
        {
             $condition = "
            AND e.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND c.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND ss.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND sub.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND tt.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
              ";
            
            if(isset($data['teacherName']) || isset($data['examName']) || isset($data['studentClass']) || isset($data['studentSection']) || isset($data['studentFromDate']) || isset($data['studentToDate']))
            {
                if(!empty($data['teacherName']))
                {
                    $condition .= " AND tt.name LIKE '%{$data['teacherName']}%' ";
                }
                if(!empty($data['examName']))
                {
                    $condition .= " AND e.exam_name LIKE '%{$data['examName']}%' ";
                }
                if(!empty($data['studentClass']))
                {
                    $condition .= " AND c.id = '{$data['studentClass']}' ";
                }
                if(!empty($data['studentSection']))
                {
                    $condition .= " AND ss.id = '{$data['studentSection']}' ";
                }
            
                if(!empty($data['studentFromDate']) && !empty($data['studentToDate']))
                {
                    $condition .= " AND e.created_at BETWEEN '{$data['studentFromDate']}' AND  '{$data['studentToDate']}' ";
                }
            
            }
      
                $d = $this->db->query("SELECT e.id,e.status,e.exam_name,e.date_of_exam,e.max_marks,e.min_marks,c.className,ss.sectionName,tt.name, tt.id as teacherId,sub.subjectName,e.created_at FROM " .$this->tableName." e
                LEFT JOIN ".Table::classTable." c ON c.id =  e.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  e.section_id
                LEFT JOIN ".Table::subjectTable." sub ON sub.id =  e.subject_id
                LEFT JOIN ".Table::teacherTable." tt ON tt.id =  e.login_user_id
                WHERE e.status != 4 $condition ORDER BY e.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $lastQuery = $this->db->last_query();

                $countSql = "SELECT count(e.id) as count  FROM " .$this->tableName." e
                LEFT JOIN ".Table::classTable." c ON c.id =  e.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  e.section_id
                LEFT JOIN ".Table::subjectTable." sub ON sub.id =  e.subject_id
                LEFT JOIN ".Table::teacherTable." tt ON tt.id =  e.login_user_id
                WHERE e.status != 4 $condition ORDER BY e.id DESC";
            }else
            {
                $d = $this->db->query("SELECT e.id,e.status,e.exam_name,e.date_of_exam,e.max_marks,e.min_marks,c.className,ss.sectionName,tt.name, tt.id as teacherId,sub.subjectName,e.created_at FROM " .$this->tableName." e
                LEFT JOIN ".Table::classTable." c ON c.id =  e.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  e.section_id
                LEFT JOIN ".Table::subjectTable." sub ON sub.id =  e.subject_id
                LEFT JOIN ".Table::teacherTable." tt ON tt.id =  e.login_user_id
                WHERE e.status != 4 ORDER BY e.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $lastQuery = $this->db->last_query();

                $countSql = "SELECT count(e.id) as count  FROM " .$this->tableName." e
                LEFT JOIN ".Table::classTable." c ON c.id =  e.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  e.section_id
                LEFT JOIN ".Table::subjectTable." sub ON sub.id =  e.subject_id
                LEFT JOIN ".Table::teacherTable." tt ON tt.id =  e.login_user_id
                WHERE e.status != 4 ORDER BY e.id DESC";
            }


            $tCount = $this->db->query($countSql)->result_array();

            $sendArr = [];
            for($i=0;$i<count($d);$i++)
            {
                $subArr = [];
               
                $subArr[] = ($j = $i + 1);
                $subArr[] = $d[$i]['id'];
                $subArr[] = substr($d[$i]['exam_name'],0,30);
                $subArr[] = $d[$i]['subjectName'];
                $subArr[] = $d[$i]['date_of_exam'];
                $subArr[] = $d[$i]['max_marks'];
                $subArr[] = $d[$i]['min_marks'];
                $subArr[] = $d[$i]['className']. " - ".$d[$i]['sectionName'];
                $subArr[] = $d[$i]['teacherId'];
                $subArr[] = $d[$i]['name'];
                if($d[$i]['status'] == '1') 
                {
                    $ns =  '2';
                }
                else{
                    $ns = '1';
                }

           
                if($d[$i]['status'] == '1') 
                {
                    $ssus = '<span class="badge badge-info">Active</span>';
                }
                else if ($d[$i]['status'] == '3'){
                    $url = base_url('exam/allResults') . '?action=resultPublished&examId=' . $d[$i]['id'];
                    $ssus = '<a href="'.$url.'" target="_blank" class="badge badge-success">Check Results</a>';
                }

                

                $subArr[] = $ssus;
                $subArr[] = $d[$i]['created_at'];
                // $subArr[] = '
                // <a href="viewTeacher/'.$d[$i]['id'].'" class="btn btn-primary" ><i class="fas fa-eye"></i></a>  
                // <a href="editTeacher/'.$d[$i]['id'].'" class="btn btn-warning" ><i class="fas fa-edit"></i></a>  
                // <a href="deleteTeacher/'.$d[$i]['id'].'" class="btn btn-danger" 
                // onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fas fa-trash"></i></a>';

                $sendArr[] = $subArr;
            }

        $dataTableArr = [
            "draw"=> $data['draw'],
            "recordsTotal"=> $tCount[0]['count'],
            "recordsFiltered"=> $tCount[0]['count'],
            "data"=>$sendArr,
            "query" => $lastQuery
        ];

        echo json_encode($dataTableArr);
        
    }


    public function allResultList($tableName,$data = '')
    {
        $this->tableName = $tableName;
        $dir = base_url().HelperClass::uploadImgDir;
        if(!empty($data))
        {
             $condition = "
                AND r.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
                AND e.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND c.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND ss.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND sub.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND tt.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
              ";
   
            if(isset($data['examId']) || isset($data['examName']) ||  isset($data['studentName'])  || isset($data['resultDate']) ||isset($data['studentClass']) || isset($data['studentSection']) || isset($data['studentFromDate']) || isset($data['studentToDate']))
            {
                if(!empty($data['studentName']))
                {
                    $condition .= " AND s.name LIKE '%{$data['studentName']}%' ";
                }
                if(!empty($data['examName']))
                {
                    $condition .= " AND e.exam_name LIKE '%{$data['examName']}%' ";
                }
                if(!empty($data['examId']))
                {
                    $condition .= " AND e.id = '{$data['examId']}' ";
                }
                if(!empty($data['studentClass']))
                {
                    $condition .= " AND c.id = '{$data['studentClass']}' ";
                }
                if(!empty($data['studentSection']))
                {
                    $condition .= " AND ss.id = '{$data['studentSection']}' ";
                }
                if(!empty($data['resultDate']))
                {
                    $condition .= " AND r.result_date = '{$data['resultDate']}' ";
                }
            
                if(!empty($data['studentFromDate']) && !empty($data['studentToDate']))
                {
                    $condition .= " AND r.created_at BETWEEN '{$data['studentFromDate']}' AND  '{$data['studentToDate']}' ";
                }
            
            }
      
                $d = $this->db->query("SELECT 
                r.id,r.marks,IF(r.resultStatus = '1', 'Pass', 'Fail') as resultStatus,r.result_date,
                e.id as examId, e.exam_name,e.date_of_exam,e.max_marks,e.min_marks,
                s.name,s.id as studentId,
                c.className,ss.sectionName,
                tt.name as teacherName, 
                tt.id as teacherId,
                sub.subjectName,
                r.created_at FROM " .$this->tableName." r
                LEFT JOIN " .Table::examTable ." e ON e.id = r.exam_id
                LEFT JOIN " .Table::studentTable ." s ON s.id = r.student_id
                LEFT JOIN ".Table::classTable." c ON c.id =  e.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  e.section_id
                LEFT JOIN ".Table::subjectTable." sub ON sub.id =  e.subject_id
                LEFT JOIN ".Table::teacherTable." tt ON tt.id =  e.login_user_id
                WHERE r.status != 4 $condition ORDER BY r.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $lastQuery = $this->db->last_query();

                $countSql = "SELECT count(r.id) as count  FROM " .$this->tableName." r
                LEFT JOIN " .Table::examTable ." e ON e.id = r.exam_id
                LEFT JOIN " .Table::studentTable ." s ON s.id = r.student_id
                LEFT JOIN ".Table::classTable." c ON c.id =  e.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  e.section_id
                LEFT JOIN ".Table::subjectTable." sub ON sub.id =  e.subject_id
                LEFT JOIN ".Table::teacherTable." tt ON tt.id =  e.login_user_id
                WHERE r.status != 4 $condition ORDER BY r.id DESC";
            }else
            {
                $d = $this->db->query("SELECT 
                r.id,r.marks,IF(r.resultStatus = '1', 'Pass', 'Fail') as resultStatus,r.result_date,
                e.id as examId, e.exam_name,e.date_of_exam,e.max_marks,e.min_marks,
                s.name,s.id as studentId,
                c.className,ss.sectionName,
                tt.name as teacherName, 
                tt.id as teacherId,
                sub.subjectName,
                r.created_at FROM " .$this->tableName." r
                LEFT JOIN " .Table::examTable ." e ON e.id = r.exam_id
                LEFT JOIN " .Table::studentTable ." s ON s.id = r.student_id
                LEFT JOIN ".Table::classTable." c ON c.id =  e.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  e.section_id
                LEFT JOIN ".Table::subjectTable." sub ON sub.id =  e.subject_id
                LEFT JOIN ".Table::teacherTable." tt ON tt.id =  e.login_user_id
                WHERE r.status != 4 ORDER BY r.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $lastQuery = $this->db->last_query();

                $countSql = "SELECT count(r.id) as count  FROM " .$this->tableName." r
                LEFT JOIN " .Table::examTable ." e ON e.id = r.exam_id
                LEFT JOIN " .Table::studentTable ." s ON s.id = r.student_id
                LEFT JOIN ".Table::classTable." c ON c.id =  e.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  e.section_id
                LEFT JOIN ".Table::subjectTable." sub ON sub.id =  e.subject_id
                LEFT JOIN ".Table::teacherTable." tt ON tt.id =  e.login_user_id
                WHERE r.status != 4 ORDER BY r.id DESC";
            }


            $tCount = $this->db->query($countSql)->result_array();

            $sendArr = [];
            for($i=0;$i<count($d);$i++)
            {
                $subArr = [];
                $subArr[] = ($j = $i + 1);
                $subArr[] = $d[$i]['id'];
                $subArr[] = $d[$i]['examId'];
                // $subArr[] = $d[$i]['exam_name'];
                $subArr[] = $d[$i]['subjectName'];
                // $subArr[] = $d[$i]['date_of_exam'];
                $subArr[] = $d[$i]['max_marks'];
                $subArr[] = $d[$i]['min_marks'];
                // $subArr[] = $d[$i]['studentId'];
                $subArr[] = $d[$i]['name'];
                $subArr[] = $d[$i]['className']. " - ".$d[$i]['sectionName'];
                $subArr[] = $d[$i]['marks'];
                if($d[$i]['resultStatus'] == 'Pass')
                    {
                        $subArr[] = '<span class="badge badge-success">'.$d[$i]['resultStatus'].'</span>';
                    }else{
                        $subArr[] = '<span class="badge badge-danger">'.$d[$i]['resultStatus'].'</span>';
                    };
                
                $subArr[] = $d[$i]['result_date'];
                // $subArr[] = $d[$i]['teacherId'];
                // $subArr[] = $d[$i]['teacherName'];
                // $subArr[] = $d[$i]['created_at'];
                // $subArr[] = '
                // <a href="viewTeacher/'.$d[$i]['id'].'" class="btn btn-primary" ><i class="fas fa-eye"></i></a>  
                // <a href="editTeacher/'.$d[$i]['id'].'" class="btn btn-warning" ><i class="fas fa-edit"></i></a>  
                // <a href="deleteTeacher/'.$d[$i]['id'].'" class="btn btn-danger" 
                // onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fas fa-trash"></i></a>';

                $sendArr[] = $subArr;
            }

        $dataTableArr = [
            "draw"=> $data['draw'],
            "recordsTotal"=> $tCount[0]['count'],
            "recordsFiltered"=> $tCount[0]['count'],
            "data"=>$sendArr,
            "query" => $lastQuery
        ];

        echo json_encode($dataTableArr);
        
    }

    public function teacherReviewsList($tableName,$data = '')
    {
        $this->tableName = $tableName;
        $dir = base_url().HelperClass::uploadImgDir;
        if(!empty($data))
        {
             $condition = "
                AND rr.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
                AND e.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND c.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND ss.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND tt.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
              ";
            // $condition = "";
            if(isset($data['examId']) ||  isset($data['teacherName'])  || isset($data['stars']) ||isset($data['resultId']) || isset($data['studentFromDate']) || isset($data['studentToDate']))
            {
                if(!empty($data['teacherName']))
                {
                    $condition .= " AND tt.name LIKE '%{$data['teacherName']}%' ";
                }
                if(!empty($data['examId']))
                {
                    $condition .= " AND e.id = '{$data['examId']}' ";
                }
                if(!empty($data['stars']))
                {
                    $condition .= " AND rr.stars = '{$data['stars']}' ";
                }
                if(!empty($data['resultId']))
                {
                    $condition .= " AND r.id = '{$data['resultId']}' ";
                }
             
                if(!empty($data['studentFromDate']) && !empty($data['studentToDate']))
                {
                    $condition .= " AND rr.created_at BETWEEN '{$data['studentFromDate']}' AND  '{$data['studentToDate']}' ";
                }
            
            }
      
                $d = $this->db->query("SELECT  rr.id as ratingId, rr.stars,rr.review, rr.review_title,IF(rr.for_what = '1', 'Result Published', '') as reasonForReview,rr.created_at,
                e.id as examId, 
                r.id as resultId,
                s.name,s.mother_name,s.father_name,
                c.className,ss.sectionName,
                tt.name as teacherName, 
                tt.id as teacherId
                 FROM " .$this->tableName." rr
                LEFT JOIN " .Table::examTable ." e ON e.id = rr.reason_id
                LEFT JOIN " .Table::resultTable ." r ON r.exam_id = e.id AND r.student_id = rr.login_user_id
                LEFT JOIN " .Table::studentTable ." s ON s.id = rr.login_user_id
                LEFT JOIN ".Table::classTable." c ON c.id =  e.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  e.section_id
                LEFT JOIN ".Table::teacherTable." tt ON tt.id =  rr.user_id
                WHERE rr.status != 4 $condition ORDER BY rr.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $lastQuery = $this->db->last_query();

                $countSql = "SELECT count(rr.id) as count  FROM " .$this->tableName." rr
                LEFT JOIN " .Table::examTable ." e ON e.id = rr.reason_id
                LEFT JOIN " .Table::resultTable ." r ON r.exam_id = e.id AND r.student_id = rr.login_user_id
                LEFT JOIN " .Table::studentTable ." s ON s.id = rr.login_user_id
                LEFT JOIN ".Table::classTable." c ON c.id =  e.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  e.section_id
                LEFT JOIN ".Table::teacherTable." tt ON tt.id =  rr.user_id
                WHERE rr.status != 4 $condition ORDER BY rr.id DESC";
            }else
            {
                $d = $this->db->query("SELECT rr.id as ratingId, rr.stars,rr.review, rr.review_title,IF(rr.for_what = '1', 'Result Published', '') as reasonForReview,rr.created_at,
                e.id as examId, 
                r.id as resultId,
                s.name,s.mother_name,s.father_name,
                c.className,ss.sectionName,
                tt.name as teacherName, 
                tt.id as teacherId
                 FROM " .$this->tableName." rr
                LEFT JOIN " .Table::examTable ." e ON e.id = rr.reason_id
                LEFT JOIN " .Table::resultTable ." r ON r.exam_id = e.id AND r.student_id = rr.login_user_id
                LEFT JOIN " .Table::studentTable ." s ON s.id = rr.login_user_id
                LEFT JOIN ".Table::classTable." c ON c.id =  e.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  e.section_id
                LEFT JOIN ".Table::teacherTable." tt ON tt.id =  rr.user_id
                WHERE rr.status != 4 ORDER BY rr.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

                $lastQuery = $this->db->last_query();

                $countSql = "SELECT count(rr.id) as count  FROM " .$this->tableName." rr
                LEFT JOIN " .Table::examTable ." e ON e.id = rr.reason_id
                LEFT JOIN " .Table::resultTable ." r ON r.exam_id = e.id AND r.student_id = rr.login_user_id
                LEFT JOIN " .Table::studentTable ." s ON s.id = rr.login_user_id
                LEFT JOIN ".Table::classTable." c ON c.id =  e.class_id
                LEFT JOIN ".Table::sectionTable." ss ON ss.id =  e.section_id
                LEFT JOIN ".Table::teacherTable." tt ON tt.id =  rr.user_id
                WHERE rr.status != 4 ORDER BY rr.id DESC";
            }


            $tCount = $this->db->query($countSql)->result_array();

            $sendArr = [];
            for($i=0;$i<count($d);$i++)
            {
                $subArr = [];
                $subArr[] = ($j = $i + 1);
                $subArr[] = $d[$i]['ratingId'];
                $subArr[] = $d[$i]['teacherName'];
                $subArr[] = $d[$i]['stars'];
                $subArr[] = $d[$i]['review_title'];
                $subArr[] = $d[$i]['review'];
                $subArr[] = $d[$i]['reasonForReview'];
                $subArr[] = $d[$i]['examId'];
                $subArr[] = $d[$i]['resultId'];
                $subArr[] = $d[$i]['name'];
                $subArr[] = $d[$i]['mother_name'] . " & " . $d[$i]['father_name'];
                $subArr[] = $d[$i]['className']. " - ".$d[$i]['sectionName'];
                $subArr[] = $d[$i]['created_at'];
                // $subArr[] = '
                // <a href="viewTeacher/'.$d[$i]['id'].'" class="btn btn-primary" ><i class="fas fa-eye"></i></a>  
                // <a href="editTeacher/'.$d[$i]['id'].'" class="btn btn-warning" ><i class="fas fa-edit"></i></a>  
                // <a href="deleteTeacher/'.$d[$i]['id'].'" class="btn btn-danger" 
                // onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fas fa-trash"></i></a>';

                $sendArr[] = $subArr;
            }

        $dataTableArr = [
            "draw"=> $data['draw'],
            "recordsTotal"=> $tCount[0]['count'],
            "recordsFiltered"=> $tCount[0]['count'],
            "data"=>$sendArr,
            "query" => $lastQuery
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

    public function showTeacherProfile($tableName,$userId)
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

    public function viewSingleTeacherAllData($tableName,$id)
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
    public function allClass($tableName,$schoolUniqueCode)
    {
        $this->tableName = $tableName;
       return $d = $this->db->query("SELECT id,className FROM " . $this->tableName ." WHERE status !='4' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();
    }
    public function allSection($tableName,$schoolUniqueCode)
    {
        $this->tableName = $tableName;
       return $d = $this->db->query("SELECT id,sectionName FROM " . $this->tableName  ." WHERE status !='4' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();
    }
    public function allCity($tableName,$schoolUniqueCode)
    {
        $this->tableName = $tableName;
       return $d = $this->db->query("SELECT id,cityName FROM " . $this->tableName ." WHERE status !='4' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();
    }
    public function allState($tableName,$schoolUniqueCode)
    {
        $this->tableName = $tableName;
       return $d = $this->db->query("SELECT id,stateName FROM " . $this->tableName ." WHERE status !='4' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();
    }
    public function allSubjects($tableName,$schoolUniqueCode)
    {
        $this->tableName = $tableName;
       return $d = $this->db->query("SELECT id,subjectName FROM " . $this->tableName ." WHERE status !='4' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();
    }



    public function showCityViaStateId($stateId)
    {
        $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
       $d = $this->db->query("SELECT id,cityName FROM " . Table::cityTable ." WHERE stateId = '$stateId' AND status !='4' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();

       $html = '';

        if(!empty($d))
        {
          foreach($d as $dd)
          {
           $html .= "<option value='".$dd['id']."'>".$dd['cityName']."</option>";
          }
          return json_encode($html);
        }
    }









    public function deleteStudent($tableName = "", $student_id = "")
    {
        if (!empty($tableName)) {
            $this->tableName    = $tableName;
            $this->student_id   = $this->sanitizeInput($student_id);
    
            $delImg = $this->db->query("SELECT image FROM " . $this->tableName . " WHERE id = " . $this->student_id . "  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ")->result_array();

            if(!empty(@$delImg))
            {
                $imgN = @$delImg[0]['image'];
                @unlink(HelperClass::uploadImgDir . $imgN);
            }

            if($this->db->query("DELETE FROM " . $this->tableName . " WHERE id = " . $this->student_id . " AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'"))
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


    public function checkIsLogin()
    {
        if(empty($this->session->userdata('id')) || empty($this->session->userdata('name')) || empty($this->session->userdata('email')) || empty($this->session->userdata('user_type')) || empty($this->session->userdata('userData')))
        {
            $msgArr = [
                'class' => 'danger',
                'msg' => 'Please Login first to access that page.',
              ];
              $this->session->set_userdata($msgArr);
            return false;
        }else {
            
            return true;
        }
    }

    public function checkPermission()
    {

        if($_SESSION['user_type'] == 'SuperCEO')
        {
            return true;
        }




        $userPermissions = $this->db->query("SELECT permissions FROM " . Table::panelMenuPermissionTable . " WHERE user_id = '{$_SESSION['id']}' AND user_type = '{$_SESSION['user_type']}' AND status = '1'")->result_array();

        $permissionArr = json_decode($userPermissions[0]['permissions'],TRUE);

        $permissionString = implode(',',$permissionArr);

        $currentPage = $_SERVER['PATH_INFO'];

        $permissionData = $this->db->query("SELECT * FROM " . Table::adminPanelMenuTable . " WHERE id IN ($permissionString) AND status = '1'")->result_array();

        $p = count($permissionData);
        $permissionDeni = false;
        for($i=0; $i < $p; $i++)
        {
            if( $currentPage == '/'.$permissionData[$i]['link'])
            {
                $permissionDeni = true;
            }
        }
           return $permissionDeni;
        
    }



    public function sendFirebaseNotification ($to = '', $notif = ''){

            // FCM API Url
            $url = 'https://fcm.googleapis.com/fcm/send';
          
            // Put your Server Key here
            $apiKey = "AAAAPb7Be5g:APA91bG5Xy_UX66wmiyKY3d9Dp_LiK5qA9_aHDDMousl9hehj6f53UQeMeAExMAuonyHizy3eI5wz0sndS9xQkIpwhwyro89DKxzvv6TLJeCmNb-t2ANcvXTYb0mS1nFNNZ8jL39sC8D";
          
            // Compile headers in one variable
            $headers = array (
              'Authorization:key= ' . $apiKey,
              'Content-Type:application/json'
            );
          
            // Add notification content to a variable for easy reference
            $notifData = [
              'title' => "Test Title",
              'body' => "Test notification body",
              //  "image": "url-to-image",//Optional
              'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
            ];
          
            $dataPayload = ['to'=> 'My Name', 
            'points'=>80, 
            'other_data' => 'This is extra payload'
            ];
          
            // Create the api body
            $apiBody = [
              'notification' => $notifData,
              'data' => $dataPayload, //Optional
              'time_to_live' => 600, // optional - In Seconds
              'to' => '/dashboard/newtopic'
              //'registration_ids' = ID ARRAY
            //   'to' => 'cc3y906oCS0:APA91bHhifJikCe-6q_5EXTdkAu57Oy1bqkSExZYkBvL6iKCq2hq3nrqKWymoxfTJRnzMSqiUkrWh4uuzzEt3yF5KZTV6tLQPOe9MCepimPDGTkrO8lyDy79O5sv046-etzqCGmKsKT4'
            ];
          
            // Initialize curl with the prepared headers and body
            $ch = curl_init();
            curl_setopt ($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt ($ch, CURLOPT_POST, true);
            curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

          
            // Execute call and save result
            $result = curl_exec($ch);

            if(curl_errno($ch)){
                echo 'Request Error:' . curl_error($ch);
                die();
            }

            print($result);
            // Close curl after call
            curl_close($ch);
          
            return $result;
         
    }


    // c2ZpBUhNQiI:APA91bFf_o-N5jdY41dORhyvnAfzLT5fuAvUV97fn-o6WOZ6xnQ0ugOCuM0vaCf_R1q4eC548GKcWwCV9RDBMBmY8KLttn8_zb8XKmpYCJxRwN3s3JwfknoYE7IUMaLr5cKphuIQjJzp
    
    public function sendWebPush($title, $body)
    {
        $token = "eKvXhMbkDTg:APA91bE4RrD5zLy_0PB8Ai871giToE3-LXMwVuj1BKGJoSCXAapuZmKIK90cXTZLyQ8GdKw0O6UgQ6sIxStQK_BX2ObqBpaO5DJ-OjcswH0lVF747gDv66VGl6QBVupctg99p4FNisCg";  
        $from = "AAAAPb7Be5g:APA91bG5Xy_UX66wmiyKY3d9Dp_LiK5qA9_aHDDMousl9hehj6f53UQeMeAExMAuonyHizy3eI5wz0sndS9xQkIpwhwyro89DKxzvv6TLJeCmNb-t2ANcvXTYb0mS1nFNNZ8jL39sC8D";
        $msg = array
              (
                'body'  => "$body",
                'title' => "$title",
                'receiver' => 'erw',
                'icon'  => "https://image.flaticon.com/icons/png/512/270/270014.png",/*Default Icon*/
                'sound' => 'mySound'/*Default sound*/
              );

        $fields = array
                (
                    'to'        => $token,
                    'notification'  => $msg
                );

        $headers = array
                (
                    'Authorization: key=' . $from,
                    'Content-Type: application/json'
                );
        //#Send Reponse To FireBase Server 
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        //print_r($result);
        curl_close( $ch );
    }

    public function getUniqueIdForSchool()
    {
       return  substr(str_shuffle(123456789),0,3).substr(str_shuffle(time()),0,3);
    }



    
}
