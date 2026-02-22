<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Local extends Model
{
    use HasFactory, Auditable;
    protected $table = 'locais'; 
    protected $fillable = [
        'sigla',
        'nome',
        'congregacao_id',
    ];
    public static function rules($id){
        return [
            'sigla' => 'required|unique:locais,sigla,'.$id,
            'nome' => 'required|unique:locais,nome,'.$id,
            'congregacao_id' => 'required|exists:congregacoes,id',
        ];
    }
    public static function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório'
        ];
    }
    public function congregacao(){
        //Um Local pertence a uma Congregação
        return $this->belongsTo('App\Models\Congregacao');
    }

    public function estoques(){
        return $this->hasMany('App\Models\Estoque');
    }

    public function temPublicacoes(){
        return $this->estoques()->count() > 0;
    }
}
