<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {return inertia('Index'); });

Route::middleware(['auth'])->group(function () {
    Route::resource('/home', App\Http\Controllers\HomeController::class);

    Route::prefix('staffs')->group(function(){
        Route::resource('/lists', App\Http\Controllers\Staff\ListController::class);
        Route::resource('/roles', App\Http\Controllers\Staff\RoleController::class);
    });

    Route::prefix('scholars')->group(function(){
        Route::resource('/listing', App\Http\Controllers\Scholar\ListController::class);
        Route::resource('/qualifiers', App\Http\Controllers\Scholar\QualifierController::class);
        Route::resource('/endorsements', App\Http\Controllers\Scholar\EndorsementController::class);
        Route::controller(App\Http\Controllers\Scholar\ListController::class)->group(function () {
            Route::get('/export','export');
        });
    });

    Route::resource('/schools', App\Http\Controllers\SchoolController::class);

    Route::prefix('logs')->group(function(){
        Route::controller(App\Http\Controllers\LogController::class)->group(function () {
            Route::get('/authentication','authentication');
            Route::get('/activities','activity');
        });
    });

    Route::prefix('settings')->group(function(){
        Route::controller(App\Http\Controllers\SettingController::class)->group(function () {
            Route::get('/backups','backups');
            Route::get('/backups/download/{file}','download');
        });
    });
});

require __DIR__.'/authentication.php';
require __DIR__.'/installation.php';
require __DIR__.'/lists.php';
