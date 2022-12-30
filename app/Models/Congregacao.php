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
    public function rules($id){
        return [
            'nome' => 'required|unique:congregacoes,nome,'.$id.'|min:3'
        ];
    }
    public function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.min' => 'O campo :attribute deve ter no mínimo 3 caracteres'
        ];
    }

}
