@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5">{{ __('Editar Inventário') }}</div>

                <div class="card-body">
                    <form id="formUpdate" method="POST" action="{{ route('inventario.update', ['inventario' => $inventario->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="selectLabelCongregacao">Congregação</span>
                            <select class="form-select @error('congregacao_id') is-invalid @enderror" id="selectCongregacao" name="congregacao_id" required>
                                <option  value="" selected>Selecione a Congregação...</option>
                                @foreach ( $congregacoes as $key => $c)
                                    <option value="{{$c->id}}" {{(@old('congregacao_id') == $c->id) || ($inventario->congregacao_id == $c->id) ? 'selected': ''}}>{{ $c->nome }}</option>
                                @endforeach
                            </select>
                        @error('congregacao_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="selectLabelCongregacao">Publicação</span>
                            <select class="form-select @error('publicacao_id') is-invalid @enderror" id="selectCongregacao" name="publicacao_id" required>
                                <option  value="" selected>Selecione a Publicação...</option>
                                @foreach ( $publicacoes as $key => $p)
                                    <option value="{{$p->id}}" {{(@old('publicacao_id') == $p->id) || ($inventario->publicacao_id == $p->id) ? 'selected': ''}}>{{ $p->nome }}</option>
                                @endforeach
                            </select>
                        @error('publicacao_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Ano') }}</span>
                            <input id="ano" type="text" class="form-control @error('ano') is-invalid @enderror text-end" name="ano" value="{{ $inventario->ano ?? old('ano') }}" required autocomplete="ano" >
                            @error('ano')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Mês') }}</span>
                            <input id="mes" type="text" class="form-control @error('mes') is-invalid @enderror text-end" name="mes" value="{{ $inventario->mes ?? old('mes') }}" required autocomplete="mes" >
                            @error('mes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Recebido') }}</span>
                            <input id="recebido" type="number" class="form-control @error('recebido') is-invalid @enderror text-end" name="recebido" value="{{ $inventario->recebido ?? old('recebido') }}" required autocomplete="recebido" >
                            @error('recebido')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Estoque') }}</span>
                            <input id="estoque" type="number" class="form-control @error('estoque') is-invalid @enderror text-end" name="estoque" value="{{ $inventario->estoque ?? old('estoque') }}" required autocomplete="estoque" >
                            @error('estoque')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Saída') }}</span>
                            <input id="saida" type="number" class="form-control @error('saida') is-invalid @enderror text-end" name="saida" value="{{ $inventario->saida ?? old('saida') }}" required autocomplete="saida" >
                            @error('saida')
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
                    <a href="{{ route('inventario.show', ['inventario' => $inventario->id]) }}" class="btn btn-sm btn-outline-warning mx-3">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection