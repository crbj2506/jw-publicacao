@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
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
                    <a href="{{ route('congregacao.edit',['congregacao' => $congregacao->id])}}" class="btn btn-sm btn-outline-warning">Editar</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header fs-5">
                    {{ __('Envios da Congregação ') }}{{ $congregacao->nome }}
                </div>

                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">Nota</th>
                                <th scope="col" class="text-center">Data</th>
                                <th scope="col" class="text-center">Ver</th>
                                <th scope="col" class="text-center">Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($congregacao->envios as $key => $e)
                                <tr>
                                    <th class="py-0 text-center" scope="row">{{$e['id']}}</th>
                                    <td class="py-0 text-center">{{$e->nota}}</td>
                                    <td class="py-0 text-center">{{$e->data}}</td>
                                    <td class="py-0 text-center"><a href="{{ route('envio.show',['envio' => $e['id']])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                                    <td class="py-0 text-center"><a href="{{ route('envio.edit',['envio' => $e['id']])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection