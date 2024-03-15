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

$route['default_controller'] = "Homepage";
$route['knowledge_base/(:any)']='knowledge_base/view/$1';
$route['404_override'] = '';
$route['demo-landingpage'] = "homepage/demo";
$route['errors/(:any)']='errors/index/$1';
$route['signup']='login/signup';
$route['adminpanel']='adminpanel';
$route['adminpanel/(:any)']='adminpanel/$1';
$route['bookshop']='study_material/book_shop';
$route['login/(:any)']='login/$1';
$route['employee/(:any)']='employee/$1';
$route['students/(:any)']='students/$1';
$route['online_admission']='online_admission';
$route['online_admission/(:any)']='online_admission/$1';
$route['online_classes']='online_classes';
$route['online_classes/(:any)']='online_classes/$1';
$route['online_app']='online_app';
$route['parents/(:any)']='parents/$1';
$route['attendance/(:any)']='attendance/$1';
$route['promotion/(:any)']='promotion/$1';
$route['fee/(:any)']='fee/$1';
$route['settings/(:any)']='settings/$1';
$route['usermanagement']='usermanagement';
$route['usermanagement/(:any)']='usermanagement/$1';
$route['notification/(:any)']='notification/$1';
$route['study_material/(:any)']='study_material/$1';
$route['forms/(:any)']='forms/$1';
$route['messages/(:any)']='messages/$1';
$route['timetable/(:any)']='timetable/$1';
$route['profile/(:any)']='profile/$1';
$route['import/(:any)']='import/$1';
$route['reports/(:any)']='reports/$1';
$route['examination/(:any)']='examination/$1';
$route['online_exams/(:any)']='online_exams/$1';
$route['trash']='trash';
$route['payroll']='payroll';
$route['calendar']='calendar';
$route['api']='api';
$route['api/(:any)']='api/$1';
$route['mobileapi']='mobileapi';
$route['mobileapi/(:any)']='mobileapi/$1';
$route['announcements']='announcements';
$route['announcements/(:any)']='announcements/$1';
$route['page']='page';
$route['page/(:any)']='page/$1';
$route['trash/(:any)']='trash/$1';
$route['payroll/(:any)']='payroll/$1';
$route['calendar/(:any)']='calendar/$1';
$route['common/(:any)']='common/$1';
$route['study_plan/(:any)']='study_plan/$1';
$route['syllabus/(:any)']='syllabus/$1';
$route['profile']='profile';
$route['accounts']='accounts';
$route['licenses/(:any)']='licenses/$1';
$route['accounts/(:any)']='accounts/$1';
$route['cronjobs/(:any)']='cronjobs/$1';
$route['default_login/(:any)']='default_login/$1';
$route['applications/(:any)']='applications/$1';
$route['licenses']='licenses';
$route['licensesrenew']='licensesrenew';
$route['licensesrenew/(:any)']='licensesrenew/$1';
$route['monitoring']='monitoring';
$route['monitoring/(:any)']='monitoring/$1';
$route['LanguageSwitcher/switchLang/(:any)']='LanguageSwitcher/switchLang/$1';
$route['dashboard']='dashboard';
$route['dashboard/(:any)']='dashboard/$1';
$route['xcrud_ajax']='xcrud_ajax';
$route['assignsubjects/(:any)']='assignsubjects/$1';
$route['multiuser'] = 'multiuser';
$route['multiuser/(:any)'] = 'multiuser/$1';
$route['logout']='logout';
$route['login']='default_login';
$route['(:any)/login']='login/index/$1';
$route['(:any)/login/activation/(:any)'] = 'login/activation/$1/$2';
$route['(:any)/login/reset/(:any)'] = 'login/reset/$1/$2';

//add by sheraz
$route['comments/(:any)']='comments/$1';
//add by sheraz

$route['gdrive']='gdrive';
$route['gdrive/generate_token']='gdrive/generate_token';
$route['gdrive/uploadfiles']='gdrive/uploadfiles';
$route['settings/generate_token']='settings/generate_token';
$route['gd_guide']='settings/googledrive_guide';
$route['disable_gd']='settings/googledrive_disable';

$route['(:any)']='school_home/school_check/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */