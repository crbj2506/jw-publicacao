@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
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
    </div>
</div>
@endsection