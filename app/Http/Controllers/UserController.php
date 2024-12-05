<?php

namespace App\Http\Controllers;

use App\Models\Permissao;
use App\Models\PermissaoUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        //       
        $users = User::paginate(50);
        return view('user.crud',['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        $permissoes = Permissao::get();
        return view('user.crud',[ 'permissoes' => $permissoes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //
        $request->validate(User::rules($id = null),User::feedback());
        $dados = $request->all('name','email','password');
        $dados['password'] = Hash::make($dados['password']);
        $user = User::create($dados);
        // Percorre as permissoes disponíveis e compara com o valor do check (on) do request
        // ID nome do check correponde ao ID da permissão no Banco
        $permissoes = Permissao::get();
        foreach ($permissoes as $key => $p) {
            if($request->all($p->id)[$p->id] == 'on'){
                $permissao_user = ['user_id' => $user->id, 'permissao_id' => $p->id ];
                PermissaoUser::create($permissao_user);
            }
        }
        return redirect()->route('user.show', ['user' => $user->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        //
        $user = User::find($id);
        if(Route::current()->action['as'] == "user.show"){
            $user->show = true;
        };
        $permissoes = Permissao::get();
        return view('user.crud', ['user' => $user, 'permissoes' => $permissoes]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(User $user)
    {
        //
        if(Route::current()->action['as'] == "user.edit"){
            $user->edit = true;
        };
        $permissoes = Permissao::get();
        return view('user.crud', ['user' => $user, 'permissoes' => $permissoes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate(User::rules_update($id),User::feedback());
        $user = User::find($id);
        $user->update($request->all());
        //Pega todas as permissões cadastradas no banco de dados
        $permissoes = Permissao::get();
        // Percorre a colection de objetos de permissões
        foreach ($permissoes as $key => $p) {
            //Busca a permissão do usuário no banco
            $permissaoUser = PermissaoUser::where('user_id',$user->id)->where('permissao_id',$p->id)->get()->first();
            // Se houve request e a permissão não existe no banco, cria. 
            if($request->all($p->id)[$p->id] == 'on'){
                if(!$permissaoUser){
                    $permissao = ['user_id' => $user->id, 'permissao_id' => $p->id ];
                    PermissaoUser::create($permissao);
                }
            }else{
                if($permissaoUser){
                    $permissaoUser->delete();
                }
            }
        }
        return redirect()->route('user.show', ['user' => $user->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}