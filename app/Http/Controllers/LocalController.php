<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class LocalController extends Controller
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
            $locais = Local::orderBy('sigla')->paginate(10);
        }else{
            $locais = Local::orderBy('sigla')->paginate(50);
        }
        return view('local.crud',['locais' => $locais]);
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
        return view('local.crud',['congregacoes' => $congregacoes]);
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
        $request->validate(Local::rules($local = null),Local::feedback());
        $local = Local::create($request->all());
        return redirect()->route('local.show', ['local' => $local]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Local  $local
     * @return \Illuminate\Contracts\View\View
     */
    public function show($local)
    {
        //
        $local = Local::find($local);
        $congregacoes = Congregacao::orderBy('nome')->get();
        if(Route::current()->action['as'] == "local.show"){
            $local->show = true;
        };
        return view('local.crud', ['local' => $local, 'congregacoes' => $congregacoes]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Local  $local
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Local $local)
    {
        //
        if(Route::current()->action['as'] == "local.edit"){
            $local->edit = true;
        };
        $congregacoes = Congregacao::orderBy('nome')->select('id as value', 'nome as text')->get();
        return view('local.crud', ['local' => $local, 'congregacoes' => $congregacoes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Local  $local
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $local)
    {
        //
        $request->validate(Local::rules($local),Local::feedback());
        $local = Local::find($local);
        $local->update($request->all());
        return redirect()->route('local.show', ['local' => $local]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Local  $local
     * @return \Illuminate\Http\Response
     */
    public function destroy(Local $local)
    {
        //
    }
}
