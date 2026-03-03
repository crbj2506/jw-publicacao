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

Route::middleware('verified', 'permissao:Publicador,Servo,Ancião,Administrador')
    ->name('home')
    ->get('/home', [App\Http\Controllers\HomeController::class, 'index']);

// ###########################
// ##  CONJUNTO CONGREGAÇÃO
// ###########################
Route::middleware('verified', 'permissao:,,Administrador')
    ->resource('congregacao', 'App\Http\Controllers\CongregacaoController');
    
// Definir congregação ativa (apenas Admin)
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('congregacao.ativa.set')
    ->post('congregacao/ativa', [App\Http\Controllers\CongregacaoController::class, 'setAtiva']);

// Limpar congregação ativa (apenas Admin)
Route::middleware('verified', 'permissao:,,Administrador')
    ->name('congregacao.ativa.reset')
    ->post('congregacao/ativa/reset', [App\Http\Controllers\CongregacaoController::class, 'resetAtiva']);

// ###########################
// ##  CONJUNTO PUBLICAÇÃO
// ###########################
Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
    ->resource('publicacao', 'App\Http\Controllers\PublicacaoController');

//Rota para POST do Filtro de Publicações
Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
    ->name('publicacao.filtrada.post')
    ->post('publicacaoFiltrada', [App\Http\Controllers\PublicacaoController::class, 'index']);
//Rota para GET do Filtro de Publicações
Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
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
Route::middleware('verified', 'permissao:Administrador,Ancião')
    ->resource('user', 'App\Http\Controllers\UserController');

// Rotas de Filtro para User
Route::middleware('verified', 'permissao:Administrador,Ancião')
    ->name('user.filtrada.post')
    ->post('userFiltrado', [App\Http\Controllers\UserController::class, 'index']);
Route::middleware('verified', 'permissao:Administrador,Ancião')
    ->name('user.filtrada.get')
    ->get('userFiltrado', [App\Http\Controllers\UserController::class, 'index']);

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
// ##  ENVIO HIERARCHY (NOVO - Accordion)
// ###########################
Route::middleware('verified', 'permissao:Ancião,Administrador')->group(function () {
    // View do accordion
    Route::get('envio', [App\Http\Controllers\EnvioHierarchyController::class, 'index'])
        ->name('envio.index');
    Route::redirect('envio-hierarchy', 'envio');
    
    // API endpoints para AJAX
    Route::get('/api/envio/search', [App\Http\Controllers\EnvioHierarchyController::class, 'search'])
        ->name('envio.search');
    
    // Envio CRUD
    Route::post('/api/envio/envio', [App\Http\Controllers\EnvioHierarchyController::class, 'storeEnvio'])
        ->name('envio.store');
    Route::put('/api/envio/envio/{envio}', [App\Http\Controllers\EnvioHierarchyController::class, 'updateEnvio'])
        ->name('envio.update');
    Route::delete('/api/envio/envio/{envio}', [App\Http\Controllers\EnvioHierarchyController::class, 'destroyEnvio'])
        ->name('envio.destroy');
    
    // Volume CRUD
    Route::post('/api/envio/volume', [App\Http\Controllers\EnvioHierarchyController::class, 'storeVolume'])
        ->name('envio.volume.store');
    Route::put('/api/envio/volume/{volume}', [App\Http\Controllers\EnvioHierarchyController::class, 'updateVolume'])
        ->name('envio.volume.update');
    Route::delete('/api/envio/volume/{volume}', [App\Http\Controllers\EnvioHierarchyController::class, 'destroyVolume'])
        ->name('envio.volume.destroy');
    
    // Conteudo CRUD
    Route::post('/api/envio/conteudo', [App\Http\Controllers\EnvioHierarchyController::class, 'storeConteudo'])
        ->name('envio.conteudo.store');
    Route::put('/api/envio/conteudo/{conteudo}', [App\Http\Controllers\EnvioHierarchyController::class, 'updateConteudo'])
        ->name('envio.conteudo.update');
    Route::delete('/api/envio/conteudo/{conteudo}', [App\Http\Controllers\EnvioHierarchyController::class, 'destroyConteudo'])
        ->name('envio.conteudo.destroy');
    
    // Publicacao quick create
    Route::post('/api/envio/publicacao', [App\Http\Controllers\EnvioHierarchyController::class, 'storePublicacao'])
        ->name('envio.publicacao.store');
});

