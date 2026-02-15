@extends('layouts.app')

@section('content')
    @php($publicacoes = isset($publicacoes) ? $publicacoes : null)
    @php($publicacao = isset($publicacao) ? $publicacao : null)
    <x-crud
        :l="$publicacoes"
        :o="$publicacao"
        r="publicacao"
        tc="Cadastra Publicação"
        te="Altera Publicação"
        ti="Lista de Publicações"
        ts="Mostra Publicação"
    >
        @if($publicacoes)
            <x-slot:filtro>     
            <div class="card-header p-1">    
            <form  id="formFiltro" method="POST" action="{{ route('publicacao.filtrada.post')}}" enctype="multipart/form-data">
                @csrf
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Filtros</span>
                    
                    <input id="codigo" name="codigo" type="text" class="form-control" placeholder="digite o Código" value="{{ $publicacoes->codigoFiltro ? $publicacoes->codigoFiltro : ''}}">
                    <input id="filtro" name="filtro" type="text" class="form-control" placeholder="digite parte do nome da Publicação" value="{{ $publicacoes->nomeFiltro ? $publicacoes->nomeFiltro : ''}}">
                    <button type="submit" class="btn btn-sm btn-outline-primary" form="formFiltro"> Filtrar </button>
                    <a href="{{ route('publicacao.index')}}" class="btn btn-sm btn-outline-success">Limpar</a>
                </div>
            </form> 
        </div> 
            </x-slot>
            <x-slot:lista>
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
            </x-slot>
        @else
            <x-slot:filtro>
            </x-slot>
            <x-slot:lista>
            </x-slot>
            <div class="container-fluid d-flex flex-wrap">
                <div class="col-12 p-2">
                    <input-group-component
                        label="Nome:" 
                        type="text"
                        name="nome" 
                        id="nome" 
                        required="required"
                        value="{{isset($publicacao) ? $publicacao->nome : (old('nome')?old('nome'):'')}}"
                        {{!isset($publicacao->show) ? '' : 'disabled' }} 
                        class="@error('nome') is-invalid @enderror {{old('nome') ? 'is-valid' : ''}}"
                        @error('nome') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
                <div class="col-12 p-2">
                    <input-group-component
                        label="Observação:" 
                        type="text"
                        name="observacao" 
                        id="observacao" 
                        value="{{isset($publicacao) ? $publicacao->observacao : (old('observacao')?old('observacao'):'')}}"
                        {{!isset($publicacao->show) ? '' : 'disabled' }} 
                        class="@error('observacao') is-invalid @enderror {{old('observacao') ? 'is-valid' : ''}}"
                        @error('observacao') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
                <div class="col-12 p-2">
                    <input-group-component
                        label="Proporção (cm):" 
                        type="number"
                        name="proporcao_cm" 
                        id="proporcao_cm" 
                        step="0.5"
                        value="{{isset($publicacao) ? $publicacao->proporcao_cm : (old('proporcao_cm')?old('proporcao_cm'):'0')}}"
                        {{!isset($publicacao->show) ? '' : 'disabled' }} 
                        class="@error('proporcao_cm') is-invalid @enderror {{old('proporcao_cm') ? 'is-valid' : ''}}"
                        @error('proporcao_cm') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
                <div class="col-12 p-2">
                    <input-group-component
                        label="Proporção (unidade):" 
                        type="number"
                        name="proporcao_unidade" 
                        id="proporcao_unidade" 
                        step="0.5"
                        value="{{isset($publicacao) ? $publicacao->proporcao_unidade : (old('proporcao_unidade')?old('proporcao_unidade'):'')}}"
                        {{!isset($publicacao->show) ? '' : 'disabled' }} 
                        class="@error('proporcao_unidade') is-invalid @enderror {{old('proporcao_unidade') ? 'is-valid' : '0'}}"
                        @error('proporcao_unidade') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
                <div class="col-12 p-2">
                    <input-group-component
                        label="Código:" 
                        type="text"
                        name="codigo" 
                        id="codigo" 
                        required="required"
                        value="{{isset($publicacao) ? $publicacao->codigo : (old('codigo')?old('codigo'):'')}}"
                        {{!isset($publicacao->show) ? '' : 'disabled' }} 
                        class="@error('codigo') is-invalid @enderror {{old('codigo') ? 'is-valid' : ''}}"
                        @error('codigo') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
                <div class="col-12 p-2">
                    <input-group-component
                        label="Item:" 
                        type="text"
                        name="item" 
                        id="item" 
                        value="{{isset($publicacao) ? $publicacao->item : (old('item')?old('item'):'')}}"
                        {{!isset($publicacao->show) ? '' : 'disabled' }} 
                        class="@error('item') is-invalid @enderror {{old('item') ? 'is-valid' : ''}}"
                        @error('item') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
                <div class="col-12 p-2">
                    <input-group-component
                        label="Imagem:" 
                        type="file"
                        name="imagem" 
                        id="imagem" 
                        filename="{{isset($publicacao) ? $publicacao->imagem : (old('imagem')?old('imagem'):'')}}"
                        {{!isset($publicacao->show) ? '' : 'disabled' }} 
                        class="@error('imagem') is-invalid @enderror {{old('imagem') ? 'is-valid' : ''}}"
                        @error('imagem') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
            </div>
        @endif
    </x-crud>
@endsection