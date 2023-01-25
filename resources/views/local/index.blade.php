@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center">
    <div class="card">
        <div class="card-header fs-5">{{ __('Lista de Locais') }}</div>

        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Sigla</th>
                        <th class="text-center" scope="col">Nome</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($locais as $key => $l)
                        <tr>
                            <th class="text-center py-0" scope="row">{{$l->id}}</th>
                            <td class="text-end py-0" scope="row">{{$l->sigla}}</td>
                            <td class=" py-0" scope="row">{{$l->nome}}</td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('local.show',['local' => $l['id']])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('local.edit',['local' => $l['id']])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-paginacao :p="$locais"></x-paginacao>
    </div>
</div>
@endsection