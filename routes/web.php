<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeanController;

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
Route::get('/studentThankyou', [StudentController::class,"studentThanks"])->name("studentThanks");

#Rutas admin
Route::get('/adminDashboard', [AdminController::class, "adminDashboard"])->name("adminDashboard");
Route::get('/adminEvaluation', [AdminController::class, "adminEvaluation"])->name("adminEvaluation");
Route::get('/adminNewEvaluation', [AdminController::class, "adminNewEvaluation"])->name("adminNewEvaluation");
Route::post("/adminNewEvaluation/create",[AdminController::class, "createNewEvaluation"])->name("createNewEvaluation");
Route::get('/adminEvaluationEdit', [AdminController::class, "adminEvaluationEdit"])->name("adminEvaluationEdit");
Route::get('/adminResults', [AdminController::class, "adminResults"])->name("adminResults");
Route::get('/adminStudent', [AdminController::class,"adminStudent"])->name("adminStudent");