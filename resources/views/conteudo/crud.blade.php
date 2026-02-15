@extends('layouts.app')

@section('content')
    @php($conteudos = isset($conteudos) ? $conteudos : null)
    @php($conteudo = isset($conteudo) ? $conteudo : null)
    <x-crud
        :l="$conteudos"
        :o="$conteudo"
        r="conteudo"
        tc="Cadastra Conteúdo"
        te="Altera Conteúdo"
        ti="Lista de Conteúdos"
        ts="Mostra Conteúdo"
    >
        @if($conteudos)
            <x-slot:filtro>
                <div class="card-header p-1">
                    <form  id="formFiltro" method="POST" action="{{ route('conteudo.filtrada.post') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Filtros</span>
                            <input id="codigo" name="codigo" type="text" class="form-control" placeholder="Código" value="{{ $conteudos->codigoFiltro ? $conteudos->codigoFiltro : ''}}">
                            <input id="publicacao" name="publicacao" type="text" class="form-control" placeholder="Publicação" value="{{ $conteudos->publicacaoFiltro ? $conteudos->publicacaoFiltro : ''}}">
                            <input id="volume" name="volume" type="text" class="form-control" placeholder="Volume" value="{{ $conteudos->volumeFiltro ? $conteudos->volumeFiltro : ''}}">
                            <input id="envio" name="envio" type="text" class="form-control" placeholder="Envio" value="{{ $conteudos->envioFiltro ? $conteudos->envioFiltro : ''}}">
                            <button type="submit" class="btn btn-sm btn-outline-primary" form="formFiltro"> Filtrar </button>
                            <a href="{{ route('conteudo.index')}}" class="btn btn-sm btn-outline-success">Limpar</a>
                        </div>
                    </form>
                </div>
            </x-slot>
            <x-slot:lista>
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Quantidade</th>
                        <th class="text-center" scope="col">Código</th>
                        <th class="text-center" scope="col">Publicação</th>
                        <th class="text-center" scope="col">Volume</th>
                        <th class="text-center" scope="col">Envio</th>
                        <th class="text-center" scope="col">Data do Envio</th>
                        <th class="text-center" scope="col">Data da Retirada</th>
                        <th class="text-center" scope="col">Congregação</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($conteudos as $key => $c) 
                        <tr>
                            <th class="py-0 text-center" scope="row">{{$c->id}}</th>
                            <td class="py-0 text-end" scope="row">{{$c->quantidade}}</td>
                            <td class="py-0 text-end" scope="row">{{$c->publicacao->codigo}}</td>
                            <td class="py-0" scope="row">{{$c->publicacao->nome}}</td>
                            <td class="py-0" scope="row">{{$c->volume->volume}}</td>
                            <td class="py-0 text-center" scope="row">{{$c->volume->envio->nota}}</td>
                            <td class="py-0 text-center" scope="row">{{$c->volume->envio->data}}</td>
                            <td class="py-0 text-center" scope="row">{{$c->volume->envio->retirada}}</td>
                            <td class="py-0 text-center" scope="row">{{$c->volume->envio->congregacao->nome}}</td>
                            <td class="py-0 text-center" scope="row"><a href="{{ route('conteudo.show',['conteudo' => $c->id])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="py-0 text-center" scope="row"><a href="{{ route('conteudo.edit',['conteudo' => $c->id])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
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
                        class="@error('publicacao_id') is-invalid @enderror {{old('publicacao_id') ? 'is-valid' : ''}}"
                        @error('publicacao_id') classinputgroup="has-validation" @enderror
                        classmessage="valid-feedback @error('publicacao_id') invalid-feedback @enderror"
                        id="publicacao_id"
                        label="Publicação:"
                        @error('publicacao_id') message="{{$message}}" @enderror
                        name="publicacao_id"
                        option="Selecione a Publicação..."
                        options="{{json_encode($publicacoes)}}"
                        old_id="{{ isset($conteudo) ? $conteudo->publicacao_id : @old('publicacao_id') }}"
                        required="required"
                        value="{{ isset($conteudo) ? $conteudo->publicacao->nome : @old('publicacao_id') }}"
                        {{isset($conteudo->show) ? 'disabled' : ''}}
                    ></select-filter-component>
                </div>

                <div class="col-12 col-xl-6 p-2">
                    <select-filter-component
                        class="@error('volume_id') is-invalid @enderror {{old('volume_id') ? 'is-valid' : ''}}"
                        @error('volume_id') classinputgroup="has-validation" @enderror
                        classmessage="valid-feedback @error('volume_id') invalid-feedback @enderror"
                        id="volume_id"
                        label="Volume:"
                        @error('volume_id') message="{{$message}}" @enderror
                        name="volume_id"
                        option="Selecione o Volume..."
                        options="{{json_encode($volumes)}}"
                        old_id="{{ isset($conteudo) ? $conteudo->volume_id : @old('volume_id') }}"
                        required="required"
                        value="{{ isset($conteudo) ? $conteudo->volume->volume . ' envio: ' . $conteudo->volume->envio->nota  . ' de ' . $conteudo->volume->envio->data: @old('volume_id') }}"
                        {{isset($conteudo->show) ? 'disabled' : ''}}
                    ></select-filter-component>
                </div>
                <div class="col-12 col-sm-6 col-md-4 p-2">
                    <input-group-component
                        label="Quantidade:" 
                        type="number"
                        name="quantidade" 
                        id="quantidade" 
                        required="required"
                        value="{{isset($conteudo) ? $conteudo->quantidade : (old('quantidade')?old('quantidade'):'')}}"
                        {{isset($conteudo->show) ? 'disabled' : ''}} 
                        class="@error('quantidade') is-invalid @enderror {{old('quantidade') ? 'is-valid' : ''}}"
                        @error('quantidade') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
            </div>
        @endif
    </x-crud>
@endsection