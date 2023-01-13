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
    public function index(Request $request)
    {
        //
        if(!empty($request->all('filtro') ) ){
            $publicacoes = $this->publicacao->where('nome', 'like', '%'. $request->all('filtro')['filtro']. '%');
        }
        $publicacoes = $publicacoes->paginate(10);
        return view('publicacao.index',['publicacoes' => $publicacoes, 'filtro' => $request->all('filtro')['filtro']]);
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
                'observacao' => $request->observacao,
                'imagem' => $imagem_urn,
                'codigo' => $request->codigo,
                'item' => $request->item
            ]);
        }else{
            $publicacao = $this->publicacao->create([
                'nome' => $request->nome,
                'observacao' => $request->observacao,
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
        $idPublicacaoAnterior = $this->publicacao->where('id', '<', $id)->max('id');
        $idPublicacaoPosterior = $this->publicacao->where('id', '>', $id)->min('id');
        return view('publicacao.show', ['publicacao' => $publicacao, 'idPublicacaoAnterior' => $idPublicacaoAnterior, 'idPublicacaoPosterior' => $idPublicacaoPosterior]);
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
        $publicacaoAnterior = $this->publicacao->where('id', '<', $publicacao->id)->get()->last();
        $publicacaoPosterior = $this->publicacao->where('id', '>', $publicacao->id)->get()->first();
        return view('publicacao.edit', ['publicacao' => $publicacao, 'publicacaoAnterior' => $publicacaoAnterior, 'publicacaoPosterior' => $publicacaoPosterior]);
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
