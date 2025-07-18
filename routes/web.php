<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeanController;
use App\Http\Controllers\LogOutController;

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


#Rutas admin
Route::get('/adminDashboard', [AdminController::class, "adminDashboard"])->name("adminDashboard");
Route::get('/adminEvaluation', [AdminController::class, "adminEvaluation"])->name("adminEvaluation");
Route::get('/adminNewEvaluation', [AdminController::class, "adminNewEvaluation"])->name("adminNewEvaluation");
Route::post("/adminNewEvaluation/create",[AdminController::class, "createNewEvaluation"])->name("createNewEvaluation");
Route::get('/adminEvaluationEdit/{id}', [AdminController::class, "adminEvaluationEdit"])->name("adminEvaluationEdit");
Route::post('/adminUpdateOrReuse', [AdminController::class, "adminUpdateOrReuse"])->name("adminUpdateOrReuse");
Route::get('/adminDelete/{surveyId}', [AdminController::class, "adminDelete"])->name("adminDelete");
Route::get('/adminEvaluationEdit/', [AdminController::class, "adminEvaluationEdited"])->name("adminEvaluationEdited");
Route::get("/admiEnableEvaluation/{surveyId}",[AdminController::class, "enableEvaluation"])->name("enableEvaluation");
Route::get("/admiUnableEvaluation/{surveyId}",[AdminController::class, "UnableEvaluation"])->name("unableEvaluation");
Route::get("/adminReUseSurvey",[AdminController::class, "reUseSurvey"])->name("reUseSurvey");
Route::get('/adminResults', [AdminController::class, "adminResults"])->name("adminResults");
Route::get('/adminStudentView/{courseId}', [AdminController::class,"adminStudentView"])->name("adminStudentView");
Route::get('/adminViewAnswer/{submitId}',[AdminController::class,"adminViewAnswer"])->name("adminViewAnswer");
Route::get('/adminEvaluation/search', [AdminController::class,"evaluationSearch"])->name("adminEvaluationSearch");
Route::get('/adminResults/search', [AdminController::class,"resultSearch"])->name("adminResultSearch");
Route::get('/adminStudent/search', [AdminController::class,"studentSearch"])->name("adminStudentSearch");
Route::get('/adminControlCourses', [AdminController::class,"adminControlCourses"])->name("adminControlCourses");
Route::post('/adminControlCourses/block/{sectionId}', [AdminController::class,"blockCourse"])->name("blockCourse");
Route::post('/adminControlCourses/unblock/{sectionId}', [AdminController::class,"unblockCourse"])->name("unblockCourse");
Route::get('/adminSearchCourses', [AdminController::class,"searchCourse"])->name("searchCourse");

#Rutas admin DCA
Route::get('/adminDcaDashboard', [AdminController::class, "adminDcaDashboard"])->name("adminDcaDashboard");
Route::get('/adminDcaResults', [AdminController::class, "adminDcaResults"])->name("adminDcaResults");
Route::get('/adminDcaStudentView', [AdminController::class, "adminDcaStudentView"])->name("adminDcaStudentView");

#Rutas decanos
Route::get('/deanDashboard', [DeanController::class, "deanDashboard"])->name("deanDashboard");
Route::get('/deanResults/{schoolId}', [DeanController::class, "deanResults"])->name("deanResults");
Route::get('/deanSchools', [DeanController::class, "deanSchools"])->name("deanSchools");
Route::get('/deanStudentView/{sectionId}', [DeanController::class, "deanStudentView"])->name("deanStudentView");
Route::get("/deanViewAnswer/{submitId}", [DeanController::class, "deanViewAnswer"])->name("deanViewAnswer");
Route::get('/deanSchools/Filter', [DeanController::class, "deanSchoolFilter"])->name("deanSchoolFilter");
Route::get('/deanResult/Filter', [DeanController::class, "deanResultsFilter"])->name("deanResultsFilter");

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
