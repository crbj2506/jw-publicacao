<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->permissoes->contains('permissao', 'Administrador');

        $query = Auditoria::with('user')->orderByDesc('created_at');

        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        } elseif ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('evento')) {
            $query->where('evento', $request->evento);
        }
        if ($request->filled('recurso')) {
            $query->where('auditable_type', 'like', '%' . $request->recurso . '%');
        }

        $perpage = $request->input('perpage', 10);
        $auditorias = $query->paginate($perpage);
        
        $users = $isAdmin ? User::orderBy('name')->get() : collect();

        return view('auditoria.index', compact('auditorias', 'users'));
    }
}
