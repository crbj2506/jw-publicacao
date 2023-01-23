@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center">
    <div class="card">
        <div class="card-header">
                    {{ __('Lista de Inventários') }}
        </div>    
        <div class="card-header p-1">    
            <form  id="formFiltro" method="POST" action="{{ route('inventario.indexFiltrado')}}" enctype="multipart/form-data">
                @csrf
                <div class="input-group input-group-sm">
                    <span class="input-group-text" id="selectLabelCongregacao">Filtros</span>
                    <select class="form-select @error('congregacao_id') is-invalid @enderror" id="selectCongregacao" name="congregacao_id">
                        <option  value="" selected>Congregação...</option>
                        @foreach ( $inventarios->congregacoesFiltro as $key => $c)
                            <option value="{{$c->id}}" {{(@old('congregacao_id') == $c->id) || ($inventarios->filtros['congregacao_id'] == $c->id) || ($inventarios->congregacaoIdFiltro == $c->id) ? 'selected': ''}}>{{ $c->nome }}</option>
                        @endforeach
                    </select>
                    <select class="form-select @error('ano') is-invalid @enderror" id="selectAno" name="ano">
                        <option  value="" selected>Ano...</option>
                        @foreach ( $inventarios->anosFiltro as $key => $ano)
                            <option value="{{$ano->ano}}" {{(@old('ano') == $ano->ano) || ($inventarios->filtros['ano'] == $ano->ano) || ($inventarios->anoFiltro == $ano->ano) ? 'selected': ''}}>{{ $ano->ano }}</option>
                        @endforeach
                    </select>
                    <select class="form-select @error('mes') is-invalid @enderror" id="selectAno" name="mes">
                        <option  value="" selected>Mês...</option>
                        @foreach ( $inventarios->mesesFiltro as $key => $mes)
                            <option value="{{$mes->mes}}" {{(@old('mes') == $mes->mes) || ($inventarios->filtros['mes'] == $mes->mes) || ($inventarios->mesFiltro == $mes->mes) ? 'selected': ''}}>{{ $mes->mes }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-primary" form="formFiltro"> Filtrar </button>
                    <a href="{{ route('inventario.index')}}" class="btn btn-sm btn-outline-success">Limpar</a>
                </div>
            </form> 
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
                            <td class="py-0 text-end" scope="row">{{$i->recebido}}</td>
                            <td class="py-0 text-end" scope="row">{{$i->estoque}}</td>
                            <td class="py-0 text-end" scope="row">{{$i->saida}}</td>
                            <td class="py-0 text-center" scope="row"><a href="{{ route('inventario.show',['inventario' => $i->id])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="py-0 text-center" scope="row"><a href="{{ route('inventario.edit',['inventario' => $i->id])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{--$inventarios->links() BUGADO!!!!!--}}
            <ul class="pagination pagination-sm justify-content-center">
                <li class="page-item {{ $inventarios->currentPage() == 1 ? 'disabled' : ''}}">
                    <a class="page-link" href="{{ $inventarios->url(1) }}">Página 1</a>
                </li>
                <li class="page-item {{ $inventarios->currentPage() == 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $inventarios->previousPageUrl() }}" tabindex="-1" aria-disabled="true">Anterior</a>
                </li>

                @for ($i = 1;  $i <= $inventarios->lastPage() ; $i++)
                    <li class="page-item {{ $inventarios->currentPage() == $i ? 'active' : '' }}
                                        {{ ($i < $inventarios->currentPage() - $inventarios->d1) || ($i > $inventarios->currentPage() + $inventarios->d2) ? 'd-none' : '' }}">
                        <a class="page-link" href="{{ $inventarios->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor
                <li class="page-item">
                    <a class="page-link {{ $inventarios->currentPage() == $inventarios->lastPage() ? 'disabled' : '' }}" href="{{ $inventarios->nextPageUrl() }}">Próxima</a>
                </li>
                <li class="page-item">
                    <a class="page-link {{ $inventarios->currentPage() == $inventarios->lastPage() ? 'disabled' : '' }}" href="{{ $inventarios->url($inventarios->lastPage()) }}">Página {{$inventarios->lastPage()}}</a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection