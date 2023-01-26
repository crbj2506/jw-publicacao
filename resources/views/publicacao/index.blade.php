@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center">
    <div class="card">
        <div class="card-header container">
            <div class="row">
                <div class="col">{{ __('Lista de Publicações Cadastradas') }}</div>
                <div class="col">
                    <form method="POST" action="{{ route('publicacao.filtrada.post') }}" enctype="multipart/form-data" id="formFiltro">
                        @csrf
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text">Filtrar por Nome</span>
                            <input id="filtro" name="filtro" type="text" class="form-control" placeholder="digite parte do nome" value="{{ $publicacoes->nomeFiltro }}">
                            <button type="submit" class="btn btn-sm btn-outline-primary" form="formFiltro">
                                {{ __('Filtrar') }}
                            </button>
                            <a href="{{ route('publicacao.index')}}" class="btn btn-sm btn-outline-success">Limpar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Código</th>
                        <th class="text-center" scope="col">Nome</th>
                        <th class="text-center" scope="col">Observação</th>
                        <th class="text-center" scope="col">Proporção</th>
                        <th class="text-center" scope="col">Item</th>
                        <th class="text-center" scope="col">Imagem</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                    </tr>
                </thead>
            <tbody>
            @foreach ($publicacoes as $key => $p)
                <tr>
                    <th class="py-0 text-center" scope="row">{{$p['id']}}</th>
                    <td class="py-0 text-end" scope="row">{{$p['codigo']}}</td>
                    <td class="py-0" scope="row">{{$p['nome']}}</td>
                    <td class="py-0 text-center" scope="row">{{$p['observacao']}}</td>
                    <td class="py-0 text-center" scope="row">
                        {{$p->proporcao_cm ? $p->proporcao_cm . ' cm' : ''}}
                        {{$p->proporcao_cm && $p->proporcao_unidade ? ' = ' : ''}}
                        {{$p->proporcao_unidade ? $p->proporcao_unidade : ''}}
                    </td>
                    <td class="py-0 text-center" scope="row">{{$p['item']}}</td>
                    <td class="py-0 text-center" scope="row"> 
                        @if ($p['imagem'])
                            <button type="button" class="btn btn-sm btn-outline-info py-0" data-bs-toggle="modal" data-bs-target="#modal{{$p['id']}}">Imagem</button>
                        @endif
                    </td>
                    <td class="py-0 text-center" scope="row"><a href="{{ route('publicacao.show',['publicacao' => $p['id']])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                    <td class="py-0 text-center" scope="row"><a href="{{ route('publicacao.edit',['publicacao' => $p['id']])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                </tr>


                @if ($p['imagem'])
                <!-- Modal -->
                <div class="modal fade" id="modal{{$p['id']}}" tabindex="-1" aria-labelledby="modalLabelmodal{{$p['id']}}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabelmodal{{$p['id']}}">{{$p['nome']}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <img src="/storage/{{$p['imagem']}}" class="img-thumbnail">
                        </div>
                        <div class="modal-footer">
                            <button type="button btn-sm" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                        </div>
                    </div>
                </div>
                @endif

            @endforeach
                </tbody>
            </table>
        </div>
        <x-paginacao :p="$publicacoes"></x-paginacao>
    </div>
</div>
@endsection
