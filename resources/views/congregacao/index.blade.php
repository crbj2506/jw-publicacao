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
@endsection