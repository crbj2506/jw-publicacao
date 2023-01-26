@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5">{{ __('Editar Estoque') }}</div>

                <div class="card-body">
                    <form id="formUpdate" method="POST" action="{{ route('estoque.update', ['estoque' => $estoque->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="selectLabelLocal">Local</span>
                            <select class="form-select @error('local_id') is-invalid @enderror" id="selectLocal" name="local_id" required>
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
                            <select class="form-select @error('publicacao_id') is-invalid @enderror" id="selectPublicacao" name="publicacao_id" required>
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
                            <input id="observacao" name="observacao" type="text" class="form-control text-end" value="{{ $estoque->publicacao->observacao }}" disabled>
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Proporção') }}</span>
                            <input id="proporcao" type="text" class="form-control text-end" value="{{$estoque->publicacao->proporcao_cm ? $estoque->publicacao->proporcao_cm . ' cm' : ''}}{{$estoque->publicacao->proporcao_cm && $estoque->publicacao->proporcao_unidade ? ' = ' : ''}}{{$estoque->publicacao->proporcao_unidade ? $estoque->publicacao->proporcao_unidade : ''}}" disabled>
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Quantidade') }}</span>
                            <input id="quantidade" type="number" class="form-control @error('quantidade') is-invalid @enderror text-end" name="quantidade" value="{{ $estoque->quantidade ?? old('quantidade') }}" required autocomplete="quantidade" >
                            @error('quantidade')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="card-footer container">
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-sm btn-outline-success me-2" form="formUpdate">
                                {{ __('Salvar') }}
                            </button>
                            <a href="{{ route('estoque.show', ['estoque' => $estoque->id]) }}" class="btn btn-sm btn-outline-warning me-2">Cancelar</a>
                            @if($estoque->quantidade == 0)
                                <button type="submit" class="me-2 btn btn-sm btn-outline-danger" form="formDelete">
                                    {{ __('Excluir') }}
                                </button>
                                <form method="POST" action="{{ route('estoque.destroy', ['estoque' => $estoque]) }}" id="formDelete">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                        <div class="col text-end">
                            <a href="{{ $estoque->estoqueAnterior ? route('estoque.edit',['estoque' => $estoque->estoqueAnterior]) : '' }}" class="btn btn-sm btn-outline-secondary @if(!$estoque->estoqueAnterior) disabled @endif me-2">Anterior</a>
                            <a href="{{ $estoque->estoquePosterior ? route('estoque.edit',['estoque' => $estoque->estoquePosterior]) : '' }}" class="btn btn-sm btn-outline-secondary @if(!$estoque->estoquePosterior) disabled @endif me-2">Posterior</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection