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
}
