<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Congregacao extends Model
{
    use HasFactory;
    protected $table = 'congregacoes';  
    protected $fillable = [
        'nome'
    ];
    public function rules($id){
        return [
            'nome' => 'required|unique:congregacoes,nome,'.$id.'|min:3'
        ];
    }
    public function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.min' => 'O campo :attribute deve ter no mínimo 3 caracteres'
        ];
    }
    // Várias Congregações podem ter várias Publicações (tabela auxiliar)
    public function publicacoes(){
        //Publicações pertencem a muitas Conngregações (tabela auxiliar)
        return $this->belongsToMany('App\Models\Publicacao', 'inventarios')->withPivot('quantidade','local');
    }

}
