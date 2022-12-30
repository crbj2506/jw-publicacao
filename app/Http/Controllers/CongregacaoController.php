<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use Illuminate\Http\Request;

class CongregacaoController extends Controller
{
    public function __construct(Congregacao $congregacao){
        $this->congregacao = $congregacao;
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $congregacoes = $this->congregacao->paginate(10);
        return view('congregacao.index',['congregacoes' => $congregacoes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('congregacao.create');
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
        $request->validate($this->congregacao->rules($id = null),$this->congregacao->feedback());
        $congregacao = $this->congregacao->create($request->all());
        return redirect()->route('congregacao.show', ['congregacao' => $congregacao->id]);
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
        $congregacao = $this->congregacao->find($id);
        return view('congregacao.show', ['congregacao' => $congregacao]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Congregacao  $congregacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Congregacao $congregacao)
    {
        //
        return view('congregacao.edit', ['congregacao' => $congregacao]);
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
        $request->validate($this->congregacao->rules($id),$this->congregacao->feedback());
        $congregacao = $this->congregacao->find($id);
        $congregacao->update($request->all());
        return redirect()->route('congregacao.show', ['congregacao' => $congregacao->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Congregacao  $congregacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Congregacao $congregacao)
    {
        //
    }
}
