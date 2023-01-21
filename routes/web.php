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

//Route::middleware('verified', 'permissao:,Servo,Administrador')
//    ->name('publicacao.index')
//    ->post('publicacao', [App\Http\Controllers\PublicacaoController::class, 'index']);

Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('permissao', 'App\Http\Controllers\PermissaoController');

Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('user', 'App\Http\Controllers\UserController');

Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('envio', 'App\Http\Controllers\EnvioController');

Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('volume', 'App\Http\Controllers\VolumeController');

Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('conteudo', 'App\Http\Controllers\ConteudoController');

Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('local', 'App\Http\Controllers\LocalController');

Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('estoque', 'App\Http\Controllers\EstoqueController');



Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('inventario.mostra')
    ->get('inventario/{ano}/{mes}/{congregacao_id}', [App\Http\Controllers\InventarioController::class, 'mostra']);

Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('inventario', 'App\Http\Controllers\InventarioController');
/*
Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('inventario.store')
    ->post('inventario/{congregacao}', [App\Http\Controllers\InventarioController::class, 'store']);

Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('inventario.show')
    ->get('inventario/{congregacao}', [App\Http\Controllers\InventarioController::class, 'show']);

Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('inventario.edit')
    ->get('inventario/{congregacao}/edit', [App\Http\Controllers\InventarioController::class, 'edit']);

Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('inventario.update')
    ->put('inventario/{congregacao}/edit', [App\Http\Controllers\InventarioController::class, 'update']);

Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('inventario.index')
    ->get('inventario', [App\Http\Controllers\InventarioController::class, 'index']);
*/