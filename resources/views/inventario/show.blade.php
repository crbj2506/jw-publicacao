@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-5">{{ __('Visualizando Inventário da Congregação ') . $congregacao->nome }}</div>
                <div class="card-body">
                    @foreach ( $congregacao->publicacoes as $key => $p)
                        @if($p->pivot->quantidade > 0)
                            <div class="input-group mb-3">
                                <span class="input-group-text">{{ $p->codigo }} - {{ $p->nome }}</span>
                                <input id="{{$p->id}}" type="number" class='text-end form-control' name="{{$p->id}}" value="{{$p->pivot->quantidade}}" disabled>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="card-footer">
                    <a href="{{ route('inventario.edit',['congregacao' => $congregacao->id])}}" class="btn btn-sm btn-primary">Editar</a>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection





