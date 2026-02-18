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
            'volume' => [
                'required',
                'regex:/^Volume ([1-9][0-9]?|100) de ([1-9][0-9]?|100) - Caixa ([1-9][0-9]{0,2})$/',
                function ($attribute, $value, $fail) {
                    if (preg_match('/^Volume (\d+) de (\d+) - Caixa \d+$/', $value, $matches)) {
                        $a = (int) $matches[1];
                        $b = (int) $matches[2];
                        if ($a > $b) {
                            $fail('O número do volume (A) não pode ser maior que o total de volumes (B).');
                        }
                    }
                },
            ],
            'envio_id' => 'required|exists:envios,id',
        ];
    }
    public static function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'volume.regex' => 'O formato deve ser "Volume A de B - Caixa C" (Ex: Volume 1 de 10 - Caixa 5), respeitando os limites (A e B até 100, Caixa até 999).',
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
