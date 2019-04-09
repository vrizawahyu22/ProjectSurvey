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

Route::post('register', 'MemberController@register');                                   //Register Member
Route::post('login/{jenis}', 'MemberController@login');                                 //Login Admin dan Member

Route::group(['middleware' => 'check-token'], function(){
    Route::delete('delete-akun/{username}', 'MemberController@deleteAkun');             //Delete Akun Admin dan Member
    Route::post('update-profil', 'MemberController@updateProfil');                      //Update Profil Member
    Route::post('upload-foto', 'MemberController@upload');                              //Upload Foto Member
    Route::get('lihat-profil', 'MemberController@lihatProfil');                         //Lihat Profil Member
    Route::get('logout', 'MemberController@logout');                                    //Logout Admin dan Member
    Route::get('verify', 'MemberController@verify')->name('signup.verify');             //Verifikasi Email Member

    Route::post('make-survey', 'SurveyController@makeSurvey');                          //Buat Survey Admin dan Member
    Route::get('lihat-survey', 'SurveyController@getSurveyMember');                           //Lihat Seluruh Survey buat Admin
    Route::get('lihat-survey/{username}', 'SurveyController@getSurveyMember');                //Lihat Survey Bagi Member Tertentu buat Admin dan Member
    Route::get('lihat-survey/{username}/{idsurvey}', 'SurveyController@getSurveyMember');     //Lihat Survey Bagi Member Tertentu dan Survey Tertentu bagi Admin dan Member
    Route::put('edit-survey/{idsurvey}', 'SurveyController@editSurvey');                                //Edit Survey Bagi Member Tertentu dan Survey Tertentu bagi Admin dan Member
    Route::delete('hapus-survey/{idsurvey}', 'SurveyController@hapusSurvey');
    
}); 
Route::get('survey', 'SurveyController@getSurvey');                            //User bisa melihat seluruh survey dan Survey Tertentu
Route::get('survey/{alamat}', 'SurveyController@getSurvey');                            //User bisa melihat seluruh survey dan Survey Tertentu



