<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class PessoaController extends Controller
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
        $nomeFiltro = null;
        $perpage = 10; // Padrão 10 conforme solicitado

        // Limpa os filtros da sessão se o acesso for direto (GET sem parâmetros)
        if (empty($request->query()) && $request->method() == 'GET') {
            $request->session()->forget('nomeFiltro');
            $request->session()->forget('perpage');
        }

        // Lógica para o filtro de Nome
        if ($request->has('nome')) {
            $nomeFiltro = $request->input('nome');
            $request->session()->put('nomeFiltro', $nomeFiltro);
        } elseif ($request->session()->exists('nomeFiltro')) {
            $nomeFiltro = $request->session()->get('nomeFiltro');
        }

        // Lógica para o número de itens por página (perpage)
        if ($request->has('perpage')) {
            $perpage = $request->input('perpage');
            $request->session()->put('perpage', $perpage);
        } elseif ($request->session()->exists('perpage')) {
            $perpage = $request->session()->get('perpage');
        }

        // Inicia a query ordenada por nome (ordem alfabética) por padrão
        // Se for administrador, inclui também as pessoas deletadas
        $pessoas = $user->ehAdmin() ? Pessoa::withTrashed()->orderBy('nome') : Pessoa::orderBy('nome');

        // Filtrar pela congregação ativa
        $pessoas->where('congregacao_id', $congregacaoId);

        if (!empty($nomeFiltro)) {
            $pessoas->where('nome', 'like', '%' . $nomeFiltro . '%');
        }

        $pessoas = $pessoas->paginate($perpage);

        // Atribui os valores ao objeto para que a View possa recuperar e manter os campos preenchidos
        $pessoas->nomeFiltro = $nomeFiltro;
        $pessoas->perpage = $perpage;
        $pessoas->filtros = $request->all('nome', 'perpage');
        
        // Passa para a view se o usuário é administrador
        $pessoas->isAdmin = $user->ehAdmin();

        return view('pessoa.crud', ['pessoas' => $pessoas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $user = Auth::user();

        // Apenas Admin, Ancião e Servo podem criar pessoas
        // Publicador não pode criar
        if (!$user->ehAdmin() && !$user->ehAnciao() && !$user->ehServidor()) {
            abort(403, 'Sem permissão para criar pessoas');
        }

        $pessoa = new Pessoa();
        return view('pessoa.crud', ['pessoa' => $pessoa]);
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

        // Apenas Admin, Ancião e Servo podem criar pessoas
        if (!$user->ehAdmin() && !$user->ehAnciao() && !$user->ehServidor()) {
            abort(403, 'Sem permissão para criar pessoas');
        }

        $request->validate(Pessoa::rules($id = null, $congregacaoId), Pessoa::feedback());
        
        // Preparar dados
        $dados = $request->all();
        
        // Preencher congregacao_id automaticamente pela congregação ativa
        $dados['congregacao_id'] = $congregacaoId;

        $pessoa = Pessoa::create($dados);
        return redirect()->route('pessoa.show', ['pessoa' => $pessoa]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pessoa  $pessoa
     * @return \Illuminate\Contracts\View\View
     */
    public function show($pessoa)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();
        $pessoa = Pessoa::withTrashed()->find($pessoa);

        if (!$pessoa) {
            abort(404, 'Pessoa não encontrada');
        }

        if ($pessoa->congregacao_id !== $congregacaoId) {
            abort(403, 'Você pode visualizar apenas pessoas de sua congregação');
        }

        if (Route::current()->action['as'] == "pessoa.show") {
            $pessoa->show = true;
        }

        $pessoa->isAdmin = $user->ehAdmin();
        return view('pessoa.crud', ['pessoa' => $pessoa]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pessoa  $pessoa
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Pessoa $pessoa)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();

        // Apenas Admin, Ancião e Servo podem editar pessoas
        if (!$user->ehAdmin() && !$user->ehAnciao() && !$user->ehServidor()) {
            abort(403, 'Sem permissão para editar pessoas');
        }

        if ($pessoa->congregacao_id !== $congregacaoId) {
            abort(403, 'Você pode editar apenas pessoas de sua congregação');
        }

        if (Route::current()->action['as'] == "pessoa.edit") {
            $pessoa->edit = true;
        }

        $pessoa->isAdmin = $user->ehAdmin();
        return view('pessoa.crud', ['pessoa' => $pessoa]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pessoa  $pessoa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $pessoa)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();
        $pessoa = Pessoa::find($pessoa);

        if (!$pessoa) {
            return back()->with('error', 'Pessoa não encontrada');
        }

        // Apenas Admin, Ancião e Servo podem editar pessoas
        if (!$user->ehAdmin() && !$user->ehAnciao() && !$user->ehServidor()) {
            abort(403, 'Sem permissão para editar pessoas');
        }

        if ($pessoa->congregacao_id !== $congregacaoId) {
            abort(403, 'Você pode editar apenas pessoas de sua congregação');
        }

        $request->validate(Pessoa::rules($pessoa->id, $pessoa->congregacao_id), Pessoa::feedback());
        
        $dados = $request->all();
        
        // Impedir que congregacao_id seja alterada
        $dados['congregacao_id'] = $pessoa->congregacao_id;

        $pessoa->update($dados);
        return redirect()->route('pessoa.show', ['pessoa' => $pessoa]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pessoa  $pessoa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Pessoa $pessoa)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();

        // Apenas Admin e Ancião podem deletar pessoas
        if (!$user->ehAdmin() && !$user->ehAnciao()) {
            return redirect()->route('pessoa.index')
                            ->with('error', 'Apenas Administrador e Ancião podem deletar pessoas');
        }

        if ($pessoa->congregacao_id !== $congregacaoId) {
            return redirect()->route('pessoa.index')
                            ->with('error', 'Você pode deletar apenas pessoas de sua congregação');
        }

        $pessoa->delete();
        return redirect()->route('pessoa.index')->with('success', 'Pessoa deletada com sucesso');
    }

    /**
     * Restore the specified resource from soft delete.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();

        // Apenas Admin pode restaurar pessoas
        if (!$user->ehAdmin()) {
            return redirect()->route('pessoa.index')
                            ->with('error', 'Apenas administrador pode restaurar pessoas');
        }

        $pessoa = Pessoa::withTrashed()->findOrFail($id);
        if ($pessoa->congregacao_id !== $congregacaoId) {
            return redirect()->route('pessoa.index')
                            ->with('error', 'Você pode restaurar apenas pessoas de sua congregação');
        }
        $pessoa->restore();
        return redirect()->route('pessoa.index')->with('success', 'Pessoa restaurada com sucesso');
    }
}
