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
Route::post('/adminControlCourses/block/{courseId}', [AdminController::class,"blockCourse"])->name("blockCourse");
Route::post('/adminControlCourses/unblock/{courseId}', [AdminController::class,"unblockCourse"])->name("unblockCourse");
Route::get('/adminSearchCourses', [AdminController::class,"searchCourse"])->name("searchCourse");

#Rutas decanos
Route::get('/deanDashboard', [DeanController::class, "deanDashboard"])->name("deanDashboard");
Route::get('/deanResults/{schoolId}', [DeanController::class, "deanResults"])->name("deanResults");
Route::get('/deanSchools', [DeanController::class, "deanSchools"])->name("deanSchools");
Route::get('/deanStudentView/{courseId}', [DeanController::class, "deanStudentView"])->name("deanStudentView");
Route::get("/deanViewAnswer/{submitId}", [DeanController::class, "deanViewAnswer"])->name("deanViewAnswer");

#Rutas directores
Route::get('/directorDashboard', [DirectorController::class, "directorDashboard"])->name("directorDashboard");
Route::get('/directorResults', [DirectorController::class, "directorResults"])->name("directorResults");
Route::get('/directorStudentView/{courseId}', [DirectorController::class, "directorStudentView"])->name("directorStudentView");
Route::get('/directorViewAnswer/{submitId}', [DirectorController::class, "directorViewAnswer"])->name("directorViewAnswer");
Route::get('/directorFilter', [DirectorController::class, "directorFilter"])->name("directorFilter");

#Ruta Log Out
Route::get("/logOut",[LogOutController::class,"logOut"])->name("logOut");




#Ruta PDF
Route::get('/exportar-resultados', [App\Http\Controllers\AdminController::class, 'exportarResultadosPDF'])->name('admin.exportarPDF');
Route::get('/director/pdf', [DirectorController::class, 'directorPDF'])->name('director.directorPDF');
Route::get('/dean/pdf', [DeanController::class, 'deanSchoolPDF'])->name('dean.deanSchoolPDF');

#Ruta Excel
Route::get('/exportar-escuelas', [DeanController::class, 'deanSchoolExcel'])->name('reporte.deanSchoolExcel');
Route::get('/exportar-resultados', [DirectorController::class, 'directorResultsExcel'])->name('reporte.directorResultsExcel');

