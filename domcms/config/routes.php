<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
/*DPR Reports*/
$route['reports/dpr/(:any)'] 			= 'dpr/manage';
$route['reputation/(:any)']				= 'reputation/dashboard';
$route['login'] 						= 'auth/login';
$route['reset_password'] 				= 'auth/reset_password';
$route['change_password'] 				= 'auth/change_password';
$route['generate_password']				= 'auth/process_forgot_pass';
$route['process_change_password']       = 'auth/password_change';
$route['authenticate'] 					= 'auth/login_user';
$route['logout']						= 'auth/logout';
$route['groups']						= 'admin/groups';
$route['edit_group']					= 'admin/groups/edit';
$route['view_group']					= 'admin/groups/view';
$route['clients']						= 'admin/clients';
$route['edit_client']					= 'admin/clients/edit';
$route['view_group']					= 'admin/clients/view';
$route['users']							= 'admin/users';
$route['edit_user']						= 'admin/users/edit';
$route['user/(:any)']					= 'admin/users/view';
$route['reputation']					= 'reputations';
$route['reputation/(:any)']				= 'reputations/$1';
$route['default_controller']            = "admin/index";
$route['404_override'] 					= 'errors/file_not_found';
/* End of file routes.php */
/* Location: ./application/config/routes.php */