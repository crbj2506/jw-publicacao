<?php

namespace App\Http\Middleware;

use App\Models\PermissaoUser;
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
    public function handle(Request $request, Closure $next, $publicador,$servo,$administrador)
    {
        $permissoes = User::find(auth()->user()->id)->permissoes;
        foreach ($permissoes as $key => $permissoaUser) {
            if(
                $permissoaUser->permissao == $publicador ||
                $permissoaUser->permissao == $servo ||
                $permissoaUser->permissao == $administrador
            ){
                return $next($request);
            }
        }
        return Response()->view('auth.acesso-negado');
    }
}
