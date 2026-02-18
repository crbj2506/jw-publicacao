<?php

namespace App\Http\Controllers;

use App\Models\Conteudo;
use App\Models\Congregacao;
use App\Models\Envio;
use App\Models\Publicacao;
use App\Models\Volume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class ConteudoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $publicacaoFiltro = null;
        $volumeFiltro = null;
        $codigoFiltro = null;
        $envioFiltro = null; // Novo filtro para Envio
        $perpage = 10; // Default perpage

        if (empty($request->query()) && $request->method() == 'GET') { // Clear filters only on direct GET access without query params
            $request->session()->forget('publicacaoFiltro');
            $request->session()->forget('volumeFiltro');
            $request->session()->forget('codigoFiltro');
            $request->session()->forget('envioFiltro'); // Limpar filtro de Envio
            $request->session()->forget('perpage');
        }
        
        // Lógica para o filtro de Publicação
        if ($request->has('publicacao')) { // Check if the parameter was sent in the request
            $publicacaoFiltro = $request->input('publicacao');
            $request->session()->put('publicacaoFiltro', $publicacaoFiltro); // Store even if empty to clear
        } elseif ($request->session()->exists('publicacaoFiltro')) {
            $publicacaoFiltro = $request->session()->get('publicacaoFiltro');
        }

        // Lógica para o filtro de Volume
        if ($request->has('volume')) { // Check if the parameter was sent in the request
            $volumeFiltro = $request->input('volume');
            $request->session()->put('volumeFiltro', $volumeFiltro);
        } elseif ($request->session()->exists('volumeFiltro')) {
            $volumeFiltro = $request->session()->get('volumeFiltro');
        }

        // Lógica para o filtro de Código
        if ($request->has('codigo')) { // Check if the parameter was sent in the request
            $codigoFiltro = $request->input('codigo');
            $request->session()->put('codigoFiltro', $codigoFiltro);
        } elseif ($request->session()->exists('codigoFiltro')) {
            $codigoFiltro = $request->session()->get('codigoFiltro');
        }

        // Lógica para o filtro de Envio
        if ($request->has('envio')) { // Check if the parameter was sent in the request
            $envioFiltro = $request->input('envio');
            $request->session()->put('envioFiltro', $envioFiltro);
        } elseif ($request->session()->exists('envioFiltro')) {
            $envioFiltro = $request->session()->get('envioFiltro');
        }
        
        $conteudos = Conteudo::orderByDesc('id');

        if (!empty($publicacaoFiltro)) {
            $conteudos = $conteudos->whereRelation('publicacao', 'nome', 'like', '%' . $publicacaoFiltro . '%');
        }

        if (!empty($volumeFiltro)) {
            $conteudos = $conteudos->whereRelation('volume', 'volume', 'like', '%' . $volumeFiltro . '%');
        }

        if (!empty($codigoFiltro)) {
            $conteudos = $conteudos->whereRelation('publicacao', 'codigo', 'like', '%' . $codigoFiltro . '%');
        }

        if (!empty($envioFiltro)) {
            $conteudos = $conteudos->whereRelation('volume.envio', 'nota', 'like', '%' . $envioFiltro . '%');
        }

        // Lógica para o número de itens por página (perpage)
        // Use has() to check if 'perpage' exists in the request, even if its value is empty.
        // This ensures that if the user explicitly sets it to empty (e.g., from a text input), it's handled.
        if ($request->has('perpage')) {
            $perpage = $request->input('perpage');
            $request->session()->put('perpage', $perpage);
        } elseif ($request->session()->exists('perpage')) {
            $perpage = $request->session()->get('perpage');
        }

        $conteudos = $conteudos->paginate($perpage);

        $viewData = $this->loadViewData();

        // Atribuir os valores dos filtros ao objeto paginado para uso na view
        $conteudos->publicacaoFiltro = $publicacaoFiltro;
        $conteudos->volumeFiltro = $volumeFiltro;
        $conteudos->codigoFiltro = $codigoFiltro;
        $conteudos->envioFiltro = $envioFiltro; // Atribuir o filtro de Envio
        $conteudos->perpage = $perpage;

        return view('conteudo.crud', array_merge(['conteudos' => $conteudos], $viewData));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $viewData = $this->loadViewData();
        return view('conteudo.crud', $viewData);
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
        $request->validate(Conteudo::rules(), Conteudo::feedback());
        $conteudo = Conteudo::create($request->all());
        return redirect()->route('conteudo.show', ['conteudo' => $conteudo]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conteudo  $conteudo
     * @return \Illuminate\Contracts\View\View
     */
    public function show($conteudo)
    {
        //
        $conteudo = Conteudo::find($conteudo);
        if(Route::current()->action['as'] == "conteudo.show"){
            $conteudo->show = true;
        }

        $viewData = $this->loadViewData();
        return view('conteudo.crud', array_merge(['conteudo' => $conteudo], $viewData));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Conteudo  $conteudo
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Conteudo $conteudo)
    {
        //
        if(Route::current()->action['as'] == "conteudo.edit"){
            $conteudo->edit = true;
        };
        
        $viewData = $this->loadViewData();
        return view('conteudo.crud', array_merge(['conteudo' => $conteudo], $viewData));
    }

    /**
     * Carrega os dados necessários para as views de Conteúdo.
     *
     * @return array
     */
    private function loadViewData()
    {
        $volumes = Volume::orderByDesc('id')->get();
        foreach ($volumes as $key => $v) {
            $volumes[$key]->text = $v->volume . ' Nota: ' . $v->envio->nota . ($v->envio->data ? ' de '. $v->envio->data : null);
            $volumes[$key]->value = $v->id;
        }

        $publicacoes = Publicacao::orderBy('nome')->select('id as value', Publicacao::raw('CONCAT(nome, " (",codigo,") ") as text'))->get();
        $congregacoes = Congregacao::orderBy('nome')->select('id as value', 'nome as text')->get();
        
        $envios = Envio::orderByDesc('id')->get();
        foreach ($envios as $key => $e) {
            $envios[$key]->text = $e->nota . ($e->data ? ' de '. $e->data : null);
            $envios[$key]->value = $e->id;
        }

        return [
            'volumes' => $volumes,
            'publicacoes' => $publicacoes,
            'congregacoes' => $congregacoes,
            'envios' => $envios
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conteudo  $conteudo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $conteudo)
    {
        //
        $request->validate(Conteudo::rules(), Conteudo::feedback());
        $conteudo = Conteudo::find($conteudo);
        $conteudo->update($request->all());
        return redirect()->route('conteudo.show', ['conteudo' => $conteudo]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Conteudo  $conteudo
     * @return null
     */
    public function destroy(Conteudo $conteudo)
    {
        //
    }
}
