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
    //return view('welcome');
    return redirect()->route('login');
});

Auth::routes(['verify' => true, 'register' => false]);

Route::middleware('verified', 'permissao:Publicador,Servo,Administrador')
    ->name('home')
    ->get('/home', [App\Http\Controllers\HomeController::class, 'index']);

Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->resource('congregacao', 'App\Http\Controllers\CongregacaoController');

Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->resource('publicacao', 'App\Http\Controllers\PublicacaoController');

Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('permissao', 'App\Http\Controllers\PermissaoController');

Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('user', 'App\Http\Controllers\UserController');

