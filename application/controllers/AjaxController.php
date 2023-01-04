<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AjaxController extends CI_Controller
{

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
		if (isset($_POST)) {
			return $this->StudentModel->listStudents($_POST);
		}
	}
	public function listStudentsPermote()
	{
		if (isset($_POST)) {
			return $this->StudentModel->listStudentsPermote($_POST);
		}
	}
	public function listTeachersAjax()
	{
		if (isset($_POST)) {
			return $this->TeacherModel->listTeacher($_POST);
		}
	}
	public function listDriversAjax()
	{
		if (isset($_POST)) {
			return $this->DriverModel->listDriver($_POST);
		}
	}
	public function allExamList()
	{
		if (isset($_POST)) {
			return $this->ExamModel->allExamList($_POST);
		}
	}

	public function allAttendanceList()
	{
		if (isset($_POST)) {
			return $this->AcademicModel->allAttendanceList($_POST);
		}
	}

	public function allTeachersAttendanceList()
	{
		if (isset($_POST)) {
			return $this->AcademicModel->allTeachersAttendanceList($_POST);
		}
	}

	public function allComplaintList()
	{
		if (isset($_POST)) {
			return $this->AcademicModel->allComplaintList($_POST);
		}
	}

	public function allResultList()
	{
		if (isset($_POST)) {
			return $this->ExamModel->allResultList($_POST);
		}
	}

	public function showAllSemesterResultsList()
	{
		if (isset($_POST)) {
			return $this->ExamModel->showAllSemesterResultsList($_POST);
		}
	}

	public function teacherReviewsList()
	{
		if (isset($_POST)) {
			return $this->TeacherModel->teacherReviewsList($_POST);
		}
	}


	public function showStudentViaClassAndSectionId()
	{
		if (isset($_POST)) {
			//HelperClass::prePrintR($_POST);
			echo $this->StudentModel->showStudentViaClassAndSectionId($_POST);
		}
	}

	public function showStudentViaClassAndSectionIdSR()
	{
		if (isset($_POST)) {
			//HelperClass::prePrintR($_POST);
			echo $this->StudentModel->showStudentViaClassAndSectionIdSR($_POST);
		}
	}




	public function showStudentViaClassAndSectionIdForCharacterCertificate()
	{
		if (isset($_POST)) {
			//HelperClass::prePrintR($_POST);
			echo $this->StudentModel->showStudentViaClassAndSectionIdForCharacterCertificate($_POST);
		}
	}

	public function showEmployeesViaDepartmentIdAndDesignationId()
	{
		if (isset($_POST)) {
			//HelperClass::prePrintR($_POST);
			echo $this->CrudModel->showEmployeesViaDepartmentIdAndDesignationId($_POST);
		}
	}


	public function showDriverListViaVechicleType()
	{
		if (isset($_POST)) {
			//HelperClass::prePrintR($_POST);
			echo $this->DriverModel->showDriverListViaVechicleType($_POST);
		}
	}

	public function totalFeesDue()
	{
		if (isset($_POST)) {
			//HelperClass::prePrintR($_POST);
			echo $this->StudentModel->totalFeesDue($_POST);
		}
	}


	public function listDigiCoinAjax()
	{
		if (isset($_POST)) {
			return $this->CrudModel->listDigiCoin(Table::getDigiCoinTable, $_POST['user_type'], $_POST);
		}
	}

	public function dateSheetList()
	{
		if (isset($_POST)) {
			return $this->CrudModel->dateSheetList(Table::secExamTable, $_POST);
		}
	}
	public function questionLists()
	{
		if (isset($_POST)) {
			return $this->CrudModel->questionLists(Table::questionsBankTable, $_POST);
		}
	}

	public function registrationLists()
	{
		if (isset($_POST)) {
			return $this->CrudModel->registrationLists(Table::registrationTable, $_POST);
		}
	}


	public function bookIdtoChapters()
	{
		if (isset($_POST)) {

			$bookId = $_POST['bookId'];
			$chaptersData = [];
			$chaptersData = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::chaptersTable . " WHERE book_id = '$bookId' AND status != '4' ORDER BY id DESC");

			$sendArr = '';
			foreach ($chaptersData as $c) {
				$sendArr .= '<option value="' . $c['id'] . '">' . $c['chapter_name'] . '</option>';
			}




			echo json_encode($sendArr);
		}
	}
	public function showBooksViaSubject()
	{
		if (isset($_POST)) {

			$subjectId = $_POST['subjectId'];
			$classId = $_POST['classId'];
			$booksData = [];
			$booksData = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::booksTable . " WHERE subject_name = '$subjectId' AND class_name = '$classId' AND status != '4' ORDER BY id DESC");

			$sendArr = '';
			foreach ($booksData as $c) {
				$sendArr .= '<option value="' . $c['id'] . '">' . $c['book_name'] . '</option>';
			}




			echo json_encode($sendArr);
		}
	}

	public function loadQuestions()
	{
		if (isset($_POST)) {

			$bookId = $_POST['bookId'];
			$chapterId = $_POST['chapterId'];
			$questionsData = [];

			$questionsData = $this->CrudModel->dbSqlQuery("SELECT q.*,b.book_name,b.class_name,b.subject_name,b.board_name,c.chapter_name FROM " . Table::questionsBankTable . " q
			LEFT JOIN " . Table::booksTable . " b ON b.id =  q.book_id
			LEFT JOIN " . Table::chaptersTable . " c ON c.id =  q.chapter_id
			WHERE q.status = '1' AND q.book_id = '$bookId' AND q.chapter_id = '$chapterId' ");

			$sendArr = '';
			foreach ($questionsData as $c) {
				$sendArr .= '<div class="leftSide card px-2 py-2 ">
				<input type="hidden" name="q[]" value="' . $c['id'] . '" />
				<div class="card-header border-top-3">For ' . ucwords($c['class_name']) . ' Class & ' . ucwords($c['subject_name']) . ' Subject</div>
				<div class="card-body"><b>Q.</b> ' . html_entity_decode($c['question'], ENT_QUOTES) . '</div>
				<div class="card-footer">From The Book ' . ucwords($c['book_name']) . '</div>
			  </div>';
			}




			echo json_encode($sendArr);
		}
	}




	public function checkEmployeeSalaryById($sessionId = '')
	{
		if (isset($_POST)) {

			$this->tableName = Table::salaryTable;

			$sendArr = [];
			$sendArr['id'] = $_POST['id'];
			$totalWorkingDays =  $this->CrudModel->totalEmployeesWorkingDaysAndHolidaysCurrentMonth($_POST['monthId'], $_POST['yearId']);

			$sendArr['workingDays'] = $totalWorkingDays;

			$condition = " AND s.id = '{$_POST['id']}' ";
			if ($sessionId != '') {
				$condition .= " AND s.schoolUniqueCode = '$sessionId' ";
			} else {
				$condition .= " AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ";
			}

			$d = $this->db->query("SELECT s.*, dep.departmentName, des.designationName FROM " . $this->tableName . " s 
            JOIN " . Table::departmentTable . " dep  ON dep.id = s.departmentId 
            JOIN " . Table::designationTable . " des ON des.id = s.designationId 
            WHERE s.status != 4 $condition ")->result_array();


			$sendArr['employeeDetails'] = $d[0];

			$totalAttendanceArr =  $this->CrudModel->getTotalAttendanceOfEmployeeCurrentMonth($_POST['id'], $_POST['monthId'], $_POST['yearId']);


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

			echo json_encode($sendArr);
			exit(0);
		}
	}



	public function addHolidayEvent()
	{
		if (isset($_POST)) {
			$add = $this->db->query("INSERT INTO " . Table::holidayCalendarTable . " (schoolUniqueCode,title,event_date,session_table_id) VALUES ('{$_SESSION['schoolUniqueCode']}','{$_POST['title']}','{$_POST['start']}','{$_SESSION['currentSession']}')");
		}
	}
	public function editHolidayEvent()
	{
		if (isset($_POST)) {
			$update = $this->db->query("UPDATE " . Table::holidayCalendarTable . " SET event_date = '{$_POST['start']}' WHERE id = '{$_POST['event_id']}'");
		}
	}
	public function getHolidayEvent()
	{

		$result = $this->db->query("SELECT * FROM " . Table::holidayCalendarTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'  ORDER BY id DESC")->result_array();

		$data = array();
		if (!empty($result)) {
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
		if (isset($_POST['stateId'])) {
			if (isset($_POST['alreadyCityId']) && $_POST['alreadyCityId'] != '' && $_POST['alreadyCityId'] != 0) {
				$alreadyCityId = $_POST['alreadyCityId'];
			} else {
				$alreadyCityId = '';
			}
			echo $this->CrudModel->showCityViaStateId($_POST['stateId'], $alreadyCityId);
		}
	}

	public function showAllSemExamsWithStudents()
	{
		if (isset($_POST)) {
			$examDetails = $this->db->query("SELECT setT.*, sub.subjectName FROM " . Table::secExamTable . " setT 
			JOIN " . Table::semExamNameTable . " se ON se.id = setT.sem_exam_id
			JOIN " . Table::subjectTable . " sub ON sub.id = setT.subject_id
			WHERE setT.class_id = '{$_POST['classId']}'
			AND setT.section_id = '{$_POST['sectionId']}' 
			AND se.id = '{$_POST['semExamId']}'")->result_array();

			$studentsViaClass = $this->db->query($sql = "SELECT * FROM " . Table::studentTable . " WHERE class_id = '{$_POST['classId']}' AND section_id = '{$_POST['sectionId']}' AND status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

			$sendArr = [
				'examDetails' => $examDetails,
				'students' => $studentsViaClass
			];
			echo json_encode($sendArr);
		}
	}

	public function showDesignationsViaDepartmentId()
	{
		if (isset($_POST)) {
			$d = $this->db->query($sql = "SELECT * FROM " . Table::designationTable . " WHERE departmentId = '{$_POST['departmentId']}' AND status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
			$html = '';

			if (!empty($d)) {
				foreach ($d as $dd) {
					$html .= "<option value='{$dd['id']}'>{$dd['designationName']}</option>";
				}
				echo json_encode($html);
				exit(0);
			}
			echo json_encode($sql);
			exit(0);
		}
	}
	public function showEmployeesViaDepAndDesId()
	{
		if (isset($_POST)) {
			$d = $this->db->query($sql = "SELECT * FROM " . Table::salaryTable . " WHERE departmentId = '{$_POST['departmentId']}' AND designationId =  '{$_POST['designationId']}' AND status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
			$html = '';

			if (!empty($d)) {
				foreach ($d as $dd) {
					$html .= "<option value='{$dd['id']}'>{$dd['empId']}  {$dd['employeeName']} </option>";
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
		if (isset($_POST)) {
			$d = $this->db->query($sql = "SELECT s.id,s.empId,s.employeeName,dep.departmentName,des.designationName, '' as msg FROM " . Table::salaryTable . " s
			INNER JOIN " . Table::departmentTable . " dep ON dep.id = s.departmentId
    		INNER JOIN " . Table::designationTable . " des ON des.id = s.designationId 
			WHERE s.departmentId = '{$_POST['departmentId']}' AND s.status = '1' AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
			$html = '';

			if (!empty($d)) {

				echo json_encode($d);
				exit(0);
			}
			echo json_encode(array(0 => array('msg' => 'No Employess Found in this department.')));
			exit(0);
		}
	}

	public function showEmployeesDetailsViaDepAndDesId()
	{
		if (isset($_POST)) {
			$d = $this->db->query($sql = "SELECT s.*, DATE_FORMAT(s.doj, '%d %M %Y') as doj,dep.departmentName,des.designationName, sch.school_name FROM " . Table::salaryTable . " s
			INNER JOIN " . Table::departmentTable . " dep ON dep.id = s.departmentId
    		INNER JOIN " . Table::designationTable . " des ON des.id = s.designationId
			INNER JOIN " . Table::schoolMasterTable . " sch ON sch.unique_id = s.schoolUniqueCode
			WHERE 
			s.departmentId = '{$_POST['departmentId']}' 
			AND s.designationId = '{$_POST['designationId']}' 
			AND s.id = '{$_POST['employeeId']}' 
			AND s.status = '1' 
			AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array()[0];
			$html = '';
			$d['todayDate'] = date('d F Y');

			if (!empty($d)) {

				echo json_encode($d);
				exit(0);
			}
			echo json_encode(array(0 => array('msg' => 'Employee Details Not Found in this department.')));
			exit(0);
		}
	}
	public function showStudentViaClassAndSectionAndStudentIdForCharacter()
	{
		if (isset($_POST)) {
			$d = $this->db->query($sql = "SELECT s.*, DATE_FORMAT(s.date_of_admission, '%d %M %Y') as doa,cl.className,sc.sectionName, sch.school_name,sst.session_start_year , sst.session_end_year, stt.id as tc_id FROM " . Table::studentTable . " s
			INNER JOIN " . Table::classTable . " cl ON cl.id = s.class_id
    		INNER JOIN " . Table::sectionTable . " sc ON sc.id = s.section_id
			INNER JOIN " . Table::schoolMasterTable . " sch ON sch.unique_id = s.schoolUniqueCode
			INNER JOIN " . Table::studentTC . " stt ON stt.student_id = s.id AND stt.schoolUniqueCode = s.schoolUniqueCode
			INNER JOIN " . Table::schoolSessionTable . " sst ON sst.id = stt.session_table_id AND stt.schoolUniqueCode = s.schoolUniqueCode
			WHERE 
			s.class_id = '{$_POST['classId']}' 
			AND s.section_id = '{$_POST['sectionId']}' 
			AND s.id = '{$_POST['studentId']}' 
			AND s.status = '3' 
			AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array()[0];
			$html = '';
			$d['todayDate'] = date('d F Y');

			if (!empty($d)) {

				echo json_encode($d);
				exit(0);
			}
			echo json_encode(array(0 => array('msg' => 'Student Details Not Found.')));
			exit(0);
		}
	}

	public function showStudentViaClassAndSectionAndStudentIdForBonefide()
	{
		if (isset($_POST)) {
			$d = @$this->db->query($sql = "SELECT s.*, DATE_FORMAT(s.date_of_admission, '%d %M %Y') as doa,cl.className,sc.sectionName, sch.school_name,sst.session_start_year , sst.session_end_year, sch.address as sAddress, city.cityName,state.stateName FROM " . Table::studentTable . " s
			INNER JOIN " . Table::classTable . " cl ON cl.id = s.class_id
    		INNER JOIN " . Table::sectionTable . " sc ON sc.id = s.section_id
    		INNER JOIN " . Table::cityTable . " city ON city.id = s.city_id
    		INNER JOIN " . Table::stateTable . " state ON state.id = s.state_id
			INNER JOIN " . Table::schoolMasterTable . " sch ON sch.unique_id = s.schoolUniqueCode
			INNER JOIN " . Table::schoolSessionTable . " sst ON sst.schoolUniqueCode = s.schoolUniqueCode
			WHERE 
			s.class_id = '{$_POST['classId']}' 
			AND s.section_id = '{$_POST['sectionId']}' 
			AND s.id = '{$_POST['studentId']}' 
			AND s.status = '1' 
			AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array()[0];

			$html = '';
			$d['todayDate'] = date('d F Y');

			if (!empty($d)) {

				echo json_encode($d);
				exit(0);
			}
			echo json_encode(array(0 => array('msg' => 'Student Details Not Found.')));
			exit(0);
		}
	}










	public function showSalaryDetails()
	{
		if (isset($_POST)) {
			$d = $this->db->query("SELECT s.*, dep.departmentName,des.designationName FROM " . Table::salaryTable . " s 
			INNER JOIN " . Table::departmentTable . " dep ON dep.id = s.departmentId
			INNER JOIN " . Table::designationTable . " des ON des.id = s.designationId
			 WHERE s.status != '4'  AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND s.id='{$_POST['salaryId']}'")->result_array()[0];
			$html = '';

			if (!empty($d)) {
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

		if (isset($_POST)) {
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

	public function showDownloadIDCard()
	{
		$this->loginCheck();
		$dataArr = [
			'pageTitle' => 'Download Student ID Cards',
			'adminPanelUrl' => $this->adminPanelURL,
		];
		$this->load->view('adminPanel/pages/header', ['data' => $dataArr]);
		$this->load->view('adminPanel/pages/qrcode/download-id-card');
	}

	public function idcard()
	{

		$this->load->view('adminPanel/pages/qrcode/idcard');
	}

	public function downloadQR($classId, $sectionId)
	{

		$this->loginCheck();
		$d = $this->db->query("SELECT qr.qrcodeUrl,qr.uniqueValue as qrName, CONCAT(cl.className, ' - ', se.sectionName) as classNames, st.roll_no
                    FROM " . Table::qrcodeTable . " qr 
                    JOIN " . Table::studentTable . " st ON st.user_id = qr.uniqueValue
                    JOIN " . Table::classTable . " cl ON cl.id = st.class_id
                    JOIN " . Table::sectionTable . " se ON se.id = st.section_id
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
		$latLng = $this->db->query("SELECT * FROM " . Table::driverTable . " WHERE status != '4' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND lat IS NOT NULL AND lng IS NOT NULL")->result_array();

		if (!empty($latLng)) {
			$totalC = count($latLng);
			$latLngArr = [];
			for ($i = 0; $i < $totalC; $i++) {
				$subArr = [
					'lat' => (float) $latLng[$i]['lat'],
					'lng' => (float) $latLng[$i]['lng'],
					'name' =>  $latLng[$i]['name'],
					'mobile' =>  $latLng[$i]['mobile'],
					'vechicle_type' =>  HelperClass::vehicleType[$latLng[$i]['vechicle_type']],
					'vechicle_no' =>  $latLng[$i]['vechicle_no'],
					'total_seats' =>  $latLng[$i]['total_seats'],
				];
				array_push($latLngArr, $subArr);
			}
			echo json_encode($latLngArr);
			die();
		}
	}


	public function loginCheck()
	{
		if (!$this->CrudModel->checkIsLogin()) {
			header('Location: ' . base_url());
		}
	}

	public function checkPermission()
	{
		if (!$this->CrudModel->checkPermission()) {
			header('Location: ' . base_url());
		}
	}
}
