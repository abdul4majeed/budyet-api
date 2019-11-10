<?php

use Illuminate\Http\Request;

Route::post('login', 'UserRegisterationController@login');
Route::post('register', 'UserRegisterationController@register');
Route::group(['middleware' => 'auth:api'], function(){
Route::post('details', 'UserRegisterationController@details');
});