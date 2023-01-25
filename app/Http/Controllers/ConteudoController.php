<?php

namespace App\Http\Controllers;

use App\Models\Conteudo;
use App\Models\Publicacao;
use App\Models\Volume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ConteudoController extends Controller
{
    public $conteudo;
    public function __construct(Conteudo $conteudo){
        $this->conteudo = $conteudo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $conteudos = $this->conteudo->orderByDesc('id');
       
        if(App::environment() == 'local'){
            $conteudos = $conteudos->paginate(10);
        }else{
            $conteudos = $conteudos->paginate(50);
        }
        return view('conteudo.index',['conteudos' => $conteudos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $volumes = Volume::orderByDesc('id')->get();
        $publicacoes = Publicacao::orderBy('nome')->get();
        return view('conteudo.create',['volumes' => $volumes,'publicacoes' => $publicacoes]);
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
        $request->validate($this->conteudo->rules(), $this->conteudo->feedback());
        $conteudo = $this->conteudo->create($request->all());
        return redirect()->route('conteudo.show', ['conteudo' => $conteudo->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conteudo  $conteudo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $conteudo = $this->conteudo->find($id);
        $volumes = Volume::orderByDesc('id')->get();
        $publicacoes = Publicacao::orderBy('nome')->get();
        return view('conteudo.show', ['conteudo' => $conteudo, 'volumes' => $volumes, 'publicacoes' => $publicacoes]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Conteudo  $conteudo
     * @return \Illuminate\Http\Response
     */
    public function edit(Conteudo $conteudo)
    {
        //
        $volumes = Volume::orderByDesc('id')->get();
        $publicacoes = Publicacao::orderBy('nome')->get();
        return view('conteudo.edit', ['conteudo' => $conteudo, 'volumes' => $volumes, 'publicacoes' => $publicacoes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conteudo  $conteudo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate($this->conteudo->rules(),$this->conteudo->feedback());
        $conteudo = $this->conteudo->find($id);
        $conteudo->update($request->all());
        return redirect()->route('conteudo.show', ['conteudo' => $conteudo->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Conteudo  $conteudo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conteudo $conteudo)
    {
        //
    }
}
