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

// ###########################
// ##  CONJUNTO CONGREGAÇÃO
// ###########################
Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->resource('congregacao', 'App\Http\Controllers\CongregacaoController');

// ###########################
// ##  CONJUNTO PUBLICAÇÃO
// ###########################
Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->resource('publicacao', 'App\Http\Controllers\PublicacaoController');

//Rota para POST do Filtro de Publicações
Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('publicacao.filtrada.post')
    ->post('publicacaoFiltrada', [App\Http\Controllers\PublicacaoController::class, 'index']);
//Rota para GET do Filtro de Publicações
Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('publicacao.filtrada.get')
    ->get('publicacaoFiltrada', [App\Http\Controllers\PublicacaoController::class, 'index']);

// ###########################
// ##  CONJUNTO PERMISÃO
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('permissao', 'App\Http\Controllers\PermissaoController');

// ###########################
// ##  CONJUNTO USER
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('user', 'App\Http\Controllers\UserController');

// ###########################
// ##  CONJUNTO ENVIO
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('envio', 'App\Http\Controllers\EnvioController');

// ###########################
// ##  CONJUNTO VOLUME
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('volume', 'App\Http\Controllers\VolumeController');

// ###########################
// ##  CONJUNTO CONTEÚDO
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('conteudo', 'App\Http\Controllers\ConteudoController');

// ###########################
// ##  CONJUNTO LOCAL
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('local', 'App\Http\Controllers\LocalController');

// ###########################
// ##  CONJUNTO ESTOQUE
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('estoque', 'App\Http\Controllers\EstoqueController');

// ###########################
// ##  CONJUNTO INVENTÁRIO
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('inventario', 'App\Http\Controllers\InventarioController');

// ###########################
// ##  CONJUNTO PESSOA
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('pessoa', 'App\Http\Controllers\PessoaController');

// ###########################
// ##  CONJUNTO PEDIDO
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('pedido', 'App\Http\Controllers\PedidoController');
    
Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('inventario.filtrado.post')
    ->post('inventarioFiltrado', [App\Http\Controllers\InventarioController::class, 'index']);

// Rota para POST do Filtro de Inventários
Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('inventario.filtrado.get')
    ->get('inventarioFiltrado', [App\Http\Controllers\InventarioController::class, 'index']);

// Rota para GET do Filtro de Inventários
Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('inventario.inventariar.get')
    ->get('inventariar', [App\Http\Controllers\InventarioController::class, 'inventariar']);

Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('inventario.inventariar.post')
    ->post('inventariar', [App\Http\Controllers\InventarioController::class, 'inventariar']);

Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('inventario.mostra')
    ->get('inventario/{ano}/{mes}/{congregacao_id}', [App\Http\Controllers\InventarioController::class, 'mostra']);

// ###########################
// ##  CONJUNTO LOG
// ###########################
Route::middleware('verified','permissao:,,Administrador')
    ->get('/log', [App\Http\Controllers\LogController::class, 'index'])
    ->name('log.index');