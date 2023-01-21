@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center">
            <div class="card">
                <div class="card-header container-fluid">
                    <div class="row">
                        <div class="col">
                            {{ __('Lista de Inventários') }}
                        </div>
                        <div class="col">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text">Filtrar por:</span>
                                <input id="filtro" name="filtro" type="text" class="form-control" placeholder="colocar aqui select ano mes e congregacao">
                                <button type="submit" class="btn btn-sm btn-outline-primary" form="formFiltro"> Filtrar </button>
                                <a href="" class="btn btn-sm btn-outline-success">Limpar</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" scope="col">Congregação</th>
                                <th class="text-center" scope="col">Ano</th>
                                <th class="text-center" scope="col">Mês</th>
                                <th class="text-center" scope="col">Código</th>
                                <th class="text-center" scope="col">Publicação</th>
                                <th class="text-center" scope="col">Recebido</th>
                                <th class="text-center" scope="col">Estoque</th>
                                <th class="text-center" scope="col">Saída</th>
                                <th class="text-center" scope="col">Ver</th>
                                <th class="text-center" scope="col">Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventarios as $key => $i) 
                                <tr>
                                    <td class="py-0 text-center" scope="row">{{$i->congregacao->nome}}</td>
                                    <td class="py-0 text-center" scope="row">{{$i->ano}}</td>
                                    <td class="py-0 text-center" scope="row">{{$i->mes}}</td>
                                    <td class="py-0 text-end" scope="row">{{$i->publicacao->codigo}}</td>
                                    <td class="py-0" scope="row">{{$i->publicacao->nome}}</td>
                                    <td class="py-0 text-center" scope="row">{{$i->recebido}}</td>
                                    <td class="py-0 text-center" scope="row">{{$i->estoque}}</td>
                                    <td class="py-0 text-center" scope="row">{{$i->saida}}</td>
                                    <td class="py-0 text-center" scope="row"><a href="{{ route('inventario.show',['inventario' => $i->id])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                                    <td class="py-0 text-center" scope="row"><a href="{{ route('inventario.edit',['inventario' => $i->id])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{--$inventarios->links() BUGADO!!!!!--}}
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href="{{ $inventarios->url(1) }}"><<</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{ $inventarios->previousPageUrl() }}" tabindex="-1" aria-disabled="true"><</a>
                        </li>@for ( $i= 1 ; $i <= $inventarios->lastPage() ; $i++)
                            <li class="page-item {{ $inventarios->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $inventarios->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="page-item">
                            <a class="page-link" href="{{ $inventarios->nextPageUrl() }}">></a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{ $inventarios->url($inventarios->lastPage()) }}">>></a>
                        </li>
                    </ul>
                </div>
            </div>
</div>
@endsection