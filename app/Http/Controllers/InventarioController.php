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
use Illuminate\Support\Facades\App;
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
    public function index(Request $request)
    {
        //

        $congregacaoIdFiltro = null;
        $anoFiltro = null;
        $mesFiltro = null; 
        $publicacaoFiltro = null; 

        if(empty($request->query())){
            $request->session()->forget('congregacaoIdFiltro');
            $request->session()->forget('anoFiltro');
            $request->session()->forget('mesFiltro');
            $request->session()->forget('publicacaoFiltro');
        };

        $inventarios = $this->inventario
        ->orderByDesc('ano')
        ->orderByDesc('mes')
        ->orderBy(Publicacao::select('nome')
        ->whereColumn('publicacoes.id', 'inventarios.publicacao_id'));

        if($request->all('publicacao')['publicacao'] || $request->session()->exists('publicacaoFiltro')){
            $publicacaoFiltro = $request->session()->exists('publicacaoFiltro') ? $request->session()->get('publicacaoFiltro') : $request->all('publicacao')['publicacao'];
            if($request->all('publicacao')['publicacao']){
                $request->session()->put('publicacaoFiltro', $publicacaoFiltro);
            }
            $inventarios = $inventarios->whereRelation('publicacao', 'nome', 'like', '%'. $publicacaoFiltro. '%');
                
        }
        if($request->all('congregacao_id')['congregacao_id'] || $request->session()->exists('congregacaoIdFiltro')){
            $congregacaoIdFiltro = $request->session()->exists('congregacaoIdFiltro') ? $request->session()->get('congregacaoIdFiltro') : $request->all('congregacao_id')['congregacao_id'];
            if($request->all('congregacao_id')['congregacao_id']){
                $request->session()->put('congregacaoIdFiltro', $congregacaoIdFiltro);
            }
            $inventarios = $inventarios->where('congregacao_id', $congregacaoIdFiltro);
        }

        if($request->all('ano')['ano'] || $request->session()->exists('anoFiltro')){
            $anoFiltro = $request->session()->exists('anoFiltro') ? $request->session()->get('anoFiltro') : $request->all('ano')['ano'];
            if($request->all('ano')['ano']){
                $request->session()->put('anoFiltro', $anoFiltro);
            }
            $inventarios = $inventarios->where('ano', $anoFiltro);
        }

        if($request->all('mes')['mes'] || $request->session()->exists('mesFiltro')){
            $mesFiltro = $request->session()->exists('mesFiltro') ? $request->session()->get('mesFiltro') : $request->all('mes')['mes'];
            if($request->all('mes')['mes']){
                $request->session()->put('mesFiltro', $mesFiltro);
            }
            $inventarios = $inventarios->where('mes', $mesFiltro);
        }
        if(App::environment() == 'local'){
            $inventarios = $inventarios->paginate(10);
        }else{
            $inventarios = $inventarios->paginate(100);
        }
        $inventarios->filtros = $request->all('congregacao_id', 'ano', 'mes');
        $inventarios->congregacoesFiltro = Congregacao::select('id','nome')->orderBy('nome')->distinct()->get();
        $inventarios->anosFiltro = Inventario::select('ano')->orderBy('ano')->distinct()->get();
        $inventarios->mesesFiltro = Inventario::select('mes')->orderBy('mes')->distinct()->get();
        $inventarios->congregacaoIdFiltro = $congregacaoIdFiltro;
        $inventarios->anoFiltro = $anoFiltro;
        $inventarios->mesFiltro = $mesFiltro;
        $inventarios->publicacaoFiltro = $publicacaoFiltro;

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
        $congregacoes = Congregacao::orderBy('nome')->get();
        $publicacoes = Publicacao::orderBy('nome')->get();
        return view('inventario.create',['congregacoes' => $congregacoes,'publicacoes' => $publicacoes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function inventariar(Request $request)
    {
        //
        // Se não existe REQUEST mostra View inventariar
        if(!$request->all()){
            $congregacoes = Congregacao::orderBy('nome')->get();
            $publicacoes = Publicacao::orderBy('nome')->get();
            return view('inventario.inventariar',['congregacoes' => $congregacoes,'publicacoes' => $publicacoes]);
        }else{
            $ano = $request->all('ano')['ano'];
            $mes = $request->all('mes')['mes'];
            $congregacao_id = (int) $request->all('congregacao_id')['congregacao_id'];
            $inventario['ano'] = $ano;
            $inventario['mes'] = $mes;
            $inventario['congregacao_id'] = $congregacao_id;
            $request->validate($this->inventario->rulesInventariar($ano,$mes,$congregacao_id,$id = null), $this->inventario->feedback());

            // Ver se tem Envios não Inventáriados e com data de retirada
            $enviosNaoInventariados = Conteudo::select('*')
                ->join('volumes','volumes.id', '=', 'conteudos.volume_id')
                ->join('envios','envios.id', '=', 'volumes.envio_id')
                ->where('envios.congregacao_id', $congregacao_id)
                ->where('envios.inventariado',0)
                ->where('envios.retirada','!=',null)
                ->pluck('nota')
                ->unique();
    
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
            return redirect()->route('inventario.index');
        }
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
        $inventarios = $this->inventario->where('ano', $ano)->where('mes', $mes)->where('congregacao_id', $congregacao_id)
        ->orderByDesc('ano')
        ->orderByDesc('mes')
        ->orderBy(Publicacao::select('nome')
            ->whereColumn('publicacoes.id', 'inventarios.publicacao_id'))
        ->paginate(50);
        
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
        $congregacoes = Congregacao::orderBy('nome')->get();
        $publicacoes = Publicacao::orderBy('nome')->get();
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
        $congregacoes = Congregacao::orderBy('nome')->get();
        $publicacoes = Publicacao::orderBy('nome')->get();
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
        $ano = $request->all('ano')['ano'] ? $request->all('ano')['ano'] : null;
        $mes = $request->all('mes')['mes'] ? $request->all('mes')['mes'] : null;
        $congregacao_id = $request->all('congregacao_id')['congregacao_id'] ? $request->all('congregacao_id')['congregacao_id'] : null;
        $request->validate($this->inventario->rulesUpdate($ano,$mes,$congregacao_id,$id),$this->inventario->feedback());
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
