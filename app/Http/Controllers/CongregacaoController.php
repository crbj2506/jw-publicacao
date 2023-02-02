<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class CongregacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        //
        if(App::environment() == 'local'){
            $congregacoes = Congregacao::paginate(10);
        }else{
            $congregacoes = Congregacao::paginate(50);
        }
        return view('congregacao.crud',['congregacoes' => $congregacoes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        return view('congregacao.crud');
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
        $request->validate(Congregacao::rules($id = null),Congregacao::feedback());
        $congregacao = Congregacao::create($request->all());
        return redirect()->route('congregacao.show', ['congregacao' => $congregacao]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Congregacao  $congregacao
     * @return \Illuminate\Contracts\View\View
     */
    public function show($congregacao)
    {
        //
        $congregacao = Congregacao::find($congregacao);
        if(Route::current()->action['as'] == "congregacao.show"){
            $congregacao->show = true;
        };
        return view('congregacao.crud', ['congregacao' => $congregacao]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Congregacao  $congregacao
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Congregacao $congregacao)
    {
        //
        if(Route::current()->action['as'] == "congregacao.edit"){
            $congregacao->edit = true;
        };
        return view('congregacao.crud', ['congregacao' => $congregacao]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Congregacao  $congregacao
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $congregacao)
    {
        //
        $request->validate(Congregacao::rules($congregacao ),Congregacao::feedback());
        $congregacao = Congregacao::find($congregacao);
        $congregacao->update($request->all());
        return redirect()->route('congregacao.show', ['congregacao' => $congregacao]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Congregacao  $congregacao
     * @return null
     */
    public function destroy(Congregacao $congregacao)
    {
        //
    }
}
