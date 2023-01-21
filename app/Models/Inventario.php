<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Inventario extends Model
{
    use HasFactory;
    protected $fillable = [
        'ano',
        'mes',
        'congregacao_id',
        'publicacao_id',
        'recebido',
        'estoque',
        'saida',
    ];
    public function rules($ano,$mes,$congregacao_id,$id){
        return [
            'ano' => [
                'required',
                'min:4',
                'max:4',
                Rule::unique('inventarios')->where(function ($query) use($ano,$mes,$congregacao_id,$id) {
                    return $query
                        ->where('ano', $ano)
                        ->where('mes', $mes)
                        ->where('congregacao_id', $congregacao_id)
                        ->where('id', '!=', $id);
                }),

            ],
            'mes' => [
                'required',
                'min:2',
                'max:2',
                Rule::unique('inventarios')->where(function ($query) use($ano,$mes,$congregacao_id,$id) {
                    return $query
                        ->where('ano', $ano)
                        ->where('mes', $mes)
                        ->where('congregacao_id', $congregacao_id)
                        ->where('id', '!=', $id);
                }),

            ],
            'congregacao_id' => [
                'required',
                'exists:congregacoes,id',
                Rule::unique('inventarios')->where(function ($query) use($ano,$mes,$congregacao_id,$id) {
                    return $query
                        ->where('ano', $ano)
                        ->where('mes', $mes)
                        ->where('congregacao_id', $congregacao_id)
                        ->where('id', '!=', $id);
                }),

            ],
        ];
    }

    public function rulesUpdate(){
        return [
            'ano' => [
                'required',
                'min:4',
                'max:4',
            ],
            'mes' => [
                'required',
                'min:2',
                'max:2',
            ],
            'congregacao_id' => [
                'required',
                'exists:congregacoes,id',
            ],
        ];
    }
    public function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório'
        ];
    }
    
    public function congregacao(){
        //Um inventário pertence a uma Conngregação
        return $this->belongsTo('App\Models\Congregacao');
    }
    public function publicacao(){
        //Um inventário pertence a uma Publicação
        return $this->belongsTo('App\Models\Publicacao');
    }
}
