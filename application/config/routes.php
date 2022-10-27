<?php
defined('BASEPATH') OR exit('No direct script access allowed');


 $route['default_controller'] = 'FrontController';
 $route['/'] = 'FrontController/index'; // login or studentview
 $route['register'] = 'FrontController/register'; // register
 $route['logout'] = 'FrontController/logout'; // logout
 $route['tc'] = 'FrontController/tc'; // tc verify
 $route['semResult'] = 'FrontController/semResult'; // semResult
 $route['feesInvoice'] = 'FrontController/feesInvoice'; // feesInvoice
 $route['idcard'] = 'FrontController/idcard'; // idcard
 // front-end routes




 // admin panel routes
 $route['adminPanel'] = 'AdminController/index';




// student routes
$baseStudent = "student/";
$studentRoutesArr = [
    'list',
    'addStudent',
    'saveStudent',
    'updateStudent',
    'permoteStudent',
    'generateTC',
    'editTC'
];

foreach($studentRoutesArr as $stRoute)
{
    $route[$baseStudent.$stRoute] = "StudentController"."/".$stRoute;
}

// get routes of student

$route[$baseStudent."viewStudent/(:any)"] = "StudentController/viewStudent/$1";
$route[$baseStudent."editStudent/(:any)"] = "StudentController/editStudent/$1";
$route[$baseStudent."deleteStudent/(:any)"] = "StudentController/deleteStudent/$1";



// teachers routes

$baseTeacher = "teacher/";
$teacherRoutesArr = [
    'list',
    'addTeacher',
    'saveTeacher',
    'updateTeacher',
    'teacherReviews',
    'attendance'
];

foreach($teacherRoutesArr as $tRoute)
{
    $route[$baseTeacher.$tRoute] = "TeacherController"."/".$tRoute;
}

// get routes of Teacher

$route[$baseTeacher."viewTeacher/(:any)"] = "TeacherController/viewTeacher/$1";
$route[$baseTeacher."editTeacher/(:any)"] = "TeacherController/editTeacher/$1";
$route[$baseTeacher."deleteTeacher/(:any)"] = "TeacherController/deleteTeacher/$1";



// driver


$baseDriver = "driver/";
$driverRouteArr = [
    'list',
    'addDriver',
    'saveDriver',
    'updateDriver',
    'showMap'
    // 'teacherReviews',
];

foreach($driverRouteArr as $driverRoute)
{
    $route[$baseDriver.$driverRoute] = "DriverController"."/".$driverRoute;
}


// get routes of driver

$route[$baseDriver."viewDriver/(:any)"] = "DriverController/viewDriver/$1";
$route[$baseDriver."editDriver/(:any)"] = "DriverController/editDriver/$1";
$route[$baseDriver."deleteDriver/(:any)"] = "DriverController/deleteDriver/$1";




// school routs
 $baseSchool = "school/";
 $schoolRoutesArr = [
    'schoolProfile'
];

foreach($schoolRoutesArr as $schoolRoute)
{
    $route[$baseSchool.$schoolRoute] = "SchoolController"."/".$schoolRoute;
}



// master routes

$baseMaster = "master/";
$masterRoutesArr = [
    'cityMaster',
    'stateMaster',
    'classMaster',
    'sectionMaster',
    'subjectMaster',
    'weekMaster',
    'hourMaster',
    'teacherSubjectsMaster',
    'timeTableSheduleMaster',
    'panelUserMaster',
    'notificationMaster',
    'visitorMaster',
    'feesMaster',
    'submitFeesMaster',
    'feesListingMaster',
    'feesInvoice',
    'monthMaster',
    'setDigiCoinMaster',
    'bannerMaster',
    'setNotificationMaster',
    'notificationDefault',
    'feeDueNotification',
    'sessionMaster',
    'givePermissionMaster' // only for super Admin
    
];

foreach($masterRoutesArr as $masRoute)
{
    $route[$baseMaster.$masRoute] = "MasterController"."/".$masRoute;
}

// get routes of Teacher

$route[$baseMaster."editPermission/(:any)/(:any)"] = "MasterController/editPermission/$1/$2"; // user_id / user_type





// digiCoins


$baseDigiCoin = "digicoin/";
$digiCoinRoutesArr = [
    'setDigiCoinMaster',
    'studentDigiCoin',
    'teacherDigiCoin',
    'giftMaster',
    'giftRedeemMaster',
    'changeGiftStatus',
    'leaderBoard'
];

