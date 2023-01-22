@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center">
    <div class="card">
        <div class="card-header fs-5">{{ __('Lista de Conteúdos') }}</div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Quantidade</th>
                        <th class="text-center" scope="col">Código</th>
                        <th class="text-center" scope="col">Publicação</th>
                        <th class="text-center" scope="col">Volume</th>
                        <th class="text-center" scope="col">Envio</th>
                        <th class="text-center" scope="col">Data do Envio</th>
                        <th class="text-center" scope="col">Data da Retirada</th>
                        <th class="text-center" scope="col">Congregação</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($conteudos as $key => $c) 
                        <tr>
                            <th class="py-0 text-center" scope="row">{{$c->id}}</th>
                            <td class="py-0 text-end" scope="row">{{$c->quantidade}}</td>
                            <td class="py-0 text-end" scope="row">{{$c->publicacao->codigo}}</td>
                            <td class="py-0" scope="row">{{$c->publicacao->nome}}</td>
                            <td class="py-0" scope="row">{{$c->volume->volume}}</td>
                            <td class="py-0 text-center" scope="row">{{$c->volume->envio->nota}}</td>
                            <td class="py-0 text-center" scope="row">{{$c->volume->envio->data}}</td>
                            <td class="py-0 text-center" scope="row">{{$c->volume->envio->retirada}}</td>
                            <td class="py-0 text-center" scope="row">{{$c->volume->envio->congregacao->nome}}</td>
                            <td class="py-0 text-center" scope="row"><a href="{{ route('conteudo.show',['conteudo' => $c->id])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="py-0 text-center" scope="row"><a href="{{ route('conteudo.edit',['conteudo' => $c->id])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{--$conteudos->links() BUGADO!!!!!--}}
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <a class="page-link" href="{{ $conteudos->url(1) }}"><<</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ $conteudos->previousPageUrl() }}" tabindex="-1" aria-disabled="true"><</a>
                </li>@for ( $i= 1 ; $i <= $conteudos->lastPage() ; $i++)
                    <li class="page-item {{ $conteudos->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $conteudos->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor
                <li class="page-item">
                    <a class="page-link" href="{{ $conteudos->nextPageUrl() }}">></a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ $conteudos->url($conteudos->lastPage()) }}">>></a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection