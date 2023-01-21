@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header fs-5">{{ __('Visualizando Inventário da Congregação ') . $congregacao->nome }}</div>
                <div class="card-body">
                    @foreach ( $congregacao->publicacoes as $key => $p)
                        @if($loop->first)
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Item</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Publicação</th>
                                        <th scope="col">Localização</th>
                                        <th scope="col">Estoque</th>
                                        <th scope="col">Atualizado em</th>
                                    </tr>
                                </thead>
                                <tbody>
                        @endif
                                    <tr>
                                        <td class="py-0 py-md-1">{{$p->item}}</td>
                                        <td class="py-0 py-md-1">{{ $p->codigo }}</td>
                                        <td class="py-0 py-md-1">{{ $p->nome }}</td>
                                        <td class="py-0 py-md-1">{{ $p->pivot->local}}</td>
                                        <td class="py-0 py-md-1">{{$p->pivot->quantidade}}</td>
                                        <td class="py-0 py-md-1">{{$p->pivot->updated_at}}</td>
                                    </tr>
                        @if($loop->last)
                                    </tbody>
                                </table>
                        @endif
                    @endforeach
                </div>
                <div class="card-footer">
                    <a href="{{ route('inventario.edit',['congregacao' => $congregacao->id])}}" class="btn btn-sm btn-outline-primary">Editar</a>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection





