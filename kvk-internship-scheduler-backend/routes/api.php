<?php

// v1
//use App\Http\Controllers\v1\CompanyController;
//use App\Http\Controllers\v1\InternshipController;
//use App\Http\Controllers\v1\LoginController;
//use App\Http\Controllers\v1\UserInfoController;

// v2
// controllers
use App\Http\Controllers\v2\AuthController;

// dependencies
use App\Http\Controllers\v2\CompanyController;
use App\Http\Controllers\v2\StudentController;
use App\Http\Controllers\v2\UserProfileController;
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
// path -> http://localhost:8000/api/v2/
// for full route list php artisan route:list in the console
Route::middleware('api')->prefix('v2')->group(function () {

    // is server working test
    Route::get('/echo', function (Request $request) {
        return "A jus dirbat? Dirbam dirbam dirbam";
    });

    // auth
    Route::post('/register', [AuthController::class, 'register'])->name("register");
    Route::post('/login', [AuthController::class, 'login'])->name("login");

    // resources
    Route::resource('/companies', CompanyController::class);
    Route::post('/search-companies', [CompanyController::class, 'searchCompanies']);

    // routes that are reachable only by authenticated users
    Route::middleware(['jwt.from.cookie', 'jwt.auth'])->group(function () {

        // User profiles

        Route::get('/profile', [UserProfileController::class, 'getProfile']);
        Route::put('/profile/update', [UserProfileController::class, 'update']);
        Route::post('/search-students', []);

        // StudentGroups

        Route::post('/search-student-groups', [StudentController::class, 'searchStudentGroups']);
        Route::post('/search-students', [StudentController::class, 'searchStudents']);

        // quick cookie test

        Route::get('/echo/auth', function (Request $request) {
            return "Maladec turi sausaini";
        });

        // routes that are reachable by roles ...
        Route::middleware('role.level:1,2,3')->group(function () {

        });

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
