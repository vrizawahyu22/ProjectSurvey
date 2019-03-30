<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('member/register', 'MemberController@register');
Route::post('member/login', 'MemberController@login');

Route::group(['middleware' => 'check-token'], function(){
    Route::get('member/delete-akun', 'MemberController@deleteAkun');
    Route::post('member/update-profil', 'MemberController@updateProfil');
    Route::post('member/upload-foto', 'MemberController@upload');
    Route::get('member/lihat-profil', 'MemberController@lihatProfil');
    Route::get('member/logout', 'MemberController@logout');
    Route::get('verify', 'MemberController@verify')->name('signup.verify');
    //kalau nanti ada endpoint yang butuh authentication tinggal dimasukkan di grup ini saja
}); 

