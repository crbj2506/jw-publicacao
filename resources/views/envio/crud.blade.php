@extends('layouts.app')

@section('content')
    @php($envios = isset($envios) ? $envios : null)
    @php($envio = isset($envio) ? $envio : null)
    <x-crud
        :l="$envios"
        :o="$envio"
        r="envio"
        tc="Cadastra Envio"
        te="Altera Envio"
        ti="Lista de Envios"
        ts="Mostra Envio"
    >
        @if($envios)
            <x-slot:filtro>
                <div class="card-header p-1">
                    <form id="formFiltro" method="POST" action="{{ route('envio.filtrada.post') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Filtros</span>
                            <input id="nota" name="nota" type="text" class="form-control" placeholder="Nota" value="{{ $envios->notaFiltro ?? '' }}">
                            <input id="congregacao" name="congregacao" type="text" class="form-control" placeholder="Congregação" value="{{ $envios->congregacaoFiltro ?? '' }}">
                            <button type="submit" class="btn btn-sm btn-outline-primary" form="formFiltro">Filtrar</button>
                            <a href="{{ route('envio.index') }}" class="btn btn-sm btn-outline-success">Limpar</a>
                        </div>
                    </form>
                </div>
            </x-slot>
            <x-slot:lista>
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Nota</th>
                        <th class="text-center" scope="col">Congregação</th>
                        <th class="text-center" scope="col">Data</th>
                        <th class="text-center" scope="col">Retirada</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($envios as $key => $e)
                        <tr>
                            <th class="text-center py-0" scope="row">{{$e->id}}</th>
                            <td class="text-center py-0" scope="row">{{$e->nota}}</td>
                            <td class="text-center py-0" scope="row">{{$e->congregacao->nome}}</td>
                            <td class="text-center py-0" scope="row">{{$e->data}}</td>
                            <td class="text-center py-0" scope="row">{{$e->retirada}}</td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('envio.show',['envio' => $e->id])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('envio.edit',['envio' => $e->id])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
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
                        old_id="{{ isset($envio) ? $envio->congregacao_id : @old('congregacao_id') }}"
                        required="required"
                        value="{{ isset($envio) ? $envio->congregacao->nome : @old('congregacao_id') }}"
                        {{isset($envio->show) ? 'disabled' : ''}}
                    ></select-filter-component>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-xl-3 p-2">
                    <input-group-component
                        label="Nota:" 
                        type="text"
                        name="nota" 
                        id="nota" 
                        required="required"
                        value="{{isset($envio) ? $envio->nota : (old('nota')?old('nota'):'')}}"
                        {{isset($envio->show) ? 'disabled' : ''}} 
                        class="@error('nota') is-invalid @enderror {{old('nota') ? 'is-valid' : ''}}"
                        @error('nota') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-xl-3 p-2">
                    <input-group-component
                        label="Data:" 
                        type="date"
                        name="data" 
                        id="data" 
                        value="{{isset($envio) ? $envio->data : (old('data')?old('data'):'')}}"
                        {{isset($envio->show) ? 'disabled' : ''}} 
                        class="@error('data') is-invalid @enderror {{old('data') ? 'is-valid' : ''}}"
                        @error('data') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-xl-3 p-2">
                    <input-group-component
                        label="Retirada:" 
                        type="date"
                        name="retirada" 
                        id="retirada" 
                        value="{{isset($envio) ? $envio->retirada : (old('retirada')?old('retirada'):'')}}"
                        {{isset($envio->show) ? 'disabled' : ''}} 
                        class="@error('retirada') is-invalid @enderror {{old('retirada') ? 'is-valid' : ''}}"
                        @error('retirada') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
            </div>
        @endif
    </x-crud>
@endsection