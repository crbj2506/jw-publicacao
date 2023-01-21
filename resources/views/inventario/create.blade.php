@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5">{{ __('Adicionar Inventário') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('inventario.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="selectLabelCongregacao">Congregação</span>
                            <select class="form-select @error('congregacao_id') is-invalid @enderror" id="selectCongregacao" name="congregacao_id" required>
                                <option  value="" selected>Selecione a Congregação...</option>
                                @foreach ( $congregacoes as $key => $c)
                                    <option value="{{$c->id}}" {{@old('congregacao_id') == $c->id ? 'selected': ''}}>{{ $c->nome }}</option>
                                @endforeach
                            </select>
                        @error('congregacao_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Ano') }}</span>
                            <input id="ano" type="text" class="form-control @error('ano') is-invalid @enderror text-end" name="ano" value="{{ $ano ?? old('ano') }}" required autocomplete="ano" >
                            @error('ano')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Mês') }}</span>
                            <input id="mes" type="text" class="form-control @error('mes') is-invalid @enderror text-end" name="mes" value="{{ $mes ?? old('mes') }}" required autocomplete="mes" >
                            @error('mes')
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