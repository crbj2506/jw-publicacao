<?php

namespace App\Http\Controllers;

use App\Models\Permissao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class PermissaoController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        //       
        $permissoes = Permissao::paginate(50);
        return view('permissao.crud',['permissoes' => $permissoes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        return view('permissao.crud');
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
        $request->validate(Permissao::rules($permissao = null),Permissao::feedback());
        $permissao = Permissao::create($request->all());
        return redirect()->route('permissao.show', ['permissao' => $permissao]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permissao
     * @return \Illuminate\Contracts\View\View
     */
    public function show($permissao)
    {
        //
        $permissao = Permissao::find($permissao);
        if(Route::current()->action['as'] == "permissao.show"){
            $permissao->show = true;
        };
        return view('permissao.crud', ['permissao' => $permissao]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permissao  $permissao
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Permissao $permissao)
    {
        //
        if(Route::current()->action['as'] == "permissao.edit"){
            $permissao->edit = true;
        };
        return view('permissao.crud', ['permissao' => $permissao]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permissao
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $permissao)
    {
        //
        $request->validate(Permissao::rules($permissao),Permissao::feedback());
        $permissao = Permissao::find($permissao);
        $permissao->update($request->all());
        return redirect()->route('permissao.show', ['permissao' => $permissao]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permissao  $permissao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permissao $permissao)
    {
        //
    }
}
