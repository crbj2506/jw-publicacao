@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5 fw-bold">{{ __('Lista de Congregações') }}</div>

                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Ver</th>
                                <th scope="col">Editar</th>
                                <th scope="col">Adicionar Publicação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($congregacoes as $key => $c)
                                <tr>
                                    <th scope="row">{{$c['id']}}</th>
                                    <td>{{$c['nome']}}</td>
                                    <td><a href="{{ route('congregacao.show',['congregacao' => $c['id']])}}" class="btn btn-sm btn-primary" class="btn btn-sm btn-warning">Ver</a></td>
                                    <td><a href="{{ route('congregacao.edit',['congregacao' => $c['id']])}}" class="btn btn-sm btn-warning">Editar</a></td>
                                    <td><a href="{{ route('inventario.create',['congregacao' => $c['id']])}}" class="btn btn-sm btn-primary">Adicionar Publicação</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{--$congregacoes->links() BUGADO!!!!!--}}
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href="{{ $congregacoes->url(1) }}"><<</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{ $congregacoes->previousPageUrl() }}" tabindex="-1" aria-disabled="true"><</a>
                        </li>@for ( $i= 1 ; $i <= $congregacoes->lastPage() ; $i++)
                            <li class="page-item {{ $congregacoes->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $congregacoes->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="page-item">
                            <a class="page-link" href="{{ $congregacoes->nextPageUrl() }}">></a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{ $congregacoes->url($congregacoes->lastPage()) }}">>></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection