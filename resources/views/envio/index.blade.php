@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center">
    <div class="card">
        <div class="card-header fs-5">{{ __('Lista de Envios') }}</div>

        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Nota</th>
                        <th class="text-center" scope="col">Congregacao</th>
                        <th class="text-center" scope="col">Data</th>
                        <th class="text-center" scope="col">Retirada</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($envios as $key => $e)
                        <tr>
                            <th class="text-center py-0" scope="row">{{$e['id']}}</th>
                            <td class="text-center py-0" scope="row">{{$e->nota}}</td>
                            <td class="text-center py-0" scope="row">{{$e->congregacao->nome}}</td>
                            <td class="text-center py-0" scope="row">{{$e->data}}</td>
                            <td class="text-center py-0" scope="row">{{$e->retirada}}</td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('envio.show',['envio' => $e['id']])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('envio.edit',['envio' => $e['id']])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
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
@endsection