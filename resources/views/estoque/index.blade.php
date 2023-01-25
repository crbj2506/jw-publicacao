@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center">
            <div class="card">
                <div class="card-header fs-5">{{ __('Lista do Estoque') }}</div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" scope="col">#</th>
                                <th class="text-center" scope="col">Local</th>
                                <th class="text-center" scope="col">Código</th>
                                <th class="text-center" scope="col">Publicacao</th>
                                <th class="text-center" scope="col">Observação</th>
                                <th class="text-center" scope="col">Quantidade</th>
                                <th class="text-center" scope="col">Ver</th>
                                <th class="text-center" scope="col">Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($estoques as $key => $e) 
                                <tr>
                                    <th class="py-0 text-center" scope="row">{{$e->id}}</th>
                                    <td class="py-0" scope="row">{{$e->local->sigla}} - {{$e->local->nome}}</td>
                                    <td class="py-0 text-end" scope="row">{{$e->publicacao->codigo}}</td>
                                    <td class="py-0" scope="row">{{$e->publicacao->nome}}</td>
                                    <td class="py-0 text-center" scope="row">{{$e->publicacao->observacao}}</td>
                                    <td class="py-0 text-center" scope="row">{{$e->quantidade}}</td>
                                    <td class="py-0 text-center" scope="row"><a href="{{ route('estoque.show',['estoque' => $e->id])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                                    <td class="py-0 text-center" scope="row"><a href="{{ route('estoque.edit',['estoque' => $e->id])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <x-paginacao :p="$estoques"></x-paginacao>
            </div>
</div>
@endsection