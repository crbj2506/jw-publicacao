@extends('layouts.app')

@section('content')
    @php($estoques = isset($estoques) ? $estoques : null)
    @php($estoque = isset($estoque) ? $estoque : null)
    <x-crud
        :l="$estoques"
        :o="$estoque"
        r="estoque"
        tc="Cadastra Conteúdo"
        te="Altera Conteúdo"
        ti="Lista de Conteúdos"
        ts="Mostra Conteúdo"
    >
        @if($estoques)
            <x-slot:lista>
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Local</th>
                        <th class="text-center" scope="col">Código</th>
                        <th class="text-center" scope="col">Publicacao</th>
                        <th class="text-center" scope="col">Observação</th>
                        <th class="text-center" scope="col">Proporção</th>
                        <th class="text-center" scope="col">Quantidade</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estoques as $key => $e) 
                        <tr>
                            <th class="py-0 text-center" scope="row">{{$e->id}}</th>
                            <td class="py-0" scope="row">{{$e->local->sigla}} - {{$e->local->nome}}</td>
                            <td class="py-0 text-end" scope="row">{{$e->publicacao->codigo}}</td>
                            <td class="py-0" scope="row">{{$e->publicacao->nome}}</td>
                            <td class="py-0 text-center" scope="row">{{$e->publicacao->observacao}}</td>
                            <td class="py-0 text-center" scope="row">
                                {{$e->publicacao->proporcao_cm ? $e->publicacao->proporcao_cm . ' cm' : ''}}
                                {{$e->publicacao->proporcao_cm && $e->publicacao->proporcao_unidade ? ' = ' : ''}}
                                {{$e->publicacao->proporcao_unidade ? $e->publicacao->proporcao_unidade : ''}}
                            </td>
                            <td class="py-0 text-center" scope="row">{{$e->quantidade}}</td>
                            <td class="py-0 text-center" scope="row"><a href="{{ route('estoque.show',['estoque' => $e->id])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="py-0 text-center" scope="row"><a href="{{ route('estoque.edit',['estoque' => $e->id])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-slot>
        @else
            <x-slot:lista>
            </x-slot>
            <div class="container-fluid d-flex flex-wrap">
                <div class="col-12 col-xl-6 p-2">
                    <select-filter-component
                        class="@error('local_id') is-invalid @enderror {{old('local_id') ? 'is-valid' : ''}}"
                        @error('local_id') classinputgroup="has-validation" @enderror
                        classmessage="valid-feedback @error('local_id') invalid-feedback @enderror"
                        id="local_id"
                        label="Local:"
                        @error('local_id') message="{{$message}}" @enderror
                        name="local_id"
                        option="Selecione o Local..."
                        options="{{json_encode($locais)}}"
                        old_id="{{ isset($estoque) ? $estoque->local_id : @old('local_id') }}"
                        required="required"
                        value="{{ isset($estoque) ? $estoque->publicacao->nome : @old('local_id') }}"
                        {{isset($estoque->show) ? 'disabled' : ''}}
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
                        old_id="{{ isset($estoque) ? $estoque->publicacao_id : @old('publicacao_id') }}"
                        required="required"
                        value="{{ isset($estoque) ? $estoque->publicacao->nome : @old('publicacao_id') }}"
                        {{isset($estoque->show) ? 'disabled' : ''}}
                    ></select-filter-component>
                </div>
                <div class="col-12 col-sm-6 col-md-4 p-2">
                    <input-group-component
                        label="Quantidade:" 
                        type="number"
                        name="quantidade" 
                        id="quantidade" 
                        required="required"
                        value="{{isset($estoque) ? $estoque->quantidade : (old('quantidade')?old('quantidade'):'')}}"
                        {{isset($estoque->show) ? 'disabled' : ''}} 
                        class="@error('quantidade') is-invalid @enderror {{old('quantidade') ? 'is-valid' : ''}}"
                        @error('quantidade') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
            </div>
        @endif
    </x-crud>
@endsection