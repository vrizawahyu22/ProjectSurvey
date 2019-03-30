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
    Route::get('member/detail', 'MemberController@detail');
    Route::delete('member/deleteakun/{id}', 'MemberController@deleteAkun');
    Route::post('member/updateprofil', 'MemberController@updateProfil');
    Route::get('member', 'MemberController@index');
    //kalau nanti ada endpoint yang butuh authentication tinggal dimasukkan di grup ini saja
}); 

