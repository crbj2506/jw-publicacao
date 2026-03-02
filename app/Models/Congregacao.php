<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Congregacao extends Model
{
    use HasFactory, Auditable;
    protected $table = 'congregacoes';  
    protected $fillable = [
        'nome'
    ];
    public static function rules($id){
        return [
            'nome' => 'required|unique:congregacoes,nome,'.$id.'|min:3'
        ];
    }
    public static function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.min' => 'O campo :attribute deve ter no mínimo 3 caracteres'
        ];
    }

    public function envios(){
        return $this->hasMany('App\Models\Envio');
    }

    public function locais(){
        return $this->hasMany('App\Models\Local');
    }

    public function inventarios(){
        return $this->hasMany('App\Models\Inventario');
    }

    // ==================== NOVOS RELACIONAMENTOS ====================

    /**
     * Todos os usuários desta congregação
     */
    public function users() {
        return $this->hasMany('App\Models\User');
    }

    /**
     * Todas as pessoas (irmãos) desta congregação
     */
    public function pessoas() {
        return $this->hasMany('App\Models\Pessoa');
    }

    /**
     * Retorna o Ancião desta congregação (se houver)
     */
    public function anciao() {
        return $this->users()
            ->whereHas('permissoes', function($query) {
                $query->where('permissao', 'Ancião');
            })
            ->first();
    }

    /**
     * Retorna todos os Anciões desta congregação
     */
    public function ancioes() {
        return $this->users()
            ->whereHas('permissoes', function($query) {
                $query->where('permissao', 'Ancião');
            });
    }

    /**
     * Retorna todos os Servos desta congregação
     */
    public function servidores() {
        return $this->users()
            ->whereHas('permissoes', function($query) {
                $query->where('permissao', 'Servo');
            });
    }

}
