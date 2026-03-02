<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Pessoa;
use App\Models\Publicacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();
        $pessoaFiltro = null;
        $publicacaoFiltro = null;
        $perpage = 10;

        if (empty($request->query()) && $request->method() == 'GET') {
            $request->session()->forget(['pessoaFiltro', 'publicacaoFiltro', 'perpage']);
        }

        if ($request->has('pessoa')) {
            $pessoaFiltro = $request->input('pessoa');
            $request->session()->put('pessoaFiltro', $pessoaFiltro);
        } elseif ($request->session()->exists('pessoaFiltro')) {
            $pessoaFiltro = $request->session()->get('pessoaFiltro');
        }

        if ($request->has('publicacao')) {
            $publicacaoFiltro = $request->input('publicacao');
            $request->session()->put('publicacaoFiltro', $publicacaoFiltro);
        } elseif ($request->session()->exists('publicacaoFiltro')) {
            $publicacaoFiltro = $request->session()->get('publicacaoFiltro');
        }

        if ($request->has('perpage')) {
            $perpage = $request->input('perpage');
            $request->session()->put('perpage', $perpage);
        } elseif ($request->session()->exists('perpage')) {
            $perpage = $request->session()->get('perpage');
        }

        // Ordenar: não entregues mais antigos primeiro, depois entregues mais recentes
        $pedidos = Pedido::orderByRaw('CASE WHEN entregue IS NULL THEN 0 ELSE 1 END')
                         ->orderBy('solicitado', 'asc')
                         ->orderBy('entregue', 'desc');

        // Filtrar pela congregação ativa do usuário
        $pedidos->where('congregacao_id', $congregacaoId);

        if (!empty($pessoaFiltro)) {
            $pedidos->whereRelation('pessoa', 'nome', 'like', "%$pessoaFiltro%");
        }
        if (!empty($publicacaoFiltro)) {
            $pedidos->whereRelation('publicacao', 'nome', 'like', "%$publicacaoFiltro%");
        }

        $pedidos = $pedidos->paginate($perpage);

        $pedidos->pessoaFiltro = $pessoaFiltro;
        $pedidos->publicacaoFiltro = $publicacaoFiltro;
        $pedidos->perpage = $perpage;

        return view('pedido.crud', ['pedidos' => $pedidos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();

        $pessoas = Pessoa::where('congregacao_id', $congregacaoId)
                        ->orderBy('nome')
                        ->select('id as value', 'nome as text')
                        ->get();
        $publicacoes = Publicacao::where('congregacao_id', $congregacaoId)
                        ->orderBy('nome')
                        ->select('id as value', 'nome as text')
                        ->get();
        
        $pedido = new Pedido();
        return view('pedido.crud', ['pedido' => $pedido, 'pessoas' => $pessoas, 'publicacoes' => $publicacoes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();

        $request->validate(Pedido::rules($request->all('pessoa_id'), $request->all('publicacao_id'), $id = null), Pedido::feedback());

        // Validar que a pessoa pertence à congregação ativa
        $pessoa = Pessoa::find($request->input('pessoa_id'));
        if (!$pessoa || $pessoa->congregacao_id !== $congregacaoId) {
            return back()->with('error', 'Pessoa não encontrada ou não pertence a sua congregação');
        }

        // Preparar dados
        $dados = $request->all();
        
        // Preencher congregacao_id automaticamente pela congregação ativa
        $dados['congregacao_id'] = $congregacaoId;

        $pedido = Pedido::create($dados);
        return redirect()->route('pedido.show', ['pedido' => $pedido]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Contracts\View\View
     */
    public function show($pedido)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();
        $pedido = Pedido::find($pedido);

        if (!$pedido) {
            abort(404, 'Pedido não encontrado');
        }

        if ($pedido->congregacao_id !== $congregacaoId) {
            abort(403, 'Você pode visualizar apenas pedidos de sua congregação');
        }

        // Buscar dados para a view (congregação ativa)
        $pessoas = Pessoa::where('congregacao_id', $congregacaoId)->orderBy('nome')->get();
        $publicacoes = Publicacao::where('congregacao_id', $congregacaoId)->orderBy('nome')->get();

        if (Route::current()->action['as'] == "pedido.show") {
            $pedido->show = true;
        }
        
        return view('pedido.crud', ['pedido' => $pedido, 'pessoas' => $pessoas, 'publicacoes' => $publicacoes]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Pedido $pedido)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();

        if ($pedido->congregacao_id !== $congregacaoId) {
            abort(403, 'Você pode editar apenas pedidos de sua congregação');
        }

        // Buscar dados para a view (congregação ativa)
        $pessoas = Pessoa::where('congregacao_id', $congregacaoId)
                        ->orderBy('nome')
                        ->select('id as value', 'nome as text')
                        ->get();
        $publicacoes = Publicacao::where('congregacao_id', $congregacaoId)
                        ->orderBy('nome')
                        ->select('id as value', 'nome as text')
                        ->get();

        if (Route::current()->action['as'] == "pedido.edit") {
            $pedido->edit = true;
        }

        return view('pedido.crud', ['pedido' => $pedido, 'pessoas' => $pessoas, 'publicacoes' => $publicacoes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $pedido)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();
        $pedido = Pedido::find($pedido);

        if (!$pedido) {
            return back()->with('error', 'Pedido não encontrado');
        }

        if ($pedido->congregacao_id !== $congregacaoId) {
            abort(403, 'Você pode editar apenas pedidos de sua congregação');
        }

        $request->validate(Pedido::rules($request->all('pessoa_id'), $request->all('publicacao_id'), $pedido->id), Pedido::feedback());

        // Validar que a pessoa pertence à congregação ativa
        $pessoa = Pessoa::find($request->input('pessoa_id'));
        if (!$pessoa || $pessoa->congregacao_id !== $congregacaoId) {
            return back()->with('error', 'Pessoa não encontrada ou não pertence a sua congregação');
        }

        $dados = $request->all();
        
        // Impedir alteração de congregacao_id
        $dados['congregacao_id'] = $pedido->congregacao_id;

        $pedido->update($dados);
        return redirect()->route('pedido.show', ['pedido' => $pedido]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Pedido $pedido)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();

        if ($pedido->congregacao_id !== $congregacaoId) {
            abort(403, 'Você pode deletar apenas pedidos de sua congregação');
        }

        $pedido->delete();
        return redirect()->route('pedido.index')->with('success', 'Pedido deletado com sucesso');
    }
}
