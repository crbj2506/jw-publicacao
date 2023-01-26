<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Inventario extends Model
{
    use HasFactory;
    protected $fillable = [
        'ano',
        'mes',
        'congregacao_id',
        'publicacao_id',
        'recebido',
        'estoque',
        'saida',
    ];
    public function rulesInventariar($ano,$mes,$congregacao_id,$id){
        $this->defineAnoMesUltimoInventariado($congregacao_id);
        return [
            'ano' => [
                'required',
                'min:4',
                'max:4',
                Rule::unique('inventarios')->where(function ($query) use($ano,$mes,$congregacao_id) {
                    return $query
                        ->where('ano', $ano)
                        ->where('mes', $mes)
                        ->where('congregacao_id', $congregacao_id);
                }),
                Rule::in(["$this->anoAnteriorAInventariar","$this->anoAInventariar"]),

            ],
            'mes' => [
                'required',
                'min:2',
                'max:2',
                Rule::unique('inventarios')->where(function ($query) use($ano,$mes,$congregacao_id) {
                    return $query
                        ->where('ano', $ano)
                        ->where('mes', $mes)
                        ->where('congregacao_id', $congregacao_id);
                }),
                Rule::in(["$this->mesAnteriorAInventariar","$this->mesAInventariar"]),

            ],
            'congregacao_id' => [
                'required',
                'exists:congregacoes,id',
                Rule::unique('inventarios')->where(function ($query) use($ano,$mes,$congregacao_id) {
                    return $query
                        ->where('ano', $ano)
                        ->where('mes', $mes)
                        ->where('congregacao_id', $congregacao_id);
                }),

            ],
        ];
    }

    public function rulesUpdate(){
        return [
            'recebido' => 'required|numeric|min:0|max:9999',
            'estoque' => 'required|numeric|min:0|max:9999',
            'saida' => 'required|numeric|min:0|max:9999',
        ];
    }
    public function feedback(){
        if(($this->anoAnteriorAInventariar == $this->anoAInventariar) || empty($this->anoAnteriorAInventariar)){
            $ano = $this->anoAInventariar;
        }else{
            $ano = $this->anoAnteriorAInventariar . ' ou ' . $this->anoAInventariar;
        }
        if($this->mesAnteriorAInventariar == $this->mesAInventariar || empty($this->mesAnteriorAInventariar)){
            $mes = $this->mesAInventariar;
        }else{
            $mes = $this->mesAnteriorAInventariar . ' ou ' . $this->mesAInventariar;
        }

        return [
            'required' => 'O campo :attribute é obrigatório',
            'ano.in' => "O campo Ano deve ser $ano",
            'mes.in' => "O campo Mês deve ser $mes",
        ];
    }

    ######################################################
    # Funções de Internas

    public function defineAnoMesUltimoInventariado($congregacao_id){

        $anomesUltimoInventariado = $this->select('ano','mes')->where('congregacao_id', $congregacao_id)->orderBy('ano')->orderBy('mes')->get()->last();
        if($anomesUltimoInventariado){
            $anomesUltimoInventariado = $anomesUltimoInventariado->getAttributes();
            $anoUltimoInventariado = $anomesUltimoInventariado['ano'];
            $mesUltimoInventariado = $anomesUltimoInventariado['mes'];
            if($mesUltimoInventariado == '12'){
                $this->anoAInventariar = (string) ((int) $anoUltimoInventariado +1);
                $this->mesAInventariar = '01';
            }elseif($mesUltimoInventariado == '09' || $mesUltimoInventariado == '10' || $mesUltimoInventariado == '11'){
                $this->anoAInventariar = $anoUltimoInventariado;
                $this->mesAInventariar = (string) ((int) $mesUltimoInventariado +1);
            }else{
                $this->anoAInventariar = $anoUltimoInventariado;
                $this->mesAInventariar = '0'.(string) ((int) $mesUltimoInventariado +1);
            }
        }else{
            $this->anoAInventariar = date("Y");
            $this->mesAInventariar = date("m");
            if($this->mesAInventariar == '01'){
                $this->anoAnteriorAInventariar = (string) ((int) $this->anoAInventariar -1);
                $this->mesAnteriorAInventariar = '12';
            }elseif($this->mesAInventariar == '11' || $this->mesAInventariar == '12'){
                $this->anoAnteriorAInventariar = $this->anoAInventariar;
                $this->mesAnteriorAInventariar = (string) ((int) $this->mesAInventariar -1);
            }else{
                $this->anoAnteriorAInventariar = $this->anoAInventariar;
                $this->mesAnteriorAInventariar = '0'.(string) ((int) $this->mesAInventariar -1);
            }
        }

    }
    

    ######################################################
    # Funções de Relacionamento
    
    public function congregacao(){
        //Um inventário pertence a uma Conngregação
        return $this->belongsTo('App\Models\Congregacao');
    }
    public function publicacao(){
        //Um inventário pertence a uma Publicação
        return $this->belongsTo('App\Models\Publicacao');
    }
}
