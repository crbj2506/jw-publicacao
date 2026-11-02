<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Traits\Auditable;

class Inventario extends Model
{
    use HasFactory, Auditable;
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
            'recebido' => 'required|numeric|min:0|max:19999',
            'estoque' => 'required|numeric|min:0|max:19999',
            'saida' => 'required|numeric|min:0|max:19999',
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

        $ultimoInventario = self::select('ano','mes')
            ->where('congregacao_id', $congregacao_id)
            ->orderByDesc('ano')
            ->orderByDesc('mes')
            ->first();

        $anoAnteriorAInventariar = null;
        $mesAnteriorAInventariar = null;

        if($ultimoInventario){
            $aInventariar = Carbon::create((int) $ultimoInventario->ano, (int) $ultimoInventario->mes, 1)->addMonthNoOverflow();
        }else{
            $aInventariar = Carbon::now()->startOfMonth();
            $anterior = $aInventariar->copy()->subMonthNoOverflow();
            $anoAnteriorAInventariar = $anterior->format('Y');
            $mesAnteriorAInventariar = $anterior->format('m');
        }

        return [
            'anoAInventariar' => $aInventariar->format('Y'),
            'mesAInventariar' => $aInventariar->format('m'),
            'anoAnteriorAInventariar' => $anoAnteriorAInventariar,
            'mesAnteriorAInventariar' => $mesAnteriorAInventariar,
        ];

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
