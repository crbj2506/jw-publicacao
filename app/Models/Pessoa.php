<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Pessoa extends Model
{
    use HasFactory, Auditable, SoftDeletes;
    protected $fillable = [
        'nome',
        'congregacao_id'
    ];
    public static function rules($id, $congregacao_id = null){
        return [
            'nome' => 'required|unique:pessoas,nome,'.$id.',id,congregacao_id,'.$congregacao_id.'|min:3'
        ];
    }
    public static function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.min' => 'O campo :attribute deve ter no mínimo 3 caracteres'
        ];
    }

    // ==================== RELACIONAMENTOS ====================

    /**
     * Uma Pessoa pertence a uma Congregação
     */
    public function congregacao() {
        return $this->belongsTo('App\Models\Congregacao');
    }

}
