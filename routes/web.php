<?php

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

// ログイン用のルート
Route::get('login', function() {return view('auth.login', [
    'info' => session('info'),
    'myerror' => session('myerror')
]);})->name('login');
Route::post('login', 'Auth\LoginController@login');

// ユーザー登録用のルート
Route::get('register', function() {return view('auth.register');});
Route::post('register', 'Auth\RegisterController@register');

Route::get('/home', 'HomeController@index');
