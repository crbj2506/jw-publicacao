<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use App\Models\Inventario;
use App\Models\Publicacao;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InventarioController extends Controller
{
    public function __construct(Inventario $inventario){
        $this->inventario = $inventario;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $inventarios = Inventario::groupBy('congregacao_id')
            ->selectRaw('congregacao_id, count(publicacao_id) as itens, sum(quantidade) as publicacoes, max(updated_at) as updated_at')
            ->paginate(10);
        return view('inventario.index',['inventarios' => $inventarios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Congregacao  $congregacao
     * @return \Illuminate\Http\Response
     */
    public function create(Congregacao $congregacao)
    {
        //
        $publicacoes = Publicacao::get();
        return view(
            'inventario.create', [ 
                'congregacao' => $congregacao, 
                'publicacoes' => $publicacoes
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Congregacao  $congregacao
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Congregacao $congregacao)
    {
        //
        //dd($request->all('quantidade'));
        $rules = [
            'publicacao_id' =>  [
                'required', 
                Rule::unique('inventarios')
                    ->where('congregacao_id', $congregacao->id)
            ],
            'quantidade' => [
                'integer',
                'between:1,10000'
            ]
        ];
        $feedback = [
            'required' => 'O campo :attribute é obrigatório',
            'publicacao_id' => 'Esta publicação já consta neste inventário',
            'quantidade.integer' => 'A quantidade informada deve ser um número inteiro',
        ];
        $request->validate($rules,$feedback);
        $this->inventario->create([
            'congregacao_id' => $congregacao->id, 
            'publicacao_id' => $request->all('publicacao_id')['publicacao_id'], 
            'quantidade' => $request->all('quantidade')['quantidade'], 
            'local' => $request->all('local')['local']]);

        return redirect()->route('inventario.show', ['congregacao' => $congregacao]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Congregacao  $congregacao
     * @return \Illuminate\Http\Response
     */
    public function show(Congregacao $congregacao)
    {
        return view('inventario.show', ['congregacao' => $congregacao]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Congregacao  $congregacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Congregacao $congregacao)
    {
        //
        $publicacoes = Publicacao::get();
        return view(
            'inventario.edit', [
                'congregacao' => $congregacao, 
                'publicacoes' => $publicacoes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Congregacao  $congregacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Congregacao $congregacao)
    {
        //
        $rules = [
            'publicacao.*' => 'required|integer|between:0,10000'
        ];

        $feedback = [
            'publicacao.*.required' => 'campo obrigatório',
            'publicacao.*.integer' => 'precisa ser inteiro',
            'publicacao.*.between' => 'precisa ser entre 0 e 10000'
        ];
        
        $request->validate($rules,$feedback);
        foreach ($request->all('publicacao')['publicacao'] as $publicacao => $quantidade) {
            if($quantidade > 0){
                $inventario = $this->inventario
                    ->where('congregacao_id', $congregacao->id)
                    ->where('publicacao_id', $publicacao)->first();
                if($inventario->quantidade != $quantidade){
                    $this->inventario
                        ->where('congregacao_id', $congregacao->id)
                        ->where('publicacao_id', $publicacao)
                        ->update(['quantidade' => $quantidade]);
                }
                if($inventario->local != $request->all('local')['local'][$publicacao]){
                    $this->inventario
                        ->where('congregacao_id', $congregacao->id)
                        ->where('publicacao_id', $publicacao)
                        ->update(['local' => $request->all('local')['local'][$publicacao]]);
                }
            }else{
                $this->inventario
                    ->where('congregacao_id', $congregacao->id)
                    ->where('publicacao_id', $publicacao)
                    ->delete();
            }

        }        
        return view('inventario.show', ['congregacao' => $congregacao]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventario $inventario)
    {
        //
    }
}
