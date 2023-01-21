@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5">{{ __('Lista de Envios') }}</div>

                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nota</th>
                                <th scope="col">Congregacao</th>
                                <th scope="col">Data</th>
                                <th scope="col">Retirada</th>
                                <th scope="col">Ver</th>
                                <th scope="col">Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($envios as $key => $e)
                                <tr>
                                    <th scope="row">{{$e['id']}}</th>
                                    <td>{{$e->nota}}</td>
                                    <td>{{$e->congregacao->nome}}</td>
                                    <td>{{$e->data}}</td>
                                    <td>{{$e->retirada}}</td>
                                    <td><a href="{{ route('envio.show',['envio' => $e['id']])}}" class="btn btn-sm btn-outline-primary" class="btn btn-sm btn-outline-warning">Ver</a></td>
                                    <td><a href="{{ route('envio.edit',['envio' => $e['id']])}}" class="btn btn-sm btn-outline-warning">Editar</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{--$envios->links() BUGADO!!!!!--}}
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href="{{ $envios->url(1) }}"><<</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{ $envios->previousPageUrl() }}" tabindex="-1" aria-disabled="true"><</a>
                        </li>@for ( $i= 1 ; $i <= $envios->lastPage() ; $i++)
                            <li class="page-item {{ $envios->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $envios->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="page-item">
                            <a class="page-link" href="{{ $envios->nextPageUrl() }}">></a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{ $envios->url($envios->lastPage()) }}">>></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection