<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('congregacaoAtivaId')) {
    /**
     * Retorna o ID da congregacao ativa (sessao) ou a congregacao do usuario.
     */
    function congregacaoAtivaId(): ?int
    {
        if (!Auth::check()) {
            return null;
        }

        $user = Auth::user();
        $defaultId = $user->congregacao_id;

        if (!$user->ehAdmin()) {
            return $defaultId;
        }

        $sessionId = session('congregacao_ativa_id');
        return $sessionId ? (int) $sessionId : $defaultId;
    }
}
