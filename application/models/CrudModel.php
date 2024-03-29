<?php

class CrudModel extends CI_Model
{
    private $tableName  = '';
    private $student_id = '';
    private $messageArr = array();

    private $startTime = "00:00:00";
    private $endTime = "23:59:59";

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
            if ($this->db->insert_id() != '') {
                return $this->db->insert_id();
            } else {
                return false;
            }
        }
    }

    public function update($tableName, $params, $stuId)
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
            if ($this->db->query($sql)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getUserId($tableName)
    {
        $this->tableName    = $tableName;
        $dd = $this->db->query("SELECT user_id FROM " . $this->tableName . " WHERE user_id IS NOT NULL ORDER BY id DESC LIMIT 1")->result_array();
        $id = explode(HelperClass::prefix, $dd[0]['user_id']);
        return HelperClass::prefix . ($id[1] + 1);
    }

    public function getSchoolId($tableName)
    {
        $this->tableName    = $tableName;
        $dd = $this->db->query("SELECT user_id FROM " . $this->tableName . " WHERE user_id IS NOT NULL ORDER BY id DESC LIMIT 1")->result_array();
        $id = explode(HelperClass::schoolIDPrefix, $dd[0]['user_id']);
        return HelperClass::schoolIDPrefix . ($id[1] + 1);
    }





    public function getTeacherId($tableName)
    {
        $this->tableName    = $tableName;
        $dd = $this->db->query("SELECT user_id FROM " . $this->tableName . " WHERE user_id IS NOT NULL ORDER BY id DESC LIMIT 1")->result_array();
        $id = explode(HelperClass::tecPrefix, $dd[0]['user_id']);
        return HelperClass::tecPrefix . ($id[1] + 1);
    }


    public function getDriverId($tableName)
    {
        $this->tableName    = $tableName;
        $dd = $this->db->query("SELECT user_id FROM " . $this->tableName . " WHERE user_id IS NOT NULL ORDER BY id DESC LIMIT 1")->result_array();
        $id = explode(HelperClass::driverPrefix, $dd[0]['user_id']);
        return HelperClass::driverPrefix . ($id[1] + 1);
    }



    public function uploadImg(array $file, $id = '', $uploadImagePath = '')
    {

        $errors = array();
        $file_name = HelperClass::imgPrefix . $id . "-" . time() . $file['image']['name'];
        $file_size = $file['image']['size'];
        $file_tmp = $file['image']['tmp_name'];
        $file_type = $file['image']['type'];
        $explode = explode('.', $file['image']['name']);
        $end = end($explode);
        $file_ext = strtolower($end);

        $extensions = array("jpeg", "jpg", "png", "gif");

        if (in_array($file_ext, $extensions) === false) {
            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        }

        if ($file_size > 2097152) {
            $errors[] = 'File size must be excately 2 MB';
        }

        if (empty($errors) == true) {

            //move_uploaded_file($file_tmp,HelperClass::uploadImgDir.$file_name);
            //    return $file_name;

            if (empty($uploadImagePath) && $uploadImagePath == "") {
                $uploadDir = HelperClass::uploadImgDir;
            } else {
                $uploadDir = $uploadImagePath;
            }

            $destUrl =  $this->compressImg($file_tmp, $uploadDir . $file_name, 80);
            $exp = explode($uploadDir, $destUrl);
            return $exp[1]; // return upload url
        } else {
            return false;
        }
    }

    public function uploadImgWithName($file, $id = '', $uploadImagePath = '')
    {

        $errors = array();
        $file_name = HelperClass::imgPrefix . $id . "-" . time() . $file['name'];
        $file_size = $file['size'];
        $file_tmp = $file['tmp_name'];
        $file_type = $file['type'];
        $explode = explode('.', $file['name']);
        $end = end($explode);
        $file_ext = strtolower($end);

        $extensions = array("jpeg", "jpg", "png", "gif");

        if (in_array($file_ext, $extensions) === false) {
            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        }

        if ($file_size > 2097152) {
            $errors[] = 'File size must be excately 2 MB';
        }

        if (empty($errors) == true) {

            //move_uploaded_file($file_tmp,HelperClass::uploadImgDir.$file_name);
            //    return $file_name;

            if (empty($uploadImagePath) && $uploadImagePath == "") {
                $uploadDir = HelperClass::uploadImgDir;
            } else {
                $uploadDir = $uploadImagePath;
            }

            $destUrl =  $this->compressImg($file_tmp, $uploadDir . $file_name, 80);
            $exp = explode($uploadDir, $destUrl);
            return $exp[1]; // return upload url
        } else {
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

    public function showAllStudents($tableName, $data = '')
    {
        $this->tableName = $tableName;
        $dir = base_url() . HelperClass::studentImagePath;
        if (!empty($data)) {
            // print_r($data);
            $condition = " 
             AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
             AND c.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
             AND ss.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
             AND st.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
             AND ct.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            ";

            if (isset($data['studentName']) || isset($data['studentClass']) || isset($data['studentMobile']) || isset($data['studentUserId']) || isset($data['studentFromDate']) || isset($data['studentToDate']) || isset($data['studentSection'])) {
                if (!empty($data['studentName'])) {
                    $condition .= " AND s.name LIKE '%{$data['studentName']}%' ";
                }
                if (!empty($data['studentClass'])) {
                    $condition .= " AND c.id = '{$data['studentClass']}' ";
                }
                if (!empty($data['studentSection'])) {
                    $condition .= " AND ss.id = '{$data['studentSection']}' ";
                }
                if (!empty($data['studentMobile'])) {
                    $condition .= " AND s.mobile LIKE '%{$data['studentMobile']}%' ";
                }
                if (!empty($data['studentUserId'])) {
                    $condition .= " AND s.user_id LIKE '%{$data['studentUserId']}%' ";
                }


                if (!empty($data['studentFromDate']) && !empty($data['studentToDate'])) {
                    $condition .= " AND s.created_at >= '{$data['studentFromDate']}' AND  s.created_at <= '{$data['studentToDate']}' ";
                }
                // if(!empty($data['studentFromDate']) )
                // {
                //     $condition .= " AND s.created_at = '{$data['studentFromDate']}'  ";
                // }
                // if( !empty($data['studentToDate']))
                // {
                //     $condition .= "  AND  s.created_at = '{$data['studentToDate']}' ";
                // }

                if (!empty($data['search']['value'])) {
                    $condition .= " 
                    OR s.name LIKE '%{$data['search']['value']}%' 
                    OR c.id LIKE '%{$data['search']['value']}%' 
                    OR s.mobile LIKE '%{$data['search']['value']}%' 
                    OR s.user_id LIKE '%{$data['search']['value']}%' 
                    ";
                }
            }

            $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,s.status,s.name,s.user_id,s.mobile,s.dob,s.pincode,
                s.driver_id, s.vechicle_type,c.className,ss.sectionName,st.stateName,ct.cityName, dt.name as DriverName,s.password,s.schoolUniqueCode,s.father_name FROM " . $this->tableName . " s
                LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                LEFT JOIN " . Table::driverTable . " dt ON dt.id =  s.driver_id
                WHERE s.status NOT IN ('3','4')  $condition ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $countSql = "SELECT count(s.id) as count  FROM " . $this->tableName . " s
                LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                LEFT JOIN " . Table::driverTable . " dt ON dt.id =  s.driver_id
                WHERE s.status NOT IN ('3','4')  $condition ORDER BY s.id DESC";
        } else {
            $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,s.status,s.name,s.user_id,s.mobile,s.dob,s.pincode,
                s.driver_id, s.vechicle_type,c.className,ss.sectionName,st.stateName,ct.cityName,dt.name as DriverName,s.password,s.schoolUniqueCode,s.father_name FROM " . $this->tableName . " s
                LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                LEFT JOIN " . Table::driverTable . " dt ON dt.id =  s.driver_id
                WHERE s.status NOT IN ('3','4')  ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $countSql = "SELECT count(s.id) as count FROM " . $this->tableName . " s
                LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                LEFT JOIN " . Table::driverTable . " dt ON dt.id =  s.driver_id
                WHERE s.status NOT IN ('3','4')  ORDER BY s.id DESC";
        }


        $tCount = $this->db->query($countSql)->result_array();

        $sendArr = [];
        for ($i = 0; $i < count($d); $i++) {
            $subArr = [];

            $subArr[] = ($j = $i + 1);
            $subArr[] = "<img src='{$d[$i]['image']}' alt='100x100' height='50px' width='50px' class='img-fluid rounded-circle' />";
            $subArr[] = $d[$i]['name'];
            $subArr[] = $d[$i]['father_name'];
            // $subArr[] = $d[$i]['user_id'];
            $subArr[] = $d[$i]['mobile'];
            $subArr[] = strtoupper($d[$i]['password']);
            $subArr[] = $d[$i]['schoolUniqueCode'];
            $subArr[] = $d[$i]['className'] . " ( " . $d[$i]['sectionName'] . " )";
            $subArr[] = $d[$i]['stateName'] . " - " . $d[$i]['cityName'] . " - " . $d[$i]['pincode'];

            if ($d[$i]['status'] == '1') {
                $ns =  '2';
            } else {
                $ns = '1';
            }


            if ($d[$i]['status'] == '1') {
                $cclas = 'success';
                $ssus = 'Active';
            } else {
                $cclas = 'danger';
                $ssus = 'Inactive';
            }



            $subArr[] = "<a href='?action=status&edit_id=" . $d[$i]['id'] . "&status=" . $ns . " ' class='badge badge-" . $cclas . "'> " . $ssus . "</a>";



            // $subArr[] = date('d-m-Y', strtotime($d[$i]['dob']));
            if ($d[$i]['driver_id'] != '' || $d[$i]['vechicle_type'] != '' || $d[$i]['vechicle_type'] != null || $d[$i]['driver_id'] != null) {
                $subArr[] = HelperClass::vehicleType[$d[$i]['vechicle_type']] . " - " . $d[$i]['DriverName'] . " <br>" . '<button onclick="assingDriver(' . $d[$i]['id'] . ')" class="btn btn-warning mt-1" >Change Driver</button>';
            } else {
                $subArr[] = '<button onclick="assingDriver(' . $d[$i]['id'] . ')" class="btn btn-info" >Assign Transport</button>';
            }

            $subArr[] = '
                <a href="viewStudent/' . $d[$i]['id'] . '" class="btn btn-primary" ><i class="fas fa-eye"></i></a>  
                <a href="editStudent/' . $d[$i]['id'] . '" class="btn btn-warning" ><i class="fas fa-edit"></i></a>  
                <a href="deleteStudent/' . $d[$i]['id'] . '" class="btn btn-danger" 
                onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fas fa-trash"></i></a>';

            $sendArr[] = $subArr;
        }

        $dataTableArr = [
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr
        ];

        echo json_encode($dataTableArr);
    }

    public function questionLists($tableName, $data = '')
    {
        $this->tableName = $tableName;
        $dir = base_url() . HelperClass::studentImagePath;
        if (!empty($data)) {
            $condition = '';

            if (isset($data['className']) || isset($data['subjectName']) || isset($data['book']) || isset($data['chapterName']) || isset($data['questionType'])) {
                if (!empty($data['className'])) {
                    $condition .= " AND b.class_name LIKE '%{$data['className']}%' ";
                }
                if (!empty($data['subjectName'])) {
                    $condition .= " AND b.subject_name LIKE '%{$data['subjectName']}%' ";
                }
                if (!empty($data['book'])) {
                    $condition .= " AND b.id = '{$data['book']}' ";
                }
                if (!empty($data['chapterName'])) {
                    $condition .= " AND c.chapter_name LIKE '%{$data['chapterName']}%' ";
                }
                if (!empty($data['questionType'])) {
                    $condition .= " AND s.questionType = '{$data['questionType']}' ";
                }

                if (!empty($data['search']['value'])) {
                    $condition .= " 
                    OR s.name LIKE '%{$data['search']['value']}%' 
                    OR c.id LIKE '%{$data['search']['value']}%' 
                    OR s.mobile LIKE '%{$data['search']['value']}%' 
                    OR s.user_id LIKE '%{$data['search']['value']}%' 
                    ";
                }
            }

            $d = $this->db->query("SELECT s.schoolUniqueCode,s.id,s.status,s.question_type,s.question,s.image,s.option_1,s.option_2,s.option_3,s.option_4,b.book_name,b.class_name,b.subject_name,b.board_name,c.chapter_name FROM " . $this->tableName . " s
                LEFT JOIN " . Table::booksTable . " b ON b.id =  s.book_id
                LEFT JOIN " . Table::chaptersTable . " c ON c.id =  s.chapter_id
                WHERE s.status NOT IN ('3','4')  $condition ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $countSql = "SELECT count(s.id) as count  FROM " . $this->tableName . " s
            LEFT JOIN " . Table::booksTable . " b ON b.id =  s.book_id
            LEFT JOIN " . Table::chaptersTable . " c ON c.id =  s.chapter_id
                WHERE s.status NOT IN ('3','4')  $condition ORDER BY s.id DESC";
        } else {
            $d = $this->db->query("SELECT s.schoolUniqueCode,s.id, s.status,s.question_type,s.question,s.image,s.option_1,s.option_2,s.option_3,s.option_4,b.book_name,b.class_name,b.subject_name,b.board_name,c.chapter_name FROM " . $this->tableName . " s
            LEFT JOIN " . Table::booksTable . " b ON b.id =  s.book_id
            LEFT JOIN " . Table::chaptersTable . " c ON c.id =  s.chapter_id
                WHERE s.status NOT IN ('3','4')  ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $countSql = "SELECT count(s.id) as count FROM " . $this->tableName . " s
            LEFT JOIN " . Table::booksTable . " b ON b.id =  s.book_id
            LEFT JOIN " . Table::chaptersTable . " c ON c.id =  s.chapter_id
                WHERE s.status NOT IN ('3','4')  ORDER BY s.id DESC";
        }


        $tCount = $this->db->query($countSql)->result_array();

        $sendArr = [];
        for ($i = 0; $i < count($d); $i++) {
            $subArr = [];

            $subArr[] = ($j = $i + 1);
            $subArr[] = HelperClass::questionTypes[$d[$i]['question_type']];
            $subArr[] = html_entity_decode($d[$i]['question'], ENT_QUOTES);
            $subArr[] = $d[$i]['board_name'];
            $subArr[] = $d[$i]['class_name'];
            $subArr[] = $d[$i]['subject_name'];
            $subArr[] = $d[$i]['book_name'];
            $subArr[] = $d[$i]['chapter_name'];
            if ($d[$i]['status'] == '1') {
                $ns =  '2';
            } else {
                $ns = '1';
            }


            // if ($d[$i]['status'] == '1') {
            //     $cclas = 'success';
            //     $ssus = 'Active';
            // } else {
            //     $cclas = 'danger';
            //     $ssus = 'Inactive';
            // }

            // $subArr[] = "<a href='?action=status&edit_id=" . $d[$i]['id'] . "&status=" . $ns . " ' class='badge badge-" . $cclas . "'> " . $ssus . "</a>";

            if ($_SESSION['schoolUniqueCode'] == $d[$i]['schoolUniqueCode']) {
                $subArr[] = '
                <a href="editStudent/' . $d[$i]['id'] . '" class="btn btn-warning" ><i class="fas fa-edit"></i></a>';
            } else {
                $subArr[] = '---';
            }

            $sendArr[] = $subArr;
        }

        $dataTableArr = [
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr
        ];

        echo json_encode($dataTableArr);
    }


    public function registrationLists($tableName, $data = '')
    {
        $this->tableName = $tableName;
        $dir = base_url() . HelperClass::studentImagePath;
        if (!empty($data)) {
            $condition = '';

            if (isset($data['className']) || isset($data['subjectName']) || isset($data['book']) || isset($data['chapterName']) || isset($data['questionType'])) {
                if (!empty($data['className'])) {
                    $condition .= " AND b.class_name LIKE '%{$data['className']}%' ";
                }
                if (!empty($data['subjectName'])) {
                    $condition .= " AND b.subject_name LIKE '%{$data['subjectName']}%' ";
                }
                if (!empty($data['book'])) {
                    $condition .= " AND b.id = '{$data['book']}' ";
                }
                if (!empty($data['chapterName'])) {
                    $condition .= " AND c.chapter_name LIKE '%{$data['chapterName']}%' ";
                }
                if (!empty($data['questionType'])) {
                    $condition .= " AND s.questionType = '{$data['questionType']}' ";
                }

                if (!empty($data['search']['value'])) {
                    $condition .= " 
                    OR s.name LIKE '%{$data['search']['value']}%' 
                    OR c.id LIKE '%{$data['search']['value']}%' 
                    OR s.mobile LIKE '%{$data['search']['value']}%' 
                    OR s.user_id LIKE '%{$data['search']['value']}%' 
                    ";
                }
            }

            $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,s.status,s.regNo,s.address,s.regDate,s.stuName,s.mobile,s.dob,s.pincode,
            s.reg_fee,c.className,st.stateName,ct.cityName,s.schoolUniqueCode,s.father_name FROM " . $this->tableName . " s
            LEFT JOIN " . Table::classTable . " c ON c.id =  s.class
            LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state
            LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city
            WHERE s.status != '5'  $condition ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $countSql = "SELECT count(s.id) as count  FROM " . $this->tableName . " s
            LEFT JOIN " . Table::classTable . " c ON c.id =  s.class
            LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state
            LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city
                WHERE s.status != '5'  $condition ORDER BY s.id DESC";
        } else {
            $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,s.status,s.regNo,s.regDate,s.address,s.stuName,s.mobile,s.dob,s.pincode,
            s.reg_fee,c.className,st.stateName,ct.cityName,s.schoolUniqueCode,s.father_name FROM " . $this->tableName . " s
           LEFT JOIN " . Table::classTable . " c ON c.id =  s.class
           LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state
           LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city
                WHERE s.status != '5'  ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $countSql = "SELECT count(s.id) as count FROM " . $this->tableName . " s
            LEFT JOIN " . Table::classTable . " c ON c.id =  s.class
            LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state
            LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city
                WHERE s.status != '5'  ORDER BY s.id DESC";
        }


        $tCount = $this->db->query($countSql)->result_array();

        $sendArr = [];
        for ($i = 0; $i < count($d); $i++) {
            $subArr = [];

            $subArr[] = ($j = $i + 1);
            $subArr[] =  $d[$i]['regNo'];
            $subArr[] = date('d F Y' ,strtotime($d[$i]['regDate']));
            $subArr[] = ucwords($d[$i]['stuName']);
            $subArr[] = $d[$i]['mobile'];
            // $subArr[] = date('d F Y' ,strtotime($d[$i]['dob']));
            $subArr[] = $d[$i]['className'];
            $subArr[] = $d[$i]['cityName'];

            $regArr = [
                1 => 'Registation Success',
                2 => 'Admission Success',
                3 => 'Doubt',
                4 => 'Not Intrested'
              ];

              $subArr[] = $regArr[$d[$i]['status']];
          
          $subArr[] = "<a href='javascript:void(0)' class='btn btn-dark' onclick='changeStatus(".$d[$i]['id'].")'>Change Status</a>";
            if ($_SESSION['schoolUniqueCode'] == $d[$i]['schoolUniqueCode']) {
                $subArr[] = '
                <a href="newRegistration?stu_id=' . $d[$i]['id'] . '" class="btn btn-warning" ><i class="fas fa-edit"></i></a>';
            } else {
                $subArr[] = '---';
            }

            $sendArr[] = $subArr;
        }

        $dataTableArr = [
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr
        ];

        echo json_encode($dataTableArr);
    }









    public function listStudentsPermote($tableName, $data = '')
    {
        $this->tableName = $tableName;
        $dir = base_url() . HelperClass::studentImagePath;
        if (!empty($data)) {
            // print_r($data);
            $condition = " 
             AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
             AND c.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
             AND ss.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
             AND st.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
             AND ct.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            ";

            if (isset($data['studentName']) || isset($data['studentClass']) || isset($data['studentMobile']) || isset($data['studentUserId']) || isset($data['studentFromDate']) || isset($data['studentToDate']) || isset($data['studentSection'])) {
                if (!empty($data['studentName'])) {
                    $condition .= " AND s.name LIKE '%{$data['studentName']}%' ";
                }
                if (!empty($data['studentClass'])) {
                    $condition .= " AND c.id = '{$data['studentClass']}' ";
                }
                if (!empty($data['studentSection'])) {
                    $condition .= " AND ss.id = '{$data['studentSection']}' ";
                }
            }

            $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,s.status,s.name,s.user_id,s.mobile,s.dob,s.pincode,
                s.driver_id, s.vechicle_type,c.className,ss.sectionName,st.stateName,ct.cityName FROM " . $this->tableName . " s
                LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                WHERE s.status NOT IN ('3','4') $condition ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $countSql = "SELECT count(s.id) as count  FROM " . $this->tableName . " s
                LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                WHERE s.status NOT IN ('3','4') $condition ORDER BY s.id DESC";
        } else {
            $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,s.status,s.name,s.user_id,s.mobile,s.dob,s.pincode,
                s.driver_id, s.vechicle_type,c.className,ss.sectionName,st.stateName,ct.cityName FROM " . $this->tableName . " s
                LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                WHERE s.status NOT IN ('3','4') ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $countSql = "SELECT count(s.id) as count FROM " . $this->tableName . " s
                LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                WHERE s.status NOT IN ('3','4') ORDER BY s.id DESC";
        }


        $tCount = $this->db->query($countSql)->result_array();

        $sendArr = [];
        for ($i = 0; $i < count($d); $i++) {
            $subArr = [];

            $subArr[] = ($j = $i + 1);
            $subArr[] = "<img src='{$d[$i]['image']}' alt='100x100' height='50px' width='50px' class='img-fluid rounded-circle' />";
            $subArr[] = $d[$i]['name'];
            $subArr[] = $d[$i]['user_id'];
            $subArr[] = $d[$i]['mobile'];
            $subArr[] = $d[$i]['className'] . " - " . $d[$i]['sectionName'];
            $subArr[] = '<button onclick="permoteStudent(' . $d[$i]['id'] . ')" class="btn btn-info" >Permote Student</button>';
            $sendArr[] = $subArr;
        }

        $dataTableArr = [
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr
        ];

        echo json_encode($dataTableArr);
    }

    public function calculatePercentageAmount($amount, $percentage)
    {
        return ceil($amount * $percentage / 100);
    }
    public function showEmployeesViaDepartmentIdAndDesignationId($data)
    {
        $this->tableName = Table::salaryTable;
        $dir = base_url() . HelperClass::studentImagePath;
        if (!empty($data)) {
            // print_r($data);
            $condition = " AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND s.departmentId = '{$data['departmentId']}' AND s.designationId = '{$data['designationId']}' ";

            $d = $this->db->query("SELECT s.*, dep.departmentName, des.designationName FROM " . $this->tableName . " s 
            JOIN " . Table::departmentTable . " dep  ON dep.id = s.departmentId 
            JOIN " . Table::designationTable . " des ON des.id = s.designationId 
            WHERE s.status != 4 $condition ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $query = $this->db->last_query();

            $tCount = $this->db->query("SELECT count(s.id) as count  FROM " . $this->tableName . " s 
            JOIN " . Table::departmentTable . " dep  ON dep.id = s.departmentId 
            JOIN " . Table::designationTable . " des ON des.id = s.designationId 
            WHERE s.status != 4 $condition ORDER BY s.id DESC")->result_array();
        }

        $sendArr = [];

        $totalWorkingDays =  $this->totalEmployeesWorkingDaysAndHolidaysCurrentMonth($data['monthId'], $data['yearId']);


        for ($i = 0; $i < count($d); $i++) {

            $totalAttendanceArr =  $this->getTotalAttendanceOfEmployeeCurrentMonth($d[$i]['id'], $data['monthId'], $data['yearId']);


            $totalPresentDays = $totalAttendanceArr['present'];
            $totalAbsentDays = $totalAttendanceArr['absent'];
            $totalHalfDays = $totalAttendanceArr['helfDay'];
            $totalLeavesDays = $totalAttendanceArr['leaves'];



            // check how many leaves allow in one month for employee
            $totalLeavesAllowPerMonth = $d[$i]['leavesPerMonth'];

            // per day salary
            $perDaySalary = $d[$i]['basicSalaryDay'];
            // per month salary
            $perMonthSalary = $d[$i]['basicSalaryMonth'];

            // if absent How much deducat per day
            $absentDeducation = $d[$i]['lwp'];

            // half day deducation
            $halfDayDeducation = $d[$i]['ded_half_day'];





            // absent / leave Deducations days
            if (($t = ($totalLeavesDays + $totalAbsentDays) - $totalLeavesAllowPerMonth) > 0) {
                $lwpDeducationsDays = $t;
            } else {
                $lwpDeducationsDays = 0;
            }


            // leave deducation amount

            if ($lwpDeducationsDays > 0) {
                $leaveDudutionAmt  =  $lwpDeducationsDays * $absentDeducation;
            } else {
                $leaveDudutionAmt = 0;
            }


            // half day deducations    

            if ($totalHalfDays > 0) {
                $halfDayDeducationAmt  =  $totalHalfDays * $halfDayDeducation;
            } else {
                $halfDayDeducationAmt = 0;
            }


            // total working days present days
            $t = ($totalLeavesDays + $totalAbsentDays) - $totalLeavesAllowPerMonth;
            if ($t > 0) {
                $totalDaysExpectToPresentForMonthlySalary = $totalWorkingDays['totalWorkingDays'] - $t;
            } else {
                $totalDaysExpectToPresentForMonthlySalary = $totalWorkingDays['totalWorkingDays'];
            }

            // echo "presentDays " . $totalPresentDays;
            // echo "expectedDays " .$totalDaysExpectToPresentForMonthlySalary;
            // die();
            if ($totalPresentDays < $totalDaysExpectToPresentForMonthlySalary) {
                // salary per day
                if ($totalPresentDays > 0) {
                    $salary0 = $totalPresentDays * $perDaySalary; // full day salary
                } else {
                    $salary0 = 0; // full day salary
                }

                if ($totalHalfDays > 0) {
                    $salary1 = $totalHalfDays * ($perDaySalary - $halfDayDeducation); // half day salary
                } else {
                    $salary1 = 0;
                }

                $ssalary = $salary0 + $salary1;
            } else if ($totalPresentDays == $totalDaysExpectToPresentForMonthlySalary) {
                if ($totalPresentDays > 0) {
                    // salary per month
                    $salary0 = $perMonthSalary;
                } else {
                    $salary0 = 0;
                }

                $ssalary = $salary0;
            }


            $da0 = ($d[$i]['dearnessAll'] > 0) ? $this->calculatePercentageAmount($ssalary, $d[$i]['dearnessAll']) : 0;
            $hra0 = ($d[$i]['hra'] > 0) ? $this->calculatePercentageAmount($ssalary, $d[$i]['hra']) : 0;
            $ca0 = ($d[$i]['conAll'] > 0) ? $this->calculatePercentageAmount($ssalary, $d[$i]['conAll']) : 0;
            $ma0 = ($d[$i]['medicalAll'] > 0) ? $this->calculatePercentageAmount($ssalary, $d[$i]['medicalAll']) : 0;
            $sa0 = ($d[$i]['specialAll'] > 0) ? $this->calculatePercentageAmount($ssalary, $d[$i]['specialAll']) : 0;

            // total allowances
            $totalAll = $da0 + $hra0 + $ca0 +  $ma0 +  $sa0;


            $ptpm0 = ($d[$i]['professionalTaxPerMonth'] > 0) ? $this->calculatePercentageAmount($ssalary, $d[$i]['professionalTaxPerMonth']) : 0;
            $pfm0 = ($d[$i]['pfPerMonth'] > 0) ? $this->calculatePercentageAmount($ssalary, $d[$i]['pfPerMonth']) : 0;
            $tds0 = ($d[$i]['tdsPerMonth'] > 0) ? $this->calculatePercentageAmount($ssalary, $d[$i]['tdsPerMonth']) : 0;

            // total deducations
            $totalDed = $ptpm0 + $pfm0 + $tds0;


            // totalSalaryAfterDeduation basicpay + allownaces - deducations
            $totalSalaryAfterDeducation = ($ssalary + $totalAll) - $totalDed;

            // totalSalaryToPay
            $totalDeducationMonth = @$leaveDudutionAmt + @$halfDayDeducationAmt + @$totalDed;
            $totalAllowMonth = @$totalAll;

            // total salary now basicpay + allowance - deducation
            $ssalaryAmount = (@$ssalary + @$totalAllowMonth) - @$totalDeducationMonth;



            $subArr = [];

            $subArr[] = ($j = $i + 1);
            $subArr[] = $d[$i]['empId'];
            $subArr[] = $d[$i]['employeeName'];
            $subArr[] = $d[$i]['departmentName'];
            $subArr[] = $d[$i]['designationName'];
            $subArr[] = $ssalaryAmount;
            $subArr[] = $totalDeducationMonth;
            $subArr[] = $totalAllowMonth;
            $subArr[] = '<button onclick="checkDetails(' . $d[$i]['id'] . ')" class="btn btn-info" >Check Details</button>';
            $sendArr[] = $subArr;
        }

        $dataTableArr = [
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr,
            "query" => $query,
            "attendanceData" => $totalAttendanceArr
        ];

        echo json_encode($dataTableArr);
    }





    public function showAllTeachers($tableName, $data = '')
    {
        $this->tableName = $tableName;
        $dir = base_url() . HelperClass::teacherImagePath;
        if (!empty($data)) {
            $condition = "
            AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND c.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND ss.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND st.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND ct.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
             ";

            if (isset($data['teacherName']) || isset($data['teacherClass']) || isset($data['teacherMobile']) || isset($data['teacherUserId']) || isset($data['teacherFromDate']) || isset($data['teacherToDate'])) {
                if (!empty($data['teacherName'])) {
                    $condition .= " AND s.name LIKE '%{$data['teacherName']}%' ";
                }
                if (!empty($data['teacherClass'])) {
                    $condition .= " AND c.className LIKE '%{$data['teacherClass']}%' ";
                }
                if (!empty($data['teacherMobile'])) {
                    $condition .= " AND s.mobile LIKE '%{$data['teacherMobile']}%' ";
                }
                if (!empty($data['teacherUserId'])) {
                    $condition .= " AND s.user_id LIKE '%{$data['teacherUserId']}%' ";
                }



                // if(!empty($data['teacherFromDate']))
                // {
                //     $condition .= " AND s.created_at = '{$data['teacherFromDate']}'  ";
                // }

                // if( !empty($data['teacherToDate']))
                // {
                //     $condition .= "  AND  s.created_at = '{$data['teacherToDate']}' ";
                // }

                if (!empty($data['teacherFromDate']) && !empty($data['teacherToDate'])) {
                    $condition .= " AND s.created_at >= '{$data['teacherFromDate']}' AND  s.created_at <= '{$data['teacherToDate']}' ";
                }

                if (!empty($data['search']['value'])) {
                    $condition .= " 
                    OR s.name LIKE '%{$data['search']['value']}%' 
                    OR c.className LIKE '%{$data['search']['value']}%' 
                    OR s.mobile LIKE '%{$data['search']['value']}%' 
                    OR s.user_id LIKE '%{$data['search']['value']}%' 
                    ";
                }
            }

            $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,s.status,s.name,s.user_id,s.password,s.schoolUniqueCode,s.mobile,s.dob,s.pincode,s.address,s.experience, s.education, s.driver_id, s.vechicle_type, c.className,ss.sectionName,st.stateName,ct.cityName, dt.name as DriverName FROM " . $this->tableName . " s
                LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                LEFT JOIN " . Table::driverTable . " dt ON dt.id =  s.driver_id
                WHERE s.status != 4 $condition ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $countSql = "SELECT count(s.id) as count  FROM " . $this->tableName . " s
                LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                LEFT JOIN " . Table::driverTable . " dt ON dt.id =  s.driver_id
                WHERE s.status != 4 $condition ORDER BY s.id DESC";
        } else {
            $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,s.status,s.name,s.user_id,,s.password,s.schoolUniqueCode,s.mobile,s.dob,s.pincode,s.address,s.experience, s.education, s.driver_id, s.vechicle_type, c.className,ss.sectionName,st.stateName,ct.cityName, dt.name as DriverName FROM " . $this->tableName . " s
                LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                LEFT JOIN " . Table::driverTable . " dt ON dt.id =  s.driver_id
                WHERE s.status != 4 ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $countSql = "SELECT count(s.id) as count FROM " . $this->tableName . " s
                LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                LEFT JOIN " . Table::driverTable . " dt ON dt.id =  s.driver_id
                WHERE s.status != 4 ORDER BY s.id DESC";
        }


        $tCount = $this->db->query($countSql)->result_array();

        $sendArr = [];
        for ($i = 0; $i < count($d); $i++) {
            $subArr = [];

            $subArr[] = ($j = $i + 1);
            $subArr[] = "<img src='{$d[$i]['image']}' alt='100x100' height='50px' width='50px' class='img-fluid rounded-circle' />";
            $subArr[] = $d[$i]['name'];
            $subArr[] = $d[$i]['user_id'];
            $subArr[] = strtoupper($d[$i]['password']);
            $subArr[] = $d[$i]['schoolUniqueCode'];
            $subArr[] = $d[$i]['mobile'];
            $subArr[] = $d[$i]['className'] . " ( " . $d[$i]['sectionName'] . " ) ";
            // $subArr[] = $d[$i]['education'];
            // $subArr[] = @HelperClass::experience[$d[$i]['experience']];
            $subArr[] = $d[$i]['stateName'] . " - " . $d[$i]['cityName'] . " - " . $d[$i]['pincode'];

            if ($d[$i]['status'] == '1') {
                $ns =  '2';
            } else {
                $ns = '1';
            }


            if ($d[$i]['status'] == '1') {
                $cclas = 'success';
                $ssus = 'Active';
            } else {
                $cclas = 'danger';
                $ssus = 'Inactive';
            }



            $subArr[] = "<a href='?action=status&edit_id=" . $d[$i]['id'] . "&status=" . $ns . " ' class='badge badge-" . $cclas . "'> " . $ssus . "</a>";

            // $subArr[] = date('d-m-Y', strtotime($d[$i]['dob']));

            if ($d[$i]['driver_id'] != '' || $d[$i]['vechicle_type'] != '' || $d[$i]['vechicle_type'] != null || $d[$i]['driver_id'] != null) {
                $subArr[] = HelperClass::vehicleType[$d[$i]['vechicle_type']] . " - " . $d[$i]['DriverName'] . " <br>" . '<button onclick="assingDriver(' . $d[$i]['id'] . ')" class="btn btn-warning mt-1" >Change Driver</button>';
            } else {
                $subArr[] = '<button onclick="assingDriver(' . $d[$i]['id'] . ')" class="btn btn-info" >Assign Transport</button>';
            }

            $subArr[] = '
                <a href="viewTeacher/' . $d[$i]['id'] . '" class="btn btn-primary" ><i class="fas fa-eye"></i></a>  
                <a href="editTeacher/' . $d[$i]['id'] . '" class="btn btn-warning" ><i class="fas fa-edit"></i></a>  
                <a href="deleteTeacher/' . $d[$i]['id'] . '" class="btn btn-danger" 
                onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fas fa-trash"></i></a>';

            $sendArr[] = $subArr;
        }

        $dataTableArr = [
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr
        ];

        echo json_encode($dataTableArr);
    }

    public function showAllDrivers($tableName, $data = '')
    {
        $this->tableName = $tableName;
        $dir = base_url() . HelperClass::driverImagePath;
        if (!empty($data)) {
            $condition = "
            AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND st.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND ct.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
             ";

            if (isset($data['dName']) || isset($data['dMobile']) || isset($data['dUserId'])) {
                if (!empty($data['dName'])) {
                    $condition .= " AND s.name LIKE '%{$data['dName']}%' ";
                }
                if (!empty($data['dMobile'])) {
                    $condition .= " AND s.mobile LIKE '%{$data['dMobile']}%' ";
                }
                if (!empty($data['dUserId'])) {
                    $condition .= " AND s.user_id LIKE '%{$data['dUserId']}%' ";
                }
            }

            $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,s.status,s.name,s.user_id,s.mobile, s.password,s.schoolUniqueCode,
                s.vechicle_type,s.vechicle_no,s.total_seats,
                s.pincode,s.address,st.stateName,ct.cityName FROM " . $this->tableName . " s
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                WHERE s.status != 4 $condition ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $countSql = "SELECT count(s.id) as count  FROM " . $this->tableName . " s
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                WHERE s.status != 4 $condition ORDER BY s.id DESC";
        } else {
            $d = $this->db->query("SELECT s.id,CONCAT('$dir',s.image) as image,s.status,s.name,s.user_id,s.mobile,s.password,s.schoolUniqueCode,
                s.vechicle_type,s.vechicle_no,s.total_seats,
                s.pincode,s.address,st.stateName,ct.cityName FROM " . $this->tableName . " s
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                WHERE s.status != 4 ORDER BY s.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $countSql = "SELECT count(s.id) as count FROM " . $this->tableName . " s
                LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
                LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
                WHERE s.status != 4 ORDER BY s.id DESC";
        }


        $tCount = $this->db->query($countSql)->result_array();

        $sendArr = [];
        for ($i = 0; $i < count($d); $i++) {
            $subArr = [];

            $subArr[] = ($j = $i + 1);
            $subArr[] = "<img src='{$d[$i]['image']}' alt='100x100' height='50px' width='50px' class='img-fluid rounded-circle' />";
            $subArr[] = $d[$i]['name'];
            // $subArr[] = $d[$i]['user_id'];
            $subArr[] = $d[$i]['mobile'];
            $subArr[] = strtoupper($d[$i]['password']);
            $subArr[] = $d[$i]['schoolUniqueCode'];
            $subArr[] = HelperClass::vehicleType[$d[$i]['vechicle_type']];
            $subArr[] = $d[$i]['vechicle_no'];
            $subArr[] = $d[$i]['total_seats'];
            $subArr[] = $d[$i]['stateName'] . " - " . $d[$i]['cityName'] . " - " . $d[$i]['pincode'];

            if ($d[$i]['status'] == '1') {
                $ns =  '2';
            } else {
                $ns = '1';
            }


            if ($d[$i]['status'] == '1') {
                $cclas = 'success';
                $ssus = 'Active';
            } else {
                $cclas = 'danger';
                $ssus = 'Inactive';
            }



            $subArr[] = "<a href='?action=status&edit_id=" . $d[$i]['id'] . "&status=" . $ns . " ' class='badge badge-" . $cclas . "'> " . $ssus . "</a>";

            $subArr[] = '
                <a href="viewDriver/' . $d[$i]['id'] . '" class="btn btn-primary" ><i class="fas fa-eye"></i></a>  
                <a href="editDriver/' . $d[$i]['id'] . '" class="btn btn-warning" ><i class="fas fa-edit"></i></a>  
                <a href="deleteDriver/' . $d[$i]['id'] . '" class="btn btn-danger" 
                onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fas fa-trash"></i></a>';

            $sendArr[] = $subArr;
        }

        $dataTableArr = [
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr
        ];

        echo json_encode($dataTableArr);
    }

    public function listDigiCoin($tableName, $userType, $data = '')
    {
        $this->tableName = $tableName;
        $joins = '';
        if (!empty($userType)) {
            if ($userType == 'Student') {
                $joins .= ' LEFT JOIN ' . Table::studentTable . ' u ON u.id = g.user_id ';
            } else if ($userType == 'Teacher') {
                $joins .= ' LEFT JOIN ' . Table::teacherTable . ' u ON u.id = g.user_id ';
            }
        }


        if (!empty($data)) {

            $condition = " AND g.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND u.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ";

            if (!empty($userType)) {
                $condition .= " AND user_type = '$userType' ";
            }

            if (isset($data['name']) || isset($data['userId']) || isset($data['fromDate']) || isset($data['toDate'])) {
                if (!empty($data['name'])) {
                    $condition .= " AND u.name LIKE '%{$data['name']}%' ";
                }
                if (!empty($data['userId'])) {
                    $condition .= " AND u.user_id LIKE '%{$data['userId']}%' ";
                }

                // if(!empty($data['toDate']))
                // {
                //     $condition .= " AND g.created_at = '{$data['fromDate']}'  ";
                // }
                // if(!empty($data['toDate']))
                // {
                //     $condition .= "  AND  g.created_at = '{$data['toDate']}' ";
                // }
                if (!empty($data['fromDate']) && !empty($data['toDate'])) {
                    $condition .= " AND g.created_at >= '{$data['fromDate']}' AND  g.created_at <= '{$data['toDate']}' ";
                }
            }

            $d = $this->db->query("SELECT g.user_type,g.for_what,g.digiCoin,g.status,u.name,u.user_id as uniqueId FROM " . $this->tableName . " g $joins WHERE g.status != 4 $condition ORDER BY g.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $countSql = "SELECT count(1) as count  FROM " . $this->tableName . " g $joins WHERE g.status != 4 $condition ORDER BY g.id DESC";
        } else {
            $d = $this->db->query("SELECT g.user_type,g.for_what,g.digiCoin,g.status,u.name,u.user_id as uniqueId FROM " . $this->tableName . " g $joins WHERE g.status != 4 ORDER BY g.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $countSql = "SELECT count(1) as count  FROM " . $this->tableName . " g $joins WHERE g.status != 4 ORDER BY g.id DESC";
        }


        $tCount = $this->db->query($countSql)->result_array();

        $sendArr = [];
        for ($i = 0; $i < count($d); $i++) {
            $subArr = [];

            $subArr[] = ($j = $i + 1);
            $subArr[] = $d[$i]['user_type'];
            $subArr[] = $d[$i]['name'];
            $subArr[] = $d[$i]['uniqueId'];
            $subArr[] = @HelperClass::actionTypeR[$d[$i]['for_what']];
            $subArr[] = $d[$i]['digiCoin'];
            if ($d[$i]['status'] == '1') {
                $subArr[] = '<span class="badge badge-success">Active</span>';
            } else {
                $subArr[] = '<span class="badge badge-success">DeActive</span>';
            };
            //  $subArr[] = '
            //  <a href="viewTeacher/'.$d[$i]['id'].'" class="btn btn-primary" ><i class="fas fa-eye"></i></a>  
            //  <a href="editTeacher/'.$d[$i]['id'].'" class="btn btn-warning" ><i class="fas fa-edit"></i></a>  
            //  <a href="deleteTeacher/'.$d[$i]['id'].'" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fas fa-trash"></i></a>';

            $sendArr[] = $subArr;
        }

        $dataTableArr = [
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr
        ];

        echo json_encode($dataTableArr);
    }

    public function allExamList($tableName, $data = '')
    {
        $this->tableName = $tableName;
        // $dir = base_url().HelperClass::uploadImgDir;
        if (!empty($data)) {
            $condition = "
            AND e.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND c.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND ss.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND sub.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND tt.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
              ";

            if (isset($data['teacherName']) || isset($data['examName']) || isset($data['studentClass']) || isset($data['studentSection']) || isset($data['studentFromDate']) || isset($data['studentToDate'])) {
                if (!empty($data['teacherName'])) {
                    $condition .= " AND tt.name LIKE '%{$data['teacherName']}%' ";
                }
                if (!empty($data['examName'])) {
                    $condition .= " AND e.exam_name LIKE '%{$data['examName']}%' ";
                }
                if (!empty($data['studentClass'])) {
                    $condition .= " AND c.id = '{$data['studentClass']}' ";
                }
                if (!empty($data['studentSection'])) {
                    $condition .= " AND ss.id = '{$data['studentSection']}' ";
                }

                // if(!empty($data['studentFromDate']) )
                // {
                //     $condition .= " AND e.created_at = '{$data['studentFromDate']}'  ";
                // }
                // if(!empty($data['studentToDate']))
                // {
                //     $condition .= "  AND  e.created_at = '{$data['studentToDate']}' ";
                // }
                if (!empty($data['studentFromDate']) && !empty($data['studentToDate'])) {
                    $condition .= " AND e.created_at >= '{$data['studentFromDate']}' AND  e.created_at <= '{$data['studentToDate']}' ";
                }
            }

            $d = $this->db->query("SELECT e.id,e.status,e.exam_name,e.date_of_exam,e.max_marks,e.min_marks,c.className,ss.sectionName,tt.name, tt.id as teacherId,sub.subjectName,e.created_at FROM " . $this->tableName . " e
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  e.subject_id
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.login_user_id
                WHERE e.status != 4 $condition ORDER BY e.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();


            $countSql = "SELECT count(e.id) as count  FROM " . $this->tableName . " e
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  e.subject_id
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.login_user_id
                WHERE e.status != 4 $condition ORDER BY e.id DESC";
        } else {
            $d = $this->db->query("SELECT e.id,e.status,e.exam_name,e.date_of_exam,e.max_marks,e.min_marks,c.className,ss.sectionName,tt.name, tt.id as teacherId,sub.subjectName,e.created_at FROM " . $this->tableName . " e
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  e.subject_id
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.login_user_id
                WHERE e.status != 4 ORDER BY e.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();

            $countSql = "SELECT count(e.id) as count  FROM " . $this->tableName . " e
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  e.subject_id
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.login_user_id
                WHERE e.status != 4 ORDER BY e.id DESC";
        }


        $tCount = $this->db->query($countSql)->result_array();

        $sendArr = [];
        for ($i = 0; $i < count($d); $i++) {
            $subArr = [];

            $subArr[] = ($j = $i + 1);
            $subArr[] = $d[$i]['id'];
            // $subArr[] = substr($d[$i]['exam_name'],0,30);
            $subArr[] = $d[$i]['exam_name'];
            $subArr[] = $d[$i]['subjectName'];
            $subArr[] = $d[$i]['date_of_exam'];
            $subArr[] = $d[$i]['max_marks'];
            $subArr[] = $d[$i]['min_marks'];
            $subArr[] = $d[$i]['className'] . " - " . $d[$i]['sectionName'];
            $subArr[] = $d[$i]['teacherId'];
            $subArr[] = $d[$i]['name'];
            if ($d[$i]['status'] == '1') {
                $ns =  '2';
            } else {
                $ns = '1';
            }


            if ($d[$i]['status'] == '1') {
                $ssus = '<span class="badge badge-info">Active</span>';
            } else if ($d[$i]['status'] == '3') {
                $url = base_url('exam/allResults') . '?action=resultPublished&examId=' . $d[$i]['id'];
                $ssus = '<a href="' . $url . '" target="_blank" class="badge badge-success">Check Results</a>';
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
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr,
            "query" => $lastQuery
        ];

        echo json_encode($dataTableArr);
    }


    public function allAttendanceList($tableName, $data = '')
    {
        $this->tableName = $tableName;
        $dir = base_url() . HelperClass::studentImagePath;
        if (!empty($data)) {
            $condition = "
                AND e.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND c.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND ss.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND st.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND tt.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
              ";

            // $condition = "";
            if (isset($data['teacherName']) || isset($data['studentName']) || isset($data['studentClass']) || isset($data['studentSection']) || isset($data['studentFromDate']) || isset($data['studentToDate']) || isset($data['attendanceStatus'])) {
                if (!empty($data['teacherName'])) {
                    $condition .= " AND tt.name LIKE '%{$data['teacherName']}%' ";
                }
                if (!empty($data['studentName'])) {
                    $condition .= " AND st.name LIKE '%{$data['studentName']}%' ";
                }
                if (!empty($data['studentClass'])) {
                    $condition .= " AND c.id = '{$data['studentClass']}' ";
                }
                if (!empty($data['studentSection'])) {
                    $condition .= " AND ss.id = '{$data['studentSection']}' ";
                }
                if (!empty($data['attendanceStatus'])) {
                    if ($data['attendanceStatus'] == 'ab') {
                        $at = '0';
                    } else {
                        $at = '1';
                    }
                    $condition .= " AND e.attendenceStatus = '$at' ";
                }

                // if(!empty($data['studentFromDate']))
                // {
                //     $condition .= " AND e.att_date = '{$data['studentFromDate']}' AND  e.att_date = '{$data['studentFromDate']}' ";
                // }
                // if(!empty($data['studentToDate']))
                // {
                //     $condition .= " AND e.att_date = '{$data['studentToDate']}' AND  e.att_date = '{$data['studentToDate']}' ";
                // }

                if (!empty($data['studentFromDate']) && !empty($data['studentToDate'])) {
                    $condition .= " AND e.att_date >= '{$data['studentFromDate']}' AND  e.att_date <= '{$data['studentToDate']}' ";
                }
            }

            $d = $this->db->query("SELECT e.id,e.attendenceStatus,e.dateTime,c.className,ss.sectionName,tt.name as teacherName, st.name, CONCAT('$dir',st.image) as image, st.user_id
                FROM " . $this->tableName . " e
                LEFT JOIN " . Table::classTable . " c ON c.className =  e.stu_class
                LEFT JOIN " . Table::sectionTable . " ss ON ss.sectionName =  e.stu_section
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.login_user_id
                LEFT JOIN " . Table::studentTable . " st ON st.id =  e.stu_id
                WHERE e.status != 4 $condition ORDER BY e.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();


            $countSql = "SELECT count(e.id) as count  FROM " . $this->tableName . " e
                LEFT JOIN " . Table::classTable . " c ON c.className =  e.stu_class
                LEFT JOIN " . Table::sectionTable . " ss ON ss.sectionName =  e.stu_section
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.login_user_id
                LEFT JOIN " . Table::studentTable . " st ON st.id =  e.stu_id
                WHERE e.status != 4 $condition ORDER BY e.id DESC";
        } else {
            $d = $this->db->query("SELECT e.id,e.attendenceStatus,e.dateTime,c.className,ss.sectionName,tt.name  as teacherName, st.name, CONCAT('$dir',st.image) as image, st.user_id FROM " . $this->tableName . " e
                LEFT JOIN " . Table::classTable . " c ON c.className =  e.stu_class
                LEFT JOIN " . Table::sectionTable . " ss ON ss.sectionName =  e.stu_section
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.login_user_id
                LEFT JOIN " . Table::studentTable . " st ON st.id =  e.stu_id
                WHERE e.status != 4 ORDER BY e.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();

            $countSql = "SELECT count(e.id) as count  FROM " . $this->tableName . " e
                LEFT JOIN " . Table::classTable . " c ON c.className =  e.stu_class
                LEFT JOIN " . Table::sectionTable . " ss ON ss.sectionName =  e.stu_section
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.login_user_id
                LEFT JOIN " . Table::studentTable . " st ON st.id =  e.stu_id
                WHERE e.status != 4 ORDER BY e.id DESC";
        }


        $tCount = $this->db->query($countSql)->result_array();

        $sendArr = [];
        for ($i = 0; $i < count($d); $i++) {
            $subArr = [];

            $subArr[] = ($j = $i + 1);
            $subArr[] = $d[$i]['id'];
            $subArr[] = "<img src='{$d[$i]['image']}' alt='100x100' height='50px' width='50px' class='img-fluid rounded-circle' />";
            $subArr[] = $d[$i]['name'];
            $subArr[] = $d[$i]['user_id'];
            $subArr[] = $d[$i]['className'] . " - " . $d[$i]['sectionName'];
            $subArr[] = ($d[$i]['attendenceStatus'] == '0') ? '<span class="badge badge-danger">Absent</span>' : '<span class="badge badge-success">Present</span>';
            $subArr[] = date('d-m-Y h:i:A', strtotime($d[$i]['dateTime']));
            $subArr[] = $d[$i]['teacherName'];
            $sendArr[] = $subArr;
        }

        $dataTableArr = [
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr,
            "query" => $lastQuery
        ];

        echo json_encode($dataTableArr);
    }

    public function allTeachersAttendanceList($tableName, $data = '')
    {
        $this->tableName = $tableName;
        // $dir = base_url().HelperClass::uploadImgDir;
        if (!empty($data)) {
            $condition = "
                AND e.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND tt.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
              ";

            // $condition = "";
            if (isset($data['teacherName']) || isset($data['studentFromDate']) || isset($data['studentToDate']) || isset($data['attendanceStatus'])) {
                if (!empty($data['teacherName'])) {
                    $condition .= " AND tt.name LIKE '%{$data['teacherName']}%' ";
                }

                if (!empty($data['attendanceStatus'])) {
                    if ($data['attendanceStatus'] == 'ab') {
                        $at = '0';
                    } else {
                        $at = '1';
                    }
                    $condition .= " AND e.attendenceStatus = '$at' ";
                }



                if (!empty($data['studentFromDate']) && !empty($data['studentToDate'])) {
                    $condition .= " AND e.att_date >= '{$data['studentFromDate']}' AND  e.att_date <= '{$data['studentToDate']}' ";
                }
            }

            $d = $this->db->query("SELECT e.id,e.attendenceStatus,e.dateTime,tt.name as teacherName, tt.user_id
                FROM " . $this->tableName . " e
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.tec_id
                WHERE e.status != 4 $condition ORDER BY e.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();


            $countSql = "SELECT count(e.id) as count  FROM " . $this->tableName . " e
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.tec_id
                WHERE e.status != 4 $condition ORDER BY e.id DESC";
        } else {
            $d = $this->db->query("SELECT e.id,e.attendenceStatus,e.dateTime,tt.name  as teacherName, tt.user_id FROM " . $this->tableName . " e
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.tec_id
                WHERE e.status != 4 ORDER BY e.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();

            $countSql = "SELECT count(e.id) as count  FROM " . $this->tableName . " e
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.tec_id
                WHERE e.status != 4 ORDER BY e.id DESC";
        }


        $tCount = $this->db->query($countSql)->result_array();

        $sendArr = [];
        for ($i = 0; $i < count($d); $i++) {
            $subArr = [];

            $subArr[] = ($j = $i + 1);
            $subArr[] = $d[$i]['id'];
            $subArr[] = $d[$i]['teacherName'];
            $subArr[] = $d[$i]['user_id'];
            $subArr[] = ($d[$i]['attendenceStatus'] == '1') ? '<span class="badge badge-success">Present</span>' : '<span class="badge badge-danger">Absent</span>';
            $subArr[] = date('d-m-Y h:i:A', strtotime($d[$i]['dateTime']));
            $sendArr[] = $subArr;
        }

        $dataTableArr = [
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr,
            "query" => $lastQuery
        ];

        echo json_encode($dataTableArr);
    }

    public function allComplaintList($tableName, $data = '')
    {
        $this->tableName = $tableName;
        // $dir = base_url().HelperClass::uploadImgDir;
        if (!empty($data)) {
            $condition = " AND e.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'  ";


            if (isset($data['complaintId']) || isset($data['guiltyPersonName']) || isset($data['studentFromDate']) || isset($data['studentToDate'])) {
                if (!empty($data['complaintId'])) {
                    $condition .= " AND e.complaint_id LIKE '%{$data['complaintId']}%' ";
                }
                if (!empty($data['guiltyPersonName'])) {
                    $condition .= " AND e.guilty_person_name LIKE '%{$data['guiltyPersonName']}%' ";
                }

                // if(!empty($data['studentFromDate']) )
                // {
                //     $condition .= " AND e.created_at = '{$data['studentFromDate']}'  ";
                // }
                // if(!empty($data['studentToDate']))
                // {
                //     $condition .= " AND  e.created_at = '{$data['studentToDate']}' ";
                // }
                if (!empty($data['studentFromDate']) && !empty($data['studentToDate'])) {
                    $condition .= " AND e.created_at >= '{$data['studentFromDate']}' AND  e.created_at <= '{$data['studentToDate']}' ";
                }
            }

            $d = $this->db->query("SELECT e.*
                FROM " . $this->tableName . " e
                WHERE e.status != 4 $condition ORDER BY e.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();

            $countSql = "SELECT count(e.id) as count  FROM " . $this->tableName . " e
                WHERE e.status != 4 $condition ORDER BY e.id DESC";
        } else {
            $d = $this->db->query("SELECT e.* FROM " . $this->tableName . " e
                WHERE e.status != 4 ORDER BY e.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();

            $countSql = "SELECT count(e.id) as count  FROM " . $this->tableName . " e
                WHERE e.status != 4 ORDER BY e.id DESC";
        }


        $tCount = $this->db->query($countSql)->result_array();

        $sendArr = [];
        for ($i = 0; $i < count($d); $i++) {
            $subArr = [];

            $subArr[] = ($j = $i + 1);
            // $subArr[] = $d[$i]['id'];
            $subArr[] = $d[$i]['complaint_id'];
            $subArr[] = HelperClass::userTypeR[$d[$i]['login_user_type']];
            $subArr[] = $d[$i]['login_user_id'];
            $subArr[] = $d[$i]['guilty_person_name'];
            $subArr[] = $d[$i]['guilty_person_position'];
            $subArr[] = $d[$i]['subject'];
            $subArr[] = $d[$i]['issue'];
            $subArr[] = ($d[$i]['action']) ? $d[$i]['action'] : '<button class="btn btn-warning" onclick="takeAction(' . $d[$i]['id'] . ' , ' . $d[$i]['login_user_id'] . ')">Take Action</button>';
            $subArr[] = ($d[$i]['action_taken_user_type']) ? HelperClass::userTypeR[$d[$i]['action_taken_user_type']] : "";
            $subArr[] = $d[$i]['action_taken_id'];
            $subArr[] = $d[$i]['action_taken_date'];
            $subArr[] = ($d[$i]['status'] == '1') ? '<span class="badge badge-info">Pending</span>' : '<span class="badge badge-success">Completed</span>';
            $subArr[] = $d[$i]['created_at'];
            $sendArr[] = $subArr;
        }

        $dataTableArr = [
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr,
            "query" => $lastQuery
        ];

        echo json_encode($dataTableArr);
    }

    public function dateSheetList($tableName, $data = '')
    {
        $this->tableName = $tableName;
        // $dir = base_url().HelperClass::uploadImgDir;
        if (!empty($data)) {
            $condition = " AND se.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'  
             AND sen.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'  
             AND c.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'  
             AND sec.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'  
             AND sub.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'  
             ";


            if (isset($data['secExamNameId']) || isset($data['classId']) || isset($data['sectionId'])) {
                if (!empty($data['secExamNameId'])) {
                    $condition .= " AND sen.id = '{$data['secExamNameId']}' ";
                }
                if (!empty($data['classId'])) {
                    $condition .= " AND c.id = '{$data['classId']}' ";
                }

                if (!empty($data['sectionId']) && !empty($data['sectionId'])) {
                    $condition .= " AND sec.id = '{$data['sectionId']}' ";
                }
            }

            $d = $this->db->query("SELECT se.id as semId, se.exam_date,se.exam_day,se.exam_start_time,se.exam_end_time, se.min_marks, se.max_marks, se.status,sen.id as semNameId, sen.sem_exam_name, sen.exam_year,c.className,sec.sectionName,sub.subjectName
                FROM " . $this->tableName . " se 
                LEFT JOIN " . Table::semExamNameTable . " sen ON sen.id =  se.sem_exam_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  se.class_id
                LEFT JOIN " . Table::sectionTable . " sec ON sec.id =  se.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  se.subject_id
                WHERE se.status != 4 $condition LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();

            $countSql = "SELECT count(se.id) as count  FROM " . $this->tableName . " se
                LEFT JOIN " . Table::semExamNameTable . " sen ON sen.id =  se.sem_exam_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  se.class_id
                LEFT JOIN " . Table::sectionTable . " sec ON sec.id =  se.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  se.subject_id
                WHERE se.status != 4 $condition ";
        } else {
            $d = $this->db->query("SELECT se.id as semId, se.exam_date,se.exam_day,se.exam_start_time,se.exam_end_time, se.min_marks, se.max_marks, se.status,sen.id as semNameId, sen.sem_exam_name, sen.exam_year,c.className,sec.sectionName,sub.subjectName
                 FROM " . $this->tableName . " se
                LEFT JOIN " . Table::semExamNameTable . " sen ON sen.id =  se.sem_exam_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  se.class_id
                LEFT JOIN " . Table::sectionTable . " sec ON sec.id =  se.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  se.subject_id
                WHERE se.status != 4 ORDER BY se.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();

            $countSql = "SELECT count(se.id) as count  FROM " . $this->tableName . " se
                LEFT JOIN " . Table::semExamNameTable . " sen ON sen.id =  se.sem_exam_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  se.class_id
                LEFT JOIN " . Table::sectionTable . " sec ON sec.id =  se.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  se.subject_id
                WHERE se.status != 4 ORDER BY se.id DESC";
        }


        $tCount = $this->db->query($countSql)->result_array();

        $sendArr = [];
        for ($i = 0; $i < count($d); $i++) {
            $subArr = [];

            $subArr[] = ($j = $i + 1);
            $subArr[] = $d[$i]['sem_exam_name'];
            $subArr[] = $d[$i]['semId'];
            $subArr[] = $d[$i]['exam_date'];
            $subArr[] = $d[$i]['exam_day'];
            $subArr[] = $d[$i]['className'] . " - " . $d[$i]['sectionName'];
            $subArr[] = $d[$i]['subjectName'];
            $subArr[] = $d[$i]['min_marks'] . " - " . $d[$i]['max_marks'];
            $subArr[] = ($d[$i]['status'] == '1') ? '<span class="badge badge-info">Active</span>' : '<span class="badge badge-success">Inactive</span>';
            $subArr[] = '<a href="' . base_url('semester/dateSheetMaster') . '?action=edit&edit_id=' . $d[$i]['semId'] . '" class="btn btn-warning">Edit</a>
                <a href="' . base_url('semester/dateSheetMaster') . '?action=delete&delete_id=' . $d[$i]['semId'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete this?\');">Delete</a>';
            $sendArr[] = $subArr;
        }

        $dataTableArr = [
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr,
            "query" => $lastQuery
        ];

        echo json_encode($dataTableArr);
    }


    public function allResultList($tableName, $data = '')
    {
        $this->tableName = $tableName;
        // $dir = base_url().HelperClass::uploadImgDir;
        if (!empty($data)) {
            $condition = "
                AND r.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
                AND e.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND c.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND ss.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND sub.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND tt.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
              ";

            if (isset($data['examId']) || isset($data['examName']) ||  isset($data['studentName'])  || isset($data['resultDate']) || isset($data['studentClass']) || isset($data['studentSection']) || isset($data['studentFromDate']) || isset($data['studentToDate'])) {
                if (!empty($data['studentName'])) {
                    $condition .= " AND s.name LIKE '%{$data['studentName']}%' ";
                }
                if (!empty($data['examName'])) {
                    $condition .= " AND e.exam_name LIKE '%{$data['examName']}%' ";
                }
                if (!empty($data['examId'])) {
                    $condition .= " AND e.id = '{$data['examId']}' ";
                }
                if (!empty($data['studentClass'])) {
                    $condition .= " AND c.id = '{$data['studentClass']}' ";
                }
                if (!empty($data['studentSection'])) {
                    $condition .= " AND ss.id = '{$data['studentSection']}' ";
                }
                if (!empty($data['resultDate'])) {
                    $condition .= " AND r.result_date = '{$data['resultDate']}' ";
                }

                // if(!empty($data['studentFromDate']) )
                // {
                //     $condition .= " AND r.created_at = '{$data['studentFromDate']}' ";
                // }
                // if(!empty($data['studentToDate']))
                // {
                //     $condition .= " AND  r.created_at = '{$data['studentToDate']}' ";
                // }
                if (!empty($data['studentFromDate']) && !empty($data['studentToDate'])) {
                    $condition .= " AND r.created_at >= '{$data['studentFromDate']}' AND  r.created_at <= '{$data['studentToDate']}' ";
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
                r.created_at FROM " . $this->tableName . " r
                LEFT JOIN " . Table::examTable . " e ON e.id = r.exam_id
                LEFT JOIN " . Table::studentTable . " s ON s.id = r.student_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  e.subject_id
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.login_user_id
                WHERE r.status != 4 $condition ORDER BY r.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();

            $countSql = "SELECT count(r.id) as count  FROM " . $this->tableName . " r
                LEFT JOIN " . Table::examTable . " e ON e.id = r.exam_id
                LEFT JOIN " . Table::studentTable . " s ON s.id = r.student_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  e.subject_id
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.login_user_id
                WHERE r.status != 4 $condition ORDER BY r.id DESC";
        } else {
            $d = $this->db->query("SELECT 
                r.id,r.marks,IF(r.resultStatus = '1', 'Pass', 'Fail') as resultStatus,r.result_date,
                e.id as examId, e.exam_name,e.date_of_exam,e.max_marks,e.min_marks,
                s.name,s.id as studentId,
                c.className,ss.sectionName,
                tt.name as teacherName, 
                tt.id as teacherId,
                sub.subjectName,
                r.created_at FROM " . $this->tableName . " r
                LEFT JOIN " . Table::examTable . " e ON e.id = r.exam_id
                LEFT JOIN " . Table::studentTable . " s ON s.id = r.student_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  e.subject_id
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.login_user_id
                WHERE r.status != 4 ORDER BY r.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();

            $countSql = "SELECT count(r.id) as count  FROM " . $this->tableName . " r
                LEFT JOIN " . Table::examTable . " e ON e.id = r.exam_id
                LEFT JOIN " . Table::studentTable . " s ON s.id = r.student_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  e.subject_id
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  e.login_user_id
                WHERE r.status != 4 ORDER BY r.id DESC";
        }


        $tCount = $this->db->query($countSql)->result_array();

        $sendArr = [];
        for ($i = 0; $i < count($d); $i++) {
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
            $subArr[] = $d[$i]['className'] . " - " . $d[$i]['sectionName'];
            $subArr[] = $d[$i]['marks'];
            if ($d[$i]['resultStatus'] == 'Pass') {
                $subArr[] = '<span class="badge badge-success">' . $d[$i]['resultStatus'] . '</span>';
            } else {
                $subArr[] = '<span class="badge badge-danger">' . $d[$i]['resultStatus'] . '</span>';
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
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr,
            "query" => $lastQuery
        ];

        echo json_encode($dataTableArr);
    }
    // showAllSemesterResultsList
    public function showAllSemesterResultsList($tableName, $data = '')
    {
        $this->tableName = $tableName;
        // $dir = base_url().HelperClass::uploadImgDir;
        if (!empty($data)) {
            //  $condition = "
            //     AND r.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
            //     AND e.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            //     AND c.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            //     AND ss.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            //     AND sub.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            //     AND tt.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            //   ";

            $condition = "";
            if (isset($data['studentName'])  || isset($data['studentClass']) || isset($data['studentSection']) || isset($data['semesterExam'])) {
                if (!empty($data['studentName'])) {
                    $condition .= " AND s.id LIKE '%{$data['studentName']}%' ";
                }
                if (!empty($data['studentClass'])) {
                    $condition .= " AND c.id = '{$data['studentClass']}' ";
                }
                if (!empty($data['studentSection'])) {
                    $condition .= " AND ss.id = '{$data['studentSection']}' ";
                }
                if (!empty($data['semesterExam'])) {
                    $condition .= " AND sen.id = '{$data['semesterExam']}' ";
                }
            }

            $d = $this->db->query("SELECT 
                r.id,r.marks,IF(r.result_status = '1', 'Pass', 'Fail') as resultStatus,
                e.id as examId, sen.sem_exam_name,e.exam_date,e.max_marks,e.min_marks,
                s.name,s.id as studentId,
                c.className,ss.sectionName,
                sub.subjectName,
                r.created_at FROM " . $this->tableName . " r
                LEFT JOIN " . Table::secExamTable . " e ON e.id = r.sec_exam_id
                LEFT JOIN " . Table::semExamNameTable . " sen ON sen.id = r.sem_id
                LEFT JOIN " . Table::studentTable . " s ON s.id = r.student_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  e.subject_id
                WHERE r.status != 4 $condition ORDER BY r.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();

            $countSql = "SELECT count(r.id) as count  FROM " . $this->tableName . " r
                LEFT JOIN " . Table::secExamTable . " e ON e.id = r.sec_exam_id
                LEFT JOIN " . Table::semExamNameTable . " sen ON sen.id = r.sem_id
                LEFT JOIN " . Table::studentTable . " s ON s.id = r.student_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  e.subject_id
                WHERE r.status != 4 $condition ORDER BY r.id DESC";
        } else {
            $d = $this->db->query("SELECT 
                r.id,r.marks,IF(r.result_status = '1', 'Pass', 'Fail') as resultStatus,
                e.id as examId, sen.sem_exam_name,e.exam_date,e.max_marks,e.min_marks,
                s.name,s.id as studentId,
                c.className,ss.sectionName,
                sub.subjectName,
                r.created_at FROM " . $this->tableName . " r
                LEFT JOIN " . Table::secExamTable . " e ON e.id = r.sec_exam_id
                LEFT JOIN " . Table::semExamNameTable . " sen ON sen.id = r.sem_id
                LEFT JOIN " . Table::studentTable . " s ON s.id = r.student_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  e.subject_id
                WHERE r.status != 4 ORDER BY r.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();

            $countSql = "SELECT count(r.id) as count  FROM " . $this->tableName . " r
                LEFT JOIN " . Table::secExamTable . " e ON e.id = r.sec_exam_id
                LEFT JOIN " . Table::semExamNameTable . " sen ON sen.id = r.sem_id
                LEFT JOIN " . Table::studentTable . " s ON s.id = r.student_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::subjectTable . " sub ON sub.id =  e.subject_id
                WHERE r.status != 4  ORDER BY r.id DESC";
        }


        $tCount = $this->db->query($countSql)->result_array();

        $sendArr = [];
        for ($i = 0; $i < count($d); $i++) {
            $subArr = [];
            $subArr[] = ($j = $i + 1);
            // $subArr[] = $d[$i]['id'];
            // $subArr[] = $d[$i]['examId'];
            $subArr[] = $d[$i]['sem_exam_name'];
            $subArr[] = $d[$i]['subjectName'];
            $subArr[] = $d[$i]['max_marks'];
            $subArr[] = $d[$i]['min_marks'];
            $subArr[] = $d[$i]['name'];
            $subArr[] = $d[$i]['className'] . " - " . $d[$i]['sectionName'];
            $subArr[] = $d[$i]['marks'];
            if ($d[$i]['resultStatus'] == 'Pass') {
                $subArr[] = '<span class="badge badge-success">' . $d[$i]['resultStatus'] . '</span>';
            } else {
                $subArr[] = '<span class="badge badge-danger">' . $d[$i]['resultStatus'] . '</span>';
            };
            $sendArr[] = $subArr;
        }

        $dataTableArr = [
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr,
            "query" => $lastQuery
        ];

        echo json_encode($dataTableArr);
    }

    public function teacherReviewsList($tableName, $data = '')
    {
        $this->tableName = $tableName;
        // $dir = base_url().HelperClass::uploadImgDir;
        if (!empty($data)) {
            $condition = "
                AND rr.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
                AND e.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND c.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND ss.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
                AND tt.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
              ";
            // $condition = "";
            if (isset($data['examId']) ||  isset($data['teacherName'])  || isset($data['stars']) || isset($data['resultId']) || isset($data['studentFromDate']) || isset($data['studentToDate'])) {
                if (!empty($data['teacherName'])) {
                    $condition .= " AND tt.name LIKE '%{$data['teacherName']}%' ";
                }
                if (!empty($data['examId'])) {
                    $condition .= " AND e.id = '{$data['examId']}' ";
                }
                if (!empty($data['stars'])) {
                    $condition .= " AND rr.stars = '{$data['stars']}' ";
                }
                if (!empty($data['resultId'])) {
                    $condition .= " AND r.id = '{$data['resultId']}' ";
                }

                //  if(!empty($data['studentFromDate']) )
                // {
                //     $condition .= " AND rr.created_at = '{$data['studentFromDate']}'  ";
                // }
                //  if( !empty($data['studentToDate']))
                // {
                //     $condition .= "  AND  rr.created_at = '{$data['studentToDate']}' ";
                // }
                if (!empty($data['studentFromDate']) && !empty($data['studentToDate'])) {
                    $condition .= " AND rr.created_at >= '{$data['studentFromDate']}' AND  rr.created_at <= '{$data['studentToDate']}' ";
                }
            }

            $d = $this->db->query("SELECT  rr.id as ratingId, rr.stars,rr.review, rr.review_title,IF(rr.for_what = '1', 'Result Published', '') as reasonForReview,rr.created_at,
                e.id as examId, 
                r.id as resultId,
                s.name,s.mother_name,s.father_name,
                c.className,ss.sectionName,
                tt.name as teacherName, 
                tt.id as teacherId
                 FROM " . $this->tableName . " rr
                LEFT JOIN " . Table::examTable . " e ON e.id = rr.reason_id
                LEFT JOIN " . Table::resultTable . " r ON r.exam_id = e.id AND r.student_id = rr.login_user_id
                LEFT JOIN " . Table::studentTable . " s ON s.id = rr.login_user_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  rr.user_id
                WHERE rr.status != 4 $condition ORDER BY rr.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();

            $countSql = "SELECT count(rr.id) as count  FROM " . $this->tableName . " rr
                LEFT JOIN " . Table::examTable . " e ON e.id = rr.reason_id
                LEFT JOIN " . Table::resultTable . " r ON r.exam_id = e.id AND r.student_id = rr.login_user_id
                LEFT JOIN " . Table::studentTable . " s ON s.id = rr.login_user_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  rr.user_id
                WHERE rr.status != 4 $condition ORDER BY rr.id DESC";
        } else {
            $d = $this->db->query("SELECT rr.id as ratingId, rr.stars,rr.review, rr.review_title,IF(rr.for_what = '1', 'Result Published', '') as reasonForReview,rr.created_at,
                e.id as examId, 
                r.id as resultId,
                s.name,s.mother_name,s.father_name,
                c.className,ss.sectionName,
                tt.name as teacherName, 
                tt.id as teacherId
                 FROM " . $this->tableName . " rr
                LEFT JOIN " . Table::examTable . " e ON e.id = rr.reason_id
                LEFT JOIN " . Table::resultTable . " r ON r.exam_id = e.id AND r.student_id = rr.login_user_id
                LEFT JOIN " . Table::studentTable . " s ON s.id = rr.login_user_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  rr.user_id
                WHERE rr.status != 4 ORDER BY rr.id DESC LIMIT {$data['start']},{$data['length']}")->result_array();

            $lastQuery = $this->db->last_query();

            $countSql = "SELECT count(rr.id) as count  FROM " . $this->tableName . " rr
                LEFT JOIN " . Table::examTable . " e ON e.id = rr.reason_id
                LEFT JOIN " . Table::resultTable . " r ON r.exam_id = e.id AND r.student_id = rr.login_user_id
                LEFT JOIN " . Table::studentTable . " s ON s.id = rr.login_user_id
                LEFT JOIN " . Table::classTable . " c ON c.id =  e.class_id
                LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  e.section_id
                LEFT JOIN " . Table::teacherTable . " tt ON tt.id =  rr.user_id
                WHERE rr.status != 4 ORDER BY rr.id DESC";
        }


        $tCount = $this->db->query($countSql)->result_array();

        $sendArr = [];
        for ($i = 0; $i < count($d); $i++) {
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
            $subArr[] = $d[$i]['className'] . " - " . $d[$i]['sectionName'];
            $subArr[] = $d[$i]['created_at'];
            // $subArr[] = '
            // <a href="viewTeacher/'.$d[$i]['id'].'" class="btn btn-primary" ><i class="fas fa-eye"></i></a>  
            // <a href="editTeacher/'.$d[$i]['id'].'" class="btn btn-warning" ><i class="fas fa-edit"></i></a>  
            // <a href="deleteTeacher/'.$d[$i]['id'].'" class="btn btn-danger" 
            // onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fas fa-trash"></i></a>';

            $sendArr[] = $subArr;
        }

        $dataTableArr = [
            "draw" => $data['draw'],
            "recordsTotal" => $tCount[0]['count'],
            "recordsFiltered" => $tCount[0]['count'],
            "data" => $sendArr,
            "query" => $lastQuery
        ];

        echo json_encode($dataTableArr);
    }

    public function singleStudent($tableName, $id)
    {
        $dir = base_url() . HelperClass::studentImagePath;
        $this->tableName = $tableName;
        return $d = $this->db->query("SELECT *,CONCAT('$dir',image) as image FROM " . $this->tableName . " WHERE id=$id AND status != 0 LIMIT 1")->result_array();
    }

    public function singleTeacher($tableName, $id)
    {
        $dir = base_url() . HelperClass::teacherImagePath;
        $this->tableName = $tableName;
        return $d = $this->db->query("SELECT *,CONCAT('$dir',image) as image FROM " . $this->tableName . " WHERE id=$id AND status != 0 LIMIT 1")->result_array();
    }

    public function singleDriver($tableName, $id)
    {
        $dir = base_url() . HelperClass::driverImagePath;
        $this->tableName = $tableName;
        return $d = $this->db->query("SELECT *,CONCAT('$dir',image) as image FROM " . $this->tableName . " WHERE id=$id AND status != 0 LIMIT 1")->result_array();
    }

    public function showStudentProfile($tableName, $userId)
    {
        $dir = base_url() . HelperClass::studentImagePath;
        $this->tableName = $tableName;
        return $d = $this->db->query("SELECT s.*, CONCAT('$dir',s.image) as image,if(s.status = '1', 'Active','InActive')as status,c.className,ss.sectionName,st.stateName,ct.cityName FROM " . $this->tableName . " s
        LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
        LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
        LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
        LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
        WHERE s.status != 0 AND s.user_id = '{$userId}' ORDER BY s.id DESC LIMIT 1")->result_array();
    }

    public function showTeacherProfile($tableName, $userId)
    {
        $dir = base_url() . HelperClass::teacherImagePath;
        $this->tableName = $tableName;
        return $d = $this->db->query("SELECT s.*, CONCAT('$dir',s.image) as image,if(s.status = '1', 'Active','InActive')as status,c.className,ss.sectionName,st.stateName,ct.cityName FROM " . $this->tableName . " s
        LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
        LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
        LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
        LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
        WHERE s.status != 0 AND s.user_id = '{$userId}' ORDER BY s.id DESC LIMIT 1")->result_array();
    }

    public function viewSingleStudentAllData($tableName, $id)
    {
        $dir = base_url() . HelperClass::studentImagePath;
        $this->tableName = $tableName;
        return $d = $this->db->query("SELECT s.*, CONCAT('$dir',s.image) as image,if(s.status = '1', 'Active','InActive')as status,c.className,ss.sectionName,st.stateName,ct.cityName FROM " . $this->tableName . " s
        LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
        LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
        LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
        LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
        WHERE s.status != 0 AND s.id = {$id} ORDER BY s.id DESC LIMIT 1")->result_array();
    }

    public function viewSingleTeacherAllData($tableName, $id)
    {
        $dir = base_url() . HelperClass::teacherImagePath;
        $this->tableName = $tableName;
        return $d = $this->db->query("SELECT s.*, CONCAT('$dir',s.image) as image,if(s.status = '1', 'Active','InActive')as status,c.className,ss.sectionName,st.stateName,ct.cityName FROM " . $this->tableName . " s
        LEFT JOIN " . Table::classTable . " c ON c.id =  s.class_id
        LEFT JOIN " . Table::sectionTable . " ss ON ss.id =  s.section_id
        LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
        LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
        WHERE s.status != 0 AND s.id = {$id} ORDER BY s.id DESC LIMIT 1")->result_array();
    }

    public function viewSingleDriverAllData($tableName, $id)
    {
        $dir = base_url() . HelperClass::driverImagePath;
        $this->tableName = $tableName;
        return $d = $this->db->query("SELECT s.*, CONCAT('$dir',s.image) as image,if(s.status = '1', 'Active','InActive')as status,st.stateName,ct.cityName FROM " . $this->tableName . " s
        LEFT JOIN " . Table::stateTable . " st ON st.id =  s.state_id
        LEFT JOIN " . Table::cityTable . " ct ON ct.id =  s.city_id
        WHERE s.status != 0 AND s.id = {$id} ORDER BY s.id DESC LIMIT 1")->result_array();
    }

    public function allClass($tableName, $schoolUniqueCode)
    {
        $this->tableName = $tableName;
        return $d = $this->db->query("SELECT id,className FROM " . $this->tableName . " WHERE status !='4' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();
    }

    public function allSection($tableName, $schoolUniqueCode)
    {
        $this->tableName = $tableName;
        return $d = $this->db->query("SELECT id,sectionName FROM " . $this->tableName  . " WHERE status !='4' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();
    }
    public function allCity($tableName, $schoolUniqueCode)
    {
        $this->tableName = $tableName;
        return $d = $this->db->query("SELECT id,cityName FROM " . $this->tableName . " WHERE status !='4' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();
    }
    public function allState($tableName, $schoolUniqueCode)
    {
        $this->tableName = $tableName;
        return $d = $this->db->query("SELECT id,stateName FROM " . $this->tableName . " WHERE status !='4' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();
    }
    public function allSubjects($tableName, $schoolUniqueCode)
    {
        $this->tableName = $tableName;
        return $d = $this->db->query("SELECT id,subjectName FROM " . $this->tableName . " WHERE status !='4' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();
    }



    public function showCityViaStateId($stateId, $alreadyCityId = '')
    {
        $schoolUniqueCode = $_SESSION['schoolUniqueCode'];

        $d = $this->db->query("SELECT id,cityName FROM " . Table::cityTable . " WHERE stateId = '$stateId' AND status !='4' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();

        $html = '';
        $select = '';
        if (!empty($d)) {
            foreach ($d as $dd) {
                if (!empty($alreadyCityId)) {
                    if ($dd['id'] == $alreadyCityId) {
                        $select = 'selected';
                    } else {
                        $select = '';
                    }
                }
                $html .= "<option " . $select . " value='" . $dd['id'] . "'>" . $dd['cityName'] . "</option>";
            }
            return json_encode($html);
        }
    }


    public function showSectionViaClassId($classId, $alreadySectionId = '')
    {
        $schoolUniqueCode = $_SESSION['schoolUniqueCode'];

        $d = $this->db->query("SELECT s.id,s.sectionName FROM " . Table::classWithSectionTable . " cs INNER JOIN ".Table::sectionTable." s ON cs.section_id = s.id
        WHERE cs.class_id = '$classId' AND cs.status !='4' AND s.status != '4' AND cs.schoolUniqueCode = '$schoolUniqueCode'")->result_array();

        $html = '';
        $select = '';
     
        if (!empty($d)) {
            foreach ($d as $dd) {
                if (!empty($alreadySectionId)) {
                    if ($dd['id'] == $alreadySectionId) {
                        $select = 'selected';
                    } else {
                        $select = '';
                    }
                }
                $html .= "<option " . $select . " value='" . $dd['id'] . "'>" . $dd['sectionName'] . "</option>";
            }
            return json_encode($html);
        }
    }









    public function deleteStudent($tableName = "", $student_id = "", $imageDir = "")
    {
        if (!empty($tableName)) {
            $this->tableName    = $tableName;
            $this->student_id   = $this->sanitizeInput($student_id);

            $delImg = $this->db->query("SELECT image FROM " . $this->tableName . " WHERE id = " . $this->student_id . "  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ")->result_array();

            if (!empty(@$delImg)) {
                $imgN = @$delImg[0]['image'];
                if (!empty($imageDir)) {
                    $imgNewDir = $imageDir;
                } else {
                    $imgNewDir = HelperClass::uploadImgDir;
                }
                @unlink($imgNewDir . $imgN);
            }

            if ($this->db->query("UPDATE " . $this->tableName . " SET status = '4' WHERE id = " . $this->student_id . " AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")) {
                return true;
            } else {
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
        if (empty($this->session->userdata('id')) || empty($this->session->userdata('name')) || empty($this->session->userdata('email')) || empty($this->session->userdata('user_type')) || empty($this->session->userdata('userData'))) {
            $msgArr = [
                'class' => 'danger',
                'msg' => 'Please Login first to access that page.',
            ];
            $this->session->set_userdata($msgArr);
            return false;
        } else {

            return true;
        }
    }

    public function checkPermission()
    {

        if ($_SESSION['user_type'] == 'SuperCEO') {
            return true;
        }




        $userPermissions = $this->db->query("SELECT permissions FROM " . Table::panelMenuPermissionTable . " WHERE user_id = '{$_SESSION['id']}' AND user_type = '{$_SESSION['user_type']}' AND status = '1'")->result_array();

        $permissionArr = json_decode($userPermissions[0]['permissions'], TRUE);

        $permissionString = implode(',', $permissionArr);

        $currentPage = $_SERVER['PATH_INFO'];

        $permissionData = $this->db->query("SELECT * FROM " . Table::adminPanelMenuTable . " WHERE id IN ($permissionString) AND status = '1'")->result_array();

        $p = count($permissionData);
        $permissionDeni = false;
        for ($i = 0; $i < $p; $i++) {
            if ($currentPage == '/' . $permissionData[$i]['link']) {
                $permissionDeni = true;
            }
        }
        return $permissionDeni;
    }





    public function totalEmployeesWorkingDaysAndHolidaysCurrentMonth($monthId = '', $yearId = '', $sessionId = '')
    {

        if ($yearId == '') {
            $currentYear = date('Y');
        } else {
            $currentYear = $yearId;
        }

        if ($monthId == '') {
            $currentMonth = date('m');
        } else {
            $currentMonth = $monthId;
        }

        $first_day = "01"; // first Days hardcoded
        $last_day =  date('t', strtotime($currentYear . '-' . $currentMonth . '-' . $first_day));
        $startDate = date($currentYear . '-' . $currentMonth . '-' . $first_day);
        $endDate = date($currentYear . '-' . $currentMonth . '-' . $last_day);


        $condition = "";
        if ($sessionId != '') {
            $condition .= " AND schoolUniqueCode = '$sessionId' ";
        } else {
            $condition .= " AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ";
        }
        $totalHolidays = $this->db->query("SELECT count(1) as c FROM " . Table::holidayCalendarTable . " WHERE status = '1' AND  event_date >= '$startDate' AND event_date <= '$endDate' $condition")->result_array()[0]['c'];

        $sendArr = [
            'totalHolidaysIncludingSundays' => $totalHolidays,
            'totalWorkingDays' => $last_day - $totalHolidays,
            'query' => $this->db->last_query(),
        ];
        return $sendArr;
    }




    public function checkEmployeeSalaryById($id, $month, $year, $sessionId = "")
    {


        $this->tableName = Table::salaryTable;

        $condition = " AND s.id = '$id' ";

        if ($sessionId != '') {
            $condition .= " AND s.schoolUniqueCode = '$sessionId' ";
        } else {
            $sessionId = '';
            $condition .= " AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ";
        }


        $sendArr = [];
        $sendArr['id'] = $id;
        $totalWorkingDays =  $this->CrudModel->totalEmployeesWorkingDaysAndHolidaysCurrentMonth($month, $year, $sessionId);

        $sendArr['workingDays'] = $totalWorkingDays;






        $d = $this->db->query("SELECT s.*, dep.departmentName, des.designationName FROM " . $this->tableName . " s 
            JOIN " . Table::departmentTable . " dep  ON dep.id = s.departmentId 
            JOIN " . Table::designationTable . " des ON des.id = s.designationId 
            WHERE s.status != 4 $condition ")->result_array();


        $sendArr['employeeDetails'] = $d[0];

        $totalAttendanceArr =  $this->CrudModel->getTotalAttendanceOfEmployeeCurrentMonth($id, $month, $year, $sessionId);


        $totalPresentDays = $totalAttendanceArr['present'];
        $totalAbsentDays = $totalAttendanceArr['absent'];
        $totalHalfDays = $totalAttendanceArr['helfDay'];
        $totalLeavesDays = $totalAttendanceArr['leaves'];


        $sendArr['attendanceData'] = $totalAttendanceArr;

        // check how many leaves allow in one month for employee
        $totalLeavesAllowPerMonth = $d[0]['leavesPerMonth'];

        // per day salary
        $perDaySalary = $d[0]['basicSalaryDay'];
        // per month salary
        $perMonthSalary = $d[0]['basicSalaryMonth'];

        // if absent How much deducat per day
        $absentDeducation = $d[0]['lwp'];

        // half day deducation
        $halfDayDeducation = $d[0]['ded_half_day'];





        // absent / leave Deducations days
        if (($t = ($totalLeavesDays + $totalAbsentDays) - $totalLeavesAllowPerMonth) > 0) {
            $lwpDeducationsDays = $t;
        } else {
            $lwpDeducationsDays = 0;
        }



        // leave deducation amount

        if ($lwpDeducationsDays > 0) {
            $leaveDudutionAmt  =  $lwpDeducationsDays * $absentDeducation;
        } else {
            $leaveDudutionAmt = 0;
        }

        $sendArr['leaves'] =  ['totalLeaves' => $lwpDeducationsDays, 'leaveAmountToDeducat' => $leaveDudutionAmt];

        // half day deducations    

        if ($totalHalfDays > 0) {
            $halfDayDeducationAmt  =  $totalHalfDays * $halfDayDeducation;
        } else {
            $halfDayDeducationAmt = 0;
        }

        $sendArr['halfDays'] =  ['totalHalfDays' => $totalHalfDays, 'halfDaysAmountToDeducat' => $halfDayDeducationAmt];

        // total working days present days
        $t = ($totalLeavesDays + $totalAbsentDays) - $totalLeavesAllowPerMonth;
        if ($t > 0) {
            $totalDaysExpectToPresentForMonthlySalary = $totalWorkingDays['totalWorkingDays'] - $t;
        } else {
            $totalDaysExpectToPresentForMonthlySalary = $totalWorkingDays['totalWorkingDays'];
        }

        $sendArr['totalWorkingDaysAspected'] =  $totalDaysExpectToPresentForMonthlySalary;

        if ($totalPresentDays < $totalDaysExpectToPresentForMonthlySalary) {
            // salary per day
            if ($totalPresentDays > 0) {
                $salary0 = $totalPresentDays * $perDaySalary; // full day salary
            } else {
                $salary0 = 0; // full day salary
            }

            if ($totalHalfDays > 0) {
                $salary1 = $totalHalfDays * ($perDaySalary - $halfDayDeducation); // half day salary
            } else {
                $salary1 = 0;
            }

            $ssalary = $salary0 + $salary1;
        } else if ($totalPresentDays == $totalDaysExpectToPresentForMonthlySalary) {
            if ($totalPresentDays > 0) {
                // salary per month
                $salary0 = $perMonthSalary;
            } else {
                $salary0 = 0;
            }

            $ssalary = $salary0;
        }


        $sendArr['basicPay'] = $ssalary;




        $da0 = ($d[0]['dearnessAll'] > 0) ? $this->CrudModel->calculatePercentageAmount($ssalary, $d[0]['dearnessAll']) : 0;
        $hra0 = ($d[0]['hra'] > 0) ? $this->CrudModel->calculatePercentageAmount($ssalary, $d[0]['hra']) : 0;
        $ca0 = ($d[0]['conAll'] > 0) ? $this->CrudModel->calculatePercentageAmount($ssalary, $d[0]['conAll']) : 0;
        $ma0 = ($d[0]['medicalAll'] > 0) ? $this->CrudModel->calculatePercentageAmount($ssalary, $d[0]['medicalAll']) : 0;
        $sa0 = ($d[0]['specialAll'] > 0) ? $this->CrudModel->calculatePercentageAmount($ssalary, $d[0]['specialAll']) : 0;

        // total allowances
        $totalAll = $da0 + $hra0 + $ca0 +  $ma0 +  $sa0;

        $sendArr['allowances'] = ['da' => $da0, 'hra' => $hra0, 'ca' => $ca0, 'ma' => $ma0, 'sa' => $sa0, 'total' => $totalAll];

        $ptpm0 = ($d[0]['professionalTaxPerMonth'] > 0) ? $this->CrudModel->calculatePercentageAmount($ssalary, $d[0]['professionalTaxPerMonth']) : 0;
        $pfm0 = ($d[0]['pfPerMonth'] > 0) ? $this->CrudModel->calculatePercentageAmount($ssalary, $d[0]['pfPerMonth']) : 0;
        $tds0 = ($d[0]['tdsPerMonth'] > 0) ? $this->CrudModel->calculatePercentageAmount($ssalary, $d[0]['tdsPerMonth']) : 0;

        // total deducations
        $totalDed = $ptpm0 + $pfm0 + $tds0;


        $sendArr['deducations'] = ['ptpm' => $ptpm0, 'pfpm' => $pfm0, 'tds' => $tds0, 'total' => $totalDed];

        // totalSalaryAfterDeduation basicpay + allownaces - deducations
        $totalSalaryAfterDeducation = ($perMonthSalary + $totalAll) - $totalDed;
        $sendArr['actualSalary'] =  $totalSalaryAfterDeducation;

        // totalSalaryToPay
        $totalDeducationMonth = @$leaveDudutionAmt + @$halfDayDeducationAmt + @$totalDed;
        $totalAllowMonth = @$totalAll;

        // total salary now basicpay + allowance - deducation
        $ssalaryAmount = (@$ssalary + @$totalAllowMonth) - @$totalDeducationMonth;

        $sendArr['totalSalaryToPay'] =  $ssalaryAmount;

        return $sendArr;
        exit(0);
    }



    public function numberToWordsCurrency(float $number)
    {

        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
        );
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
    }

    public function dateToWords(float $number)
    {

        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
        );
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) : '';
        return ($Rupees ? $Rupees : '');
    }


    public function getTotalAttendanceOfEmployeeCurrentMonth($id, $monthId = '', $yearId = '', $sessionId = '')
    {

        if ($yearId == '') {
            $currentYear = date('Y');
        } else {
            $currentYear = $yearId;
        }

        if ($monthId == '') {
            $currentMonth = date('m');
        } else {
            $currentMonth = $monthId;
        }
        $first_day = "01"; // first Days hardcoded
        $last_day =  date('t', strtotime($currentYear . '-' . $currentMonth . '-' . $first_day));
        $startDate = date($currentYear . '-' . $currentMonth . '-' . $first_day);
        $endDate = date($currentYear . '-' . $currentMonth . '-' . $last_day);

        $condition = "";
        if ($sessionId != '') {
            $condition .= " AND schoolUniqueCode = '$sessionId' ";
        } else {
            $condition .= " AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ";
        }
        $totalPresents = $this->db->query("SELECT count(1) as c FROM " . Table::staffattendanceTable . " WHERE  status = '1' $condition  AND attendenceStatus = '2' AND employee_id = '$id' AND  att_date >= '$startDate' AND att_date <= '$endDate'")->result_array()[0]['c'];

        $totalAbsents = $this->db->query("SELECT count(1) as c FROM " . Table::staffattendanceTable . " WHERE status = '1' $condition AND  attendenceStatus = '1' AND employee_id = '$id' AND  att_date >= '$startDate' AND att_date <= '$endDate'")->result_array()[0]['c'];

        $totalHalfDays = $this->db->query("SELECT count(1) as c FROM " . Table::staffattendanceTable . " WHERE  status = '1' $condition  AND attendenceStatus = '3' AND employee_id = '$id' AND  att_date >= '$startDate' AND att_date <= '$endDate'")->result_array()[0]['c'];

        $totalHalfLeaves = $this->db->query("SELECT count(1) as c FROM " . Table::staffattendanceTable . " WHERE  status = '1' $condition  AND attendenceStatus = '4' AND employee_id = '$id' AND  att_date >= '$startDate' AND att_date <= '$endDate'")->result_array()[0]['c'];




        $sendArr = [
            'present' => $totalPresents,
            'absent' => $totalAbsents,
            'helfDay' => $totalHalfDays,
            'leaves' => $totalHalfLeaves,
            'query' => $this->db->last_query(),
        ];
        return $sendArr;
    }

    public function extractQrCodeAndReturnUserType($qrCode)
    {
        // https://qverify.in?driid=dvm-dri00001
        $e = explode('?', $qrCode);
        $qr = (string) $e[1];
        $userType = '';
        if (strpos($qr, HelperClass::prefix)) {
            $userType = HelperClass::userTypeR[1];
        } else if (strpos($qr, HelperClass::tecPrefix)) {
            $userType = HelperClass::userTypeR[2];
        } else if (strpos($qr, HelperClass::driverPrefix)) {
            $userType = HelperClass::userTypeR[7];
        }
        return $userType;
    }


    public function sendFirebaseNotification($to = '', $notif = '')
    {

        // FCM API Url
        $url = 'https://fcm.googleapis.com/fcm/send';

        // Put your Server Key here
        $apiKey = "AAAAPb7Be5g:APA91bG5Xy_UX66wmiyKY3d9Dp_LiK5qA9_aHDDMousl9hehj6f53UQeMeAExMAuonyHizy3eI5wz0sndS9xQkIpwhwyro89DKxzvv6TLJeCmNb-t2ANcvXTYb0mS1nFNNZ8jL39sC8D";

        // Compile headers in one variable
        $headers = array(
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

        $dataPayload = [
            'to' => 'My Name',
            'points' => 80,
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
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));


        // Execute call and save result
        $result = curl_exec($ch);

        if (curl_errno($ch)) {
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
        $msg = array(
            'body'  => "$body",
            'title' => "$title",
            'receiver' => 'erw',
            'icon'  => "https://image.flaticon.com/icons/png/512/270/270014.png",/*Default Icon*/
            'sound' => 'mySound'/*Default sound*/
        );

        $fields = array(
            'to'        => $token,
            'notification'  => $msg
        );

        $headers = array(
            'Authorization: key=' . $from,
            'Content-Type: application/json'
        );
        //#Send Reponse To FireBase Server 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        //print_r($result);
        curl_close($ch);
    }

    public function getUniqueIdForSchool()
    {
        return  substr(str_shuffle(123456789), 0, 3) . substr(str_shuffle(time()), 0, 3);
    }







    // query with sql
    public function dbSqlQuery($sql)
    {
        return $this->db->query($sql)->result_array();
    }


    // run query insert or update or delete
    public function runQueryIUD($sql)
    {
        return $this->db->query($sql);
    }



    // replace notifications words

    public function replaceNotificationsWords($str, $data = [])
    {
        $str = str_replace("{parents}", "Parents", $str);
        $str = str_replace("{student}", "Students", $str);

        if (!empty($data) && is_array($data)) {
            if (!empty($data['examId'])) {

                $str = str_replace("{examid}", $data['examId'], $str);
            }

            if (!empty($data['invoiceId'])) {

                $str = str_replace("{invoice}", $data['invoiceId'], $str);
            }

            if (!empty($data['complaintId'])) {

                $str = str_replace("{complaintid}", $data['complaintId'], $str);
            }

            if (!empty($data['identity'])) {

                $str = str_replace("{identity}", $data['identity'], $str);
            }

            if (!empty($data['subjectName'])) {

                $str = str_replace("{subjectName}", $data['subjectName'], $str);
            }

            if (!empty($data['teacherName'])) {

                $str = str_replace("{teacherName}", $data['teacherName'], $str);
            }

            if (!empty($data['examName'])) {

                $str = str_replace("{examName}", $data['examName'], $str);
            }
        }

        return $str;
    }






    // firebase notification system

    public function sendFireBaseNotificationWithDeviceId($registration_ids, $title, $body, $image, $sound)
    {
        $fields = array(
            'registration_ids' => $registration_ids,
            // 'data' => ['title'=>$title,'body'=>$body,'image'=>$image,'sound'=>$sound],
            'data' => ['mesgTitle'    => $title, 'alert'  => $body],
            'notification' => ['title' => $title, 'body' => $body, 'image' => $image, 'sound' => $sound]
        );
        return $this->sendPushNotification($fields);
    }

    /*
    * This function will make the actuall curl request to firebase server
    * and then the message is sent 
    */
    private function sendPushNotification($fields)
    {

        // $FIREBASE_API_KEY = 'AAAAQYspnuk:APA91bEPPfgjLopJqUtJ0DmD0q19vKQ6kkQ_vgGRG9CL7YGrd46g1h_Bz6-JwnU_4ANwGMW7Uzu7MUGFXhjsbDD2dyWuFhY9-q0vvCTBQzWzv42VE-wn0Jvj3glkTUp_P9lrzn-xbB7f';

        $FIREBASE_API_KEY = 'AAAA4IuVEk8:APA91bHjVHQT1X5PyyXZyRh5-iCRk2PU-ecajeFWR2aFcnEZqBZDU_3p3VXS77O1VEdoP5IvIUFGslyLVJ7LfgLEccvkYU-C1Ua7-BqDGPnJbiGVjxqVZVYrXpEiFsRLVePokXdC-0Xx';
        //firebase server url to send the curl request
        $url = 'https://fcm.googleapis.com/fcm/send';

        //building headers for the request
        $headers = array(
            'Authorization: key=' . $FIREBASE_API_KEY,
            'Content-Type: application/json'
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);

        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //adding the fields in json format 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        //finally executing the curl request 
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        //Now close the connection
        curl_close($ch);

        //and return the result 
        return $result;
    }


    // student fees details via student Id
    public function showStudentFeesViaIdClassAndSection($stuId, $classId, $sectionId, $schoolCode, $sessionId)
    {
        $sendArr = [];
        $dir = base_url() . HelperClass::studentImagePath;

        $studentData = @$this->db->query("SELECT s.*,CONCAT('$dir',s.image) as image,cl.className,se.sectionName FROM " . Table::studentTable . " s
        JOIN " . Table::classTable . " cl ON cl.id = s.class_id
        JOIN " . Table::sectionTable . " se ON se.id = s.section_id
        WHERE s.status = '1' AND s.schoolUniqueCode = '$schoolCode' AND s.id = '$stuId'")->result_array()[0];

        $feesDetails = $this->db->query("SELECT DISTINCT(fee_group_id) FROM " . Table::newfeeclasswiseTable . " WHERE class_id = '$classId' AND section_id = '$sectionId' AND schoolUniqueCode = '$schoolCode' AND student_id = '$stuId'  GROUP BY fee_group_id")->result_array();

        $gAmount = 0.00;
        $gFine = 0.00;
        $gdiscount = 0.00;
        $gFine = 0.00;
        $gFineD = 0.00;
        $gPaid = 0.00;
        $gBalance = 0.00;
        $sendArr = [
            'gAmount' => $gAmount,
            'gFine' =>  $gFine,
            'gdiscount' => $gdiscount,
            'gFineD' => $gFineD,
            'gPaid' => $gPaid,
            'gBalance' => $gBalance
        ];

        $sendArr['deposits'] = [];
        $todayDate = date('Y-m-d');

        $j = 1;
        $a = 1;
        $b = 1;


        foreach ($feesDetails as $f) {
            $sqln = "SELECT nfm.id as fmtId, nfm.amount, nfm.fineType,nfm.finePercentage,nfm.fineFixAmount, nfm.dueDate,
            nft.id as nftId, nft.feeTypeName, nft.shortCode, nfg.id as nfgId, nfg.feeGroupName FROM
            " . Table::newfeemasterTable . " nfm 
            JOIN " . Table::newfeestypesTable . " nft ON nft.id = nfm.newFeeType
            JOIN " . Table::newfeesgroupsTable . " nfg ON nfg.id = nfm.newFeeGroupId
            WHERE nfm.newFeeGroupId = '{$f['fee_group_id']}' ";

            $groupWiseFeeDetails = $this->db->query($sqln)->result_array();
            $fGN = @$groupWiseFeeDetails[0]['feeGroupName'];


            foreach ($groupWiseFeeDetails as $gwf) {
                // search student all depoists
                $fineAmount = 0.00;
                if ($todayDate > $gwf['dueDate']) {
                    if ($gwf['fineType'] == '1') {
                        $fineAmount = 0.00;
                    } else if ($gwf['fineType'] == '2') {
                        // percenrtage
                        $fineAmount = ceil($gwf['amount'] * @$gwf['finePercentage'] / 100);
                    } else if ($gwf['fineType'] == '3') {
                        // fixed amount
                        $fineAmount = @$gwf['fineFixAmount'];
                    }
                } else {
                    $fineAmount = 0.00;
                }

                if ($fineAmount == 0) {
                    $fShow = false;
                } else {
                    $fShow = true;
                }



                $feesDeposits = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeessubmitmasterTable . " WHERE stuId = '$stuId' AND classId = '$classId' AND sectionId = '$sectionId' AND fmtId = '{$gwf['fmtId']}' AND nftId = '{$gwf['nftId']}' AND nfgId = '{$gwf['nfgId']}' AND status = '1' AND session_table_id = '$sessionId'");


                $depositAmt = 0.00;
                $fineAmt = 0.00;
                $discountAmt = 0.00;
                if (!empty($feesDeposits)) {


                    foreach ($feesDeposits as $fd) {
                        $depositAmt = $depositAmt + $fd['depositAmount'];
                        $fineAmt = $fineAmt + $fd['fine'];
                        $discountAmt = $discountAmt + $fd['discount'];
                        $b++;
                    }
                }

                $amountNow = $gwf['amount'] - $depositAmt;

                $bstatusBalance = ($gwf['amount'] - $depositAmt) - $discountAmt;

                $gAmount = $gAmount + $gwf['amount'];
                $gFine = $gFine + $fineAmount;

                $gdiscount = $gdiscount + $discountAmt;
                $gFineD = $gFineD + $fineAmt;
                $gPaid = $gPaid + $depositAmt;
                if ($amountNow > 0) {
                    $bblance = $amountNow - $discountAmt;
                } else {
                    $bblance = ($gwf['amount'] - $depositAmt) - $discountAmt;
                }


                $a = 1;
                $depositAmt = 0.00;
                $fineAmt = 0.00;
                $discountAmt = 0.00;
                $subArr = [];
                if (!empty($feesDeposits)) {

                    foreach ($feesDeposits as $fd) {



                        $depositAmt = @$depositAmt + $fd['depositAmount'];
                        $fineAmt = @$fineAmt + $fd['fine'];
                        $discountAmt = @$discountAmt + $fd['discount'];

                        $paymentMode = ($fd['paymentMode'] == '1') ? 'Offline' : 'Online';

                        $depositDate =  date('d-m-y', strtotime($fd['depositDate']));
                        $invoiceId = $fd['invoiceId'];

                        // $discount = $fd['discount'];
                        // $fine = $fd['fine'];
                        // $depositamount = $fd['depositAmount'];

                        $subArr['depositAmount'] = $depositAmt;
                        $subArr['fine'] = $fineAmt;
                        $subArr['discount'] = $discountAmt;
                        $subArr['paymentMode'] =  $paymentMode;
                        $subArr['depositDate'] = $depositDate;
                        $subArr['invoiceId'] = $invoiceId;
                        array_push($sendArr['deposits'], $subArr);
                    }
                }

                $j++;
            }
        }
        $sendArr['gAmount'] = $gAmount;
        $sendArr['gFine'] =  $gFine;
        $sendArr['gdiscount'] = $gdiscount;
        $sendArr['gFineD'] = $gFineD;
        $sendArr['gPaid'] = $gPaid;
        $sendArr['gBalance'] = $gBalance;
        $sendArr['totalPaidIncludingDiscounts'] = $sendArr['gPaid'] + $sendArr['gdiscount'];
        $sendArr['totalDueNow'] = $sendArr['gAmount'] - $sendArr['totalPaidIncludingDiscounts'];



        return $sendArr;
    }

    // student fees details month wise via student Id
    public function showStudentMonthWiseFeesViaIdClassAndSection($stuId, $classId, $sectionId, $schoolCode, $sessionId)
    {
        $sendArr = [];
        $feesDuesMonths = [];
        $dir = base_url() . HelperClass::studentImagePath;

        $studentData = @$this->db->query($s1 = "SELECT s.*,CONCAT('$dir',s.image) as image,cl.className,se.sectionName FROM " . Table::studentTable . " s
         JOIN " . Table::classTable . " cl ON cl.id = s.class_id
         JOIN " . Table::sectionTable . " se ON se.id = s.section_id
         WHERE s.status = '1' AND s.schoolUniqueCode = '$schoolCode' AND s.id = '$stuId'")->result_array()[0];


        $feesDetails = $this->db->query($s2 = "SELECT DISTINCT(fee_group_id) FROM " . Table::newfeeclasswiseTable . " WHERE class_id = '$classId' AND section_id = '$sectionId' AND schoolUniqueCode = '$schoolCode' AND student_id = '$stuId'  GROUP BY fee_group_id")->result_array();

       

        $gAmount = 0.00;
        $gFine = 0.00;
        $gdiscount = 0.00;
        $gFine = 0.00;
        $gFineD = 0.00;
        $gPaid = 0.00;
        $gBalance = 0.00;
        $sendArr = [
            'gAmount' => $gAmount,
            'gFine' =>  $gFine,
            'gdiscount' => $gdiscount,
            'gFineD' => $gFineD,
            'gPaid' => $gPaid,
            'gBalance' => $gBalance
        ];

        $sendArr['deposits'] = [];
        $todayDate = date('Y-m-d');

        $j = 1;
        $a = 1;
        $b = 1;


        $feesString = "'January Fees','February Fees','March Fees','April Fees','May Fees','June Fees','July Fees','August Fees','September Fees','October Fees','November Fees','December Fees'";

        foreach ($feesDetails as $f) {
            $sqln = "SELECT nfm.id as fmtId, nfm.amount, nfm.fineType,nfm.finePercentage,nfm.fineFixAmount, nfm.dueDate,
             nft.id as nftId, nft.feeTypeName, nft.shortCode, nfg.id as nfgId, nfg.feeGroupName FROM
             " . Table::newfeemasterTable . " nfm 
             JOIN " . Table::newfeestypesTable . " nft ON nft.id = nfm.newFeeType
             JOIN " . Table::newfeesgroupsTable . " nfg ON nfg.id = nfm.newFeeGroupId
             WHERE nfm.newFeeGroupId = '{$f['fee_group_id']}' AND nft.feeTypeName IN ($feesString)";

            

            $groupWiseFeeDetails = $this->db->query($sqln)->result_array();

            $fGN = @$groupWiseFeeDetails[0]['feeGroupName'];


            foreach ($groupWiseFeeDetails as $gwf) {
                // search student all depoists
                $fineAmount = 0.00;
                if ($todayDate > $gwf['dueDate']) {
                    if ($gwf['fineType'] == '1') {
                        $fineAmount = 0.00;
                    } else if ($gwf['fineType'] == '2') {
                        // percenrtage
                        $fineAmount = ceil($gwf['amount'] * @$gwf['finePercentage'] / 100);
                    } else if ($gwf['fineType'] == '3') {
                        // fixed amount
                        $fineAmount = @$gwf['fineFixAmount'];
                    }
                } else {
                    $fineAmount = 0.00;
                }

                if ($fineAmount == 0) {
                    $fShow = false;
                } else {
                    $fShow = true;
                }



                $feesDeposits = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeessubmitmasterTable . " WHERE stuId = '$stuId' AND classId = '$classId' AND sectionId = '$sectionId' AND fmtId = '{$gwf['fmtId']}' AND nftId = '{$gwf['nftId']}' AND nfgId = '{$gwf['nfgId']}' AND status = '1' AND session_table_id = '$sessionId'");


                $depositAmt = 0.00;
                $fineAmt = 0.00;
                $discountAmt = 0.00;

                if (!empty($feesDeposits)) {


                    foreach ($feesDeposits as $fd) {
                        $depositAmt = $depositAmt + $fd['depositAmount'];
                        $fineAmt = $fineAmt + $fd['fine'];
                        $discountAmt = $discountAmt + $fd['discount'];
                        $b++;
                    }
                } else {
                    array_push($feesDuesMonths, $gwf['feeTypeName']);
                }

                $amountNow = $gwf['amount'] - $depositAmt;

                $bstatusBalance = ($gwf['amount'] - $depositAmt) - $discountAmt;

                $gAmount = $gAmount + $gwf['amount'];
                $gFine = $gFine + $fineAmount;

                $gdiscount = $gdiscount + $discountAmt;
                $gFineD = $gFineD + $fineAmt;
                $gPaid = $gPaid + $depositAmt;
                if ($amountNow > 0) {
                    $bblance = $amountNow - $discountAmt;
                } else {
                    $bblance = ($gwf['amount'] - $depositAmt) - $discountAmt;
                }


                $a = 1;
                $depositAmt = 0.00;
                $fineAmt = 0.00;
                $discountAmt = 0.00;
                $subArr = [];
                if (!empty($feesDeposits)) {

                    foreach ($feesDeposits as $fd) {



                        $depositAmt = @$depositAmt + $fd['depositAmount'];
                        $fineAmt = @$fineAmt + $fd['fine'];
                        $discountAmt = @$discountAmt + $fd['discount'];

                        $paymentMode = ($fd['paymentMode'] == '1') ? 'Offline' : 'Online';

                        $depositDate =  date('d-m-y', strtotime($fd['depositDate']));
                        $invoiceId = $fd['invoiceId'];

                        // $discount = $fd['discount'];
                        // $fine = $fd['fine'];
                        // $depositamount = $fd['depositAmount'];

                        $subArr['depositAmount'] = $depositAmt;
                        $subArr['fine'] = $fineAmt;
                        $subArr['discount'] = $discountAmt;
                        $subArr['paymentMode'] =  $paymentMode;
                        $subArr['depositDate'] = $depositDate;
                        $subArr['invoiceId'] = $invoiceId;
                        array_push($sendArr['deposits'], $subArr);
                    }
                }

                $j++;
            }
        }
        $sendArr['gAmount'] = $gAmount;
        $sendArr['gFine'] =  $gFine;
        $sendArr['gdiscount'] = $gdiscount;
        $sendArr['gFineD'] = $gFineD;
        $sendArr['gPaid'] = $gPaid;
        $sendArr['gBalance'] = $gBalance;
        $sendArr['totalPaidIncludingDiscounts'] = $sendArr['gPaid'] + $sendArr['gdiscount'];
        $sendArr['totalDueNow'] = $sendArr['gAmount'] - $sendArr['totalPaidIncludingDiscounts'];
        $sendArr['feesDuesMonths'] = $feesDuesMonths;


        

        return $sendArr;
    }

    // show student old session fees details 

    public function showStudentOldSessionFeesDetails($stuId)
    {

        return $oldFeesDetails =  $this->db->query("SELECT sh.fees_due FROM " . Table::studentHistoryTable . " sh 
        JOIN " . Table::schoolSessionTable . " ss ON ss.id = sh.old_session_id OR ss.id = sh.session_table_id
        WHERE sh.student_id = '$stuId' ORDER BY sh.id DESC LIMIT 1")->result_array();
    }




    // save attendence
    public function submitAttendence($stu_id)
    {


        $this->load->model('APIModel');
        $currentDate = date_create()->format('Y-m-d');

        $d = $this->db->query("SELECT stu_id FROM " . Table::attendenceTable . " WHERE att_date = '$currentDate' AND stu_id = '$stu_id' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' LIMIT 1")->result_array();

        if (!empty($d)) {
            return;
        }

        $de = $this->db->query("SELECT c.className,sec.sectionName FROM " . Table::studentTable . " s 
     JOIN " . Table::classTable . " c ON c.id = s.class_id
     JOIN " . Table::sectionTable . " sec ON sec.id = s.section_id
      WHERE s.id = '$stu_id' AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' LIMIT 1")->result_array();

        $insertArr = [
            "schoolUniqueCode" => $_SESSION['schoolUniqueCode'],
            "stu_id" => $stu_id,
            "stu_class" => $de[0]['className'],
            "stu_section" => $de[0]['sectionName'],
            "login_user_id" => '1',
            "login_user_type" => 'Staff',
            "attendenceStatus" => '1',
            "dateTime" => date_create()->format('Y-m-d h:i:s'),
            "att_date" => date_create()->format('Y-m-d'),
            "session_table_id" => $_SESSION['currentSession']
        ];
        $insertId = $this->CrudModel->insert(Table::attendenceTable, $insertArr);
        if (!empty($insertId)) {

            // check digiCoin is set for this attendence time for students
            $digiCoinF =  $this->APIModel->checkIsDigiCoinIsSet(HelperClass::actionType['Attendence'], HelperClass::userType['Student'], $_SESSION['schoolUniqueCode']);

            if ($digiCoinF) {
                // insert the digicoin
                $insertDigiCoin = $this->APIModel->insertDigiCoin($stu_id, HelperClass::userTypeR['1'], HelperClass::actionType['Attendence'], $digiCoinF, $_SESSION['schoolUniqueCode'], $insertId);
                if ($insertDigiCoin) {
                    return true;
                } else {
                    return;
                }
            }

            return true;
        }
    }
}
