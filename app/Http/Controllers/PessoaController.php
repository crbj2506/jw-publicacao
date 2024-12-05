<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class PessoaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        //
        $pessoas = Pessoa::paginate(50);
        return view('pessoa.crud',['pessoas' => $pessoas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        return view('pessoa.crud');
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
        $request->validate(Pessoa::rules($id = null),Pessoa::feedback());
        $pessoa = Pessoa::create($request->all());
        return redirect()->route('pessoa.show', ['pessoa' => $pessoa]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pessoa  $pessoa
     * @return \Illuminate\Contracts\View\View
     */
    public function show($pessoa)
    {
        //
        $pessoa = Pessoa::find($pessoa);
        if(Route::current()->action['as'] == "pessoa.show"){
            $pessoa->show = true;
        };
        return view('pessoa.crud', ['pessoa' => $pessoa]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pessoa  $pessoa
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Pessoa $pessoa)
    {
        //
        if(Route::current()->action['as'] == "pessoa.edit"){
            $pessoa->edit = true;
        };
        return view('pessoa.crud', ['pessoa' => $pessoa]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pessoa  $pessoa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $pessoa)
    {
        //
        $request->validate(Pessoa::rules($pessoa ),Pessoa::feedback());
        $pessoa = Pessoa::find($pessoa);
        $pessoa->update($request->all());
        return redirect()->route('pessoa.show', ['pessoa' => $pessoa]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pessoa  $pessoa
     * @return null
     */
    public function destroy(Pessoa $pessoa)
    {
        //
    }
}
