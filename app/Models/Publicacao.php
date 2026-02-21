<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Publicacao extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table = 'publicacoes';  
    protected $fillable = [
        'nome',
        'observacao',
        'proporcao_cm',
        'proporcao_unidade',
        'codigo',
        'imagem'

    ];

    public static function rules($id){
        return [
            'nome' => 'required|unique:publicacoes,nome,'.$id.'|min:5',
            'codigo' => 'unique:publicacoes,codigo,'.$id.'|min:2|max:10',
            'proporcao_cm' => 'numeric|min:0|max:20',
            'proporcao_unidade' => 'numeric|integer|min:0|max:9999',
            //'imagem' => 'file|mimes:jpg',
        ];
    }
    public static function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O nome da publicação já existe',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres'
        ];
    }

    public function proporcao(){
        // $proporcao = null;
        // $proporcao .= $this->proporcao_cm ? $this->proporcao_cm . ' cm' : '';
        // $proporcao .= $this->proporcao_cm && $this->proporcao_unidade ? ' = ' : '';
        // $proporcao .= $this->proporcao_unidade ? $this->proporcao_unidade : '';
        // return $proporcao;
        if ($this->proporcao_unidade != 0) {
            $proporcao = $this->proporcao_cm / $this->proporcao_unidade;
        } else {
            $proporcao = 0; 
        }
        return $proporcao;        
    }
}
