<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Pessoa;
use App\Models\Publicacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        //
        if(App::environment() == 'local'){
            $pedidos = Pedido::orderByDesc('solicitado')->paginate(10);
        }else{
            $pedidos = Pedido::orderByDesc('solicitado')->paginate(50);
        }
        return view('pedido.crud',['pedidos' => $pedidos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        $pessoas = Pessoa::orderBy('nome')->select('id as value', 'nome as text')->get();
        $publicacoes = Publicacao::orderBy('nome')->select('id as value', 'nome as text')->get();
        return view('pedido.crud',['pessoas'=>$pessoas,'publicacoes'=>$publicacoes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //
        $request->validate(Pedido::rules($request->all('pessoa_id'),$request->all('publicacao_id'),$id = null),Pedido::feedback());
        $pedido = Pedido::create($request->all());
        return redirect()->route('pedido.show', ['pedido' => $pedido]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Contracts\View\View
     */
    public function show($pedido)
    {
        //
        $pessoas = Pessoa::orderBy('nome')->get();
        $publicacoes = Publicacao::orderBy('nome')->get();
        $pedido = Pedido::find($pedido);
        if(Route::current()->action['as'] == "pedido.show"){
            $pedido->show = true;
        };
        return view('pedido.crud', ['pedido' => $pedido,'pessoas'=>$pessoas,'publicacoes'=>$publicacoes]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Pedido $pedido)
    {
        //
        if(Route::current()->action['as'] == "pedido.edit"){
            $pedido->edit = true;
        };
        $pessoas = Pessoa::orderBy('nome')->select('id as value', 'nome as text')->get();
        $publicacoes = Publicacao::orderBy('nome')->select('id as value', 'nome as text')->get();
        return view('pedido.crud', ['pedido' => $pedido,'pessoas'=>$pessoas,'publicacoes'=>$publicacoes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $pedido)
    {
        //
        $request->validate(Pedido::rules($request->all('pessoa_id'),$request->all('publicacao_id'),$pedido),Pedido::feedback());
        $pedido = Pedido::find($pedido);
        $pedido->update($request->all());
        return redirect()->route('pedido.show', ['pedido' => $pedido]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return null
     */
    public function destroy(Pedido $pedido)
    {
        //
    }
}
