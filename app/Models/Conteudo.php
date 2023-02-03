<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conteudo extends Model
{
    use HasFactory;
    protected $fillable = [
        'volume_id',
        'publicacao_id',
        'quantidade',
    ];
    public static function rules(){
        return [
            'volume_id' => 'required|exists:volumes,id',
            'publicacao_id' => 'required|exists:publicacoes,id',
            'quantidade' => 'required|min:1|max:9999',
        ];
    }
    public static function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório'
        ];
    }
    public function volume(){
        //Um Conteúdo pertence a um Volume
        return $this->belongsTo('App\Models\Volume');
    }
    public function publicacao(){
        //Um Conteúdo pertence a uma Publicação
        return $this->belongsTo('App\Models\Publicacao');
    }
}
