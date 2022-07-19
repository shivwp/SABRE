<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\HomepageApiController;
use App\Http\Controllers\Api\NotificationsApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// User Login

Route::post('login', [App\Http\Controllers\Api\UserApiController::class, 'userLogin']);

//Use Register API
Route::post('register', [App\Http\Controllers\Api\UserApiController::class, 'userRegister']);
Route::post('register-step-two', [App\Http\Controllers\Api\UserApiController::class, 'userRegisterStepTwo']);
Route::post('register-step-three', [App\Http\Controllers\Api\UserApiController::class, 'userRegisterStepThree']);

//Update User API
Route::post('update-user', [App\Http\Controllers\Api\UserApiController::class, 'userUpdate']);
Route::post('update-user-step-two', [App\Http\Controllers\Api\UserApiController::class, 'userUpdateStepTwo']);
Route::post('update-user-step-three', [App\Http\Controllers\Api\UserApiController::class, 'userUpdateStepThree']);

//Update User Profile
Route::post('update-profile', [App\Http\Controllers\Api\UserApiController::class, 'edituserdetails']);

// Registration OTP Verification
Route::post('registration-otp-verification', [App\Http\Controllers\Api\UserApiController::class, 'registrationOTPVerification']);

//Apply Job
Route::post('apply-job', [App\Http\Controllers\Api\JobApiController::class, 'applyJob']);

// Create New Password
Route::post('create-new-password', [App\Http\Controllers\Api\UserApiController::class, 'createNewPassword']);

//State List
Route::post('states', [App\Http\Controllers\Api\UserApiController::class, 'stateList']);

//Send Otp
Route::post('resend-otp', [App\Http\Controllers\Api\UserApiController::class, 'resendOTP']);

//Verify Otp
Route::post('verify-otp', [App\Http\Controllers\Api\UserApiController::class, 'verifyOTP']);


Route::post('/userforgototp', [App\Http\Controllers\Api\UserApiController::class, 'userforgototp']);
Route::post('/verifyforgototp', [App\Http\Controllers\Api\UserApiController::class, 'verifyforgototp']);

//changepassword
Route::post('change-password', [App\Http\Controllers\Api\UserApiController::class, 'changePassword']);
//forgot Password
Route::post('forgot-otp', [App\Http\Controllers\Api\UserApiController::class, 'sendforgetotp']);
//Reset Password
Route::post('reset-password', [App\Http\Controllers\Api\UserApiController::class, 'resetpassword']);


//HomePage Api
Route::post('home', [App\Http\Controllers\Api\HomepageApiController::class, 'index']);

//User Availability
Route::post('user-availability', [App\Http\Controllers\Api\UserApiController::class, 'availableUser']);

//Update User Availability
Route::post('update-user-availability', [App\Http\Controllers\Api\UserApiController::class, 'UpdateavailableUser']);

//Single Assignment
Route::post('single-assignment', [App\Http\Controllers\Api\HomepageApiController::class, 'singleJob']);

//User Assignment
Route::post('user-assignment', [App\Http\Controllers\Api\JobApiController::class, 'userAssignment']);

//Add User Expense
Route::post('add-user-Expense', [App\Http\Controllers\Api\UserApiController::class, 'addUserExpense']);

//User Expense
Route::post('user-Expense', [App\Http\Controllers\Api\UserApiController::class, 'userExpense']);

//Job Category
Route::post('job-category', [App\Http\Controllers\Api\JobApiController::class, 'jobCategory']);

//SOP List
Route::post('sop-list', [App\Http\Controllers\Api\HomepageApiController::class, 'faq_list']);

//Save Certificate Subscription
Route::post('save-certificate-subscription', [App\Http\Controllers\Api\NotificationsApiController::class, 'save_cerificate_subscription']);

//Send Notification
Route::post('send-notification', [App\Http\Controllers\Api\NotificationsApiController::class, 'sendNotification']);


Route::middleware('auth:api')->group(function () {

    Route::post('my-account', [App\Http\Controllers\Api\UserApiController::class, 'userdetails']);

    //Notification
    Route::post('notification-list', [App\Http\Controllers\Api\NotificationsApiController::class, 'index']);

    // Users
    Route::apiResource('users', 'UsersApiController');

    //ticket-category
    Route::post('my-account', [App\Http\Controllers\Api\UserApiController::class, 'userdetails']);
    

    //feedback
    Route::post('feedback', [App\Http\Controllers\Api\ProductApiController::class, 'feedbacksave']);
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('optimize');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "Cache is cleared";
});
