<?php
defined('BASEPATH') OR exit('No direct script access allowed');


 $route['default_controller'] = 'FrontController';
 $route['/'] = 'FrontController/index'; // login or studentview
 $route['register'] = 'FrontController/register'; // register
 $route['logout'] = 'FrontController/logout'; // logout
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
];

foreach($teacherRoutesArr as $tRoute)
{
    $route[$baseTeacher.$tRoute] = "TeacherController"."/".$tRoute;
}

// get routes of Teacher

$route[$baseTeacher."viewTeacher/(:any)"] = "TeacherController/viewTeacher/$1";
$route[$baseTeacher."editTeacher/(:any)"] = "TeacherController/editTeacher/$1";
$route[$baseTeacher."deleteTeacher/(:any)"] = "TeacherController/deleteTeacher/$1";




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
    'monthMaster',
    'setDigiCoinMaster',
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

// get routes of Teacher

//$route[$baseDigiCoin."editPermission/(:any)"] = "DigiCoinController/editPermission/$1";







// Ajax routes
$baseAjax = 'ajax/';
$ajaxRoutesArr = [
    'listStudentsAjax',
    'listTeachersAjax',
    'showStudentViaClassAndSectionId',
    'totalFeesDue',
    'listDigiCoinAjax'
    
];

foreach($ajaxRoutesArr as $ajRoute)
{
    $route[$baseAjax.$ajRoute] = "AjaxController"."/".$ajRoute;
}



// qr code list
$route['listQR'] = 'AjaxController/listQR';
$route['listQRCodeAjax'] = 'AjaxController/listQRCodeAjax';


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
    'visitorEntry'
];

foreach($apiRoutesArr as $apiRoute)
{
    $route[$baseAPI.$apiRoute] = "APIController"."/".$apiRoute;
}









$route['/welcome'] = 'Welcome/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
