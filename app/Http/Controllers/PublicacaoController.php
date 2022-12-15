<?php

namespace App\Http\Controllers;

use App\Models\Publicacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicacaoController extends Controller
{
    public function __construct(Publicacao $publicacao){
        $this->publicacao = $publicacao;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$publicacoes = $this->publicacao->all();
        $publicacoes = $this->publicacao->paginate(10);
        return view('publicacao.index',['publicacoes' => $publicacoes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('publicacao.create');
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
        $request->validate($this->publicacao->rules($id = null),$this->publicacao->feedback());
        $imagem = $request->file('imagem');
        if($imagem){
            $imagem_urn = $imagem->store('imagens', 'public');
            $publicacao = $this->publicacao->create([
                'nome' => $request->nome,
                'imagem' => $imagem_urn,
                'codigo' => $request->codigo,
                'item' => $request->item
            ]);
        }else{
            $publicacao = $this->publicacao->create([
                'nome' => $request->nome,
                'codigo' => $request->codigo,
                'item' => $request->item
            ]);
        }
        return redirect()->route('publicacao.show', ['publicacao' => $publicacao->id]);
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
        $publicacao = $this->publicacao->find($id);
        return view('publicacao.show', ['publicacao' => $publicacao]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Publicacao  $publicacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Publicacao $publicacao)
    {
        //
        return view('publicacao.edit', ['publicacao' => $publicacao]);
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
        $publicacao = $this->publicacao->find($id);
        $request->validate($this->publicacao->rules($id),$this->publicacao->feedback());
        $imagem = $request->file('imagem');
        // Se existe imagem nova informada para atualizar
        if($imagem){
            //Verifica se existia imagem anterior
            if($publicacao->getOriginal()['imagem']){
                Storage::disk('public')->delete($publicacao->imagem);
            }
            $imagem_urn = $imagem->store('imagens', 'public');
        }

        $publicacao->fill($request->all());
        if($imagem){
            $publicacao->imagem = $imagem_urn;
        }
        $publicacao->save();
        return redirect()->route('publicacao.show', ['publicacao' => $publicacao->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $publicacao = $this->publicacao->find($id);
        $publicacao->delete();
        return redirect()->route('publicacao.index');
    }
}