foreach($digiCoinRoutesArr as $digiRoute)
{
    $route[$baseDigiCoin.$digiRoute] = "DigiCoinController"."/".$digiRoute;
}




// exam & result


$baseExam = "exam/";
$examRoutesArr = [
    'allExams',
    'allResults',
];

foreach($examRoutesArr as $examRoute)
{
    $route[$baseExam.$examRoute] = "ExamController"."/".$examRoute;
}



// academic


$baseAcademic = "academic/";
$academicRoutesArr = [
    'allAttendance',
    'allDeparture',
    'allComplaints',
    'allTeachersAttendance',
    'holidayCalendar'
];

foreach($academicRoutesArr as $aRoute)
{
    $route[$baseAcademic.$aRoute] = "AcademicController"."/".$aRoute;
}





// semester Wise Exam


$semesterBase = "semester/";
$semesterRoutesArr = [
    'semesterMaster',
    'dateSheetMaster',
    'dateSheetList',
    'downloadDateSheet',
    'showAllSemesterResults'
];

foreach($semesterRoutesArr as $semRoute)
{
    $route[$semesterBase.$semRoute] = "SemesterController"."/".$semRoute;
}




// Ajax routes
$baseAjax = 'ajax/';
$ajaxRoutesArr = [
    'listStudentsAjax',
    'listTeachersAjax',
    'listDriversAjax',
    'showStudentViaClassAndSectionId',
    'totalFeesDue',
    'listDigiCoinAjax',
    'showCityViaStateId',
    'allExamList',
    'allResultList',
    'teacherReviewsList',
    'getLatLng',
    'showDriverListViaVechicleType',
    'allAttendanceList',
    'allDepartureList',
    'allComplaintList',
    'allTeachersAttendanceList',
    'addHolidayEvent',
    'editHolidayEvent',
    'getHolidayEvent',
    'dateSheetList',
    'showAllSemExamsWithStudents',
    'listStudentsPermote',
    'showAllSemesterResultsList'
    
];

foreach($ajaxRoutesArr as $ajRoute)
{
    $route[$baseAjax.$ajRoute] = "AjaxController"."/".$ajRoute;
}



// qr code list
$route['listQR'] = 'AjaxController/listQR';
$route['listQRCodeAjax'] = 'AjaxController/listQRCodeAjax';
$route['showDownloadQR'] = 'AjaxController/showDownloadQR';
$route["downloadQR/(:any)/(:any)/(:any)"] = "AjaxController/downloadQR/$1/$2/$3"; // user_id / 

// api routes
$baseAPI = 'api/v1/';
$apiRoutesArr = [
    'login', 
    'showStudentsForAttendence',
    'submitAttendence',
    'showSubmitAttendenceData',
    'allClasses',
    'allSections',
    'allSubjects',
    'submitDeparture',
    'showSubmitDepartureData',
    'showStudentDetails',
    'addExam',
    'showAllExam',
    'showSingleExam',
    'updateExam',
    'addResult',
    'addHomeWork',
    'showAllHomeWorks',
    'showSingleHomeWork',
    'updateHomeWork',
    'walletHistory',
    'getAlreadyDigiCoinCount',
    'checkAllGifts',
    'showGiftsForRedeem',
    'redeemGifts',
    'giftRedeemStatus',
    'leaderBoard',
    'visitorEntry',
    'bannerForApp',
    'notificationsForParent',
    'studentDashboard',
    'showAllStudentsForSwitchProfile',
    'showAttendanceDataForStudentId',
    'upateDriverLatLng',
    'studentFeesSubmitData',
    'validateQRCode',
    'getDriverLatLng',
    'addComplaint',
    'showResultDataWithExam',
    'checkAppUpdate',
    'holidayCalender',
    'showSemesterExamNames',
    'showAllSemesterExam',
    'addSemesterExamResult'
];

foreach($apiRoutesArr as $apiRoute)
{
    $route[$baseAPI.$apiRoute] = "APIController"."/".$apiRoute;
}





// cron routes
$route['cron/examAlert'] = 'CronController/examAlert';
$route['cron/resultAlert'] = 'CronController/resultAlert';



$route['/welcome'] = 'Welcome/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
