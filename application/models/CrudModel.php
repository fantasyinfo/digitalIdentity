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

  /**
     * insert function
     *
     * @param string $tableName enter table name
     * @param array $params paramertsers in array with key value pairs
     * @return void return the true or false
     */
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
                return true;
            }else 
            {
                return false;
            }
        }
    }


    public function getUserId()
    {
        $dd = $this->db->query("SELECT user_id FROM " .Table::studentTable ." WHERE user_id IS NOT NULL ORDER BY id DESC LIMIT 1")->result_array();
        $id = explode(HelperClass::prefix,$dd[0]['user_id']);
        return HelperClass::prefix . ($id[1] + 1);
    }


    public function uploadImg(array $file,$id = '')
    {
       // print_r($file);
        // Set preference 
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



    /**
     * udpate student function
     *
     * @param string $tableName
     * @param array $params
     * @param string $student_id
     * @return void
     */
    public function update($tableName = "", $params = array(), $student_id = "")
    {
        if (!empty($tableName) && !empty($params) && !empty($student_id)) {


            $this->tableName    = $tableName;
            $this->student_id   = $student_id;

            $keys               = array_keys($params);
            $values             = array_values($params);


            $combine = "";
            for ($i = 0; $i < count($params); $i++) {
                $combine .= "$keys[$i] = " . "'$values[$i]'" . " , ";
            }
            $combine = rtrim($combine, ' , ');


            $sql = 'UPDATE ' . $this->tableName . ' SET ' . $combine . ' WHERE _id = ' . $this->student_id . ' ';

            if ($this->conn->exec($sql)) {
                $data   = array();
                $sql    = "SELECT * FROM $this->tableName WHERE _id = $this->student_id";
                $stmt   = $this->conn->query($sql);
                if ($stmt->execute()) {
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $row                    = $stmt->fetch();
                    $newArr['_id']          = $row['_id'];
                    $newArr['_name']        = $row['_name'];
                    $newArr['_email_id']    = $row['_email_id'];
                    $newArr['_created_at']  = date('d-m-Y', strtotime($row['_created_at']));
                    array_push($data, $newArr);
                }
                array_push($this->messageArr, $this->pushArr(200, 'Student Updated Successfully', $data));
            } else {
                array_push($this->messageArr, $this->pushArr(404, 'Student Not Updated! Error'));
            }
        }
    }

    /**
     * showAllStudents function
     *
     * @param string $tableName = table name to fetch all data
     * @return void
     */
    public function showAllStudents($tableName = "")
    {
        if (!empty($tableName)) {

            $this->tableName = $tableName;

            $sql             = 'SELECT * FROM ' . $this->tableName . '';
            $stmt            = $this->conn->query($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $totalResults    =  $stmt->rowCount();
            $newArr          = array();
            if ($totalResults > 0) {
                while ($row     = $stmt->fetchAll()) {
                    $newArr[]   = $row;
                }
                array_push($this->messageArr, $this->pushArr(200, 'Data Found Successfully', $newArr));
            } else {
                array_push($this->messageArr, $this->pushArr(404, 'Data Not Found! Error'));
            }
        }
    }


    /**
     * showing single data function
     *
     * @param string table name
     * @param string student id
     * @return void
     */
    public function showSingleStudent($tableName = "", $student_id = "")
    {
        if (!empty($tableName)) {

            $this->tableName    = $tableName;
            $this->student_id   = $this->sanitizeInput($student_id);
            $sql    = 'SELECT * FROM ' . $this->tableName . ' WHERE _id = ' . $this->student_id . '';
            $stmt   = $this->conn->query($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $totalResults =  $stmt->rowCount();
            $newArr = array();
            if ($totalResults > 0) {
                while ($row     = $stmt->fetch()) {
                    $newArr[]   = $row;
                }
                array_push($this->messageArr, $this->pushArr(200, 'Data Found Successfully', $newArr));
            } else {
                array_push($this->messageArr, $this->pushArr(404, 'Data Not Found! Error'));
            }
        }
    }

    /**
     * deleting the studend data function
     *
     * @param string table name
     * @param string student id
     * @return void
     */
    public function deleteStudent($tableName = "", $student_id = "")
    {
        if (!empty($tableName)) {
            $this->tableName    = $tableName;
            $this->student_id   = $this->sanitizeInput($student_id);
            $sql    = 'DELETE FROM ' . $this->tableName . ' WHERE _id = ' . $this->student_id . '';
            $stmt   = $this->conn->query($sql);
            if ($stmt->execute()) {
                array_push($this->messageArr, $this->pushArr(200, 'Student Deleted Successfully'));
            } else {
                array_push($this->messageArr, $this->pushArr(404, 'Student Not Deleted!! Error'));
            }
        }
    }


    public function paginationResult($tableName = "", $limit = null)
    {
        $page = 1;
        $offset = 0;
        $this->tableName = $tableName;

        $totalSTMT = $this->conn->prepare("SELECT * FROM $this->tableName");
        $totalSTMT->execute();
        $totalRecord = $totalSTMT->rowCount();
        $totalPages = ceil($totalRecord / $limit);


        if (isset($_GET['page'])) {
            if ($_GET['page'] <= 1) {
                $_GET['page'] = 1;
            }

            if ($_GET['page'] > $totalPages) {
                $_GET['page'] = $totalPages;
            }
            $page = $this->sanitizeInput($_GET['page']);
        }

        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM $this->tableName ";

        if ($limit != null) {
            $sql .= " LIMIT $offset, $limit";
        }


        $stmt =  $this->conn->prepare($sql);
        if ($stmt->execute()) {
            $data =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            array_push($this->messageArr, $this->pushArr(200, 'Data Found', $data));
        } else {
            array_push($this->messageArr, $this->pushArr(404, 'Data Not Found!! Error'));
        }
    }



    /**
     * showMessage function return the json object with message, 
     *
     * @return void
     */
    public function showMessage()
    {
        echo json_encode($this->messageArr);
    }

    /**
     * sanitizing the cooming data from user function
     *
     * @param string / integer $input
     * @return void
     */
    public function sanitizeInput($input)
    {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    /**
     * pushing the error or success msg with data and status code function
     *
     * @param string $code
     * @param string $msg
     * @param array $data (optional)
     * @return void
     */
    public function pushArr($code = "", $msg = "", $data = array())
    {

        $pushArr =  array('status' => $code, 'message' => $msg);

        if (!empty($data)) {
            $pushArr['data'] = $data;
        }
        return $pushArr;
    }
}