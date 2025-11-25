<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==============================
// PUBLIC ROUTES (No Login Required)
// ==============================
$routes->get('/', 'AuthController::login'); // Default route
$routes->get('/app-login', 'AuthController::login');
$routes->post('/login/authenticate', 'AuthController::authenticate');
$routes->get('/app-register', 'AuthController::register');
$routes->post('/register/save', 'AuthController::save');
$routes->get('/logout', 'AuthController::logout');

// Unauthorized access page
$routes->get('/unauthorized', 'Home::unauthorized');

// ==============================
// ADMIN ROUTES (Protected by auth:admin filter)
// ==============================
$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {

  // Dashboard
  $routes->get('/', 'Home::index');

  // --- USER MANAGEMENT ---
  $routes->get('user-list', 'UserController::user_list');
  $routes->post('user/add', 'UserController::add');
  $routes->get('user/edit/(:num)', 'UserController::edit/$1');
  $routes->post('user/update', 'UserController::update');
  $routes->post('user/delete/(:num)', 'UserController::delete/$1');
  $routes->get('change-password', 'AuthController::changed_password');
  $routes->post('update-password', 'AuthController::save_password');
  $routes->get('profile-update/(:num)', 'UserController::profile_update/$1');
  $routes->post('profile-save', 'UserController::save_profile');
  // --- CLIENT MANAGEMENT ---
  $routes->get('client-list', 'ClientController::client_list');
  $routes->get('get-users', 'UserController::getUsers');
  $routes->get('get-clients/(:num)', 'ClientController::getClientsByUser/$1');
  $routes->get('get-customers/(:num)', 'CustomerController::getCustomers/$1');
  $routes->post('client/add', 'ClientController::add_client');
  $routes->get('client/edit-client/(:num)', 'ClientController::edit_client/$1');
  $routes->post('client/update', 'ClientController::update_client');
  $routes->post('client/delete/(:num)', 'ClientController::delete_client/$1');
  $routes->get('client-assign-history', 'ClientController::client_assing_history');

  // --- CUSTOMER MANAGEMENT ---
  $routes->get('customer-list', 'CustomerController::customer_list');
  $routes->post('customer/add', 'CustomerController::add_customer');
  $routes->get('customer/edit/(:num)', 'CustomerController::edit_customer/$1');
  $routes->get('customer/customer-detail/(:num)', 'CustomerController::customer_detail/$1');
  $routes->post('customer/delete/(:num)', 'CustomerController::delete_customer/$1');
  $routes->post('customer/update', 'CustomerController::updateCustomer');
  $routes->post('reassign-customer', 'CustomerController::reassign_customer');
  $routes->post('bulk-reassign-customers', 'CustomerController::bulk_reassign_customers');
  $routes->get('customer-assign-history', 'CustomerController::customer_assign_history');
  // --- Category Managment ----
  $routes->get('services', 'ServicesController::services_list');
  $routes->get('service-list', 'ServicesController::service');
  $routes->post('service/add', 'ServicesController::create_service');
  $routes->post('service/delete/(:num)', 'ServicesController::delete_service/$1');
  $routes->get('client/get-users-for-client/(:num)', 'ClientController::get_users_for_client/$1');
  $routes->get('get-client-users/(:num)', 'CustomerController::getClientUsers/$1');


  // --- TRANSACTION MANAGEMENT ---

  $routes->get('transaction-list', 'TransactionController::transaction_list');
  $routes->post('transaction/add', 'TransactionController::create_transaction');
  $routes->get('transaction/getTransaction/(:num)', 'TransactionController::getTransaction/$1');
  $routes->post('transaction/payNow', 'TransactionController::payNow');
  $routes->post('transaction-delete/(:num)', 'TransactionController::delete_transaction/$1');
  $routes->get('transaction-history/(:num)', 'TransactionController::get_payment_history/$1');
  $routes->post('client/assign_user', 'ClientController::assign_user');
  $routes->get('get-countries', 'UserController::getCountry');
  $routes->get('get-states/(:num)', 'UserController::getStateByCountry/$1');
  $routes->get('get-cities/(:num)', 'UserController::getCityByState/$1');

  $routes->get('invoice/preview/(:num)', 'InvoiceController::preview/$1');
  $routes->post('invoice/save/(:num)', 'InvoiceController::saveInvoice/$1');
  $routes->get('invoice/view/(:num)', 'InvoiceController::view/$1');
  $routes->get('company-manage', 'CompanyInfoController::company_info');
  $routes->post('save-company-info', 'CompanyInfoController::save');


  // --- APP SETTINGS ---

  $routes->get('app-settings', 'SettingController::settings');

  // --- PROFILE PHOTO UPLOAD ---
  $routes->post('upload-profile', 'UserController::uploadProfile');
  $routes->post('remove-profile', 'UserController::removeProfile');
  $routes->get('product/(:num)', 'ClientController::single_client/$1');

  $routes->get('login-history', 'AuthController::loging_history');
});


// ==============================
// USER ROUTES (Protected by auth:user filter)
// ==============================
$routes->group('user', ['filter' => 'auth:user'], function ($routes) {

  // --- CLIENT MANAGEMENT ---
  // $routes->get('/', 'ClientController::client_list');
  // $routes->get('client-list', 'ClientController::client_list');
  // $routes->post('client/add', 'ClientController::add_client');
  // $routes->get('client/edit-client/(:num)', 'ClientController::edit_client/$1');
  // $routes->post('client/update/(:num)', 'ClientController::update_client/$1');
  // $routes->post('client/delete/(:num)', 'ClientController::delete_client/$1');
  $routes->get('profile-update/(:num)', 'UserController::profile_update/$1');
  $routes->post('profile-save', 'UserController::save_profile');
  // --- CUSTOMER MANAGEMENT ---
  $routes->get('get-users', 'UserController::getUsers');
  $routes->get('get-clients/(:num)', 'ClientController::getClientsByUser/$1');
  $routes->get('get-customers/(:num)', 'CustomerController::getCustomers/$1');
  $routes->get('/', 'CustomerController::customer_list');
  $routes->post('customer/add', 'CustomerController::add_customer');
  $routes->get('customer/edit/(:num)', 'CustomerController::edit_customer/$1');

  $routes->get('transaction-list', 'TransactionController::transaction_list');
  $routes->post('transaction/add', 'TransactionController::create_transaction');
  $routes->get('transaction/getTransaction/(:num)', 'TransactionController::getTransaction/$1');
  $routes->post('transaction/payNow', 'TransactionController::payNow');
  $routes->post('transaction-delete/(:num)', 'TransactionController::delete_transaction/$1');
  $routes->get('transaction-history/(:num)', 'TransactionController::get_payment_history/$1');
  $routes->get('customer/customer-detail/(:num)', 'CustomerController::customer_detail/$1');
  $routes->get('get-countries', 'UserController::getCountry');
  $routes->get('get-states/(:num)', 'UserController::getStateByCountry/$1');
  $routes->get('get-cities/(:num)', 'UserController::getCityByState/$1');

  // --- APP SETTINGS ---
  $routes->get('app-settings', 'SettingController::settings');

  // --- PROFILE MANAGEMENT ---
  $routes->post('upload-profile', 'UserController::uploadProfile');
  $routes->post('remove-profile', 'UserController::removeProfile');

  $routes->get('change-password', 'AuthController::changed_password');
  $routes->post('update-password', 'AuthController::save_password');
  $routes->get('login-history', 'AuthController::loging_history');
});

// ==================================
// SHARED UPLOAD ROUTE (if needed globally)
// ==================================
$routes->post('/upload-profile', 'UserController::uploadProfile');
