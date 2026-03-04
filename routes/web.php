<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeanController;
use App\Http\Controllers\DcaController;
use App\Http\Controllers\LogOutController;
use App\Http\Controllers\AcademicChargeController;
use App\Http\Controllers\SenenController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',[LoginController::class,"login"])->name("login");
Route::get("/auth/callback",[LoginController::class,"handdleCallBack"]);

 

Route::middleware(['role:Alumno'])->group(function() {

Route::get('/studentDashboard', [StudentController::class,"studentDashboard"])->name("studentDashboard");
Route::get('/studentEvaluation', [StudentController::class,"studentEvaluation"])->name("studentEvaluation");
Route::post('/studentSubmit/{courseId}', [StudentController::class,"studentSubmit"])->name("studentSubmit");
Route::get('/studentThankyou', [StudentController::class,"studentThanks"])->name("studentThanks");
});


Route::middleware(['role:Docente Coordinador de Área'])->group(function() {

Route::get('/adminDcaDashboard', [DcaController::class, "dcaDashboard"])->name("adminDcaDashboard");
Route::get('/adminDcaResults', [DcaController::class, "dcaResults"])->name("adminDcaResults");
Route::get('/adminDcaStudentView/{sectionId}', [DcaController::class, "dcaStudentView"])->name("adminDcaStudentView");
Route::get('/adminDcaViewAnswer/{submitId}',[DcaController::class, "dcaViewAnswer"])->name("adminDcaViewAnswer");
});


Route::middleware(['role:Director de Docencia'])->group(function() {
    Route::controller(AdminController::class)->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('/Dashboard', 'adminDashboard')->name('adminDashboard');
            Route::get('/Evaluation', 'adminEvaluation')->name('adminEvaluation');
            Route::get('/NewEvaluation', 'adminNewEvaluation')->name('adminNewEvaluation');
            Route::post('/NewEvaluation/create', 'createNewEvaluation')->name('createNewEvaluation');
            Route::get('/EvaluationEdit/{id}', 'adminEvaluationEdit')->name('adminEvaluationEdit');
            Route::post('/UpdateOrReuse', 'adminUpdateOrReuse')->name('adminUpdateOrReuse');
            Route::get('/Delete/{surveyId}', 'adminDelete')->name('adminDelete');
            Route::get('/EvaluationEdit/', 'adminEvaluationEdited')->name('adminEvaluationEdited');
            Route::get('/EnableEvaluation/{surveyId}', 'enableEvaluation')->name('enableEvaluation');
            Route::get('/UnableEvaluation/{surveyId}', 'UnableEvaluation')->name('unableEvaluation');
            Route::get('/ReUseSurvey', 'reUseSurvey')->name('reUseSurvey');
            Route::get('/Results', 'adminResults')->name('adminResults');
            Route::get('/StudentView/{courseId}', 'adminStudentView')->name('adminStudentView');
            Route::get('/ViewAnswer/{submitId}', 'adminViewAnswer')->name('adminViewAnswer');
            Route::get('/Evaluation/search', 'evaluationSearch')->name('adminEvaluationSearch');
            Route::get('/Results/search', 'resultSearch')->name('adminResultSearch');
            Route::get('/Student/search', 'studentSearch')->name('adminStudentSearch');
            Route::get('/ControlCourses', 'adminControlCourses')->name('adminControlCourses');
            Route::post('/ControlCourses/block/{sectionId}', 'blockCourse')->name('blockCourse');
            Route::post('/ControlCourses/unblock/{sectionId}', 'unblockCourse')->name('unblockCourse');
            Route::get('/SearchCourses', 'searchCourse')->name('searchCourse');
            Route::get('/pdf', 'exportarResultadosPDF')->name('admin.adminPDF');
            Route::get('/excel', 'adminResultsExcel')->name('reporte.adminResultsExcel');
            Route::get('/badIndicator/{sectionId}', 'negativeIndicator')->name('negativeIndicator');
        });
    });
   Route::get('/academicCharge',[AcademicChargeController::class, "charge"])->name("charge");  
});
  

Route::middleware(['role:Decano de Facultad'])->group(function() {
    Route::controller(DeanController::class)->group(function () {
        Route::get('/deanDashboard', 'deanDashboard')->name('deanDashboard');
        Route::get('/deanResults/{schoolId}', 'deanResults')->name('deanResults');
        Route::get('/deanSchools', 'deanSchools')->name('deanSchools');
        Route::get('/deanStudentView/{sectionId}', 'deanStudentView')->name('deanStudentView');
        Route::get('/deanViewAnswer/{submitId}', 'deanViewAnswer')->name('deanViewAnswer');
        Route::get('/deanSchools/Filter', 'deanSchoolFilter')->name('deanSchoolFilter');
        Route::get('/deanResult/Filter', 'deanResultsFilter')->name('deanResultsFilter');
        Route::get('/deanLastFive', 'deanLastFive')->name('deanLastFive');    
        Route::get('/dean/pdf','deanSchoolPDF')->name('dean.deanSchoolPDF');
        Route::get('/dean/excel', 'deanSchoolExcel')->name('reporte.deanSchoolExcel');
});
});

Route::middleware(['role:Vicerrector Académico'])->group(function () {
    Route::prefix('senen')->controller(SenenController::class)->group(function () {
            Route::get('/Dashboard', 'deanDashboard')->name('senen.dashboard');
            Route::get('/Results/{schoolId}', 'deanResults')->name('senen.results');
            Route::get('/Schools', 'deanSchools')->name('senen.schools');
            Route::get('/StudentView/{sectionId}', 'deanStudentView')->name('senen.studentView');
            Route::get('/ViewAnswer/{submitId}', 'deanViewAnswer')->name('senen.viewAnswer');
            Route::get('/Schools/Filter', 'deanSchoolFilter')->name('senen.schools.filter');
            Route::get('/Result/Filter', 'deanResultsFilter')->name('senen.results.filter');
            Route::get('/LastFive', 'deanLastFive')->name('senen.lastFive');
            Route::get('/pdf', 'deanSchoolPDF')->name('senen.school.pdf');
            Route::get('/excel', 'deanSchoolExcel')->name('senen.school.excel');

        });
});




Route::middleware(['role:Director de Escuela'])->group(function() {

    Route::get('/directorDashboard', [DirectorController::class, "directorDashboard"])->name("directorDashboard");
    Route::get('/directorResults', [DirectorController::class, "directorResults"])->name("directorResults");
    Route::get('/directorStudentView/{sectionId}', [DirectorController::class, "directorStudentView"])->name("directorStudentView");
    Route::get('/directorViewAnswer/{submitId}', [DirectorController::class, "directorViewAnswer"])->name("directorViewAnswer");
    Route::get('/directorFilter', [DirectorController::class, "directorFilter"])->name("directorFilter");
    Route::get('/director/pdf', [DirectorController::class, 'directorPDF'])->name('director.directorPDF');
    Route::get('/director/excel', [DirectorController::class, 'directorResultsExcel'])->name('reporte.directorResultsExcel');

});
    

#Ruta Log Out
Route::get("/logOut",[LogOutController::class,"logOut"])->name("logOut");

#ruta no autorizado
Route::get('/unauthorized',[LoginController::class,"unauthorized"])->name('unauthorized');
#Ruta sesion muerta
Route::get('/endedSession',[LoginController::class,"sessionDead"])->name('sessionDead');
