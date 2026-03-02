<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Auditable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'congregacao_id',
        'created_by_user_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public static function rules($id){
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'password' => ['required', 'string', 'min:8', 'confirmed']
            //'permissao' => 'required|unique:permissoes,permissao,'.$id.'|min:3'
        ];
    }

    public static function rules_update($id){
        return [
            'name' => ['required', 'string', 'max:255'],
            //'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            //'permissao' => 'required|unique:permissoes,permissao,'.$id.'|min:3'
        ];
    }
    public static function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'email' => 'Informe um e-mail válido', 
            'unique' => 'Este :attribute já está em uso', 
            'min' => 'O campo :attribute deve ter pelo menos :min caracteres', 
            'confirmed' => 'As senhas não conferem',
        ];
    }

    public function permissoes(){
        return $this->belongsToMany('App\Models\Permissao', 'permissoes_users');
    }

    // ==================== RELACIONAMENTOS ====================

    /**
     * Um User pertence a uma Congregação
     */
    public function congregacao() {
        return $this->belongsTo('App\Models\Congregacao');
    }

    /**
     * Usuários criados por este User (para auditoria)
     */
    public function usuariosCriados() {
        return $this->hasMany('App\Models\User', 'created_by_user_id');
    }

    /**
     * User que criou este usuário
     */
    public function criadoPor() {
        return $this->belongsTo('App\Models\User', 'created_by_user_id');
    }

    // ==================== SCOPES ====================

    /**
     * Retorna apenas usuários com congregação atribuída
     */
    public function scopeTemCongregacao($query) {
        return $query->whereNotNull('congregacao_id');
    }

    /**
     * Retorna apenas usuários SEM congregação atribuída (novos)
     */
    public function scopeSemCongregacao($query) {
        return $query->whereNull('congregacao_id');
    }

    // ==================== HELPERS DE PERMISSÃO ====================

    /**
     * Verifica se o usuário é Administrador
     */
    public function ehAdmin() {
        return $this->permissoes()
            ->where('permissao', 'Administrador')
            ->exists();
    }

    /**
     * Verifica se o usuário é Ancião
     */
    public function ehAnciao() {
        return $this->permissoes()
            ->where('permissao', 'Ancião')
            ->exists();
    }

    /**
     * Verifica se o usuário é Servo
     */
    public function ehServidor() {
        return $this->permissoes()
            ->where('permissao', 'Servo')
            ->exists();
    }

    /**
     * Verifica se o usuário é Publicador
     */
    public function ehPublicador() {
        return $this->permissoes()
            ->where('permissao', 'Publicador')
            ->exists();
    }

    /**
     * Retorna a permissão de maior nível do usuário
     */
    public function permissaoMaiorNivel() {
        $hierarquia = [
            'Administrador' => 1,
            'Ancião' => 2,
            'Servo' => 3,
            'Publicador' => 4,
        ];

        $permissoes = $this->permissoes()
            ->pluck('permissao')
            ->toArray();

        if (empty($permissoes)) {
            return 'Sem permissão';
        }

        $permissaoMaior = collect($permissoes)
            ->sort(function($a, $b) use ($hierarquia) {
                return $hierarquia[$a] - $hierarquia[$b];
            })
            ->first();

        return $permissaoMaior;
    }
}
