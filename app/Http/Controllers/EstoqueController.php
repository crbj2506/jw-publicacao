<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Publicacao;
use App\Models\Local;
use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    public $estoque;
    public function __construct(Estoque $estoque){
        $this->estoque = $estoque;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $estoques = $this->estoque->paginate(50);
        return view('estoque.index',['estoques' => $estoques]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $locais = Local::all();
        $publicacoes = Publicacao::all();
        return view('estoque.create',['locais' => $locais,'publicacoes' => $publicacoes]);
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
        $local_id = $request->all('local_id');
        $publicacao_id = $request->all('publicacao_id');
        $request->validate($this->estoque->rules($local_id, $publicacao_id, $id = null), $this->estoque->feedback());
        $estoque = $this->estoque->create($request->all());
        return redirect()->route('estoque.show', ['estoque' => $estoque->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estoque  $estoque
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $estoque = $this->estoque->find($id);
        $locais = Local::all();
        $publicacoes = Publicacao::all();
        return view('estoque.show', ['estoque' => $estoque, 'locais' => $locais, 'publicacoes' => $publicacoes]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estoque  $estoque
     * @return \Illuminate\Http\Response
     */
    public function edit(Estoque $estoque)
    {
        //
        $locais = Local::all();
        $publicacoes = Publicacao::all();
        return view('estoque.edit', ['estoque' => $estoque, 'locais' => $locais, 'publicacoes' => $publicacoes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estoque  $estoque
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $local_id = $request->all('local_id');
        $publicacao_id = $request->all('publicacao_id');
        $request->validate($this->estoque->rules($local_id, $publicacao_id, $id), $this->estoque->feedback());
        $estoque = $this->estoque->find($id);
        $estoque->update($request->all());
        return redirect()->route('estoque.show', ['estoque' => $estoque->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estoque  $estoque
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estoque $estoque)
    {
        //
    }
}