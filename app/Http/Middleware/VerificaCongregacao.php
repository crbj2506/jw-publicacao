<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerificaCongregacao
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se user está autenticado
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Admin pode acessar tudo (sem restrição de congregacao_id)
        if ($user->ehAdmin()) {
            return $next($request);
        }

        // Para não-Admin: verificar se tem congregacao_id atribuída
        if (!$user->congregacao_id) {
            abort(403, 'Sua conta ainda não foi atribuída a nenhuma congregação. Contate um Administrador.');
        }

        // Usuário tem congregacao_id válida, continuar
        return $next($request);
    }
}
