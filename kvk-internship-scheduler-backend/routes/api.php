<?php

// v1
//use App\Http\Controllers\v1\CompanyController;
//use App\Http\Controllers\v1\InternshipController;
//use App\Http\Controllers\v1\LoginController;
//use App\Http\Controllers\v1\UserInfoController;

// v2
// controllers
use App\Http\Controllers\v2\Auth\AuthController;
use App\Http\Controllers\v2\CompanyController;
use App\Http\Controllers\v2\InternshipController;
use App\Http\Controllers\v2\StudentController;
use App\Http\Controllers\v2\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// dependencies

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
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // resources
    Route::resource('/companies', CompanyController::class);
    Route::post('/search-companies', [CompanyController::class, 'searchCompanies']);

    // routes that are reachable only by authenticated users
    Route::middleware(['jwt.from.cookie', 'jwt.auth'])->group(function () {

        // Logout
        Route::post('/logout', [AuthController::class, 'logout']);

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

        // Internships

        Route::post('/internship', [InternshipController::class, 'getInternship']);
        Route::get('/internship-active', [InternshipController::class, 'getActiveInternship']);

        Route::resource('/internships', InternshipController::class);
        Route::post('/internships/student-group', [InternshipController::class, 'getStudentGroupInternships']);
        Route::post('/internships/student-group-active', [InternshipController::class, 'getStudentGroupActiveInternships']);

    });
});
