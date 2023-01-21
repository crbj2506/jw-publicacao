<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    use HasFactory;
    protected $fillable = [
        'nota',
        'data',
        'retirada',
        'congregacao_id',
    ];
    public function rules($id){
        return [
            'nota' => 'required|unique:envios,nota,'.$id.'|min:7|max:10',
            'data' => 'nullable|date',
            'retirada' => 'nullable|date',
            'congregacao_id' => 'exists:congregacoes,id',
        ];
    }
    public function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nota.min' => 'O campo :attribute deve ter no mínimo 7 caracteres',
            'nota.max' => 'O campo :attribute deve ter no máximo 10 caracteres',
        ];
    }
    // 1 Envio tem 1 ou mais volumes
    public function volumes(){
        return $this->hasMany('App\Models\Volume');
    }
    // O envio pertence a uma congregação
    public function congregacao(){
        return $this->belongsTo('App\Models\Congregacao');
    }
}
