<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class LocalController extends Controller
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

        // Inicia a query ordenada por Sigla por padrão
        $locais = Local::orderBy('sigla');

        // Filtrar pela congregação ativa
        $locais->where('congregacao_id', $congregacaoId);

        if (!empty($nomeFiltro)) {
            $locais->where('nome', 'like', '%' . $nomeFiltro . '%');
        }

        $locais = $locais->paginate($perpage);

        // Atribui os valores ao objeto para que a View possa recuperar e manter os campos preenchidos
        $locais->nomeFiltro = $nomeFiltro;
        $locais->perpage = $perpage;
        $locais->filtros = $request->all('nome', 'perpage');

        return view('local.crud',['locais' => $locais]);
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
        $congregacoes = Congregacao::where('id', $congregacaoId)
            ->orderBy('nome')
            ->select('id as value', 'nome as text')
            ->get();
        return view('local.crud',['congregacoes' => $congregacoes]);
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
        // Se vier do modal, validar com nome_local e depois remapear
        if ($request->has('nome_local')) {
            $rules = [
                'sigla' => 'required|unique:locais,sigla,NULL,id,congregacao_id,'.$congregacaoId,
                'nome_local' => 'required|unique:locais,nome,NULL,id,congregacao_id,'.$congregacaoId,
            ];
            $messages = [
                'required' => 'O campo :attribute é obrigatório',
                'unique' => 'O valor para :attribute já existe',
            ];
            $validated = $request->validate($rules, $messages);
            // Remapear nome_local para nome
            $validated['nome'] = $validated['nome_local'];
            unset($validated['nome_local']);
            $validated['congregacao_id'] = $congregacaoId;
            $local = Local::create($validated);
            // Redirecionar de volta se foi enviado pelo modal
            if ($request->has('redirect_to') && $request->input('redirect_to') === 'back') {
                return redirect()->back();
            }
        } else {
            // Validação normal para criação via rota direta
            $request->validate(Local::rules($id = null, $congregacaoId), Local::feedback());
            $dados = $request->all();
            $dados['congregacao_id'] = $congregacaoId;
            $local = Local::create($dados);
        }
        return redirect()->route('local.show', ['local' => $local]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Local  $local
     * @return \Illuminate\Contracts\View\View
     */
    public function show($local)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();
        $local = Local::find($local);
        if (!$local) {
            return back()->with('error', 'Local não encontrado');
        }
        if ($local->congregacao_id !== $congregacaoId) {
            abort(403, 'Sem permissão para acessar este local');
        }
        $congregacoes = Congregacao::where('id', $congregacaoId)->orderBy('nome')->get();
        if(Route::current()->action['as'] == "local.show"){
            $local->show = true;
        };
        return view('local.crud', ['local' => $local, 'congregacoes' => $congregacoes]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Local  $local
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Local $local)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();
        if ($local->congregacao_id !== $congregacaoId) {
            abort(403, 'Sem permissão para acessar este local');
        }
        if(Route::current()->action['as'] == "local.edit"){
            $local->edit = true;
        };
        $congregacoes = Congregacao::where('id', $congregacaoId)
            ->orderBy('nome')
            ->select('id as value', 'nome as text')
            ->get();
        return view('local.crud', ['local' => $local, 'congregacoes' => $congregacoes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Local  $local
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $local)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();
        $localObj = Local::find($local);
        if (!$localObj) {
            return back()->with('error', 'Local não encontrado');
        }
        if ($localObj->congregacao_id !== $congregacaoId) {
            abort(403, 'Sem permissão para atualizar este local');
        }
        $request->validate(Local::rules($local, $localObj->congregacao_id), Local::feedback());
        $dados = $request->all();
        $dados['congregacao_id'] = $localObj->congregacao_id;
        $localObj->update($dados);
        return redirect()->route('local.show', ['local' => $localObj]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Local  $local
     * @return \Illuminate\Http\Response
     */
    public function destroy(Local $local)
    {
        $user = Auth::user();
        $congregacaoId = congregacaoAtivaId();
        if ($local->congregacao_id !== $congregacaoId) {
            abort(403, 'Sem permissão para excluir este local');
        }
        if ($local->temPublicacoes()) {
            return redirect()->route('local.index')->withErrors('Este local contém publicações e não pode ser excluído.');
        }
        
        $local->delete();
        return redirect()->route('local.index')->with('success', 'Local excluído com sucesso.');
    }
}
