<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Publicacao;
use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class EstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request  $request)
    {
        //
        $publicacaoFiltro = null; 
        if(empty($request->query())){
            $request->session()->forget('publicacaoFiltro');
        };

        $estoques = Estoque::select('*')
            ->orderBy(Local::select('sigla')
                ->whereColumn('locais.id', 'estoques.local_id')
        );

        if($request->all('publicacao')['publicacao'] || $request->session()->exists('publicacaoFiltro')){
            $publicacaoFiltro = $request->session()->exists('publicacaoFiltro') ? $request->session()->get('publicacaoFiltro') : $request->all('publicacao')['publicacao'];
            if($request->all('publicacao')['publicacao']){
                $request->session()->put('publicacaoFiltro', $publicacaoFiltro);
            }
            $estoques = $estoques->whereRelation('publicacao', 'nome', 'like', '%'. $publicacaoFiltro. '%');
                
        }

        $estoques = $estoques->paginate(100);
        $estoques->publicacaoFiltro = $publicacaoFiltro;

        return view('estoque.crud',['estoques' => $estoques]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        $locais = Local::orderBy('id')->get();
        foreach ($locais as $key => $l) {
            $locais[$key]->text = $l->sigla . ' - ' . $l->nome ;
            $locais[$key]->value = $l->id;
        }
        $publicacoes = Publicacao::orderBy('nome')->select('id as value', 'nome as text')->get();
        return view('estoque.crud',['locais' => $locais,'publicacoes' => $publicacoes]);
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
        $local_id = $request->all('local_id');
        $publicacao_id = $request->all('publicacao_id');
        $request->validate(Estoque::rules($local_id, $publicacao_id, $id = null), Estoque::feedback());
        $estoque = Estoque::create($request->all());
        return redirect()->route('estoque.show', ['estoque' => $estoque->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estoque  $estoque
     * @return \Illuminate\Contracts\View\View
     */
    public function show($estoque)
    {
        //
        $estoque = Estoque::find($estoque);
        $locais = Local::orderBy('sigla')->get();
        $publicacoes = Publicacao::orderBy('nome')->get();

        $estoques = Estoque::select('*')
            ->orderBy(Local::select('sigla')
                ->whereColumn('locais.id', 'estoques.local_id')
        )->get();

        $indiceEstoqueAnterior = array_search($estoque, $estoques->all()) - 1;
        $indiceEstoquePosterior = array_search($estoque, $estoques->all()) + 1;
        $estoqueAnterior = $estoques->get($indiceEstoqueAnterior);
        $estoquePosterior = $estoques->get($indiceEstoquePosterior);
        $estoque->objetoAnterior = $estoqueAnterior ? $estoqueAnterior->id : null;
        $estoque->objetoPosterior = $estoquePosterior ? $estoquePosterior->id : null;
        if(Route::current()->action['as'] == "estoque.show"){
            $estoque->show = true;
        };
        return view('estoque.crud', ['estoque' => $estoque, 'locais' => $locais, 'publicacoes' => $publicacoes]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estoque  $estoque
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Estoque $estoque)
    {
        //
        $locais = Local::orderBy('id')->get();
        foreach ($locais as $key => $l) {
            $locais[$key]->text = $l->sigla . ' - ' . $l->nome ;
            $locais[$key]->value = $l->id;
        }
        $publicacoes = Publicacao::orderBy('nome')->select('id as value', 'nome as text')->get();

        $estoques = Estoque::select('*')
            ->orderBy(Local::select('sigla')
                ->whereColumn('locais.id', 'estoques.local_id')
        )->get();
        
        $indiceEstoqueAnterior = array_search($estoque, $estoques->all()) - 1;
        $indiceEstoquePosterior = array_search($estoque, $estoques->all()) + 1;
        $estoqueAnterior = $estoques->get($indiceEstoqueAnterior);
        $estoquePosterior = $estoques->get($indiceEstoquePosterior);
        $estoque->objetoAnterior = $estoqueAnterior ? $estoqueAnterior : null;
        $estoque->objetoPosterior = $estoquePosterior ? $estoquePosterior : null;
        if(Route::current()->action['as'] == "estoque.edit"){
            $estoque->edit = true;
        };
        return view('estoque.crud', ['estoque' => $estoque, 'locais' => $locais, 'publicacoes' => $publicacoes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estoque  $estoque
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $estoque)
    {
        //
        $local_id = $request->all('local_id');
        $publicacao_id = $request->all('publicacao_id');
        $request->validate(Estoque::rules($local_id, $publicacao_id, $estoque), Estoque::feedback());
        $estoque = Estoque::find($estoque);
        $estoque->update($request->all());
        return redirect()->route('estoque.show', ['estoque' => $estoque->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estoque  $estoque
     * @return \Illuminate\Http\Response
     */
    public function destroy($estoque)
    {
        //
        $estoque = Estoque::find($estoque);
        $estoque->delete();
        return redirect()->route('estoque.index');
    }
}
