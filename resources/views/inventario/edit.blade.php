@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header fs-5">{{ __('Atualizar Inventário da Congregação ') . $congregacao->nome }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('inventario.update', ['congregacao' => $congregacao] ) }}" enctype="multipart/form-data" id="formUpdate">
                        @csrf
                        @method('PUT')

                        @foreach ( $congregacao->publicacoes as $key => $p)
                            @if($p->pivot->quantidade > 0)
                                <div class="input-group mb-3">
                                    <span class="input-group-text">{{ $p->codigo }} - {{ $p->nome }}</span>
                                    <input id="publicacao[{{$p->id}}]" type="number" class="text-end form-control @error('publicacao.'.$p->id) is-invalid @enderror" name="publicacao[{{$p->id}}]" value="{{ old("publicacao.'.$p->id.'") ? old("publicacao.'.$p->id.'") : $p->pivot->quantidade }}" required>
                                    <div class="invalid-feedback">
                                    @error('publicacao.'.$p->id)
                                        {{$message}}
                                    @enderror
                                    </div>
                                    <span class="input-group-text">{{ __('Local') }}</span>
                                    <input id="local[{{$p->id}}]" type="text" class="text-end form-control @error('local.'.$p->id) is-invalid @enderror" name="local[{{$p->id}}]" value="{{ old("publicacao.'.$p->id.'") ? old("local.'.$p->id.'") : $p->pivot->local }}">
                                    <div class="invalid-feedback">
                                    @error('local.'.$p->id)
                                        {{$message}}
                                    @enderror
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </form>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-sm btn-outline-success" form="formUpdate">
                        {{ __('Atualizar') }}
                    </button>
                    <a href="{{ route('inventario.show', ['congregacao' => $congregacao]) }}" class="btn btn-sm btn-outline-warning mx-3">Cancelar</a>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header fs-5">{{ __('Adicionar Publicação ao Inventário da Congregação') . $congregacao->nome }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('inventario.store', ['congregacao' => $congregacao] ) }}" enctype="multipart/form-data" id="formCreate">
                        @csrf
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="selectLabelPublicacao">Publicação</span>
                            <select class="form-select @error('publicacao_id') is-invalid @enderror" id="selectPublicacao" name="publicacao_id" required>
                                <option  value="" selected>Selecione a Publicação...</option>
                                @foreach ( $publicacoes as $key => $p)
                                    @if(!$congregacao->publicacoes->contains($p->id))
                                        <option value="{{$p->id}}" {{@old('publicacao_id') == $p->id ? 'selected': ''}}>{{ $p->codigo }} - {{ $p->nome }}</option>
                                    @endif
                                @endforeach
                            </select>
                        @error('publicacao_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="InputLabelQuantidade">Quantidade</span>
                            <input id="quantidade" type="number" class="text-end form-control @error('quantidade') is-invalid @enderror"  name="quantidade" required>
                            @error('quantidade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="InputLabelLocal">Local</span>
                            <input id="local" type="text" class="text-end form-control @error('local') is-invalid @enderror"  name="local" required>
                            @error('local')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>


                <div class="card-footer">
                    <button type="submit" class="btn btn-sm btn-outline-success" form="formCreate">
                        {{ __('Adicionar') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection