<?php

use Illuminate\Http\Request;

Route::post('/contact/store','ContactUsController@store');
// Route::resource('/contact', 'ContactUsController');//->middleware('cors');

// Route::get('creates', array('middleware' => 'cors', 'uses' => 'ContactUsController@create'));

