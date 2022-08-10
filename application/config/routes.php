<?php
defined('BASEPATH') OR exit('No direct script access allowed');


 $route['default_controller'] = 'AdminController';
$route['/'] = 'AdminController/index';



// student routes
$baseStudent = "student/";
$studentRoutesArr = [
    'list',
    'addStudent',
    'saveStudent',
];

foreach($studentRoutesArr as $stRoute)
{
    $route[$baseStudent.$stRoute] = "StudentController"."/".$stRoute;
}

// Ajax routes
$baseAjax = 'ajax/';
$ajaxRoutesArr = [
    'listStudentsAjax',
];

foreach($ajaxRoutesArr as $ajRoute)
{
    $route[$baseAjax.$ajRoute] = "AjaxController"."/".$ajRoute;
}










$route['/welcome'] = 'Welcome/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
