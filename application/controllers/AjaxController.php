<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AjaxController extends CI_Controller {

	public $viewDir = 'adminPanel/';
	public $adminPanelURL = "assets/adminPanel/";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('StudentModel');
		$this->load->model('TeacherModel');
		$this->load->model('DriverModel');
		$this->load->model('CrudModel');
		$this->load->model('QRModel');
		$this->load->model('ExamModel');
		$this->load->model('AcademicModel');
	}

	public function listStudentsAjax()
	{
		if(isset($_POST))
		{
			return $this->StudentModel->listStudents($_POST);
		}
	}
	public function listStudentsPermote()
	{
		if(isset($_POST))
		{
			return $this->StudentModel->listStudentsPermote($_POST);
		}
	}
	public function listTeachersAjax()
	{
		if(isset($_POST))
		{
			return $this->TeacherModel->listTeacher($_POST);
		}
	}
	public function listDriversAjax()
	{
		if(isset($_POST))
		{
			return $this->DriverModel->listDriver($_POST);
		}
	}
	public function allExamList()
	{
		if(isset($_POST))
		{
			return $this->ExamModel->allExamList($_POST);
		}
	}

	public function allAttendanceList()
	{
		if(isset($_POST))
		{
			return $this->AcademicModel->allAttendanceList($_POST);
		}
	}

	public function allTeachersAttendanceList()
	{
		if(isset($_POST))
		{
			return $this->AcademicModel->allTeachersAttendanceList($_POST);
		}
	}

	public function allComplaintList()
	{
		if(isset($_POST))
		{
			return $this->AcademicModel->allComplaintList($_POST);
		}
	}

	public function allResultList()
	{
		if(isset($_POST))
		{
			return $this->ExamModel->allResultList($_POST);
		}
	}

	public function showAllSemesterResultsList()
	{
		if(isset($_POST))
		{
			return $this->ExamModel->showAllSemesterResultsList($_POST);
		}
	}
	
	public function teacherReviewsList()
	{
		if(isset($_POST))
		{
			return $this->TeacherModel->teacherReviewsList($_POST);
		}
	}


	public function showStudentViaClassAndSectionId()
	{
		if(isset($_POST))
		{
			//HelperClass::prePrintR($_POST);
			echo $this->StudentModel->showStudentViaClassAndSectionId($_POST);
		}
	}


	public function showDriverListViaVechicleType()
	{
		if(isset($_POST))
		{
			//HelperClass::prePrintR($_POST);
			echo $this->DriverModel->showDriverListViaVechicleType($_POST);
		}
	}

	public function totalFeesDue()
	{
		if(isset($_POST))
		{
			//HelperClass::prePrintR($_POST);
			echo $this->StudentModel->totalFeesDue($_POST);
		}
	}


	public function listDigiCoinAjax()
	{
		if(isset($_POST))
		{
			return $this->CrudModel->listDigiCoin(Table::getDigiCoinTable,$_POST['user_type'], $_POST);
		}
	}

	public function dateSheetList()
	{
		if(isset($_POST))
		{
			return $this->CrudModel->dateSheetList(Table::secExamTable, $_POST);
		}
	}






	
	public function addHolidayEvent()
	{
		if(isset($_POST))
		{
			$add = $this->db->query("INSERT INTO ".Table::holidayCalendarTable." (schoolUniqueCode,title,event_date,session_table_id) VALUES ('{$_SESSION['schoolUniqueCode']}','{$_POST['title']}','{$_POST['start']}','{$_SESSION['currentSession']}')");
		}
	}
	public function editHolidayEvent()
	{
		if(isset($_POST))
		{
			$update = $this->db->query("UPDATE ".Table::holidayCalendarTable." SET event_date = '{$_POST['start']}' WHERE id = '{$_POST['event_id']}'");
		}
	}
	public function getHolidayEvent()
	{
		
			$result = $this->db->query("SELECT * FROM ".Table::holidayCalendarTable." WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'  ORDER BY id DESC")->result_array();

			$data = array();
			if(!empty($result))
			{
				foreach ($result as $row) {
					$data[] = array(
						'id' => $row["id"],
						'title' => $row["title"],
						'start' => $row["event_date"]
					);
				}
				// print_r($data);
				echo json_encode($data);
			}
		
	}

	public function showCityViaStateId()
	{
		if(isset($_POST['stateId']))
		{
			echo $this->CrudModel->showCityViaStateId($_POST['stateId']);
		}
	}

	public function showAllSemExamsWithStudents()
	{
		if(isset($_POST))
		{
			$examDetails = $this->db->query("SELECT setT.*, sub.subjectName FROM ".Table::secExamTable." setT 
			JOIN ".Table::semExamNameTable." se ON se.id = setT.sem_exam_id
			JOIN ".Table::subjectTable." sub ON sub.id = setT.subject_id
			WHERE setT.class_id = '{$_POST['classId']}'
			AND setT.section_id = '{$_POST['sectionId']}' 
			AND se.id = '{$_POST['semExamId']}'")->result_array();

			$studentsViaClass = $this->db->query($sql = "SELECT * FROM ".Table::studentTable." WHERE class_id = '{$_POST['classId']}' AND section_id = '{$_POST['sectionId']}' AND status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

			$sendArr = [
				'examDetails' => $examDetails,
				'students' => $studentsViaClass
			];
			echo json_encode($sendArr);
		}
	}

	public function showDesignationsViaDepartmentId()
	{
		if(isset($_POST))
		{
			$d = $this->db->query($sql = "SELECT * FROM ".Table::designationTable." WHERE departmentId = '{$_POST['departmentId']}' AND status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
       		$html = '';

			if(!empty($d))
			{
			foreach($d as $dd)
			{
			$html .= "<option value='{$dd['id']}'>{$dd['designationName']}</option>";
			}
			echo json_encode($html);
			exit(0);
			}
			echo json_encode($sql);
			exit(0);
		}
	}
	public function showEmployeesViaDepartmentId()
	{
		if(isset($_POST))
		{
			$d = $this->db->query($sql = "SELECT s.empId,s.employeeName,dep.departmentName,des.designationName, '' as msg FROM ".Table::salaryTable." s
			INNER JOIN ".Table::departmentTable." dep ON dep.id = s.departmentId
    		INNER JOIN ".Table::designationTable." des ON des.id = s.designationId 
			WHERE s.departmentId = '{$_POST['departmentId']}' AND s.status = '1' AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
       		$html = '';

			if(!empty($d))
			{
			
			echo json_encode($d);
			exit(0);
			}
			echo json_encode(array(0 => array('msg' => 'No Employess Found in this department.')));
			exit(0);
		}
	}

	public function showSalaryDetails()
	{
		if(isset($_POST))
		{
			$d = $this->db->query("SELECT s.*, dep.departmentName,des.designationName FROM " . Table::salaryTable . " s 
			INNER JOIN ".Table::departmentTable." dep ON dep.id = s.departmentId
			INNER JOIN ".Table::designationTable." des ON des.id = s.designationId
			 WHERE s.status != '4'  AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND s.id='{$_POST['salaryId']}'")->result_array()[0];
       		$html = '';

			if(!empty($d))
			{
				echo json_encode($d);
				exit(0);
			}
		}
	}


	public function listQR()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'QR Code List',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view('adminPanel/pages/header', ['data' => $dataArr]);
		$this->load->view('adminPanel/pages/qrcode/list');
	}

	public function listQRCodeAjax()
	{

		if(isset($_POST))
		{
			return $this->QRModel->listQR($_POST);
		}
	}


	public function showDownloadQR()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Download QR List',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view('adminPanel/pages/header', ['data' => $dataArr]);
		$this->load->view('adminPanel/pages/qrcode/download-qr');
	}
	public function downloadQR($classId,$sectionId)
	{

		$this->loginCheck();
		$d = $this->db->query("SELECT qr.qrcodeUrl,qr.uniqueValue as qrName, CONCAT(cl.className, ' - ', se.sectionName) as classNames, st.roll_no
                    FROM ".Table::qrcodeTable." qr 
                    JOIN ".Table::studentTable." st ON st.user_id = qr.uniqueValue
                    JOIN ".Table::classTable." cl ON cl.id = st.class_id
                    JOIN ".Table::sectionTable." se ON se.id = st.section_id
                    WHERE qr.status != 0 AND cl.id = '$classId' AND se.id = '$sectionId' ORDER BY qr.id DESC")->result_array();

					HelperClass::prePrintR($d);




		// $dataArr = [
		// 	'pageTitle' => 'Download QR List',
		// 	'adminPanelUrl' => $this->adminPanelURL,
		// ];
		// $this->load->view('adminPanel/pages/header', ['data' => $dataArr]);
		// $this->load->view('adminPanel/pages/qrcode/download-qr');
	}

	public function getLatLng()
	{
		header('Access-Control-Allow-Origin: *');
		$latLng = $this->db->query("SELECT * FROM ".Table::driverTable." WHERE status != '4' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND lat IS NOT NULL AND lng IS NOT NULL")->result_array();
  
		if(!empty($latLng))
		{
			$totalC = count($latLng);
			$latLngArr = [];
			for($i=0; $i < $totalC; $i++)
			{
			$subArr = [
				'lat' => (float) $latLng[$i]['lat'],
				'lng' => (float) $latLng[$i]['lng'],
				'name' =>  $latLng[$i]['name'],
				'mobile' =>  $latLng[$i]['mobile'],
				'vechicle_type' =>  HelperClass::vehicleType[$latLng[$i]['vechicle_type']],
				'vechicle_no' =>  $latLng[$i]['vechicle_no'],
				'total_seats' =>  $latLng[$i]['total_seats'],
			];
			array_push($latLngArr,$subArr);
			}
			echo json_encode($latLngArr);
			die();
		}
	}


	public function loginCheck()
	{
		if(!$this->CrudModel->checkIsLogin())
		{
			header('Location: '.base_url());
		}
	}

	public function checkPermission()
	{
		if(!$this->CrudModel->checkPermission())
		{
			header('Location: '.base_url());
		}
	}
}