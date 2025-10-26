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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',[LoginController::class,"login"])->name("login");
Route::get("/auth/callback",[LoginController::class,"handdleCallBack"]);

 Route::middleware(['role:CARLOS DANIEL PALMA ANTUNEZ'])->group(function() {
 Route::get("/Student",[StudentController::class,"studentHome"])->name("studentHome");
 });

Route::middleware(['role:Carlos Palma'])->group(function() {
 Route::get("/Director",[DirectorController::class,"directorHome"])->name("directorHome");
 });

Route::middleware(['role:Alexa Gómez'])->group(function() {
 Route::get("/Admin",[AdminController::class,"adminHome"])->name("adminHome");
 });
 Route::middleware(['role:Michelle Medina'])->group(function() {
 Route::get("/Dean",[DeanController::class,"deanHome"])->name("deanHome");
 });


#Rutas estudiantes
Route::get('/studentDashboard', [StudentController::class,"studentDashboard"])->name("studentDashboard");
Route::get('/studentEvaluation', [StudentController::class,"studentEvaluation"])->name("studentEvaluation");
Route::post('/studentSubmit/{courseId}', [StudentController::class,"studentSubmit"])->name("studentSubmit");
Route::get('/studentThankyou', [StudentController::class,"studentThanks"])->name("studentThanks");


#Rutas admin DCA
Route::get('/adminDcaDashboard', [DcaController::class, "dcaDashboard"])->name("adminDcaDashboard");
Route::get('/adminDcaResults', [DcaController::class, "dcaResults"])->name("adminDcaResults");
Route::get('/adminDcaStudentView/{sectionId}', [DcaController::class, "dcaStudentView"])->name("adminDcaStudentView");
Route::get('/adminDcaViewAnswer/{submitId}',[DcaController::class, "dcaViewAnswer"])->name("adminDcaViewAnswer");

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
    });
});
Route::get('/academicCharge',[AcademicChargeController::class, "charge"])->name("charge");


Route::controller(DeanController::class)->group(function () {
    Route::get('/deanDashboard', 'deanDashboard')->name('deanDashboard');
    Route::get('/deanResults/{schoolId}', 'deanResults')->name('deanResults');
    Route::get('/deanSchools', 'deanSchools')->name('deanSchools');
    Route::get('/deanStudentView/{sectionId}', 'deanStudentView')->name('deanStudentView');
    Route::get('/deanViewAnswer/{submitId}', 'deanViewAnswer')->name('deanViewAnswer');
    Route::get('/deanSchools/Filter', 'deanSchoolFilter')->name('deanSchoolFilter');
    Route::get('/deanResult/Filter', 'deanResultsFilter')->name('deanResultsFilter');
    Route::get('/deanLastFive', 'deanLastFive')->name('deanLastFive');
});
#Rutas decanos
/*
Route::get('/deanDashboard', [DeanController::class, "deanDashboard"])->name("deanDashboard");
Route::get('/deanResults/{schoolId}', [DeanController::class, "deanResults"])->name("deanResults");
Route::get('/deanSchools', [DeanController::class, "deanSchools"])->name("deanSchools");
Route::get('/deanStudentView/{sectionId}', [DeanController::class, "deanStudentView"])->name("deanStudentView");
Route::get("/deanViewAnswer/{submitId}", [DeanController::class, "deanViewAnswer"])->name("deanViewAnswer");
Route::get('/deanSchools/Filter', [DeanController::class, "deanSchoolFilter"])->name("deanSchoolFilter");
Route::get('/deanResult/Filter', [DeanController::class, "deanResultsFilter"])->name("deanResultsFilter");
Route::get("/deanLastFive",[DeanController::class, "deanLastFive"])->name("deanLastFive");
*/
#Rutas directores
Route::get('/directorDashboard', [DirectorController::class, "directorDashboard"])->name("directorDashboard");
Route::get('/directorResults', [DirectorController::class, "directorResults"])->name("directorResults");
Route::get('/directorStudentView/{sectionId}', [DirectorController::class, "directorStudentView"])->name("directorStudentView");
Route::get('/directorViewAnswer/{submitId}', [DirectorController::class, "directorViewAnswer"])->name("directorViewAnswer");
Route::get('/directorFilter', [DirectorController::class, "directorFilter"])->name("directorFilter");

#Ruta Log Out
Route::get("/logOut",[LogOutController::class,"logOut"])->name("logOut");


#Ruta PDF
Route::get('/admin/pdf', [AdminController::class, 'exportarResultadosPDF'])->name('admin.adminPDF');
Route::get('/director/pdf', [DirectorController::class, 'directorPDF'])->name('director.directorPDF');
Route::get('/dean/pdf', [DeanController::class, 'deanSchoolPDF'])->name('dean.deanSchoolPDF');

#Ruta Excel
Route::get('/dean/excel', [DeanController::class, 'deanSchoolExcel'])->name('reporte.deanSchoolExcel');
Route::get('/director/excel', [DirectorController::class, 'directorResultsExcel'])->name('reporte.directorResultsExcel');
Route::get('/admin/excel', [AdminController::class, 'adminResultsExcel'])->name('reporte.adminResultsExcel');

#ruta no autorizado
Route::get('/unauthorized',[LoginController::class,"unauthorized"])->name('unauthorized');
#Ruta sesion muerta
Route::get('/endedSession',[LoginController::class,"sessionDead"])->name('sessionDead');
