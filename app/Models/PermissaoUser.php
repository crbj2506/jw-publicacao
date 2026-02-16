<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class PermissaoUser extends Model
{
    use HasFactory, Auditable;
    protected $table = 'permissoes_users';  
    protected $fillable = [
        'permissao_id',
        'user_id',
    ];
}