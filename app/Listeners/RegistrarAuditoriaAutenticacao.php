<?php

namespace App\Listeners;

use App\Models\Auditoria;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Request;

class RegistrarAuditoriaAutenticacao
{
    public function handle($event)
    {
        $evento = '';
        if ($event instanceof Login) {
            $evento = 'login';
        } elseif ($event instanceof Logout) {
            $evento = 'logout';
        }

        if ($evento) {
            Auditoria::create([
                'user_id'    => $event->user->id,
                'evento'     => $evento,
                'url'        => Request::fullUrl(),
                'ip'         => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        }
    }
}