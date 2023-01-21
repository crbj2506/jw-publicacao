@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5">{{ __('Adicionar Volume') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('volume.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="selectLabelEnvio">Envio</span>
                            <select class="form-select @error('envio_id') is-invalid @enderror" id="selectEnvio" name="envio_id" required>
                                <option  value="" selected>Selecione o Envio...</option>
                                @foreach ( $envios as $key => $e)
                                    <option value="{{$e->id}}" {{@old('envio_id') == $e->id ? 'selected': ''}}>{{ $e->nota }} {{ $e->data ? 'de ' . $e->data : ''  }}</option>
                                @endforeach
                            </select>
                        @error('envio_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Volume') }}</span>
                            <input id="volume" type="text" class="form-control @error('volume') is-invalid @enderror" name="volume" value="{{ $volume->volume ?? old('volume') }}" required autocomplete="volume" autofocus>
                            @error('volume')
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