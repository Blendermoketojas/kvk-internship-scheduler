<?php

// v1
use App\Http\Controllers\v1\CompanyController;
use App\Http\Controllers\v1\InternshipController;
use App\Http\Controllers\v1\LoginController;
use App\Http\Controllers\v1\UserInfoController;

// v2
use App\Http\Controllers\v2\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// v2
Route::middleware('api')->prefix('v2')->group(function () {

    // auth
    Route::post('/register', [AuthController::class, 'register'])->name("register");
    Route::post('/login', [AuthController::class, 'login'])->name("login");

    //

    Route::middleware('jwt.auth')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/echo', function (Request $request) {
       return "Dirbam dirbam dirbam";
    });

    Route::middleware('role.level:1,2,3')->group(function () {

    });
});

// v1
// Route::redirect('/', '/Login');
// Route::get('/Login', function () {
//     return view('Login');
// })->name('auth');

//    Route::post('/LoginCheck', [LoginController::class, 'login'])->name('login');
//
//    Route::post('/userinfo', [UserInfoController::class, 'getUserInfo'])->name('userInfo');
//
//    // Route::get('/api/user', 'LoginController@login');
//
//    route::post('/updateprofile', [UserInfoController::class, 'updateUserInfo'])->name('updateProfile');
//
//    route::get('/retrievecompanydata', [CompanyController::class, 'retrieveData'])->name('retrieveCompanyData');
//
//    route::post('/sendevent', [InternshipController::class, 'sendEvent'])->name('sendEvent');
//
//    route::get('/pullevents', [InternshipController::class, 'pullEvents'])->name('pullEvents');
//
//    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
