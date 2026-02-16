<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Volume extends Model
{
    use HasFactory, Auditable;
    protected $fillable = [
        'volume',
        'envio_id',
    ];
    public static function rules($id){
        return [
            'volume' => 'required',
            'envio_id' => 'required|exists:envios,id',
        ];
    }
    public static function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório'
        ];
    }
    // O volume ou vários pertencem a um envio
    public function envio(){
        return $this->belongsTo('App\Models\Envio');
    }
    // Vários Volumes podem ter várias Publicações (tabela auxiliar)
    public function publicacoes(){
        //Publicações pertencem a muitos Volumes (tabela auxiliar)
        return $this->belongsToMany('App\Models\Publicacao', 'conteudos')->withPivot('quantidade','updated_at');
    }

    public function conteudos(){
        return $this->hasMany('App\Models\Conteudo');
    }

}
