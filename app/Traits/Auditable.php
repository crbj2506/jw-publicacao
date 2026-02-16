<?php

namespace App\Traits;

use App\Models\Auditoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            $model->audit('criado');
        });

        static::updated(function ($model) {
            $model->audit('atualizado');
        });

        static::deleted(function ($model) {
            $model->audit('excluído');
        });
    }

    protected function audit($evento)
    {
        $valoresAntigos = null;
        $valoresNovos = null;

        if ($evento === 'atualizado') {
            $valoresAntigos = array_intersect_key($this->getOriginal(), $this->getDirty());
            $valoresNovos = $this->getDirty();
        } elseif ($evento === 'criado') {
            $valoresNovos = $this->getAttributes();
        } elseif ($evento === 'excluído') {
            $valoresAntigos = $this->getAttributes();
        }

        Auditoria::create([
            'user_id' => Auth::id(),
            'evento' => $evento,
            'auditable_type' => get_class($this),
            'auditable_id' => $this->id,
            'valores_antigos' => $valoresAntigos,
            'valores_novos' => $valoresNovos,
            'url' => Request::fullUrl(),
            'ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}