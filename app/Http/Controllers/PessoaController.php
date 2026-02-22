<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class PessoaController extends Controller
{
    /**
     * Check if the current user is an administrator.
     *
     * @return bool
     */
    private function isAdmin()
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        $permissoes = $user->permissoes;
        foreach ($permissoes as $permissao) {
            if ($permissao->permissao === 'Administrador') {
                return true;
            }
        }
        return false;
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
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
        $pessoas = $this->isAdmin() ? Pessoa::withTrashed()->orderBy('nome') : Pessoa::orderBy('nome');

        if (!empty($nomeFiltro)) {
            $pessoas->where('nome', 'like', '%' . $nomeFiltro . '%');
        }

        $pessoas = $pessoas->paginate($perpage);

        // Atribui os valores ao objeto para que a View possa recuperar e manter os campos preenchidos
        $pessoas->nomeFiltro = $nomeFiltro;
        $pessoas->perpage = $perpage;
        $pessoas->filtros = $request->all('nome', 'perpage');
        
        // Passa para a view se o usuário é administrador
        $pessoas->isAdmin = $this->isAdmin();

        return view('pessoa.crud',['pessoas' => $pessoas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        return view('pessoa.crud');
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
        $request->validate(Pessoa::rules($id = null),Pessoa::feedback());
        $pessoa = Pessoa::create($request->all());
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
        //
        $pessoa = Pessoa::withTrashed()->find($pessoa);
        if(Route::current()->action['as'] == "pessoa.show"){
            $pessoa->show = true;
        };
        $pessoa->isAdmin = $this->isAdmin();
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
        //
        if(Route::current()->action['as'] == "pessoa.edit"){
            $pessoa->edit = true;
        };
        $pessoa->isAdmin = $this->isAdmin();
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
        //
        $request->validate(Pessoa::rules($pessoa ),Pessoa::feedback());
        $pessoa = Pessoa::find($pessoa);
        $pessoa->update($request->all());
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
        if (!$this->isAdmin()) {
            return redirect()->route('pessoa.index')->withErrors('Apenas administradores podem excluir pessoas.');
        }
        
        $pessoa->delete();
        return redirect()->route('pessoa.index')->with('success', 'Pessoa excluída com sucesso.');
    }
    
    /**
     * Restore the specified resource from soft delete.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('pessoa.index')->withErrors('Apenas administradores podem restaurar pessoas.');
        }
        
        $pessoa = Pessoa::withTrashed()->findOrFail($id);
        $pessoa->restore();
        return redirect()->route('pessoa.index')->with('success', 'Pessoa restaurada com sucesso.');
    }
}
