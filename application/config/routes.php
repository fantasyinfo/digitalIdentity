<?php
defined('BASEPATH') OR exit('No direct script access allowed');


 $route['default_controller'] = 'FrontController';
 $route['/'] = 'FrontController/index';
 // front-end routes




 // admin panel routes
 $route['/adminPanel'] = 'AdminController/index';



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





// master routes

$baseMaster = "master/";
$masterRoutesArr = [
    'cityMaster',
    'stateMaster',
    'classMaster',
    'sectionMaster'
    
];

foreach($masterRoutesArr as $masRoute)
{
    $route[$baseMaster.$masRoute] = "MasterController"."/".$masRoute;
}

// get routes of Teacher

$route[$baseMaster."viewTeacher/(:any)"] = "MasterController/viewTeacher/$1";
$route[$baseMaster."editTeacher/(:any)"] = "MasterController/editTeacher/$1";
$route[$baseMaster."deleteTeacher/(:any)"] = "MasterController/deleteTeacher/$1";















// Ajax routes
$baseAjax = 'ajax/';
$ajaxRoutesArr = [
    'listStudentsAjax',
    'listTeachersAjax',
    
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
    'submitDeparture',
    'showSubmitDepartureData'
];

foreach($apiRoutesArr as $apiRoute)
{
    $route[$baseAPI.$apiRoute] = "APIController"."/".$apiRoute;
}









$route['/welcome'] = 'Welcome/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
