@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5">{{ __('Adicionar Envio') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('envio.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="selectLabelPublicacao">Congregação</span>
                            <select class="form-select @error('congregacao_id') is-invalid @enderror" id="selectPublicacao" name="congregacao_id" required>
                                <option  value="" selected>Selecione a Congregação...</option>
                                @foreach ( $congregacoes as $key => $p)
                                    <option value="{{$p->id}}" {{@old('congregacao_id') == $p->id ? 'selected': ''}}>{{ $p->nome }}</option>
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

                        <div class="row mb-0">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-outline-primary">
                                    {{ __('Cadastrar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection