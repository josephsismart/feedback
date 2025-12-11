<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Main';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;



//admin
$route['map'] = 'system/Map';
$route['get_subcategories'] = 'system/Index/get_subcategories';
$route['login'] = 'system/Login';
$route['consent'] = 'system/Consent';
$route['signup'] = 'system/Signup';
$route['index'] = 'system/Index';
$route['registration'] = 'system/Registration';

$route['updatePassword'] = 'userpassword/Changepassword/updatePassword';
$route['changepassword'] = 'userpassword/Changepassword';

$route['logout'] = 'system/Login/request_logout';
$route['requestlogin'] = 'system/Login/request_login';
$route['requestsignup'] = 'system/Signup/request_signup';
$route['sendSMS'] = 'system/Signup/sendSMS';
$route['sendEmail'] = 'system/Signup/email_verification';

$route['search-barangay'] = 'userpublicmap/map/search_barangay';

// $route['save_national'] = 'useradmin/Dataentry/savenational';
// $route['save_local'] = 'uservalidator/Dataentry/savelocal';
// $route['get_national'] = 'useradmin/Dataentry/getnational';
// $route['get_local'] = 'uservalidator/Dataentry/getlocal';
// $route['get_localN'] = 'useradmin/Dataentry/getlocal';