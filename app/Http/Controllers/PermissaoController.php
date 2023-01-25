<?php

namespace App\Http\Controllers;

use App\Models\Permissao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PermissaoController extends Controller
{

    public $permissao;
    public function __construct(Permissao $permissao){
        $this->permissao = $permissao;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $permissoes = $this->permissao;
       
        if(App::environment() == 'local'){
            $permissoes = $permissoes->paginate(10);
        }else{
            $permissoes = $permissoes->paginate(50);
        }
        return view('permissao.index',['permissoes' => $permissoes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('permissao.create');
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
        $request->validate($this->permissao->rules($id = null),$this->permissao->feedback());
        $permissao = $this->permissao->create($request->all());
        return redirect()->route('permissao.show', ['permissao' => $permissao->id]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $permissao = $this->permissao->find($id);
        return view('permissao.show', ['permissao' => $permissao]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permissao  $permissao
     * @return \Illuminate\Http\Response
     */
    public function edit(Permissao $permissao)
    {
        //
        return view('permissao.edit', ['permissao' => $permissao]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate($this->permissao->rules($id),$this->permissao->feedback());
        $permissao = $this->permissao->find($id);
        $permissao->update($request->all());
        return redirect()->route('permissao.show', ['permissao' => $permissao->id]);
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
