<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Auth
$routes->get('/', 'Auth::login');
$routes->post('auth/process', 'Auth::process');
$routes->get('auth/login', 'Auth::login');
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/registerProcess', 'Auth::registerProcess');
$routes->get('auth/forgot', 'Auth::forgot');
$routes->post('auth/resetPassword', 'Auth::resetPassword');
$routes->get('logout', 'Auth::logout');

// Account
$routes->get('acount', 'Acount::index');
$routes->post('update-role/(:num)', 'Acount::updateRole/$1');
$routes->get('delete-user/(:num)', 'Acount::deleteUser/$1');

// Dashboard User
$routes->get('Homeuser', 'DashboardUser::HomeUser');
$routes->get('DashboardUser/HomeUser', 'DashboardUser::HomeUser');
$routes->get('DashboardUser/HistorySiswa', 'DashboardUser::HistorySiswa');
$routes->get('Historysiswa', 'DashboardUser::Historysiswa');
$routes->get('ProfileUser', 'DashboardUser::ProfileUser');
$routes->match(['get', 'post'], 'ProfileUser/deleteAccount', 'DashboardUser::deleteAccount');
$routes->get('ProfileUser/getUser', 'DashboardUser::getUser');
$routes->post('ProfileUser/updateProfile', 'DashboardUser::updateProfile');

// Form Kelas (User)
$routes->get('FormKelas', 'FormKelas::Formkelas');
$routes->post('save-kelas', 'FormKelas::save');
$routes->get('FormKelas/delete/(:num)', 'FormKelas::delete/$1');
$routes->get('FormEdit/FormKelasEdit/(:num)', 'FormEdit::FormKelasEdit/$1');
$routes->post('FormEdit/update/(:num)', 'FormEdit::update/$1');

// Form Absensi (User)
$routes->get('FormAbsensi/FormSiswa', 'FormAbsensi::FormSiswa');
$routes->post('save-siswa', 'FormAbsensi::save');
$routes->get('HistorySiswa', 'FormAbsensi::HistorySiswa');

// History (User)
$routes->get('history/delete/(:num)', 'History::delete/$1');
$routes->get('History/edit/(:num)', 'History::edit/$1');
$routes->post('History/update/(:num)', 'History::update/$1');

// Dashboard Admin
$routes->get('AdminHome', 'DashboardAdmin::HomeAdmin');
$routes->get('DashboardAdmin/HomeAdmin', 'DashboardAdmin::HomeAdmin');
$routes->get('DashboardAdmin/HistorySiswaAd', 'DashboardAdmin::HistorySiswaAd');
$routes->get('HistorysiswaAd', 'DashboardAdmin::HistorysiswaAd');
$routes->get('DashboardAdmin/ChartAdmin', 'DashboardAdmin::ChartAdmin');
$routes->get('DashboardAdmin/Settings', 'DashboardAdmin::Settings');
$routes->get('DashboardAdmin/exportExcel', 'DashboardAdmin::exportExcel');
$routes->get('DashboardAdmin/Spreadsheet', 'DashboardAdmin::Spreadsheet');
$routes->post('DashboardAdmin/updateRole', 'DashboardAdmin::updateRole');
$routes->post('DashboardAdmin/updatePermission', 'DashboardAdmin::updatePermission');

// Form Kelas Admin
$routes->get('FormKelasAdmin', 'FormKelasAdmin::Formkelasadmin');
$routes->post('save-kelasAdmin', 'FormKelasAdmin::saveAdmin');
$routes->get('FormKelasAdmin/delete/(:num)', 'FormKelasAdmin::delete/$1');
$routes->get('FormEditAd/FormKelasEditAd/(:num)', 'FormEditAd::FormKelasEditAd/$1');
$routes->post('FormEditAd/update/(:num)', 'FormEditAd::update/$1');

// Form Absensi Admin
$routes->get('FormAbsensiAd/FormSiswaAd', 'FormAbsensiAd::FormSiswaAd');
$routes->post('save-siswaAd', 'FormAbsensiAd::saveAd');
$routes->get('HistorySiswaAd', 'FormAbsensiAd::HistorySiswaAd');

// History Admin
$routes->get('historyad/delete/(:num)', 'HistoryAd::delete/$1');
$routes->get('HistoryAd/edit/(:num)', 'HistoryAd::edit/$1');
$routes->post('HistoryAd/update/(:num)', 'HistoryAd::update/$1');

// Profile Admin
$routes->get('ProfileAd', 'DashboardAdmin::ProfileAd');
$routes->match(['get', 'post'], 'ProfileAd/deleteAccount', 'DashboardAdmin::deleteAccount');
$routes->get('ProfileAd/getUserAd', 'DashboardAdmin::getUserAd');
$routes->post('ProfileAd/updateProfileAd', 'DashboardAdmin::updateProfileAd');

// Settings Admin
$routes->get('Settings/deleteAccountS/(:num)', 'DashboardAdmin::deleteAccountS/$1');
$routes->get('Settings/getUserAdS', 'DashboardAdmin::getUserAdS');
$routes->post('Settings/updateProfileAdS', 'DashboardAdmin::updateProfileAdS');