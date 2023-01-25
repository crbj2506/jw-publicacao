@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center">
    <div class="card">
        <div class="card-header fs-5">{{ __('Lista de Congregações') }}</div>

        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Nome</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($congregacoes as $key => $c)
                        <tr>
                            <th class="text-center py-0" scope="row">{{$c['id']}}</th>
                            <td class="text-center py-0" scope="row">{{$c['nome']}}</td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('congregacao.show',['congregacao' => $c['id']])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('congregacao.edit',['congregacao' => $c['id']])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-paginacao :p="$congregacoes"></x-paginacao>
    </div>
</div>
@endsection