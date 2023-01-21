@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5">{{ __('Lista de Volumes') }}</div>

                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Volume</th>
                                <th scope="col">Envio</th>
                                <th scope="col">Data</th>
                                <th scope="col">Congregação</th>
                                <th scope="col">Ver</th>
                                <th scope="col">Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($volumes as $key => $v)
                                <tr>
                                    <th scope="row">{{$v['id']}}</th>
                                    <td>{{$v->volume}}</td>
                                    <td>{{$v->envio->nota}}</td>
                                    <td>{{$v->envio->data}}</td>
                                    <td>{{$v->envio->congregacao->nome}}</td>
                                    <td><a href="{{ route('volume.show',['volume' => $v['id']])}}" class="btn btn-sm btn-outline-primary" class="btn btn-sm btn-outline-warning">Ver</a></td>
                                    <td><a href="{{ route('volume.edit',['volume' => $v['id']])}}" class="btn btn-sm btn-outline-warning">Editar</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{--$volumes->links() BUGADO!!!!!--}}
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href="{{ $volumes->url(1) }}"><<</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{ $volumes->previousPageUrl() }}" tabindex="-1" aria-disabled="true"><</a>
                        </li>@for ( $i= 1 ; $i <= $volumes->lastPage() ; $i++)
                            <li class="page-item {{ $volumes->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $volumes->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="page-item">
                            <a class="page-link" href="{{ $volumes->nextPageUrl() }}">></a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{ $volumes->url($volumes->lastPage()) }}">>></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection