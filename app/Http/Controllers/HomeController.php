<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use App\Models\Inventario;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $congregacoes = Congregacao::orderBy('nome')->get();
        foreach ($congregacoes as $i1 => $congregacao) {
            $totalPublicacoes = null;
            $publicacoes_id = null;
            $local_id = null;
            $dataAlteracaoEstoque = null;
            $ultimoInventario = null;
            foreach($congregacao->inventarios as $i21 => $inventario){
                $ultimoInventario = $inventario->select('ano','mes')->where('congregacao_id', $congregacao->id)->orderBy('ano')->orderBy('mes')->get()->last();
            }
            if(!isset($ultimoInventario)){
                $ultimoInventario = new Inventario;
                $ultimoInventario->ano = null;
                $ultimoInventario->mes = null;
            }
            foreach($congregacao->locais as $i22 => $local){
                $local_id[] = $local->id;
                foreach($local->estoques as $i3 => $estoque){
                    $publicacoes_id[] = $estoque->publicacao_id;
                    $totalPublicacoes = $totalPublicacoes + $estoque->quantidade;
                    $dataAlteracaoEstoque = $dataAlteracaoEstoque > $estoque->updated_at ? $dataAlteracaoEstoque : $estoque->updated_at;
                }
            }
            $congregacoes[$i1]->totalPublicacoes = $totalPublicacoes;
            $publicacoes_id = array_unique($publicacoes_id);
            $congregacoes[$i1]->totalItens = count($publicacoes_id);
            $congregacoes[$i1]->totalLocais = count($local_id);
            $congregacoes[$i1]->dataAlteracaoEstoque = $dataAlteracaoEstoque;
            $congregacoes[$i1]->ultimoInventario = $ultimoInventario;
            $congregacoes[$i1]->enviosQuantidade = $congregacao->envios->count();
        }
        //dd($congregacoes, $publicacoes_id, $totalPublicacoes);
        return view('home',['congregacoes' => $congregacoes]);
    }
}
