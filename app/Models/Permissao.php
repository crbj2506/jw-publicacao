<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Permissao extends Model
{
    use HasFactory, Auditable;
    protected $table = 'permissoes';  
    protected $fillable = [
        'permissao'
    ];
    public static function rules($id){
        return [
            'permissao' => 'required|unique:permissoes,permissao,'.$id.'|min:3'
        ];
    }
    public static function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'permissao.min' => 'O campo :attribute deve ter no mínimo 3 caracteres'
        ];
    }

    public function users(){
        return $this->belongsToMany('App\Models\User', 'permissoes_users');
    }
}
