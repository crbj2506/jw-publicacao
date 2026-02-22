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
Route::middleware('verified', 'permissao:,,Administrador')
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

// Rotas de Filtro para User
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('user.filtrada.post')
    ->post('userFiltrado', [App\Http\Controllers\UserController::class, 'index']);
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('user.filtrada.get')
    ->get('userFiltrado', [App\Http\Controllers\UserController::class, 'index']);

// ###########################
// ##  CONJUNTO ENVIO
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('envio', 'App\Http\Controllers\EnvioController');

// Rotas de Filtro para Envio
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('envio.filtrada.post')
    ->post('envioFiltrado', [App\Http\Controllers\EnvioController::class, 'index']);
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('envio.filtrada.get')
    ->get('envioFiltrado', [App\Http\Controllers\EnvioController::class, 'index']);

// ###########################
// ##  CONJUNTO VOLUME
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('volume', 'App\Http\Controllers\VolumeController');

// Rotas de Filtro para Volume
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('volume.filtrada.post')
    ->post('volumeFiltrado', [App\Http\Controllers\VolumeController::class, 'index']);
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('volume.filtrada.get')
    ->get('volumeFiltrado', [App\Http\Controllers\VolumeController::class, 'index']);

// ###########################
// ##  CONJUNTO CONTEÚDO
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')->resource('conteudo', 'App\Http\Controllers\ConteudoController');
//Rota para POST do Filtro de Conteúdo
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('conteudo.filtrada.post')
    ->post('conteudoFiltrado', [App\Http\Controllers\ConteudoController::class, 'index']);
//Rota para GEST do Filtro de Conteúdo
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('conteudo.filtrada.get')
    ->get('conteudoFiltrado', [App\Http\Controllers\ConteudoController::class, 'index']);

// ###########################
// ##  CONJUNTO LOCAL
// ###########################
Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->resource('local', 'App\Http\Controllers\LocalController');

// Rota para POST do Filtro de Locais
Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('local.filtrada.post')
    ->post('localFiltrado', [App\Http\Controllers\LocalController::class, 'index']);
// Rota para GET do Filtro de Locais
Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('local.filtrada.get')
    ->get('localFiltrado', [App\Http\Controllers\LocalController::class, 'index']);

// ###########################
// ##  CONJUNTO ESTOQUE
// ###########################
Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->resource('estoque', 'App\Http\Controllers\EstoqueController');

//Rota para POST do Filtro de Estoque
Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('estoque.filtrado.post')
    ->post('estoqueFiltrado', [App\Http\Controllers\EstoqueController::class, 'index']);

//Rota para GET do Filtro de Estoque
Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->name('estoque.filtrado.get')
    ->get('estoqueFiltrado', [App\Http\Controllers\EstoqueController::class, 'index']);

// ###########################
// ##  CONJUNTO PESSOA
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('pessoa', 'App\Http\Controllers\PessoaController');

// Rota para POST do Filtro de Pessoas
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('pessoa.filtrada.post')
    ->post('pessoaFiltrada', [App\Http\Controllers\PessoaController::class, 'index']);
// Rota para GET do Filtro de Pessoas
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('pessoa.filtrada.get')
    ->get('pessoaFiltrada', [App\Http\Controllers\PessoaController::class, 'index']);

// ###########################
// ##  CONJUNTO PEDIDO
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('pedido', 'App\Http\Controllers\PedidoController');

// Rotas de Filtro para Pedido
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('pedido.filtrada.post')
    ->post('pedidoFiltrado', [App\Http\Controllers\PedidoController::class, 'index']);
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('pedido.filtrada.get')
    ->get('pedidoFiltrado', [App\Http\Controllers\PedidoController::class, 'index']);

// ###########################
// ##  CONJUNTO INVENTÁRIO
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('inventario', 'App\Http\Controllers\InventarioController');

// Rota para POST do Filtro de Inventários
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('inventario.filtrado.post')
    ->post('inventarioFiltrado', [App\Http\Controllers\InventarioController::class, 'index']);

// Rota para GET do Filtro de Inventários
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('inventario.filtrado.get')
    ->get('inventarioFiltrado', [App\Http\Controllers\InventarioController::class, 'index']);

// Rota Fazer Inventário GET (Informar Congregação, Ano e Mês)
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('inventariar.get')
    ->get('inventariar', [App\Http\Controllers\InventarioController::class, 'inventariar']);

// Rota Fazer Inventário POST (Faz o Inventário)
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('inventariar.post')
    ->post('inventariar', [App\Http\Controllers\InventarioController::class, 'inventariar']);

Route::middleware('verified', 'permissao:,,Administrador')
    ->name('inventario.mostra')
    ->get('inventario/{ano}/{mes}/{congregacao_id}', [App\Http\Controllers\InventarioController::class, 'mostra']);

// ###########################
// ##  CONJUNTO LOG
// ###########################
Route::middleware('verified','permissao:,,Administrador')
    ->get('/log', [App\Http\Controllers\LogController::class, 'index'])
    ->name('log.index');
Route::middleware('verified','permissao:,,Administrador')
    ->post('/logFiltrado', [App\Http\Controllers\LogController::class, 'index'])
    ->name('log.filtrado.post');
Route::middleware('verified','permissao:,,Administrador')
    ->get('/logFiltrado', [App\Http\Controllers\LogController::class, 'index'])
    ->name('log.filtrado.get');

// ###########################
// ##  CONJUNTO AUDITORIA
// ###########################
Route::middleware('verified', 'permissao:,Servo,Administrador')
    ->get('/auditoria', [App\Http\Controllers\AuditoriaController::class, 'index'])
    ->name('auditoria.index');