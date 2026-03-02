<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class PermissaoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$permissoesPermitidas)
    {
        $permissoes = User::find(auth()->user()->id)->permissoes;
        foreach ($permissoes as $permissaoUser) {
            if (in_array($permissaoUser->permissao, $permissoesPermitidas, true)) {
                return $next($request);
            }
        }
        return Response()->view('auth.acesso-negado');
    }
}
