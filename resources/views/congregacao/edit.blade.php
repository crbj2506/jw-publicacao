@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5">{{ __('Editar Congregação - ID: ') }}{{ $congregacao->id}}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('congregacao.update', ['congregacao' => $congregacao->id]) }}" enctype="multipart/form-data" id="formUpdate">
                        @csrf
                        @method('PUT')
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('Nome') }}</span>
                            <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ $congregacao->nome ?? old('nome') }}" required autocomplete="nome" autofocus>
                            @error('nome')
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
                    <a href="{{ route('congregacao.show', ['congregacao' => $congregacao->id]) }}" class="btn btn-sm btn-outline-warning mx-3">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection