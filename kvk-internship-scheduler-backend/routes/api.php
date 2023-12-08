<?php

// v1
//use App\Http\Controllers\v1\CompanyController;
//use App\Http\Controllers\v1\InternshipController;
//use App\Http\Controllers\v1\LoginController;
//use App\Http\Controllers\v1\UserInfoController;

// v2
// controllers
use App\Http\Controllers\v2\Auth\AuthController;
use App\Http\Controllers\v2\CommentController;
use App\Http\Controllers\v2\CompanyController;
use App\Http\Controllers\v2\FileController;
use App\Http\Controllers\v2\InternshipController;
use App\Http\Controllers\v2\ResultFormController;
use App\Http\Controllers\v2\InternshipFileManagementController;
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

        Route::post('/search-profiles-role', [UserProfileController::class, 'searchProfilesByRole']);
        Route::post('/search-profiles', [UserProfileController::class, 'searchUserProfiles']);
        Route::post('/profile/update-picture', [UserProfileController::class, 'updateProfilePicture']);

        // StudentGroups

        Route::post('/search-student-groups', [StudentController::class, 'searchStudentGroups']);
        Route::post('/search-students', [StudentController::class, 'searchStudents']);

        Route::get('/linked-students', [StudentController::class, 'getLinkedStudents']);

        // Internships

        Route::get('/internship-active', [InternshipController::class, 'getActiveInternship']);
        Route::get('/internships', [InternshipController::class, 'getCurrentUserInternships']);

        Route::post('/internship', [InternshipController::class, 'getInternship']);
        Route::post('/internships', [InternshipController::class, 'createInternship']);
        Route::post('/user/internships', [InternshipController::class, 'getUserInternships']);
        Route::post('/internships/student-group', [InternshipController::class, 'getStudentGroupInternships']);
        Route::post('/internships/student-group-active', [InternshipController::class,
            'getStudentGroupActiveInternships']);
        Route::post('/search/internship/titles', [InternshipController::class, 'searchInternshipTitles']);

        Route::put('/internship-update', [InternshipController::class, 'updateInternship']);

        Route::delete('/internship-delete', [InternshipController::class, 'deleteInternship']);

        // Comments

        Route::post('/internship/comments', [CommentController::class, 'getAllInternshipComments']);
        Route::post('/comments', [CommentController::class, 'createComment']);

        Route::delete('/comments', [CommentController::class, 'deleteComment']);

        Route::put('/comments', [CommentController::class, 'updateComment']);

        // ResultsForms

        Route::post('/result/template/modify', [ResultFormController::class, 'modifyTemplate']);
        Route::post('/result/template/get', [ResultFormController::class, 'getTemplate']);
        Route::post('/result/search/question', [ResultFormController::class, 'searchQuestion']);
        Route::post('/result/search/likert', [ResultFormController::class, 'searchLikert']);
        Route::post('/result/template/attach', [ResultFormController::class, 'attachTemplate']);
        Route::post('/result/template/detach', [ResultFormController::class, 'detachTemplate']);

        // Internship File Management

        Route::post('/internship/upload-document-with-files',
            [InternshipFileManagementController::class, 'uploadDocumentWithFiles']);

        Route::post('/internship/delete-document-with-files',
            [InternshipFileManagementController::class, 'deleteDocumentWithFiles']);

        Route::post('/internship/internship/update-document',
            [InternshipFileManagementController::class, 'updateDocument']);

        Route::get('/user/internship/documents', [InternshipFileManagementController::class,
            'getAllUserInternshipDocuments']);

        Route::post('/internship/document-download', [InternshipFileManagementController::class,
            'downloadInternshipDocument']);

        Route::post('/internship/documents', [InternshipFileManagementController::class,
            'getInternshipDocuments']);

        Route::post('/internship/documents-files', [InternshipFileManagementController::class,
            'getInternshipDocumentsWithFiles']);

        // File Management

        Route::post('/file/delete', [FileController::class, 'deleteFile']);
        Route::post('/files/create', [FileController::class, 'createFiles']);
    });
});
