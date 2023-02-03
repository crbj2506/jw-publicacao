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
            <x-slot:lista>
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
                            <td class="text-end py-0" scope="row">{{$l->sigla}}</td>
                            <td class=" py-0" scope="row">{{$l->nome}}</td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('local.show',['local' => $l['id']])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('local.edit',['local' => $l['id']])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-slot>
        @else
            <x-slot:lista>
            </x-slot>
            <div class="container-fluid d-flex flex-wrap">
                <div class="col-12 col-sm-8 col-md-7 col-lg-6 col-xl-5 col-xxl-4 p-2">
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