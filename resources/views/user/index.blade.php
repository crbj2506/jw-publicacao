@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center">
    <div class="card">
        <div class="card-header fs-5">{{ __('Lista de Usuários') }}</div>

        <div class="card-body">
            <table class="table table-striped table-hover">
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
                    @foreach ($users as $key => $p)
                        <tr>
                            <th class="text-center py-0" scope="row">{{$p['id']}}</th>
                            <td class="text-center py-0" scope="row">{{$p['name']}}</td>
                            <td class="text-center py-0" scope="row">{{$p['email']}}</td>
                            <td class="text-center py-0" scope="row">{{$p['email_verified_at']}}</td>
                            <td class="text-center py-0" scope="row">{{$p['created_at']}}</td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('user.show',['user' => $p['id']])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('user.edit',['user' => $p['id']])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-paginacao :p="$users"></x-paginacao>
    </div>
</div>
@endsection