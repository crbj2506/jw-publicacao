@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="container-fluid d-flex justify-content-center">
        <div class="card mb-3">
            <div class="card-header">
                <div class="container">
                    <div class="row">
                        <div class="col-auto me-auto fs-5">
                            {{ __('Visualizando Volume: ') }} {{ $volume->volume }}
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('volume.index')}}" class="btn btn-sm btn-link">Listar</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="selectLabelEnvio">Envio</span>
                    <select class="form-select @error('envio_id') is-invalid @enderror" id="selectPublicacao" name="envio_id" disabled>
                        <option  value="" selected>Selecione o Envio...</option>
                        @foreach ( $envios as $key => $e)
                            <option value="{{$e->id}}" {{(@old('envio_id') == $e->id) || ($volume->envio_id == $e->id) ? 'selected': ''}}>{{ $e->nota }} {{ $e->data ? 'de ' . $e->data : ''  }}</option>
                        @endforeach
                    </select>
                @error('envio_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                </div>
            
                <div class="input-group mb-3">
                    <span class="input-group-text">{{ __('Volume') }}</span>
                    <input id="volume" type="text" class="form-control" name="volume" value="{{ $volume->volume }}" disabled>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('volume.edit',['volume' => $volume->id])}}" class="btn btn-sm btn-outline-primary">Editar</a>
            </div>
        </div>
    </div>
    <div class="container-fluid d-flex justify-content-center">
        <div class="card">
            <div class="card-header fs-5">{{ __('Lista de Conteúdos') }}</div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col">#</th>
                            <th class="text-center" scope="col">Quantidade</th>
                            <th class="text-center" scope="col">Código</th>
                            <th class="text-center" scope="col">Publicação</th>
                            <th class="text-center" scope="col">Data do Envio</th>
                            <th class="text-center" scope="col">Data da Retirada</th>
                            <th class="text-center" scope="col">Congregação</th>
                            <th class="text-center" scope="col">Ver</th>
                            <th class="text-center" scope="col">Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($volume->conteudos as $key => $c) 
                            <tr>
                                <th class="py-0 text-center" scope="row">{{$c->id}}</th>
                                <td class="py-0 text-end" scope="row">{{$c->quantidade}}</td>
                                <td class="py-0 text-end" scope="row">{{$c->publicacao->codigo}}</td>
                                <td class="py-0" scope="row">{{$c->publicacao->nome}}</td>
                                <td class="py-0 text-center" scope="row">{{$c->volume->envio->data}}</td>
                                <td class="py-0 text-center" scope="row">{{$c->volume->envio->retirada}}</td>
                                <td class="py-0 text-center" scope="row">{{$c->volume->envio->congregacao->nome}}</td>
                                <td class="py-0 text-center" scope="row"><a href="{{ route('conteudo.show',['conteudo' => $c->id])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                                <td class="py-0 text-center" scope="row"><a href="{{ route('conteudo.edit',['conteudo' => $c->id])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>
</div>
@endsection