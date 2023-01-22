@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center">
    <div class="card">
        <div class="card-header">{{ __('Principal') }}</div>

        <div class="card-body">
            @foreach ( $congregacoes as $key => $c)
                @if($loop->first)
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" scope="col">#</th>
                                <th class="text-center" scope="col">Congregação</th>
                                <th class="text-center" scope="col">Envios</th>
                                <th class="text-center" scope="col">Locais</th>
                                <th class="text-center" scope="col">Itens</th>
                                <th class="text-center" scope="col">Puplicações</th>
                                <th class="text-center" scope="col">Último Inventário</th>
                                <th class="text-center" scope="col">Estoque Alterado</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif
                        <tr>
                            <th class="text-center py-0" scope="row">{{$c->id}}</th>
                            <td class="text-center py-0" scope="row">{{$c->nome}}</td>
                            <td class="text-center py-0" scope="row">{{$c->enviosQuantidade}}</td>
                            <td class="text-center py-0" scope="row">{{$c->totalLocais}}</td>
                            <td class="text-center py-0" scope="row">{{$c->totalItens}}</td>
                            <td class="text-center py-0" scope="row">{{$c->totalPublicacoes}}</td>
                            <td class="text-center py-0" scope="row">{{$c->ultimoInventario['ano']}}-{{$c->ultimoInventario['mes']}}</td>
                            <td class="text-center py-0" scope="row">{{$c->dataAlteracaoEstoque}}</td>
                        </tr>
                @if($loop->last)
                        </tbody>
                    </table>
                @endif
                
            @endforeach
        </div>
        <div class="card-footer">{{ __('') }}</div>
    </div>
</div>
@endsection
