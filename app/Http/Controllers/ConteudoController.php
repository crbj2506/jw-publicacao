<?php

namespace App\Http\Controllers;

use App\Models\Conteudo;
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
    public function index()
    {
        //
        $conteudos = Conteudo::orderByDesc('id');
       
        if(App::environment() == 'local'){
            $conteudos = $conteudos->paginate(10);
        }else{
            $conteudos = $conteudos->paginate(50);
        }
        return view('conteudo.crud',['conteudos' => $conteudos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        $volumes = Volume::orderByDesc('id')->get();
        foreach ($volumes as $key => $v) {
            $volumes[$key]->text = $v->volume . ' Nota: ' . $v->envio->nota . ($v->envio->data ? ' de '. $v->envio->data : null);
            $volumes[$key]->value = $v->id;
        }
        $publicacoes = Publicacao::orderBy('nome')->select('id as value', 'nome as text')->get();
        return view('conteudo.crud',['volumes' => $volumes,'publicacoes' => $publicacoes]);
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
        $volumes = Volume::orderByDesc('id')->get();
        $publicacoes = Publicacao::orderBy('nome')->get();
        if(Route::current()->action['as'] == "conteudo.show"){
            $conteudo->show = true;
        };
        return view('conteudo.crud', ['conteudo' => $conteudo, 'volumes' => $volumes, 'publicacoes' => $publicacoes]);
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
        $volumes = Volume::orderByDesc('id')->get();
        foreach ($volumes as $key => $v) {
            $volumes[$key]->text = $v->volume . ' Nota: ' . $v->envio->nota . ($v->envio->data ? ' de '. $v->envio->data : null);
            $volumes[$key]->value = $v->id;
        }
        $publicacoes = Publicacao::orderBy('nome')->select('id as value', 'nome as text')->get();
        return view('conteudo.crud', ['conteudo' => $conteudo, 'volumes' => $volumes, 'publicacoes' => $publicacoes]);
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
