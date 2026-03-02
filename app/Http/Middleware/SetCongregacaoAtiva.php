<?php

namespace App\Http\Middleware;

use App\Models\Congregacao;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetCongregacaoAtiva
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->ehAdmin()) {
                $congregacaoAtivaId = $request->session()->get('congregacao_ativa_id');

                if (!$congregacaoAtivaId || !Congregacao::whereKey($congregacaoAtivaId)->exists()) {
                    $congregacaoAtivaId = $user->congregacao_id;
                    $request->session()->put('congregacao_ativa_id', $congregacaoAtivaId);
                }

                // Mantem o contexto ativo somente em memoria (sem gravar no banco)
                $user->setAttribute('congregacao_id', $congregacaoAtivaId);
                $user->syncOriginalAttribute('congregacao_id');
                Auth::setUser($user);
            }
        }

        return $next($request);
    }
}
