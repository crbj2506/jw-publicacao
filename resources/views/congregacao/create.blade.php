@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5 fw-bold">{{ __('Adicionar Congregação') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('congregacao.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="input-group mb-3">
                            <span class="input-group-text fw-bold">{{ __('Nome') }}</span>
                            <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ $congregacao->nome ?? old('nome') }}" required autocomplete="nome" autofocus>
                            @error('nome')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary">
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