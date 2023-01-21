<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use App\Models\Conteudo;
use App\Models\Envio;
use App\Models\Estoque;
use App\Models\Inventario;
use App\Models\Publicacao;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InventarioController extends Controller
{
    public $inventario;

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
        $inventarios = $this->inventario->paginate(50);
        return view('inventario.index',['inventarios' => $inventarios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $congregacoes = Congregacao::all();
        $publicacoes = Publicacao::all();
        return view('inventario.create',['congregacoes' => $congregacoes,'publicacoes' => $publicacoes]);
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
        $ano = $request->all('ano')['ano'];
        $mes = $request->all('mes')['mes'];
        $congregacao_id = (int) $request->all('congregacao_id')['congregacao_id'];
        $inventario['ano'] = $ano;
        $inventario['mes'] = $mes;
        $inventario['congregacao_id'] = $congregacao_id;
        $request->validate($this->inventario->rules($ano,$mes,$congregacao_id,$id = null), $this->inventario->feedback());

        // Ver se tem Estoque
        //$estoques = Estoque::select('publicacao_id', DB::raw('sum(quantidade) as quantidade_publicacao'))->groupBy('publicacao_id')->get();

        /*$estoque = DB::table('estoques')
            ->select('*')
            ->join('locais','locais.id', '=', 'estoques.local_id')
            ->where('locais.congregacao_id', $congregacao_id)
            ->get()
            ;
        dd($estoque);*/

        // Ver se tem Envios não Inventáriados
        $enviosNaoInventariados = Conteudo::select('*')
            ->join('volumes','volumes.id', '=', 'conteudos.volume_id')
            ->join('envios','envios.id', '=', 'volumes.envio_id')
            ->where('envios.congregacao_id', $congregacao_id)
            ->where('envios.inventariado',0)
            ->pluck('nota')
            ->unique();

        //dd('enviosNaoInventariados',$enviosNaoInventariados);

        // Inventário Anterior
        if($mes == '01'){
            $inventarioAnterior['ano'] = (string)((int) $ano -1);
            $inventarioAnterior['mes'] = '12';
        }elseif($mes == '10' | $mes == '11'){
            $inventarioAnterior['ano'] = (string) $ano;
            $inventarioAnterior['mes'] = (string)((int) $mes -1);
        }else{
            $inventarioAnterior['ano'] = (string) $ano;
            $inventarioAnterior['mes'] = '0'. (int) $mes -1;
        }

        $publicacoesEmEstoque = Estoque::select('*')
            ->join('locais','locais.id', '=', 'estoques.local_id')
            ->where('locais.congregacao_id', $congregacao_id)
            ->pluck('publicacao_id')
            ->unique();
        $publicacoesRecebidas = Conteudo::select('*')
            ->join('volumes','volumes.id', '=', 'conteudos.volume_id')
            ->join('envios','envios.id', '=', 'volumes.envio_id')
            ->where('envios.congregacao_id', $congregacao_id)
            ->where('envios.inventariado',0)
            ->pluck('publicacao_id')
            ->unique();
        $publicacoesParaInventariar = $publicacoesEmEstoque->merge($publicacoesRecebidas)->unique();

        //dd('Publicações Recebidas', $publicacoesRecebidas, 'Publicações em Estoque da Congragação', $publicacoesEmEstoque, 'Publicações para Inventarias', $publicacoesParaInventariar);
        //dd('Publicações em Estoque da Congragação', $publicacoesEmEstoque);

        foreach ($publicacoesParaInventariar as $key => $publicacao_id) {
            $recebido = (int) DB::table('conteudos')->select('*')
                ->join('volumes','volumes.id', '=', 'conteudos.volume_id')
                ->join('envios','envios.id', '=', 'volumes.envio_id')
                ->where('envios.congregacao_id',$congregacao_id)
                ->where('conteudos.publicacao_id', $publicacao_id)
                ->whereIn('envios.nota', $enviosNaoInventariados)
                ->sum('quantidade');
            
            $estoque = (int) DB::table('estoques')->select('*')
                ->join('locais', 'locais.id','=','estoques.local_id')
                ->where('locais.congregacao_id',$congregacao_id)
                ->where('estoques.publicacao_id', $publicacao_id)
                ->sum('quantidade');
                

            if(Inventario::where('ano', $inventarioAnterior['ano'])
                ->where('mes',$inventarioAnterior['mes'])
                ->where('congregacao_id', $congregacao_id)
                ->where('publicacao_id', $publicacao_id)
                ->get()
                ->isEmpty()
            ){
                $estoqueAnterior = 0;
            }else{
                $estoqueAnterior =  (int) Inventario::where('ano', $inventarioAnterior['ano'])
                    ->where('mes',$inventarioAnterior['mes'])
                    ->where('congregacao_id', $congregacao_id)
                    ->where('publicacao_id', $publicacao_id)
                    ->sum('estoque');
            }
                
            // SAÍDA = Estoque Anterior (da publicação) MAIS Recebido Atual MENOS Estoque Atual
            if($recebido == 0 && $estoqueAnterior == 0){
                $saida = 0;
            }else{
                $saida = $estoqueAnterior + $recebido - $estoque;
            }
            if($saida == 0 && $recebido == 0 && $estoque == 0){
                continue;
            }
            
            //dd(Inventario::where('ano', $inventarioAnterior['ano'])
            //->where('mes',$inventarioAnterior['mes'])
            //->where('publicacao_id', $publicacao_id)->get());
            $itemInventario = [
                'ano' => $ano,
                'mes' => $mes,
                'congregacao_id' => $congregacao_id,
                'publicacao_id' => $publicacao_id,
                'recebido' => $recebido,
                'estoque' => $estoque,
                'saida' => $saida,
            ];
            $this->inventario->create($itemInventario);

            $publicacoes[] = Publicacao::find($publicacao_id)->get();
        }
        foreach ($enviosNaoInventariados as $key => $nota) {
            Envio::where('nota',$nota)->update(['inventariado' => 1]);
        }
        return redirect()->route('inventario.mostra', ['ano' => $ano, 'mes' => $mes, 'congregacao_id' => $congregacao_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function mostra($ano,$mes,$congregacao_id)
    {
        //
        $inventarios = $this->inventario->where('ano', $ano)->where('mes', $mes)->where('congregacao_id', $congregacao_id)->paginate(10);
        
        //dd($ano,$mes,$congregacao_id, $inventarios);
        return view('inventario.index', ['inventarios' => $inventarios]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $inventario = $this->inventario->find($id);
        $congregacoes = Congregacao::all();
        $publicacoes = Publicacao::all();
        return view('inventario.show', ['inventario' => $inventario, 'congregacoes' => $congregacoes, 'publicacoes' => $publicacoes]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventario $inventario)
    {
        //
        $congregacoes = Congregacao::all();
        $publicacoes = Publicacao::all();
        return view('inventario.edit', ['inventario' => $inventario, 'congregacoes' => $congregacoes, 'publicacoes' => $publicacoes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate($this->inventario->rulesUpdate(),$this->inventario->feedback());
        $inventario = $this->inventario->find($id);
        $inventario->update($request->all());
        return redirect()->route('inventario.show', ['inventario' => $inventario->id]);
    }
















    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexOld()
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
    public function createOld(Congregacao $congregacao)
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
    public function storeOld(Request $request, Congregacao $congregacao)
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
            'congregacao' => $request->all('congregacao')['congregacao']]);

        return redirect()->route('inventario.show', ['congregacao' => $congregacao]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Congregacao  $congregacao
     * @return \Illuminate\Http\Response
     */
    public function showOld(Congregacao $congregacao)
    {
        return view('inventario.show', ['congregacao' => $congregacao]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Congregacao  $congregacao
     * @return \Illuminate\Http\Response
     */
    public function editOld(Congregacao $congregacao)
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
    public function updateOld(Request $request, Congregacao $congregacao)
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
                if($inventario->congregacao != $request->all('congregacao')['congregacao'][$publicacao]){
                    $this->inventario
                        ->where('congregacao_id', $congregacao->id)
                        ->where('publicacao_id', $publicacao)
                        ->update(['congregacao' => $request->all('congregacao')['congregacao'][$publicacao]]);
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
