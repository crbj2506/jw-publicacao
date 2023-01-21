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
                                {{ __('Visualizando Conteúdo: ') }} {{ $conteudo->id }}
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('conteudo.index')}}" class="btn btn-sm btn-link">Listar</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('conteudo.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="selectLabelVolume">Volume</span>
                            <select class="form-select @error('volume_id') is-invalid @enderror" id="selectVolume" name="volume_id" disabled>
                                <option  value="" selected>Selecione o Volume...</option>
                                @foreach ( $volumes as $key => $v)
                                    <option value="{{$v->id}}" {{(@old('volume_id') == $v->id) || ($conteudo->volume_id == $v->id) ? 'selected': ''}}>{{ $v->volume }} do envio {{ $v->envio->nota }} da Congregação {{ $v->envio->congregacao->nome }} </option>
                                @endforeach
                            </select>
                        @error('volume_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="selectLabelPublicacao">Publicação</span>
                            <select class="form-select @error('publicacao_id') is-invalid @enderror" id="selectPublicacao" name="publicacao_id" disabled>
                                <option  value="" selected>Selecione a Publicação...</option>
                                @foreach ( $publicacoes as $key => $p)
                                    <option value="{{$p->id}}" {{(@old('publicacao_id') == $p->id) || ($conteudo->publicacao_id == $p->id) ? 'selected': ''}}>{{ $p->nome }}</option>
                                @endforeach
                            </select>
                        @error('publicacao_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Quantidade') }}</span>
                            <input id="quantidade" type="number" class="form-control @error('quantidade') is-invalid @enderror text-end" name="quantidade" value="{{ $conteudo->quantidade ?? old('quantidade') }}" disabled autocomplete="quantidade" >
                            @error('quantidade')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8">
                                <a href="{{ route('conteudo.edit',['conteudo' => $conteudo->id])}}" class="btn btn-sm btn-outline-primary">Editar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection