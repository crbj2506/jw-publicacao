<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use App\Models\Envio;
use Illuminate\Http\Request;

class EnvioController extends Controller
{
    public function __construct(Envio $envio){
        $this->envio = $envio;
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $envios = $this->envio->paginate(10);
        return view('envio.index',['envios' => $envios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $congregacoes = Congregacao::orderBy('nome')->get();
        return view('envio.create',['congregacoes' => $congregacoes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate($this->envio->rules($id = null),$this->envio->feedback());
        $envio = $this->envio->create($request->all());
        return redirect()->route('envio.show', ['envio' => $envio->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $envio = $this->envio->find($id);
        $congregacoes = Congregacao::orderBy('nome')->get();
        return view('envio.show', ['envio' => $envio, 'congregacoes' => $congregacoes]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Envio  $envio
     * @return \Illuminate\Http\Response
     */
    public function edit(Envio $envio)
    {
        //
        $congregacoes = Congregacao::orderBy('nome')->get();
        return view('envio.edit', ['envio' => $envio, 'congregacoes' => $congregacoes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate($this->envio->rules($id),$this->envio->feedback());
        $envio = $this->envio->find($id);
        $envio->update($request->all());
        return redirect()->route('envio.show', ['envio' => $envio->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Envio  $envio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Envio $envio)
    {
        //
    }
}
