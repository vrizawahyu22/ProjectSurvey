<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::name('admin')->group(function () {
    Route::post('admin/login', 'CAdmin@login');
    Route::get('admin/coba', 'CAdmin@coba')->name('admin.coba');
});

Route::get('/register', function()
{
    return View::make('register');
});
