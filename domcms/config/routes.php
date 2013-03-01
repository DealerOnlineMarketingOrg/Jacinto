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
/*auth redirects*/
$route['login'] 						= 'auth/login';
$route['authenticate'] 					= 'auth/login/authenticate';
$route['logout']						= 'auth/logout';

//admin redirects
$route['groups']						= 'admin/groups';
$route['groups/(:any)']					= 'admin/groups/$1';

$route['clients']						= 'admin/clients';
$route['clients/(:any)']				= 'admin/clients/$1';

$route['users']							= 'admin/users';
$route['users/(:any)']					= 'admin/users/$1';

$route['contacts']						= 'admin/contacts';
$route['contacts/(:any)']				= 'admin/contacts/$1';

$route['agency']						= 'admin/agency';
$route['agency/(:any)']					= 'admin/agency/$1';

$route['dpr']							= 'reports/dpr';
$route['dpr/(:any)']					= 'reports/dpr/$1';

//redirects
$route['reputation']					= 'reputations/dashboard';
$route['bing']							= 'reputations/bing/dashboard';

$route['default_controller']            = "admin/dashboard";
$route['404_override'] 					= 'errors/file_not_found';
/* End of file routes.php */
/* Location: ./application/config/routes.php */