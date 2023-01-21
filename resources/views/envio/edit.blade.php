@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5">{{ __('Editar Envio - Nota: ') }}{{ $envio->nota}}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('envio.update', ['envio' => $envio->id]) }}" enctype="multipart/form-data" id="formUpdate">
                        @csrf
                        @method('PUT')

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="selectLabelPublicacao">Congregação</span>
                            <select class="form-select @error('congregacao_id') is-invalid @enderror" id="selectPublicacao" name="congregacao_id" required>
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
                            <input id="nota" type="text" class="form-control @error('nota') is-invalid @enderror" name="nota" value="{{ $envio->nota ?? old('nota') }}" required autocomplete="nota" autofocus>
                            @error('nota')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Data') }}</span>
                            <input id="data" type="date" class="form-control @error('data') is-invalid @enderror" name="data" value="{{ $envio->data ?? old('data') }}">
                            @error('data')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Retirada') }}</span>
                            <input id="retirada" type="date" class="form-control @error('retirada') is-invalid @enderror" name="retirada" value="{{ $envio->retirada ?? old('retirada') }}">
                            @error('retirada')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-sm btn-outline-success" form="formUpdate">
                        {{ __('Salvar') }}
                    </button>
                    <a href="{{ route('envio.show', ['envio' => $envio->id]) }}" class="btn btn-sm btn-outline-warning mx-3">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection