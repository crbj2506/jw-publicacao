<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $fillable = [
        'user_id',
        'evento',
        'auditable_type',
        'auditable_id',
        'valores_antigos',
        'valores_novos',
        'url',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'valores_antigos' => 'json',
        'valores_novos' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}