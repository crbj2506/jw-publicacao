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
        'imagem',
        'congregacao_id'
    ];

    public static function rules($id, $congregacao_id = null){
        return [
            'nome' => 'required|unique:publicacoes,nome,'.$id.',id,congregacao_id,'.$congregacao_id.'|min:5',
            'codigo' => 'unique:publicacoes,codigo,'.$id.',id,congregacao_id,'.$congregacao_id.'|min:2|max:10',
            'proporcao_cm' => 'numeric|min:0|max:50|required_with:proporcao_unidade',
            'proporcao_unidade' => 'numeric|integer|min:0|max:9999|required_with:proporcao_cm',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:512', // 512 KB = 500 KB
        ];
    }
    public static function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O nome da publicação já existe',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres',
            'proporcao_cm.required_with' => 'O campo proporção (cm) é obrigatório quando proporção (unidade) é informada',
            'proporcao_unidade.required_with' => 'O campo proporção (unidade) é obrigatório quando proporção (cm) é informada',
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

    public function estoques(){
        //Uma Publicação possui vários Estoques
        return $this->hasMany('App\\Models\\Estoque');
    }

    public function congregacao(){
        return $this->belongsTo('App\\Models\\Congregacao');
    }
}
