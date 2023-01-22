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
                                {{ __('Visualizando Local - ID: ') }} {{ $local->id }}
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('local.index')}}" class="btn btn-sm btn-link">Listar</a>
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
                                <option value="{{$c->id}}" {{(@old('congregacao_id') == $c->id) || ($local->congregacao_id == $c->id) ? 'selected': ''}}>{{ $c->nome }}</option>
                            @endforeach
                        </select>
                    @error('congregacao_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    </div>
                
                    <div class="input-group mb-3">
                        <span class="input-group-text">{{ __('Sigla') }}</span>
                        <input id="sigla" type="text" class="form-control" name="sigla" value="{{ $local->sigla }}" disabled>
                    </div>
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text">{{ __('Nome') }}</span>
                        <input id="nome" type="text" class="form-control" name="nome" value="{{ $local->nome }}" disabled>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('local.edit',['local' => $local->id])}}" class="btn btn-sm btn-outline-primary">Editar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection