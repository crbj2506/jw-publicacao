<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Estoque extends Model
{
    use HasFactory;
    protected $fillable = [
        'local_id',
        'publicacao_id',
        'quantidade',
    ];
    public function rules($local_id,$publicacao_id,$id){
        return [
            'local_id' => [
                'required',
                'exists:locais,id',
                Rule::unique('estoques')->where(function ($query) use($local_id,$publicacao_id,$id) {
                    return $query
                        ->where('local_id', $local_id)
                        ->where('publicacao_id', $publicacao_id)
                        ->where('id', '!=', $id);
                }),

            ],
            'publicacao_id' => [
                'required',
                'exists:publicacoes,id',
                Rule::unique('estoques')->where(function ($query) use($local_id,$publicacao_id,$id) {
                    return $query
                        ->where('local_id', $local_id)
                        ->where('publicacao_id', $publicacao_id)
                        ->where('id', '!=', $id);
                }),

            ],
            'quantidade' => 'required|numeric|min:0|max:9999',
        ];
    }
    public function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório'
        ];
    }
    public function local(){
        //Um Estoque pertence a um Local
        return $this->belongsTo('App\Models\Local');
    }
    public function publicacao(){
        //Um Estoque pertence a uma Publicação
        return $this->belongsTo('App\Models\Publicacao');
    }
}
