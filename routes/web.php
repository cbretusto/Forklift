<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExportDataController;
use App\Http\Controllers\ForkliftRequestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/user_management', function () {
    return view('user_management');
})->name('user_management');

Route::get('/forklift_request', function () {
    return view('forklift_request');
})->name('forklift_request');

Route::get('/export_report', function () {
    return view('export_report');
})->name('export_report');


/**
 * USER CONTROLLER
 * Note: always use snake case naming convention to route & route name then set camel case to the method for best practice
 * Reply: Noted Sir Jannus manyak
**/

Route::get('/get_user_log', [UserController::class, 'getUserLog'])->name('get_user_log');
Route::get('/view_users', [UserController::class, 'viewUsers'])->name('view_users');
Route::get('/get_employee_no', [UserController::class, 'getEmployeeID'])->name('get_employee_no');
Route::get('/get_employee_info', [UserController::class, 'getEmployeeInfo'])->name('get_employee_info');
Route::post('/add_edit_user', [UserController::class, 'addEditUser'])->name('add_edit_user');
Route::get('/get_user_info_by_id', [UserController::class, 'getUserInfoById'])->name('get_user_info_by_id');
Route::post('/change_user_stat', [UserController::class, 'changeUserStat'])->name('change_user_stat');
Route::get('/view_approver_list', [UserController::class, 'viewApproverList'])->name('view_approver_list');
Route::get('/get_approver_name', [UserController::class, 'getApproverName'])->name('get_approver_name');
Route::post('/add_edit_approver', [UserController::class, 'addEditApprover'])->name('add_edit_approver');
Route::get('/get_user_approver_info_by_id', [UserController::class, 'getUserApproverInfoById'])->name('get_user_approver_info_by_id');

Route::get('/view_forklift_request', [ForkliftRequestController::class, 'viewRequest'])->name('view_forklift_request');
Route::get('/view_approved_forklift_request', [ForkliftRequestController::class, 'viewApprovedRequest'])->name('view_approved_forklift_request');
Route::get('/view_disapproved_forklift_request', [ForkliftRequestController::class, 'viewDisapprovedRequest'])->name('view_disapproved_forklift_request');
Route::get('/view_cancelled_forklift_request', [ForkliftRequestController::class, 'viewCancelledRequest'])->name('view_cancelled_forklift_request');
Route::get('/view_served_forklift_request', [ForkliftRequestController::class, 'viewServedRequest'])->name('view_served_forklift_request');
Route::get('/get_request_info', [ForkliftRequestController::class, 'getRequestInfo'])->name('get_request_info');
Route::get('/get_clerk_supervisor_secthead', [ForkliftRequestController::class, 'getClerkSupervisorSectionHead'])->name('get_clerk_supervisor_secthead');
Route::get('/get_forklift_operator', [ForkliftRequestController::class, 'getForkliftOperator'])->name('get_forklift_operator');
Route::post('/add_edit_forklift_request', [ForkliftRequestController::class, 'addEditForkliftRequest'])->name('add_edit_forklift_request');
Route::get('/get_forklift_request_info_by_id', [ForkliftRequestController::class, 'getForkliftRequestApproverInfoById'])->name('get_forklift_request_info_by_id');
Route::post('/forklift_request_approval', [ForkliftRequestController::class, 'forkliftRequestApproval'])->name('forklift_request_approval');

// EXPORT DATA
Route::get('/export/{from}/{to}', [ExportDataController::class, 'export']);

