<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    protected $log;
    //
    public function __construct(Log $log){
        $this->log = $log;
    }
    public function index(Request $request)
    {
        $tipoFiltro = null;
        $origemFiltro = null;
        $rotaFiltro = null;
        $usuarioFiltro = null;
        $perpage = 10;

        if (empty($request->query()) && $request->method() == 'GET') {
            $request->session()->forget(['tipoFiltro', 'origemFiltro', 'rotaFiltro', 'usuarioFiltro', 'perpage']);
        }

        // Captura de filtros da Request ou Sessão
        foreach(['tipo', 'origem', 'rota', 'usuario'] as $f) {
            $var = $f . 'Filtro';
            if ($request->has($f)) {
                $$var = $request->input($f);
                $request->session()->put($var, $$var);
            } elseif ($request->session()->exists($var)) {
                $$var = $request->session()->get($var);
            }
        }

        if ($request->has('perpage')) {
            $perpage = $request->input('perpage');
            $request->session()->put('perpage', $perpage);
        } elseif ($request->session()->exists('perpage')) {
            $perpage = $request->session()->get('perpage');
        }

        $logs = $this->log->orderByDesc('created_at');

        if (!empty($tipoFiltro)) $logs->where('tipo', 'like', "%$tipoFiltro%");
        if (!empty($origemFiltro)) $logs->where('ip_origem', 'like', "%$origemFiltro%");
        if (!empty($rotaFiltro)) $logs->where('rota', 'like', "%$rotaFiltro%");
        if (!empty($usuarioFiltro)) {
            $logs->whereRelation('user', 'name', 'like', "%$usuarioFiltro%");
        }

        $logs = $logs->paginate($perpage);

        $logs->tipoFiltro = $tipoFiltro;
        $logs->origemFiltro = $origemFiltro;
        $logs->rotaFiltro = $rotaFiltro;
        $logs->usuarioFiltro = $usuarioFiltro;
        $logs->perpage = $perpage;

        return view('log.index',[ 'logs' => $logs ]);
    }
}