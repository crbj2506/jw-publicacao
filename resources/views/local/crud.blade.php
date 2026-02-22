@extends('layouts.app')

@section('content')
    @php($locais = isset($locais) ? $locais : null)
    @php($local = isset($local) ? $local : null)
    <x-crud
        :l="$locais"
        :o="$local"
        r="local"
        tc="Cadastra Local"
        te="Altera Local"
        ti="Lista de Locais"
        ts="Mostra Local"
    >
        @if($locais)
            <x-slot:filtro>
                <div class="card-header p-1">
                    <form id="formFiltro" method="POST" action="{{ route('local.filtrada.post') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Filtros</span>
                            <input id="nome" name="nome" type="text" class="form-control" placeholder="Digite o nome do local para buscar" value="{{ $locais->nomeFiltro ?? '' }}">
                            <button type="submit" class="btn btn-sm btn-outline-primary" form="formFiltro"> Filtrar </button>
                            <a href="{{ route('local.index') }}" class="btn btn-sm btn-outline-success">Limpar</a>
                        </div>
                    </form>
                </div>
            </x-slot>
            <x-slot:lista>
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Sigla</th>
                        <th class="text-center" scope="col">Nome</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                        <th class="text-center" scope="col">Excluir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($locais as $key => $l)
                        <tr>
                            <th class="text-center py-0" scope="row">{{$l->id}}</th>
                            <td class="text-end py-0" scope="row">{{$l->sigla}}</td>
                            <td class=" py-0" scope="row">{{$l->nome}}</td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('local.show',['local' => $l->id])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('local.edit',['local' => $l->id])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                            <td class="text-center py-0" scope="row">
                                @if(!$l->temPublicacoes())
                                    <form id="formExcluir_{{$l->id}}" method="POST" action="{{ route('local.destroy',['local' => $l->id])}}" enctype="multipart/form-data">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0" form="formExcluir_{{$l->id}}" onclick="return confirm('Tem certeza que deseja excluir este local?')"> Excluir </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-outline-danger py-0" disabled title="Este local contém publicações">Excluir</button>
                                @endif
                            </td>
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
                <div class="col-12 col-sm-10 col-md-9 col-lg-8 col-xl-7 col-xxl-6 p-2">
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
                        old_id="{{ isset($local) ? $local->congregacao_id : @old('congregacao_id') }}"
                        required="required"
                        value="{{ isset($local) ? $local->congregacao->nome : @old('congregacao_id') }}"
                        {{isset($local->show) ? 'disabled' : ''}}
                    ></select-filter-component>
                </div>
                <div class="col-12 col-sm-4 col-md-3 col-lg-2 p-2">
                    <input-group-component
                        label="Sigla:" 
                        type="text"
                        name="sigla" 
                        id="sigla" 
                        required="required"
                        value="{{isset($local) ? $local->sigla : (old('sigla')?old('sigla'):'')}}"
                        {{isset($local->show) ? 'disabled' : ''}} 
                        class="@error('sigla') is-invalid @enderror {{old('sigla') ? 'is-valid' : ''}}"
                        @error('sigla') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
                <div class="col-12 col-sm-8 col-md-7 col-lg-6 col-xl-5 col-xxl-4 p-2">
                    <input-group-component
                        label="Nome:" 
                        type="text"
                        name="nome" 
                        id="nome" 
                        required="required"
                        value="{{isset($local) ? $local->nome : (old('nome')?old('nome'):'')}}"
                        {{isset($local->show) ? 'disabled' : ''}} 
                        class="@error('nome') is-invalid @enderror {{old('nome') ? 'is-valid' : ''}}"
                        @error('nome') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
            </div>
        @endif
    </x-crud>
@endsection