@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5">{{ __('Editar Local - ID: ') }}{{ $local->id}}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('local.update', ['local' => $local->id]) }}" enctype="multipart/form-data" id="formUpdate">
                        @csrf
                        @method('PUT')

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="selectLabelPublicacao">Congregação</span>
                            <select class="form-select @error('congregacao_id') is-invalid @enderror" id="selectPublicacao" name="congregacao_id" required>
                                <option  value="" selected>Selecione a Congregação...</option>
                                @foreach ( $congregacoes as $key => $c)
                                    <option value="{{$c->id}}" {{(@old('congregacao_id') == $c->id) || ($local->congregacao_id == $c->id) ? 'selected': ''}}>{{ $c->nome }}</option>
                                @endforeach
                            </select>
                        @error('congregacao_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Nota') }}</span>
                            <input id="sigla" type="text" class="form-control @error('sigla') is-invalid @enderror" name="sigla" value="{{ $local->sigla ?? old('sigla') }}" required autocomplete="sigla" autofocus>
                            @error('sigla')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Data') }}</span>
                            <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ $local->nome ?? old('nome') }}">
                            @error('nome')
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
                    <a href="{{ route('local.show', ['local' => $local->id]) }}" class="btn btn-sm btn-outline-warning mx-3">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection