<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Publicacao extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'publicacoes';  
    protected $fillable = [
        'nome',
        'observacao',
        'proporcao_cm',
        'proporcao_unidade',
        'codigo',
        'item',
        'imagem'

    ];

    public function rules($id){
        return [
            'nome' => 'required|unique:publicacoes,nome,'.$id.'|min:5',
            'codigo' => 'unique:publicacoes,codigo,'.$id.'|min:2|max:10',
            'item' => 'nullable|sometimes|unique:publicacoes,item,'.$id.'|min:4|max:7',
            'proporcao_cm' => 'numeric|min:0|max:20',
            'proporcao_unidade' => 'numeric|integer|min:0|max:9999',
            //'imagem' => 'file|mimes:jpg',
        ];
    }
    public function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O nome da publicação já existe',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres'
        ];
    }
}
