<?php

namespace App\Http\Controllers;
use App\Models\Publicacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class PublicacaoController extends Controller
{
    public $publicacao;
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
        $nomeFiltro = null;

        if(empty($request->query())){
            $request->session()->forget('nomeFiltro');
        };

        $publicacoes = $this->publicacao;

        if($request->all('filtro')['filtro'] || $request->session()->exists('nomeFiltro')){
            $nomeFiltro = $request->session()->exists('nomeFiltro') ? $request->session()->get('nomeFiltro') : $request->all('filtro')['filtro'];
            if($request->all('filtro')['filtro']){
                $request->session()->put('nomeFiltro', $nomeFiltro);
            }
            $publicacoes = $publicacoes->where('nome', 'like', '%'. $nomeFiltro. '%');
        }
        if(App::environment() == 'local'){
            $publicacoes = $publicacoes->paginate(10);
        }else{
            $publicacoes = $publicacoes->paginate(100);
        }

        $publicacoes->filtros = $request->all('filtro');
        $publicacoes->nomeFiltro = $nomeFiltro;

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
                'observacao' => $request->observacao,
                'proporcao_cm' => $request->proporcao_cm,
                'proporcao_unidade' => $request->proporcao_unidade,
                'imagem' => $imagem_urn,
                'codigo' => $request->codigo,
                'item' => $request->item
            ]);
        }else{
            $publicacao = $this->publicacao->create([
                'nome' => $request->nome,
                'observacao' => $request->observacao,
                'proporcao_cm' => $request->proporcao_cm,
                'proporcao_unidade' => $request->proporcao_unidade,
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
