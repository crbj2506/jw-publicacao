@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-auto me-auto fs-5">
                                {{ __('Visualizando Estoque: ') }} {{ $estoque->id }}
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('estoque.index')}}" class="btn btn-sm btn-link">Listar</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('estoque.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="selectLabelLocal">Local</span>
                            <select class="form-select @error('local_id') is-invalid @enderror" id="selectLocal" name="local_id" disabled>
                                <option  value="" selected>Selecione o Local...</option>
                                @foreach ( $locais as $key => $l)
                                    <option value="{{$l->id}}" {{(@old('local_id') == $l->id) || ($estoque->local_id == $l->id) ? 'selected': ''}}>{{ $l->sigla }} - {{ $l->nome }}</option>
                                @endforeach
                            </select>
                        @error('local_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="selectLabelPublicacao">Publicação</span>
                            <select class="form-select @error('publicacao_id') is-invalid @enderror" id="selectPublicacao" name="publicacao_id" disabled>
                                <option  value="" selected>Selecione a Publicação...</option>
                                @foreach ( $publicacoes as $key => $p)
                                    <option value="{{$p->id}}" {{(@old('publicacao_id') == $p->id) || ($estoque->publicacao_id == $p->id) ? 'selected': ''}}>{{ $p->nome }}</option>
                                @endforeach
                            </select>
                        @error('publicacao_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Observação') }}</span>
                            <input id="observacao" type="text" class="form-control text-end" value="{{ $estoque->publicacao->observacao }}" disabled>
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Proporção') }}</span>
                            <input id="proporcao" type="text" class="form-control text-end" value="{{$estoque->publicacao->proporcao_cm ? $estoque->publicacao->proporcao_cm . ' cm' : ''}}{{$estoque->publicacao->proporcao_cm && $estoque->publicacao->proporcao_unidade ? ' = ' : ''}}{{$estoque->publicacao->proporcao_unidade ? $estoque->publicacao->proporcao_unidade : ''}}" disabled>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Quantidade') }}</span>
                            <input id="quantidade" type="number" class="form-control @error('quantidade') is-invalid @enderror text-end" name="quantidade" value="{{ $estoque->quantidade ?? old('quantidade') }}" disabled autocomplete="quantidade" >
                            @error('quantidade')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8">
                            </div>
                        </div>
                    </form>
                </div>           
                <div class="card-footer container">
                    <div class="row">
                        <div class="col">
                            <a href="{{ route('estoque.edit',['estoque' => $estoque->id])}}" class="btn btn-sm btn-outline-primary">Editar</a>
                        </div>
                        <div class="col text-end">
                            <a href="{{ $estoque->estoqueAnterior ? route('estoque.show',['estoque' => $estoque->estoqueAnterior]) : '' }}" class="btn btn-sm btn-outline-secondary @if(!$estoque->estoqueAnterior) disabled @endif me-2">Anterior</a>
                            <a href="{{ $estoque->estoquePosterior ? route('estoque.show',['estoque' => $estoque->estoquePosterior]) : '' }}" class="btn btn-sm btn-outline-secondary @if(!$estoque->estoquePosterior) disabled @endif me-2">Posterior</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection