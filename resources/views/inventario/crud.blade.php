@extends('layouts.app')

@section('content')
    @php($inventarios = isset($inventarios) ? $inventarios : null)
    @php($inventario = isset($inventario) ? $inventario : null)
    <x-crud
        :l="$inventarios"
        :o="$inventario"
        r="inventario"
        tc="Cadastra Inventário"
        te="Altera Inventário"
        ti="Lista de Inventários"
        ts="Mostra Inventário"
    >
        @if($inventarios)
            <x-slot:filtro>     
        <div class="card-header p-1">    
            <form  id="formFiltro" method="POST" action="{{ route('inventario.filtrado.post')}}" enctype="multipart/form-data">
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
                    
                    <input id="publicacao" name="publicacao" type="text" class="form-control" placeholder="digite parte do nome" value="{{ $inventarios->publicacaoFiltro ? $inventarios->publicacaoFiltro : ''}}">
                    <button type="submit" class="btn btn-sm btn-outline-primary" form="formFiltro"> Filtrar </button>
                    <a href="{{ route('inventario.index')}}" class="btn btn-sm btn-outline-success">Limpar</a>
                </div>
            </form> 
        </div>
            </x-slot>
            <x-slot:lista>
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
            </x-slot>
        @else
            <x-slot:filtro>
            </x-slot>
            <x-slot:lista>
            </x-slot>
            <div class="container-fluid d-flex flex-wrap">
                <div class="col-12 col-xl-6 p-2">
                    <select-filter-component
                        class="@error('congregacao_id') is-invalid @enderror {{old('congregacao_id') ? 'is-valid' : ''}}"
                        @error('congregacao_id') classinputgroup="has-validation" @enderror
                        classmessage="valid-feedback @error('congregacao_id') invalid-feedback @enderror"
                        id="congregacao_id"
                        label="Congregação:"
                        @error('congregacao_id') message="{{$message}}" @enderror
                        name="congregacao_id"
                        option="Selecione a Congregação..."
                        options="{{json_encode($congregacoes)}}"
                        old_id="{{ isset($inventario) ? $inventario->congregacao_id : @old('congregacao_id') }}"
                        required="required"
                        value="{{ isset($inventario) ? $inventario->congregacao->nome : @old('congregacao_id') }}"
                        {{isset($inventario->show) || isset($inventario->edit) ? 'disabled' : ''}}
                    ></select-filter-component>
                </div>
                <div class="col-12 col-xl-6 p-2">
                    <select-filter-component
                        class="@error('publicacao_id') is-invalid @enderror {{old('publicacao_id') ? 'is-valid' : ''}}"
                        @error('publicacao_id') classinputgroup="has-validation" @enderror
                        classmessage="valid-feedback @error('publicacao_id') invalid-feedback @enderror"
                        id="publicacao_id"
                        label="Publicação:"
                        @error('publicacao_id') message="{{$message}}" @enderror
                        name="publicacao_id"
                        option="Selecione a Publicação..."
                        options="{{json_encode($publicacoes)}}"
                        old_id="{{ isset($inventario) ? $inventario->publicacao_id : @old('publicacao_id') }}"
                        required="required"
                        value="{{ isset($inventario) ? $inventario->publicacao->nome : @old('publicacao_id') }}"
                        {{isset($inventario->show) || isset($inventario->edit) ? 'disabled' : ''}}
                    ></select-filter-component>
                </div>
                <div class="col-12 col-xl-6 p-2">
                    <select-filter-component
                        class="@error('ano') is-invalid @enderror {{old('ano') ? 'is-valid' : ''}}"
                        @error('ano') classinputgroup="has-validation" @enderror
                        classmessage="valid-feedback @error('ano') invalid-feedback @enderror"
                        id="ano"
                        label="Ano:"
                        @error('ano') message="{{$message}}" @enderror
                        name="ano"
                        option="Selecione o Ano..."
                        options="{{json_encode($anos)}}"
                        old_id="{{ isset($inventario) ? $inventario->ano : @old('ano') }}"
                        required="required"
                        value="{{ isset($inventario) ? $inventario->ano : @old('ano') }}"
                        {{isset($inventario->show) || isset($inventario->edit) ? 'disabled' : ''}}
                    ></select-filter-component>
                </div>
                <div class="col-12 col-xl-6 p-2">
                    <select-filter-component
                        class="@error('mes') is-invalid @enderror {{old('mes') ? 'is-valid' : ''}}"
                        @error('mes') classinputgroup="has-validation" @enderror
                        classmessage="valid-feedback @error('mes') invalid-feedback @enderror"
                        id="mes"
                        label="Mês:"
                        @error('mes') message="{{$message}}" @enderror
                        name="mes"
                        option="Selecione o Mês..."
                        options="{{json_encode($meses)}}"
                        old_id="{{ isset($inventario) ? $inventario->mes : @old('mes') }}"
                        required="required"
                        value="{{ isset($inventario) ? $inventario->mes : @old('mes') }}"
                        {{isset($inventario->show) || isset($inventario->edit) ? 'disabled' : ''}}
                    ></select-filter-component>
                </div>
                <div class="col-12 col-sm-6 col-md-4 p-2">
                    <input-group-component
                        label="Recebido:" 
                        type="number"
                        name="recebido" 
                        id="recebido" 
                        required="required"
                        value="{{isset($inventario) ? $inventario->recebido : (old('recebido')?old('recebido'):'')}}"
                        {{isset($inventario->show) ? 'disabled' : ''}} 
                        class="@error('recebido') is-invalid @enderror {{old('recebido') ? 'is-valid' : ''}}"
                        @error('recebido') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
                <div class="col-12 col-sm-6 col-md-4 p-2">
                    <input-group-component
                        label="Estoque:" 
                        type="number"
                        name="estoque" 
                        id="estoque" 
                        required="required"
                        value="{{isset($inventario) ? $inventario->estoque : (old('estoque')?old('estoque'):'')}}"
                        {{isset($inventario->show) ? 'disabled' : ''}} 
                        class="@error('estoque') is-invalid @enderror {{old('estoque') ? 'is-valid' : ''}}"
                        @error('estoque') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
                <div class="col-12 col-sm-6 col-md-4 p-2">
                    <input-group-component
                        label="Saída:" 
                        type="number"
                        name="saida" 
                        id="saida" 
                        required="required"
                        value="{{isset($inventario) ? $inventario->saida : (old('saida')?old('saida'):'')}}"
                        {{isset($inventario->show) ? 'disabled' : ''}} 
                        class="@error('saida') is-invalid @enderror {{old('saida') ? 'is-valid' : ''}}"
                        @error('saida') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
            </div>
        @endif
    </x-crud>
@endsection