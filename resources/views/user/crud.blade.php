@extends('layouts.app')

@section('content')
    @php($users = isset($users) ? $users : null)
    @php($user = isset($user) ? $user : null)
    <x-crud
        :l="$users"
        :o="$user"
        r="user"
        tc="Cadastra Usuário"
        te="Altera Usuário"
        ti="Lista de Usuários"
        ts="Mostra Usuário"
    >
        @if($users)
            <x-slot:filtro>
            </x-slot>
            <x-slot:lista>
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Nome</th>
                        <th class="text-center" scope="col">E-mail</th>
                        <th class="text-center" scope="col">Verificado em</th>
                        <th class="text-center" scope="col">Criado em</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $u)
                        <tr>
                            <th class="text-center py-0" scope="row">{{$u->id}}</th>
                            <td class="text-center py-0" scope="row">{{$u->name}}</td>
                            <td class="text-center py-0" scope="row">{{$u->email}}</td>
                            <td class="text-center py-0" scope="row">{{$u->email_verified_at}}</td>
                            <td class="text-center py-0" scope="row">{{$u->created_at}}</td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('user.show',['user' => $u->id])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('user.edit',['user' => $u->id])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-slot>
        @else
            <x-slot:filtro>
            </x-slot>
            <x-slot:lista>
            </x-slot>
            <div class="container-fluid d-flex flex-wrap">
                <div class="col-12 p-2">
                    <input-group-component
                        label="Nome:" 
                        type="text"
                        name="name" 
                        id="name" 
                        required="required"
                        value="{{ old('name', $user->name ?? '') }}"
                        {{!isset($user->show) ? '' : 'disabled' }} 
                        class="@error('name') is-invalid @enderror {{old('name') ? 'is-valid' : ''}}"
                        @error('name') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
                <div class="col-12 p-2">
                    <input-group-component
                        label="E-mail:" 
                        type="text"
                        name="email" 
                        id="email" 
                        required="required"
                        value="{{ old('email', $user->email ?? '') }}"
                        {{ isset($user->show) || isset($user->edit) ? 'disabled' : '' }}
                        class="@error('email') is-invalid @enderror {{old('email') ? 'is-valid' : ''}}"
                        @error('email') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
@if(!isset($user->show) && !isset($user->edit))
                <div class="col-12 p-2">
                    <input-group-component
                        label="Senha:" 
                        type="password"
                        name="password" 
                        id="password" 
                        required="required"
                        class="@error('password') is-invalid @enderror"
                        @error('password') message="{{ $message }}" @enderror>
                    </input-group-component>
                </div>
                <div class="col-12 p-2">
                    <input-group-component
                        label="Confirme a senha:" 
                        type="password"
                        name="password_confirmation" 
                        id="password_confirmation" 
                        required="required">
                    </input-group-component>
                </div>
@endif
                <div class="col-12 p-2">
                    <div class="border rounded p-2 m-3">
                        @foreach ($permissoes as $key => $p)
                            @php($checked = '')
                            @foreach($user->permissoes as $indice =>$pu)
                                @if($pu->id == $p->id)
                                    @php($checked = 'checked')
                                @endif
                            @endforeach
                                <div class="form-check-inline form-switch">
                                    <input class="form-check-input" type="checkbox" id="{{ $p->id }}" name="{{ $p->id }}" {{ $checked }} {{isset($user->show) ? 'disabled' : ''}}>
                                    <label class="form-check-label px-1" for="{{$p->id}}"> {{ $p->permissao }}</label>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </x-crud>
@endsection