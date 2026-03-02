<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\User;
use App\Models\Congregacao; // Importar o modelo Congregacao
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $congregacaoId = congregacaoAtivaId();
        $isAdmin = $user->permissoes->contains('permissao', 'Administrador');
        $isAnciao = $user->permissoes->contains('permissao', 'Ancião');

        $query = Auditoria::with('user.congregacao')->orderByDesc('created_at');

        // Aplica o filtro de user_ids se preenchido
        if ($request->filled('user_ids')) {
            $selectedUserIds = $request->user_ids;
            // Remover qualquer valor vazio ou inválido
            $selectedUserIds = array_filter($selectedUserIds, function($id) {
                return !empty($id) && is_numeric($id);
            });
            
            if (!empty($selectedUserIds)) {
                $query->whereIn('user_id', $selectedUserIds);
            }
        }

        // Lógica de restrição de acesso baseada no perfil do usuário
        if (!$isAdmin) {
            if ($isAnciao) {
                // Ancião vê auditoria de usuários de sua congregação
                $query->whereHas('user', function ($q) use ($congregacaoId) {
                    $q->where('congregacao_id', $congregacaoId);
                });
            } else {
                // Outros usuários veem apenas seus próprios registros
                $query->where('user_id', $user->id);
            }
        } else {
            // Administrador pode filtrar por congregação
            if ($request->filled('congregacao_ids')) {
                $selectedCongregacaoIds = $request->congregacao_ids;
                // Remover qualquer valor vazio ou inválido
                $selectedCongregacaoIds = array_filter($selectedCongregacaoIds, function($id) {
                    return !empty($id) && is_numeric($id);
                });
                
                if (!empty($selectedCongregacaoIds)) {
                    $query->whereHas('user', function ($q) use ($selectedCongregacaoIds) {
                        $q->whereIn('congregacao_id', $selectedCongregacaoIds);
                    });
                }
            }
        }
        
        // Filtros independentes que funcionam para todos
        if ($request->filled('eventos')) {
            $selectedEventos = $request->eventos;
            // Remover valores vazios
            $selectedEventos = array_filter($selectedEventos, function($evento) {
                return !empty($evento);
            });
            
            if (!empty($selectedEventos)) {
                $query->whereIn('evento', $selectedEventos);
            }
        }
        if ($request->filled('recurso') && $request->recurso != '') {
            $query->where('auditable_type', 'like', '%' . $request->recurso . '%');
        }

        $perpage = $request->input('perpage', 10);
        $auditorias = $query->paginate($perpage);
        
        // Manter os parâmetros de filtro na paginação
        $auditorias->appends($request->except('page'));
        
        // Popular lista de usuários: Admin vê todos, Ancião vê de sua congregação
        $users = collect();
        if ($isAdmin) {
            $users = User::orderBy('name')->get()->map(function($user) {
                return ['value' => $user->id, 'text' => $user->name];
            });
        } elseif ($isAnciao) {
            $users = User::where('congregacao_id', $congregacaoId)->orderBy('name')->get()->map(function($user) {
                return ['value' => $user->id, 'text' => $user->name];
            });
        }

        // Popular lista de congregações para Administradores
        $congregacoes = $isAdmin ? Congregacao::orderBy('nome')->select('id as value', 'nome as text')->get() : collect();

        // Popular lista de eventos
        $eventos = collect([
            ['value' => 'login', 'text' => 'Login'],
            ['value' => 'logout', 'text' => 'Logout'],
            ['value' => 'criado', 'text' => 'Criado'],
            ['value' => 'atualizado', 'text' => 'Atualizado'],
            ['value' => 'excluído', 'text' => 'Excluído'],
        ]);

        return view('auditoria.index', compact('auditorias', 'users', 'congregacoes', 'eventos'));
    }
}
