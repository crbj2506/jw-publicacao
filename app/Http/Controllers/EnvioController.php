<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use App\Models\Envio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class EnvioController extends Controller
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
            $envios = Envio::orderByDesc('data')->paginate(10);
        }else{
            $envios = Envio::orderByDesc('data')->paginate(50);
        }
        return view('envio.crud',['envios' => $envios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        $congregacoes = Congregacao::orderBy('nome')->select('id as value', 'nome as text')->get();
        return view('envio.crud',['congregacoes' => $congregacoes]);
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
        $request->validate(Envio::rules($id = null),Envio::feedback());
        $envio = Envio::create($request->all());
        return redirect()->route('envio.show', ['envio' => $envio->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Envio  $envio
     * @return \Illuminate\Contracts\View\View
     */
    public function show($envio)
    {
        //
        $envio = Envio::find($envio);
        $congregacoes = Congregacao::orderBy('nome')->get();
        if(Route::current()->action['as'] == "envio.show"){
            $envio->show = true;
        };
        return view('envio.crud', ['envio' => $envio, 'congregacoes' => $congregacoes]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Envio  $envio
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Envio $envio)
    {
        //
        if(Route::current()->action['as'] == "envio.edit"){
            $envio->edit = true;
        };
        $congregacoes = Congregacao::orderBy('nome')->select('id as value', 'nome as text')->get();
        return view('envio.crud', ['envio' => $envio, 'congregacoes' => $congregacoes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Envio  $envio
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $envio)
    {
        //
        $request->validate(Envio::rules($envio),Envio::feedback());
        $envio = Envio::find($envio);
        $envio->update($request->all());
        return redirect()->route('envio.show', ['envio' => $envio]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Envio  $envio
     * @return null
     */
    public function destroy(Envio $envio)
    {
        //
    }
}
