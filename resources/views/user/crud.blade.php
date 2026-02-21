@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
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
                <div class="card-header p-1">
                    <form id="formFiltro" method="POST" action="{{ route('user.filtrada.post') }}">
                        @csrf
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Filtros</span>
                            <input name="name" type="text" class="form-control" placeholder="Nome" value="{{ $users->nameFiltro ?? '' }}">
                            <input name="email" type="text" class="form-control" placeholder="E-mail" value="{{ $users->emailFiltro ?? '' }}">
                            <button type="submit" class="btn btn-sm btn-outline-primary">Filtrar</button>
                            <a href="{{ route('user.index') }}" class="btn btn-sm btn-outline-success">Limpar</a>
                        </div>
                    </form>
                </div>
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
                        <th class="text-center" scope="col">Excluir</th>
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
                            <td class="text-center py-0" scope="row">
                                @if(is_null($u->email_verified_at))
                                    <!-- Botão que aciona o modal -->
                                    <button type="button" class="btn btn-sm btn-outline-danger py-0" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{$u->id}}">
                                        Excluir
                                    </button>

                                    <!-- Formulário de exclusão (fora do botão) -->
                                    <form id="delete-form-{{$u->id}}" action="{{ route('user.destroy', ['user' => $u->id]) }}" method="post" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </td>
                        </tr>

                        @if(is_null($u->email_verified_at))
                        <!-- Modal de Confirmação -->
                        <div class="modal fade" id="confirmDeleteModal{{$u->id}}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel{{$u->id}}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel{{$u->id}}">Confirmar Exclusão</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                    </div>
                                    <div class="modal-body">
                                        Tem certeza de que deseja excluir o usuário <strong>{{ $u->name }}</strong>? Esta ação não pode ser desfeita.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <!-- Botão que submete o formulário externo usando o atributo 'form' -->
                                        <button type="submit" class="btn btn-danger" form="delete-form-{{$u->id}}">Confirmar Exclusão</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
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