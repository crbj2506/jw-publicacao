<?php

namespace App\Http\Controllers;
use App\Models\Publicacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class PublicacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        //
        $nomeFiltro = null;
        $codigoFiltro = null;

        if(empty($request->query())){
            $request->session()->forget('nomeFiltro');
            $request->session()->forget('codigoFiltro');
        };

        $publicacoes = Publicacao::orderBy('nome');

        if($request->all('filtro')['filtro'] || $request->session()->exists('nomeFiltro')){
            $nomeFiltro = $request->session()->exists('nomeFiltro') ? $request->session()->get('nomeFiltro') : $request->all('filtro')['filtro'];
            if($request->all('filtro')['filtro']){
                $request->session()->put('nomeFiltro', $nomeFiltro);
            }
            $publicacoes = $publicacoes->where('nome', 'like', '%'. $nomeFiltro. '%');
        }

        if($request->all('codigo')['codigo'] || $request->session()->exists('codigoFiltro')){
            $codigoFiltro = $request->session()->exists('codigoFiltro') ? $request->session()->get('codigoFiltro') : $request->all('codigo')['codigo'];
            if($request->all('codigo')['codigo']){
                $request->session()->put('codigoFiltro', $codigoFiltro);
            }
            $publicacoes = $publicacoes->where('codigo', 'like', '%'. $codigoFiltro. '%');
        }

        $publicacoes = $publicacoes->paginate(100);

        $publicacoes->filtros = $request->all('filtro','codigo');
        $publicacoes->nomeFiltro = $nomeFiltro;
        $publicacoes->codigoFiltro = $codigoFiltro;

        return view('publicacao.crud',['publicacoes' => $publicacoes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        return view('publicacao.crud');
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
        $request->validate(Publicacao::rules($id = null),Publicacao::feedback());
        $imagem = $request->file('imagem');
        if($imagem){
            $imagem_urn = $imagem->store('imagens', 'public');
            $publicacao = Publicacao::create([
                'nome' => $request->nome,
                'observacao' => $request->observacao,
                'proporcao_cm' => $request->proporcao_cm,
                'proporcao_unidade' => $request->proporcao_unidade,
                'imagem' => $imagem_urn,
                'codigo' => $request->codigo,
                'item' => $request->item
            ]);
        }else{
            $publicacao = Publicacao::create([
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
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        //
        $publicacao = Publicacao::find($id);
        $publicacoes = Publicacao::orderBy('nome')->get();
        $indicePublicacaoAnterior = array_search($publicacao, $publicacoes->all()) - 1;
        $indicePublicacaoPosterior = array_search($publicacao, $publicacoes->all()) + 1;
        $publicacaoAnterior = $publicacoes->get($indicePublicacaoAnterior);
        $publicacaoPosterior = $publicacoes->get($indicePublicacaoPosterior);
        $publicacao->objetoAnterior = $publicacaoAnterior ? $publicacaoAnterior->id : null;
        $publicacao->objetoPosterior = $publicacaoPosterior ? $publicacaoPosterior->id : null;

        if(Route::current()->action['as'] == "publicacao.show"){
            $publicacao->show = true;
        };
        return view('publicacao.crud', ['publicacao' => $publicacao]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Publicacao  $publicacao
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Publicacao $publicacao)
    {
        //
        $publicacao = Publicacao::find($publicacao->id);
        $publicacoes = Publicacao::orderBy('nome')->get();
        $indicePublicacaoAnterior = array_search($publicacao, $publicacoes->all()) - 1;
        $indicePublicacaoPosterior = array_search($publicacao, $publicacoes->all()) + 1;
        $publicacaoAnterior = $publicacoes->get($indicePublicacaoAnterior);
        $publicacaoPosterior = $publicacoes->get($indicePublicacaoPosterior);
        $publicacao->objetoAnterior = $publicacaoAnterior ? $publicacaoAnterior->id : null;
        $publicacao->objetoPosterior = $publicacaoPosterior ? $publicacaoPosterior->id : null;

        if(Route::current()->action['as'] == "publicacao.edit"){
            $publicacao->edit = true;
        };
        return view('publicacao.crud', ['publicacao' => $publicacao]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        //
        $publicacao = Publicacao::find($id);
        $request->validate(Publicacao::rules($id),Publicacao::feedback());
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        //
        $publicacao = Publicacao::find($id);
        $publicacao->delete();
        return redirect()->route('publicacao.index');
    }
}
