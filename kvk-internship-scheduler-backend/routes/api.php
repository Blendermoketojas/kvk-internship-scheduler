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
use App\Http\Controllers\v2\ResultGradeController;
use App\Http\Controllers\v2\StudentController;
use App\Http\Controllers\v2\StudentGroupController;
use App\Http\Controllers\v2\UserProfileController;
use App\Http\Controllers\v2\ConversationController;
use App\Http\Controllers\v2\MessageController;
use App\Http\Controllers\v2\UserSearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// dependencies
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;

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
        return "Serveris dirba";
    });

    // auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // routes that are reachable only by authenticated users
    Route::middleware(['jwt.from.cookie', 'jwt.auth'])->group(function () {



        // Register external
        Route::post('/register-external', [AuthController::class, 'registerExternalUser']);

        // Logout
        Route::post('/logout', [AuthController::class, 'logout']);

        // companies
        Route::get('/companies', [CompanyController::class, 'getAllCompanies']);
        Route::post('/search-companies', [CompanyController::class, 'searchCompanies']);
        Route::post('/company', [CompanyController::class, 'getCompany']);
        Route::post('/create-company', [CompanyController::class, 'createCompany']);
        Route::post('/edit-company', [CompanyController::class, 'editCompany']);
        Route::post('/delete-company', [CompanyController::class, 'deleteCompany']);

        // User profiles

        Route::get('/profile', [UserProfileController::class, 'getProfile']);
        Route::put('/profile/update', [UserProfileController::class, 'update']);
        Route::post('/search-profiles-role', [UserProfileController::class, 'searchProfilesByRole']);
        Route::post('/search-profiles', [UserProfileController::class, 'searchUserProfiles']);
        Route::post('/profile/update-picture', [UserProfileController::class, 'updateProfilePicture']);
        Route::post('/profile/id', [UserProfileController::class, 'getUserProfileById']);
        Route::post('/get-group-users-profile', [UserProfileController::class, 'getGroupUsersProfile']);

        // StudentGroups

        Route::post('/search-student-groups', [StudentController::class, 'searchStudentGroups']);
        Route::post('/search-students', [StudentController::class, 'searchStudents']);

        Route::get('/linked-students', [StudentController::class, 'getLinkedStudents']);
        Route::get('/linked-students-internships-active', [
            StudentController::class,
            'getLinkedStudentsActiveInternships'
        ]);

        Route::post('/get-student-group', [StudentGroupController::class, 'getStudentGroup']);
        Route::post('/update-student-group', [StudentGroupController::class, 'updateStudentGroup']);
        Route::post('/delete-student-group', [StudentGroupController::class, 'deleteStudentGroup']);
        Route::post('/create-student-group', [StudentGroupController::class, 'createStudentGroup']);

        // Internships

        Route::get('/internship-active', [InternshipController::class, 'getActiveInternship']);
        Route::get('/internships', [InternshipController::class, 'getCurrentUserInternships']);
        Route::post('/internship', [InternshipController::class, 'getInternship']);
        Route::post('/internships', [InternshipController::class, 'createInternship']);
        Route::post('/user/internships', [InternshipController::class, 'getUserInternships']);
        Route::post('/internships/student-group', [InternshipController::class, 'getStudentGroupInternships']);
        Route::post('/internships/student-group-active', [
            InternshipController::class,
            'getStudentGroupActiveInternships'
        ]);
        Route::post('/search/internship/titles', [InternshipController::class, 'searchInternshipTitles']);
        Route::get('/filter/not-evaluated-internships', [
            InternshipController::class,
            'filterNotEvaluatedInternships'
        ]);

        Route::get('/get-linked-students-inactive-internships', [
            InternshipController::class,
            'getLinkedStudentsInactiveInternshipsService'
        ]);

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
        Route::post('/result/search/template', [ResultFormController::class, 'searchTemplate']);
        Route::post('/result/answer/create', [ResultFormController::class, 'createResult']);
        Route::post('/result/answer/get', [ResultFormController::class, 'getResult']);
        Route::post('/result/internship/templates', [ResultFormController::class, 'getTemplateByInternshipId']);

        // FormsCharts

        Route::post('/charts/templates', [ResultFormController::class, 'getTemplatesFromDateAndStudentGroup']);
        Route::post('/charts/get', [ResultFormController::class, 'getDataFromTemplate']);

        // ResultGrades

        Route::post('/result/grade/create', [ResultGradeController::class, 'createGrade']);
        Route::post('/result/grade/modify', [ResultGradeController::class, 'modifyGrade']);
        Route::post('/result/grade/delete', [ResultGradeController::class, 'deleteGrade']);
        Route::post('/result/grade/get', [ResultGradeController::class, 'getStudentGrades']);

        //Chatting

        Route::post('/conversations', [ConversationController::class, 'createConversation']);
        Route::post('/add-user-to-group', [ConversationController::class, 'addUserToGroup']);
        Route::get('/getUsersConversations', [ConversationController::class, 'getUsersConversations']);
        Route::get('/getGroupConversations', [ConversationController::class, 'getGroupConversations']);
        Route::get('/conversations/{conversationId}/messages', [ConversationController::class, 'getConversationMessages']);

        Route::post('/sendMessage', [MessageController::class, 'sendMessage']);
        Route::post('/deleteMessage', [MessageController::class, 'DeleteMessage']);

        // Internship File Management

        Route::post(
            '/internship/upload-document-with-files',
            [InternshipFileManagementController::class, 'uploadDocumentWithFiles']
        );

        Route::post(
            '/internship/delete-document-with-files',
            [InternshipFileManagementController::class, 'deleteDocumentWithFiles']
        );

        Route::post(
            '/internship/internship/update-document',
            [InternshipFileManagementController::class, 'updateDocument']
        );

        Route::get('/user/internship/documents', [
            InternshipFileManagementController::class,
            'getAllUserInternshipDocuments'
        ]);

        Route::post('/internship/document-download', [
            InternshipFileManagementController::class,
            'downloadInternshipDocument'
        ]);

        Route::post('/internship/documents', [
            InternshipFileManagementController::class,
            'getInternshipDocuments'
        ]);

        Route::post('/internship/documents-files', [
            InternshipFileManagementController::class,
            'getInternshipDocumentsWithFiles'
        ]);

        Route::post('/documents/internship-by-id', [
            InternshipFileManagementController::class,
            'getDocumentsByInternshipId'
        ]);

        // File Management

        Route::post('/file/delete', [FileController::class, 'deleteFile']);
        Route::post('/files/create', [FileController::class, 'createFiles']);
    });
});
