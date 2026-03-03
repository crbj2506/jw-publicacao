<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use App\Models\Permissao;
use App\Models\PermissaoUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

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

        // Servo e Publicador não podem visualizar usuários
        if (!$user->ehAdmin() && !$user->ehAnciao()) {
            abort(403, 'Sem permissão para acessar usuários');
        }

        $nameFiltro = null;
        $emailFiltro = null;
        $perpage = 10; // Padrão 10 conforme solicitado

        if (empty($request->query()) && $request->method() == 'GET') {
            $request->session()->forget(['nameFiltro', 'emailFiltro', 'perpage']);
        }

        if ($request->has('name')) {
            $nameFiltro = $request->input('name');
            $request->session()->put('nameFiltro', $nameFiltro);
        } elseif ($request->session()->exists('nameFiltro')) {
            $nameFiltro = $request->session()->get('nameFiltro');
        }

        if ($request->has('email')) {
            $emailFiltro = $request->input('email');
            $request->session()->put('emailFiltro', $emailFiltro);
        } elseif ($request->session()->exists('emailFiltro')) {
            $emailFiltro = $request->session()->get('emailFiltro');
        }

        if ($request->has('perpage')) {
            $perpage = $request->input('perpage');
            $request->session()->put('perpage', $perpage);
        } elseif ($request->session()->exists('perpage')) {
            $perpage = $request->session()->get('perpage');
        }

        // Ordenação alfabética por padrão
        $users = User::orderBy('name', 'asc');

        // Filtrar pela congregação ativa
        $users->where('congregacao_id', $congregacaoId);

        if (!empty($nameFiltro)) $users->where('name', 'like', "%$nameFiltro%");
        if (!empty($emailFiltro)) $users->where('email', 'like', "%$emailFiltro%");

        $users = $users->paginate($perpage);

        $users->nameFiltro = $nameFiltro;
        $users->emailFiltro = $emailFiltro;
        $users->perpage = $perpage;

        return view('user.crud', ['users' => $users]);
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

        // Apenas Admin e Ancião podem criar usuários
        if (!$user->ehAdmin() && !$user->ehAnciao()) {
            abort(403, 'Sem permissão para criar usuários');
        }

        // Buscar permissões que o usuário pode atribuir
        $ordemPermissoes = ['Administrador' => 1, 'Ancião' => 2, 'Servo' => 3, 'Publicador' => 4];
        
        if ($user->ehAdmin()) {
            // Admin pode atribuir qualquer permissão
            $permissoes = Permissao::whereIn('permissao', ['Administrador', 'Ancião', 'Servo', 'Publicador'])
                ->get()
                ->sortBy(function($p) use ($ordemPermissoes) {
                    return $ordemPermissoes[$p->permissao] ?? 999;
                })
                ->values();
            // Admin usa congregação ativa
            $congregacoes = Congregacao::where('id', $congregacaoId)->get();
        } else {
            // Ancião pode atribuir apenas Ancião, Servo, Publicador (não Admin)
            $permissoes = Permissao::whereIn('permissao', ['Ancião', 'Servo', 'Publicador'])
                ->get()
                ->sortBy(function($p) use ($ordemPermissoes) {
                    return $ordemPermissoes[$p->permissao] ?? 999;
                })
                ->values();
            // Ancião vê apenas sua congregação (hidden)
            $congregacoes = collect([$user->congregacao]);
        }

        $novoUser = new User();
        return view('user.crud', [
            'user' => $novoUser,
            'permissoes' => $permissoes,
            'congregacoes' => $congregacoes,
            'isCreating' => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $loggedUser = Auth::user();
        $congregacaoId = congregacaoAtivaId();

        // Apenas Admin e Ancião podem criar usuários
        if (!$loggedUser->ehAdmin() && !$loggedUser->ehAnciao()) {
            abort(403, 'Sem permissão para criar usuários');
        }

        // Validar dados base
        $request->validate(User::rules($id = null), User::feedback());

        // Validar permissões selecionadas (checkboxes)
        $permissoesDisponiveis = Permissao::all();
        $permissoesSelecionadas = $permissoesDisponiveis->filter(function($p) use ($request) {
            return $request->has("permissao_{$p->id}");
        });

        if ($permissoesSelecionadas->isEmpty()) {
            return back()->with('error', 'Selecione pelo menos um perfil');
        }

        // Validar se o usuário logado pode atribuir essas permissões
        if ($loggedUser->ehAdmin()) {
            // Admin: pode criar qualquer permissão
            $permissoesPermitidas = ['Administrador', 'Ancião', 'Servo', 'Publicador'];
        } else {
            // Ancião: pode criar Ancião, Servo, Publicador (NÃO Admin)
            $permissoesPermitidas = ['Ancião', 'Servo', 'Publicador'];
        }

        $temNaoPermitida = $permissoesSelecionadas->contains(function($p) use ($permissoesPermitidas) {
            return !in_array($p->permissao, $permissoesPermitidas, true);
        });

        if ($temNaoPermitida) {
            return back()->with('error', 'Não tem permissão para atribuir este perfil');
        }

        // Criar usuário
        $dados = $request->all('name', 'email', 'password');
        $dados['password'] = Hash::make($dados['password']);
        $dados['congregacao_id'] = $congregacaoId;
        $dados['created_by_user_id'] = $loggedUser->id;

        $novoUser = User::create($dados);

        // Atribuir permissões selecionadas
        $novoUser->permissoes()->attach($permissoesSelecionadas->pluck('id')->all());

        $novoUser->sendEmailVerificationNotification();

        return redirect()
            ->route('user.show', ['user' => $novoUser->id])
            ->with('status', 'Usuário criado! Verifique seu e-mail para confirmar a conta.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();
        $targetUser = User::find($id);

        if (!$targetUser) {
            abort(404, 'Usuário não encontrado');
        }

        // Servo e Publicador não podem visualizar usuários
        if (!$user->ehAdmin() && !$user->ehAnciao()) {
            abort(403, 'Sem permissão para visualizar usuários');
        }

        if ($targetUser->congregacao_id !== $congregacaoId) {
            abort(403, 'Você pode visualizar apenas usuários de sua congregação');
        }

        if (Route::current()->action['as'] == "user.show") {
            $targetUser->show = true;
        }
        
        $ordemPermissoes = ['Administrador' => 1, 'Ancião' => 2, 'Servo' => 3, 'Publicador' => 4];
        $permissoes = Permissao::get()->sortBy(function($p) use ($ordemPermissoes) {
            return $ordemPermissoes[$p->permissao] ?? 999;
        })->values();
        $congregacoes = Congregacao::where('id', $congregacaoId)->get();
        return view('user.crud', [
            'user' => $targetUser, 
            'permissoes' => $permissoes,
            'congregacoes' => $congregacoes
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(User $user)
    {
        $loggedUser = Auth::user();
        $congregacaoId = congregacaoAtivaId();

        // Servo e Publicador não podem editar usuários
        if (!$loggedUser->ehAdmin() && !$loggedUser->ehAnciao()) {
            abort(403, 'Sem permissão para editar usuários');
        }

        if ($user->congregacao_id !== $congregacaoId) {
            abort(403, 'Você pode editar apenas usuários de sua congregação');
        }

        if (!$loggedUser->ehAdmin() && $user->ehAdmin()) {
            abort(403, 'Ancião não pode editar permissões de um Administrador');
        }

        if (Route::current()->action['as'] == "user.edit") {
            $user->edit = true;
        }

        // Buscar permissões apropriadas
        $ordemPermissoes = ['Administrador' => 1, 'Ancião' => 2, 'Servo' => 3, 'Publicador' => 4];
        
        if ($loggedUser->ehAdmin()) {
            $permissoes = Permissao::all()->sortBy(function($p) use ($ordemPermissoes) {
                return $ordemPermissoes[$p->permissao] ?? 999;
            })->values();
            $congregacoes = Congregacao::where('id', $congregacaoId)->get();
        } else {
            // Ancião pode atribuir Ancião, Servo, Publicador (não Admin)
            $permissoes = Permissao::whereIn('permissao', ['Ancião', 'Servo', 'Publicador'])
                ->get()
                ->sortBy(function($p) use ($ordemPermissoes) {
                    return $ordemPermissoes[$p->permissao] ?? 999;
                })
                ->values();
            $congregacoes = collect([$loggedUser->congregacao]);
        }

        return view('user.crud', [
            'user' => $user,
            'permissoes' => $permissoes,
            'congregacoes' => $congregacoes
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $loggedUser = Auth::user();
        $congregacaoId = congregacaoAtivaId();
        $targetUser = User::find($id);

        if (!$targetUser) {
            return back()->with('error', 'Usuário não encontrado');
        }

        // Servo e Publicador não podem editar usuários
        if (!$loggedUser->ehAdmin() && !$loggedUser->ehAnciao()) {
            abort(403, 'Sem permissão para editar usuários');
        }

        if ($targetUser->congregacao_id !== $congregacaoId) {
            abort(403, 'Você pode editar apenas usuários de sua congregação');
        }

        if (!$loggedUser->ehAdmin() && $targetUser->ehAdmin()) {
            abort(403, 'Ancião não pode alterar permissões de um Administrador');
        }

        $request->request->remove('congregacao_id');

        // Validar dados
        $request->validate(User::rules_update($id), User::feedback());
        $dados = $request->all();
        $dados['congregacao_id'] = $targetUser->congregacao_id;
        $targetUser->update($dados);

        // Atualizar permissões
        $ordemPermissoes = ['Administrador' => 1, 'Ancião' => 2, 'Servo' => 3, 'Publicador' => 4];

        if ($loggedUser->ehAdmin()) {
            $permissoesPermitidas = ['Administrador', 'Ancião', 'Servo', 'Publicador'];
        } else {
            $permissoesPermitidas = ['Ancião', 'Servo', 'Publicador'];
        }

        $permissoes = Permissao::get()->sortBy(function($p) use ($ordemPermissoes) {
            return $ordemPermissoes[$p->permissao] ?? 999;
        })->values();
        foreach ($permissoes as $p) {
            if (!in_array($p->permissao, $permissoesPermitidas, true)) {
                continue;
            }

            $temPermissao = $request->has("permissao_{$p->id}");
            $jaTem = $targetUser->permissoes()->where('permissao_id', $p->id)->exists();

            // Se está marcado e não tem, adiciona
            if ($temPermissao && !$jaTem) {
                $targetUser->permissoes()->attach($p->id);
            }
            // Se não está marcado e tem, remove
            elseif (!$temPermissao && $jaTem) {
                $targetUser->permissoes()->detach($p->id);
            }
        }

        return redirect()->route('user.show', ['user' => $targetUser->id])
                        ->with('status', 'Usuário atualizado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $loggedUser = Auth::user();
        $congregacaoId = congregacaoAtivaId();

        // Servo e Publicador não podem deletar usuários
        if (!$loggedUser->ehAdmin() && !$loggedUser->ehAnciao()) {
            abort(403, 'Sem permissão para deletar usuários');
        }

        // Ancião só pode deletar usuários de sua congregação
        if (!$loggedUser->ehAdmin() && $user->congregacao_id !== $congregacaoId) {
            abort(403, 'Você pode deletar apenas usuários de sua congregação');
        }

        if (!$loggedUser->ehAdmin() && $user->ehAdmin()) {
            abort(403, 'Ancião não pode excluir um Administrador');
        }

        // Regra de negócio: só permite excluir se o e-mail do usuário não foi verificado.
        if ($user->email_verified_at !== null) {
            return redirect()
                ->route('user.index')
                ->with('error', 'Não é possível excluir um usuário com e-mail verificado.');
        }

        $user->delete();

        return redirect()
            ->route('user.index')
            ->with('status', 'Usuário excluído com sucesso.');
    }
}