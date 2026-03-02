<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CongregacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();

        // Servo e Publicador não podem acessar congregações
        if (!$user->ehAdmin() && !$user->ehAnciao()) {
            abort(403, 'Sem permissão para acessar congregações');
        }

        // Admin vê todas as congregações
        if ($user->ehAdmin()) {
            $congregacoes = Congregacao::paginate(50);
        } 
        // Ancião vê apenas sua congregação
        else {
            $congregacoes = Congregacao::where('id', $congregacaoId)->paginate(50);
        }

        return view('congregacao.crud', ['congregacoes' => $congregacoes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $user = Auth::user();

        // Apenas Admin pode criar congregações
        if (!$user->ehAdmin()) {
            abort(403, 'Apenas Administrador pode criar congregações');
        }

        return view('congregacao.crud');
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

        // Apenas Admin pode criar congregações
        if (!$user->ehAdmin()) {
            abort(403, 'Apenas Administrador pode criar congregações');
        }

        $request->validate(Congregacao::rules($id = null), Congregacao::feedback());
        $congregacao = Congregacao::create($request->all());
        return redirect()->route('congregacao.show', ['congregacao' => $congregacao]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Congregacao  $congregacao
     * @return \Illuminate\Contracts\View\View
     */
    public function show($congregacao)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();
        $congregacao = Congregacao::find($congregacao);

        if (!$congregacao) {
            abort(404, 'Congregação não encontrada');
        }

        // Servo e Publicador não podem visualizar
        if (!$user->ehAdmin() && !$user->ehAnciao()) {
            abort(403, 'Sem permissão para visualizar congregações');
        }

        // Ancião só pode ver sua congregação
        if (!$user->ehAdmin() && $congregacaoId !== $congregacao->id) {
            abort(403, 'Você pode visualizar apenas sua congregação');
        }

        if (Route::current()->action['as'] == "congregacao.show") {
            $congregacao->show = true;
        }
        return view('congregacao.crud', ['congregacao' => $congregacao]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Congregacao  $congregacao
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Congregacao $congregacao)
    {
        $user = Auth::user();

        // Apenas Admin pode editar congregações
        if (!$user->ehAdmin()) {
            abort(403, 'Apenas Administrador pode editar congregações');
        }

        if (Route::current()->action['as'] == "congregacao.edit") {
            $congregacao->edit = true;
        }
        return view('congregacao.crud', ['congregacao' => $congregacao]);
    }

    /**
     * Define a congregação ativa para o Administrador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setAtiva(Request $request)
    {
        $user = Auth::user();

        if (!$user->ehAdmin()) {
            abort(403, 'Apenas Administrador pode alterar a congregação ativa');
        }

        $request->validate([
            'congregacao_ativa_id' => ['required', 'exists:congregacoes,id'],
        ]);

        $request->session()->put('congregacao_ativa_id', $request->input('congregacao_ativa_id'));

        return redirect()->back();
    }

    /**
     * Reseta a congregação ativa para a congregação padrão do Administrador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetAtiva(Request $request)
    {
        $user = Auth::user();

        if (!$user->ehAdmin()) {
            abort(403, 'Apenas Administrador pode alterar a congregação ativa');
        }

        $request->session()->forget('congregacao_ativa_id');

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Congregacao  $congregacao
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $congregacao)
    {
        $user = Auth::user();

        // Apenas Admin pode atualizar congregações
        if (!$user->ehAdmin()) {
            abort(403, 'Apenas Administrador pode atualizar congregações');
        }

        $request->validate(Congregacao::rules($congregacao), Congregacao::feedback());
        $congregacao = Congregacao::find($congregacao);
        $congregacao->update($request->all());
        return redirect()->route('congregacao.show', ['congregacao' => $congregacao]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Congregacao  $congregacao
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Congregacao $congregacao)
    {
        $user = Auth::user();

        // Apenas Admin pode deletar congregações
        if (!$user->ehAdmin()) {
            abort(403, 'Apenas Administrador pode deletar congregações');
        }

        $congregacao->delete();
        return redirect()->route('congregacao.index')
                        ->with('success', 'Congregação deletada com sucesso');
    }
}
