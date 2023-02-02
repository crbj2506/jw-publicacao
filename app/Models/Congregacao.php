<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Congregacao extends Model
{
    use HasFactory;
    protected $table = 'congregacoes';  
    protected $fillable = [
        'nome'
    ];
    public static function rules($id){
        return [
            'nome' => 'required|unique:congregacoes,nome,'.$id.'|min:3'
        ];
    }
    public static function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.min' => 'O campo :attribute deve ter no mínimo 3 caracteres'
        ];
    }

    public function envios(){
        return $this->hasMany('App\Models\Envio');
    }

    public function locais(){
        return $this->hasMany('App\Models\Local');
    }

    public function inventarios(){
        return $this->hasMany('App\Models\Inventario');
    }

}
