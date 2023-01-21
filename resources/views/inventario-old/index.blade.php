@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header fs-5">{{ __('Lista de Inventários') }}</div>
                <div class="card-body">
                    @foreach ( $inventarios as $key => $i)
                        @if($loop->first)
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Inventário</th>
                                        <th scope="col">Itens</th>
                                        <th scope="col">Publicações</th>
                                        <th scope="col">Atualizado em</th>
                                        <th scope="col">Ver Inventário</th>
                                    </tr>
                                </thead>
                                <tbody>
                        @endif
                                    <tr>
                                        <td class="py-0 py-md-1">{{$i->congregacao->nome}}</td>
                                        <td class="py-0 py-md-1">{{$i->itens}}</td>
                                        <td class="py-0 py-md-1">{{$i->publicacoes}}</td>
                                        <td class="py-0 py-md-1">{{$i->updated_at}}</td>
                                        <td><a href="{{ route('inventario.show',['congregacao' => $i->congregacao->id])}}" class="btn btn-sm btn-outline-primary">Ver Inventário</a></td>
                                    </tr>
                        @if($loop->last)
                                    </tbody>
                                </table>
                        @endif
                    @endforeach
                </div>
                <div class="card-footer">
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection





