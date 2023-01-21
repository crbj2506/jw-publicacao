@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5">{{ __('Lista de Locais') }}</div>

                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" scope="col">#</th>
                                <th class="text-center" scope="col">Sigla</th>
                                <th class="text-center" scope="col">Nome</th>
                                <th class="text-center" scope="col">Ver</th>
                                <th class="text-center" scope="col">Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($locais as $key => $l)
                                <tr>
                                    <th class="text-center py-0" scope="row">{{$l->id}}</th>
                                    <td class="text-center py-0" scope="row">{{$l->sigla}}</td>
                                    <td class="text-center py-0" scope="row">{{$l->nome}}</td>
                                    <td class="text-center py-0" scope="row"><a href="{{ route('local.show',['local' => $l['id']])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                                    <td class="text-center py-0" scope="row"><a href="{{ route('local.edit',['local' => $l['id']])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{--$locais->links() BUGADO!!!!!--}}
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href="{{ $locais->url(1) }}"><<</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{ $locais->previousPageUrl() }}" tabindex="-1" aria-disabled="true"><</a>
                        </li>@for ( $i= 1 ; $i <= $locais->lastPage() ; $i++)
                            <li class="page-item {{ $locais->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $locais->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="page-item">
                            <a class="page-link" href="{{ $locais->nextPageUrl() }}">></a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{ $locais->url($locais->lastPage()) }}">>></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection