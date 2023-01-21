@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="container">
                        <div class="row">
                            <div class="col-auto me-auto fs-5">
                                {{ __('Visualizando Envio - Nota: ') }} {{ $envio->nota }}
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('envio.index')}}" class="btn btn-sm btn-link">Listar</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="selectLabelPublicacao">Congregação</span>
                        <select class="form-select @error('congregacao_id') is-invalid @enderror" id="selectPublicacao" name="congregacao_id" disabled>
                            <option  value="" selected>Selecione a Congregação...</option>
                            @foreach ( $congregacoes as $key => $c)
                                <option value="{{$c->id}}" {{(@old('congregacao_id') == $c->id) || ($envio->congregacao_id == $c->id) ? 'selected': ''}}>{{ $c->nome }}</option>
                            @endforeach
                        </select>
                    @error('congregacao_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    </div>
                
                    <div class="input-group mb-3">
                        <span class="input-group-text">{{ __('Nota') }}</span>
                        <input id="nota" type="text" class="form-control" name="nota" value="{{ $envio->nota }}" disabled>
                    </div>
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text">{{ __('Data') }}</span>
                        <input id="data" type="date" class="form-control" name="data" value="{{ $envio->data }}" disabled>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">{{ __('Retirada') }}</span>
                        <input id="retirada" type="retirada" class="form-control" name="retirada" value="{{ $envio->retirada }}" disabled>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('envio.edit',['envio' => $envio->id])}}" class="btn btn-sm btn-outline-primary">Editar</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header fs-5">
                    {{ __('Volumes do envio ') }}{{ $envio->nota }}
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Volume</th>
                                <th scope="col">Envio</th>
                                <th scope="col">Data</th>
                                <th scope="col">Congregação</th>
                                <th scope="col">Ver</th>
                                <th scope="col">Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($envio->volumes as $key => $v)
                                <tr>
                                    <th scope="row">{{$v['id']}}</th>
                                    <td>{{$v->volume}}</td>
                                    <td>{{$v->envio->nota}}</td>
                                    <td>{{$v->envio->data}}</td>
                                    <td>{{$v->envio->congregacao->nome}}</td>
                                    <td><a href="{{ route('volume.show',['volume' => $v['id']])}}" class="btn btn-sm btn-outline-primary" class="btn btn-sm btn-outline-warning">Ver</a></td>
                                    <td><a href="{{ route('volume.edit',['volume' => $v['id']])}}" class="btn btn-sm btn-outline-warning">Editar</a></td>
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
</div>
@endsection