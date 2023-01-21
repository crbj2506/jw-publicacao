<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volume extends Model
{
    use HasFactory;
    protected $fillable = [
        'volume',
        'envio_id',
    ];
    public function rules($id){
        return [
            'volume' => 'required',
            'envio_id' => 'required|exists:envios,id',
        ];
    }
    public function feedback(){
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

}
