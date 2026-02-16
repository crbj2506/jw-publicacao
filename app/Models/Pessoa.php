<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Pessoa extends Model
{
    use HasFactory, Auditable;
    protected $fillable = [
        'nome'
    ];
    public static function rules($id){
        return [
            'nome' => 'required|unique:pessoas,nome,'.$id.'|min:3'
        ];
    }
    public static function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.min' => 'O campo :attribute deve ter no mínimo 3 caracteres'
        ];
    }

}