// ###########################
// ##  CONJUNTO LOCAL
// ###########################
Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
    ->resource('local', 'App\Http\Controllers\LocalController');

// Rota para POST do Filtro de Locais
Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
    ->name('local.filtrada.post')
    ->post('localFiltrado', [App\Http\Controllers\LocalController::class, 'index']);
// Rota para GET do Filtro de Locais
Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
    ->name('local.filtrada.get')
    ->get('localFiltrado', [App\Http\Controllers\LocalController::class, 'index']);

// ###########################
// ##  CONJUNTO ESTOQUE
// ###########################
Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
    ->name('estoque.rapido')
    ->get('estoque/rapido', [App\Http\Controllers\EstoqueController::class, 'rapido']);

Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
    ->name('estoque.old')
    ->get('estoque-old', [App\Http\Controllers\EstoqueController::class, 'indexOld']);

Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
    ->resource('estoque', 'App\Http\Controllers\EstoqueController');

//Rota para POST do Filtro de Estoque
Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
    ->name('estoque.filtrado.post')
    ->post('estoqueFiltrado', [App\Http\Controllers\EstoqueController::class, 'indexOld']);

//Rota para GET do Filtro de Estoque
Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
    ->name('estoque.filtrado.get')
    ->get('estoqueFiltrado', [App\Http\Controllers\EstoqueController::class, 'indexOld']);

// ###########################
// ##  CONJUNTO PESSOA
// ###########################
Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
    ->resource('pessoa', 'App\Http\Controllers\PessoaController');

// Rota para restaurar pessoa deletada
Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
    ->name('pessoa.restore')
    ->post('pessoa/{id}/restore', [App\Http\Controllers\PessoaController::class, 'restore']);

// Rota para POST do Filtro de Pessoas
Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
    ->name('pessoa.filtrada.post')
    ->post('pessoaFiltrada', [App\Http\Controllers\PessoaController::class, 'index']);
// Rota para GET do Filtro de Pessoas
Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
    ->name('pessoa.filtrada.get')
    ->get('pessoaFiltrada', [App\Http\Controllers\PessoaController::class, 'index']);

// ###########################
// ##  CONJUNTO PEDIDO
// ###########################
Route::middleware('verified', 'permissao:Publicador,Servo,Ancião,Administrador')
    ->resource('pedido', 'App\Http\Controllers\PedidoController');

// Rotas de Filtro para Pedido
Route::middleware('verified', 'permissao:Publicador,Servo,Ancião,Administrador')
    ->name('pedido.filtrada.post')
    ->post('pedidoFiltrado', [App\Http\Controllers\PedidoController::class, 'index']);
Route::middleware('verified', 'permissao:Publicador,Servo,Ancião,Administrador')
    ->name('pedido.filtrada.get')
    ->get('pedidoFiltrado', [App\Http\Controllers\PedidoController::class, 'index']);

// ###########################
// ##  CONJUNTO INVENTÁRIO
// ###########################
Route::middleware('verified', 'permissao:,Servo,Ancião,Administrador')
    ->resource('inventario', 'App\Http\Controllers\InventarioController');

// Rota para POST do Filtro de Inventários
Route::middleware('verified', 'permissao:,Servo,Ancião,Administrador')
    ->name('inventario.filtrado.post')
    ->post('inventarioFiltrado', [App\Http\Controllers\InventarioController::class, 'index']);

// Rota para GET do Filtro de Inventários
Route::middleware('verified', 'permissao:,Servo,Ancião,Administrador')
    ->name('inventario.filtrado.get')
    ->get('inventarioFiltrado', [App\Http\Controllers\InventarioController::class, 'index']);

// Rota Fazer Inventário POST (Faz o Inventário)
Route::middleware('verified', 'permissao:,Servo,Ancião,Administrador')
    ->name('inventariar.post')
    ->post('inventariar', [App\Http\Controllers\InventarioController::class, 'inventariar']);

Route::middleware('verified', 'permissao:,Servo,Ancião,Administrador')
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
Route::middleware('verified', 'permissao:Servo,Ancião,Administrador')
    ->get('/auditoria', [App\Http\Controllers\AuditoriaController::class, 'index'])
    ->name('auditoria.index');