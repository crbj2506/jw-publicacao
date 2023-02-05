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
    public static function rulesInventariar($ano,$mes,$congregacao_id,$id){
        $anoMesUltimoInventariado  = self::defineAnoMesUltimoInventariado($congregacao_id);
        $anoAnteriorAInventariar = $anoMesUltimoInventariado['anoAnteriorAInventariar'];
        $anoAInventariar = $anoMesUltimoInventariado['anoAInventariar'];
        $mesAnteriorAInventariar = $anoMesUltimoInventariado['mesAnteriorAInventariar'];
        $mesAInventariar = $anoMesUltimoInventariado['mesAInventariar'];
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
                Rule::in(["$anoAnteriorAInventariar","$anoAInventariar"]),

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
                Rule::in(["$mesAnteriorAInventariar","$mesAInventariar"]),

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

    public static function rulesUpdate(){
        return [
            'recebido' => 'required|numeric|min:0|max:9999',
            'estoque' => 'required|numeric|min:0|max:9999',
            'saida' => 'required|numeric|min:0|max:9999',
        ];
    }
    public static function feedback($congregacao_id){
        $anoMesUltimoInventariado  = self::defineAnoMesUltimoInventariado($congregacao_id);
        $anoAnteriorAInventariar = $anoMesUltimoInventariado['anoAnteriorAInventariar'];
        $anoAInventariar = $anoMesUltimoInventariado['anoAInventariar'];
        $mesAnteriorAInventariar = $anoMesUltimoInventariado['mesAnteriorAInventariar'];
        $mesAInventariar = $anoMesUltimoInventariado['mesAInventariar'];

        if(($anoAnteriorAInventariar == $anoAInventariar) || empty($anoAnteriorAInventariar)){
            $ano = $anoAInventariar;
        }else{
            $ano = $anoAnteriorAInventariar . ' ou ' . $anoAInventariar;
        }
        if($mesAnteriorAInventariar == $mesAInventariar || empty($mesAnteriorAInventariar)){
            $mes = $mesAInventariar;
        }else{
            $mes = $mesAnteriorAInventariar . ' ou ' . $mesAInventariar;
        }

        return [
            'required' => 'O campo :attribute é obrigatório',
            'ano.in' => "O campo Ano deve ser $ano",
            'mes.in' => "O campo Mês deve ser $mes",
        ];
    }

    ######################################################
    # Funções de Internas

    public static function defineAnoMesUltimoInventariado($congregacao_id){

        $anomesUltimoInventariado = self::select('ano','mes')->where('congregacao_id', $congregacao_id)->orderBy('ano')->orderBy('mes')->get()->last();
        if($anomesUltimoInventariado){
            $anomesUltimoInventariado = $anomesUltimoInventariado->getAttributes();
            $anoUltimoInventariado = $anomesUltimoInventariado['ano'];
            $mesUltimoInventariado = $anomesUltimoInventariado['mes'];
            if($mesUltimoInventariado == '12'){
                $anoAInventariar = (string) ((int) $anoUltimoInventariado +1);
                $mesAInventariar = '01';
            }elseif($mesUltimoInventariado == '09' || $mesUltimoInventariado == '10' || $mesUltimoInventariado == '11'){
                $anoAInventariar = $anoUltimoInventariado;
                $mesAInventariar = (string) ((int) $mesUltimoInventariado +1);
            }else{
                $anoAInventariar = $anoUltimoInventariado;
                $mesAInventariar = '0'.(string) ((int) $mesUltimoInventariado +1);
            }
        }else{
            $anoAInventariar = date("Y");
            $mesAInventariar = date("m");
            if($mesAInventariar == '01'){
                $anoAnteriorAInventariar = (string) ((int) $anoAInventariar -1);
                $mesAnteriorAInventariar = '12';
            }elseif($mesAInventariar == '11' || $mesAInventariar == '12'){
                $anoAnteriorAInventariar = $anoAInventariar;
                $mesAnteriorAInventariar = (string) ((int) $mesAInventariar -1);
            }else{
                $anoAnteriorAInventariar = $anoAInventariar;
                $mesAnteriorAInventariar = '0'.(string) ((int) $mesAInventariar -1);
            }
        }
        return ['anoAInventariar' => isset($anoAInventariar)?$anoAInventariar:null, 'mesAInventariar' => isset($mesAInventariar)?$mesAInventariar:null, 'anoAnteriorAInventariar' => isset($anoAnteriorAInventariar)?$anoAnteriorAInventariar:null, 'mesAnteriorAInventariar' => isset($mesAnteriorAInventariar)?$mesAnteriorAInventariar:null];

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
