@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center">
    <div class="card">
        <div class="card-header fs-5">{{ __('Lista de Permissões') }}</div>

        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Permissão</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissoes as $key => $p)
                        <tr>
                            <th class="text-center py-0" scope="row">{{$p['id']}}</th>
                            <td class="text-center py-0" scope="row">{{$p['permissao']}}</td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('permissao.show',['permissao' => $p['id']])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('permissao.edit',['permissao' => $p['id']])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{--$permissoes->links() BUGADO!!!!!--}}
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <a class="page-link" href="{{ $permissoes->url(1) }}"><<</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ $permissoes->previousPageUrl() }}" tabindex="-1" aria-disabled="true"><</a>
                </li>@for ( $i= 1 ; $i <= $permissoes->lastPage() ; $i++)
                    <li class="page-item {{ $permissoes->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $permissoes->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor
                <li class="page-item">
                    <a class="page-link" href="{{ $permissoes->nextPageUrl() }}">></a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ $permissoes->url($permissoes->lastPage()) }}">>></a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection