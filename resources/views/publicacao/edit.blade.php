@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Editar Publicação - ID: ') }}{{ $publicacao->id}}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('publicacao.update', ['publicacao' => $publicacao->id]) }}" enctype="multipart/form-data" id="formUpdate">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="nome" class="col-md-4 col-form-label text-md-end">{{ __('Nome') }}</label>

                            <div class="col-md-6">
                                <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ $publicacao->nome ?? old('nome') }}" required autocomplete="nome" autofocus>

                                @error('nome')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="codigo" class="col-md-4 col-form-label text-md-end">{{ __('Código') }}</label>

                            <div class="col-md-6">
                                <input id="codigo" type="text" class="form-control @error('codigo') is-invalid @enderror" name="codigo" value="{{ $publicacao->codigo ?? old('codigo') }}" required autocomplete="codigo" autofocus>

                                @error('codigo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="item" class="col-md-4 col-form-label text-md-end">{{ __('Item') }}</label>

                            <div class="col-md-6">
                                <input id="item" type="text" class="form-control @error('item') is-invalid @enderror" name="item" value="{{ $publicacao->item ?? old('item') }}" required autocomplete="codigo" autofocus>

                                @error('item')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="imagem" class="col-md-4 col-form-label text-md-end">{{ __('Imagem') }}</label>
                            <div class="col-md-6">
                                @if ($publicacao->imagem)
                                    <img src="/storage/{{$publicacao->imagem}}"  class="img-thumbnail">
                                @endif
                                <input id="imagem" type="file" class="form-control-file" name="imagem">
                            </div>
                        </div>
                    </form>
                </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-sm btn-outline-success" form="formUpdate">
                            {{ __('Salvar') }}
                        </button>
                        <button type="submit" class="mx-3 btn btn-sm btn-danger" form="formDelete">
                            {{ __('Excluir') }}
                        </button>
                        <a href="{{ route('publicacao.show', ['publicacao' => $publicacao->id]) }}" class="btn btn-sm btn-outline-warning">Cancelar</a>

                        <form method="POST" action="{{ route('publicacao.destroy', ['publicacao' => $publicacao->id]) }}" id="formDelete">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection

