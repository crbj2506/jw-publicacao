@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="container">
                        <div class="row">
                            <div class="col-auto me-auto fs-5">
                                {{ __('Visualizando Congregação') }}
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('congregacao.index')}}" class="btn btn-sm btn-link">Listar</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="input-group mb-3">
                        <span class="input-group-text">{{ __('Nome') }}</span>
                        <input id="nome" type="text" class="form-control" name="nome" value="{{ $congregacao->nome }}" disabled>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('congregacao.edit',['congregacao' => $congregacao->id])}}" class="btn btn-sm btn-outline-primary">Editar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection