<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use App\Traits\Auditable;

class Pedido extends Model
{
    use HasFactory, Auditable;
    protected $fillable = [
        'pessoa_id',
        'publicacao_id',
        'congregacao_id',
        'quantidade',
        'solicitado',
        'entregue',
    ];
    public static function rules($pessoa_id,$publicacao_id,$id){
        return [
            'pessoa_id' => [
                'required',
                'exists:pessoas,id',
                Rule::unique('pedidos')->where(function ($query) use($pessoa_id,$publicacao_id,$id) {
                    return $query
                        ->where('pessoa_id', $pessoa_id)
                        ->where('publicacao_id', $publicacao_id)
                        ->whereNull('entregue')
                        ->where('id', '!=', $id);
                }),

            ],
            'publicacao_id' => [
                'required',
                'exists:publicacoes,id',
                Rule::unique('pedidos')->where(function ($query) use($pessoa_id,$publicacao_id,$id) {
                    return $query
                        ->where('pessoa_id', $pessoa_id)
                        ->where('publicacao_id', $publicacao_id)
                        ->whereNull('entregue')
                        ->where('id', '!=', $id);
                }),

            ],
            'quantidade' => 'required|numeric|min:1|max:9999',
            'solicitado' => 'required|date',
            'entregue' => 'nullable|date',
        ];
    }
    public static function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'pessoa_id.unique' => 'Já existe um pedido pendente para esta pessoa com esta publicação',
            'publicacao_id.unique' => 'Já existe um pedido pendente para esta pessoa com esta publicação',
        ];
    }
    public function pessoa(){
        //Um Pedido pertence a uma Pessoa
        return $this->belongsTo('App\Models\Pessoa');
    }
    public function publicacao(){
        //Um Pedido pertence a uma Publicação
        return $this->belongsTo('App\Models\Publicacao');
    }

    public function congregacao(){
        //Um Pedido pertence a uma Congregação
        return $this->belongsTo('App\Models\Congregacao');
    }
}
