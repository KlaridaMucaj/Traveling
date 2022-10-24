<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployersController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\TripController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//
//Route::get('/', function () {
//    return view('welcome');
//});


Route::get('/show', [EmployersController::class, 'calculate']);

Route::get('/checkin', [CheckinController::class, 'check']);

Route::get('/usersCheckin', [CheckinController::class, 'getUsersWithCheckins'])->name('usersCheckin');

Route::any('/price', [TripController::class, 'getPrice']);

//Route::get('/view', [CheckinController::class, 'index'])->name('view');

//Route::get('/usersCheckin', ['as'   => 'usersCheckin', 'uses' => function () {
//
//            $users = User::query();
//
//            return DataTables::of($users)->make(true);
//    }
//]);
