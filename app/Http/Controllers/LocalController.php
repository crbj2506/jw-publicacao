<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocalController extends Controller
{

    public $local;

    public function __construct(Local $local){
        $this->local = $local;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $locais = $this->local;
       
        if(App::environment() == 'local'){
            $locais = $locais->paginate(10);
        }else{
            $locais = $locais->paginate(50);
        }
        return view('local.index',['locais' => $locais]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $congregacoes = Congregacao::orderBy('nome')->get();
        return view('local.create',['congregacoes' => $congregacoes]);
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
        $request->validate($this->local->rules($id = null),$this->local->feedback());
        $local = $this->local->create($request->all());
        return redirect()->route('local.show', ['local' => $local->id]);
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
        $local = $this->local->find($id);
        $congregacoes = Congregacao::orderBy('nome')->get();
        return view('local.show', ['local' => $local, 'congregacoes' => $congregacoes]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Local  $local
     * @return \Illuminate\Http\Response
     */
    public function edit(Local $local)
    {
        //
        $congregacoes = Congregacao::orderBy('nome')->get();
        return view('local.edit', ['local' => $local, 'congregacoes' => $congregacoes]);
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
        $request->validate($this->local->rules($id),$this->local->feedback());
        $local = $this->local->find($id);
        $local->update($request->all());
        return redirect()->route('local.show', ['local' => $local->id]);
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
