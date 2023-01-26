@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Adicionar Publicação') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('publicacao.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="nome" class="col-md-4 col-form-label text-md-end">{{ __('Nome') }}</label>

                            <div class="col-md-6">
                                <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}" required autocomplete="nome" autofocus>

                                @error('nome')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="observacao" class="col-md-4 col-form-label text-md-end">{{ __('Observação') }}</label>

                            <div class="col-md-6">
                                <input id="observacao" type="text" class="form-control @error('observacao') is-invalid @enderror" name="observacao" value="{{ old('observacao') }}" autocomplete="observacao" autofocus>

                                @error('observacao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="proporcao" class="col-md-4 col-form-label text-md-end">{{ __('Proporção Centímetros') }}</label>

                            <div class="col-md-6">
                                <input id="proporcao_cm" type="number" step="0.5" class="form-control @error('proporcao_cm') is-invalid @enderror" name="proporcao_cm" value="{{ old('proporcao_cm') }}" autocomplete="proporcao_cm" autofocus>

                                @error('proporcao_cm')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="codigo" class="col-md-4 col-form-label text-md-end">{{ __('Proporção Unidades') }}</label>
                            <div class="col-md-6">

                                <input id="proporcao_unidade" type="number" class="form-control @error('proporcao_unidade') is-invalid @enderror" name="proporcao_unidade" value="{{ old('proporcao_unidade') }}" autocomplete="proporcao_unidade" autofocus>

                                @error('proporcao_unidade')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="codigo" class="col-md-4 col-form-label text-md-end">{{ __('Código') }}</label>

                            <div class="col-md-6">
                                <input id="codigo" type="text" class="form-control @error('codigo') is-invalid @enderror" name="codigo" value="{{ old('codigo') }}" required autocomplete="codigo" autofocus>

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
                                <input id="item" type="text" class="form-control @error('item') is-invalid @enderror" name="item" value="{{ old('item') }}" autocomplete="codigo" autofocus>

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
                                <input id="imagem" type="file" class="form-control" name="imagem">

                                @error('imagem')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
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
