<?php

namespace App\Providers;

use App\Models\Congregacao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        error_reporting(E_ALL & ~E_DEPRECATED);

        View::composer('layouts.app', function ($view) {
            if (!Auth::check()) {
                return;
            }

            $user = Auth::user();

            if (!$user->ehAdmin()) {
                return;
            }

            $congregacoesAdmin = Congregacao::orderBy('nome')->select('id', 'nome')->get();
            $congregacaoPadraoId = $user->getOriginal('congregacao_id') ?? $user->congregacao_id;
            $congregacaoAtivaId = session('congregacao_ativa_id', $congregacaoPadraoId);
            $congregacaoAtiva = $congregacoesAdmin->firstWhere('id', $congregacaoAtivaId);

            $view->with('congregacoesAdmin', $congregacoesAdmin)
                ->with('congregacaoAtivaId', $congregacaoAtivaId)
                ->with('congregacaoAtiva', $congregacaoAtiva)
                ->with('congregacaoPadraoId', $congregacaoPadraoId);
        });
    }
}
