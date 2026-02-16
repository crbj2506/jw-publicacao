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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $notaFiltro = null;
        $congregacaoFiltro = null;
        $perpage = 10; // Padrão 10 conforme solicitado

        // Limpa os filtros da sessão se o acesso for direto (GET sem parâmetros)
        if (empty($request->query()) && $request->method() == 'GET') {
            $request->session()->forget(['notaFiltro', 'congregacaoFiltro', 'perpage']);
        }

        // Lógica para o filtro de Nota
        if ($request->has('nota')) {
            $notaFiltro = $request->input('nota');
            $request->session()->put('notaFiltro', $notaFiltro);
        } elseif ($request->session()->exists('notaFiltro')) {
            $notaFiltro = $request->session()->get('notaFiltro');
        }

        // Lógica para o filtro de Congregação (Nome)
        if ($request->has('congregacao')) {
            $congregacaoFiltro = $request->input('congregacao');
            $request->session()->put('congregacaoFiltro', $congregacaoFiltro);
        } elseif ($request->session()->exists('congregacaoFiltro')) {
            $congregacaoFiltro = $request->session()->get('congregacaoFiltro');
        }

        // Lógica para o número de itens por página (perpage)
        if ($request->has('perpage')) {
            $perpage = $request->input('perpage');
            $request->session()->put('perpage', $perpage);
        } elseif ($request->session()->exists('perpage')) {
            $perpage = $request->session()->get('perpage');
        }

        $envios = Envio::orderByDesc('id');

        if (!empty($notaFiltro)) $envios->where('nota', 'like', "%$notaFiltro%");
        if (!empty($congregacaoFiltro)) {
            $envios->whereRelation('congregacao', 'nome', 'like', "%$congregacaoFiltro%");
        }

        $envios = $envios->paginate($perpage);

        // Atribui os valores ao objeto para que a View e a Paginação funcionem corretamente
        $envios->notaFiltro = $notaFiltro;
        $envios->congregacaoFiltro = $congregacaoFiltro;
        $envios->perpage = $perpage;

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
