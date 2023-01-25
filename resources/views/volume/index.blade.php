@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center">
    <div class="card">
        <div class="card-header fs-5">{{ __('Lista de Volumes') }}</div>

        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Volume</th>
                        <th class="text-center" scope="col">Envio</th>
                        <th class="text-center" scope="col">Data</th>
                        <th class="text-center" scope="col">Congregação</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($volumes as $key => $v)
                        <tr>
                            <th class="text-center py-0" scope="row">{{$v['id']}}</th>
                            <td class="py-0" scope="row">{{$v->volume}}</td>
                            <td class="text-center py-0" scope="row">{{$v->envio->nota}}</td>
                            <td class="text-center py-0" scope="row">{{$v->envio->data}}</td>
                            <td class="text-center py-0" scope="row">{{$v->envio->congregacao->nome}}</td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('volume.show',['volume' => $v['id']])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('volume.edit',['volume' => $v['id']])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-paginacao :p="$volumes"></x-paginacao>
    </div>
</div>
@endsection