@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5">{{ __('Editar Volume: ') }}{{ $volume->volume}}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('volume.update', ['volume' => $volume->id]) }}" enctype="multipart/form-data" id="formUpdate">
                        @csrf
                        @method('PUT')

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="selectLabelPublicacao">Envio</span>
                            <select class="form-select @error('envio_id') is-invalid @enderror" id="selectEnvio" name="envio_id" required>
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
                            <input id="volume" type="text" class="form-control @error('volume') is-invalid @enderror" name="volume" value="{{ $volume->volume ?? old('volume') }}" required autocomplete="volume" autofocus>
                            @error('volume')
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
                    <a href="{{ route('volume.show', ['volume' => $volume->id]) }}" class="btn btn-sm btn-outline-warning mx-3">Cancelar</a>
                </div>
            </div>

            
            <div class="card">
            
            </div>
        </div>
    </div>
</div>
@endsection