@extends('layouts.app')

@section('content')
    @php($conteudos = isset($conteudos) ? $conteudos : null)
    @php($conteudo = isset($conteudo) ? $conteudo : null)
    <x-crud
        :l="$conteudos"
        :o="$conteudo"
        r="conteudo"
        tc="Cadastra Conteúdo"
        te="Altera Conteúdo"
        ti="Lista de Conteúdos"
        ts="Mostra Conteúdo"
    >
        @if($conteudos)
            <x-slot:filtro>
                <div class="card-header p-1">
                    <form  id="formFiltro" method="POST" action="{{ route('conteudo.filtrada.post') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Filtros</span>
                            <input id="codigo" name="codigo" type="text" class="form-control" placeholder="Código" value="{{ $conteudos->codigoFiltro ? $conteudos->codigoFiltro : ''}}">
                            <input id="publicacao" name="publicacao" type="text" class="form-control" placeholder="Publicação" value="{{ $conteudos->publicacaoFiltro ? $conteudos->publicacaoFiltro : ''}}">
                            <input id="volume" name="volume" type="text" class="form-control" placeholder="Volume" value="{{ $conteudos->volumeFiltro ? $conteudos->volumeFiltro : ''}}">
                            <input id="envio" name="envio" type="text" class="form-control" placeholder="Envio" value="{{ $conteudos->envioFiltro ? $conteudos->envioFiltro : ''}}">
                            <button type="submit" class="btn btn-sm btn-outline-primary" form="formFiltro"> Filtrar </button>
                            <a href="{{ route('conteudo.index')}}" class="btn btn-sm btn-outline-success">Limpar</a>
                        </div>
                    </form>
                </div>
            </x-slot>
            <x-slot:lista>
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Quantidade</th>
                        <th class="text-center" scope="col">Código</th>
                        <th class="text-center" scope="col">Publicação</th>
                        <th class="text-center" scope="col">Volume</th>
                        <th class="text-center" scope="col">Envio</th>
                        <th class="text-center" scope="col">Data do Envio</th>
                        <th class="text-center" scope="col">Data da Retirada</th>
                        <th class="text-center" scope="col">Congregação</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($conteudos as $key => $c) 
                        <tr>
                            <th class="py-0 text-center" scope="row">{{$c->id}}</th>
                            <td class="py-0 text-end" scope="row">{{$c->quantidade}}</td>
                            <td class="py-0 text-end" scope="row">{{$c->publicacao->codigo}}</td>
                            <td class="py-0" scope="row">{{$c->publicacao->nome}}</td>
                            <td class="py-0" scope="row">{{$c->volume->volume}}</td>
                            <td class="py-0 text-center" scope="row">{{$c->volume->envio->nota}}</td>
                            <td class="py-0 text-center" scope="row">{{$c->volume->envio->data}}</td>
                            <td class="py-0 text-center" scope="row">{{$c->volume->envio->retirada}}</td>
                            <td class="py-0 text-center" scope="row">{{$c->volume->envio->congregacao->nome}}</td>
                            <td class="py-0 text-center" scope="row"><a href="{{ route('conteudo.show',['conteudo' => $c->id])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="py-0 text-center" scope="row"><a href="{{ route('conteudo.edit',['conteudo' => $c->id])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-slot>
        @else
            <x-slot:filtro>
            </x-slot>
            <x-slot:lista>
            </x-slot>
            <div class="container-fluid d-flex flex-wrap">
                <div class="col-12 col-xl-6 p-2">
                    <select-filter-component
                        class="@error('publicacao_id') is-invalid @enderror {{old('publicacao_id') ? 'is-valid' : ''}}"
                        @error('publicacao_id') classinputgroup="has-validation" @enderror
                        classmessage="valid-feedback @error('publicacao_id') invalid-feedback @enderror"
                        id="publicacao_id"
                        label="Publicação:"
                        @error('publicacao_id') message="{{$message}}" @enderror
                        name="publicacao_id"
                        option="Selecione a Publicação..."
                        options="{{json_encode($publicacoes)}}"
                        old_id="{{ isset($conteudo) ? $conteudo->publicacao_id : @old('publicacao_id') }}"
                        required="required"
                        value="{{ isset($conteudo) ? $conteudo->publicacao->nome : @old('publicacao_id') }}"
                        {{isset($conteudo->show) ? 'disabled' : ''}}
                    ></select-filter-component>
                </div>

                <div class="col-12 col-xl-6 p-2">
                    <select-filter-component
                        class="@error('volume_id') is-invalid @enderror {{old('volume_id') ? 'is-valid' : ''}}"
                        @error('volume_id') classinputgroup="has-validation" @enderror
                        classmessage="valid-feedback @error('volume_id') invalid-feedback @enderror"
                        id="volume_id"
                        label="Volume:"
                        @error('volume_id') message="{{$message}}" @enderror
                        name="volume_id"
                        option="Selecione o Volume..."
                        options="{{json_encode($volumes)}}"
                        old_id="{{ isset($conteudo) ? $conteudo->volume_id : @old('volume_id') }}"
                        required="required"
                        value="{{ isset($conteudo) ? $conteudo->volume->volume . ' envio: ' . $conteudo->volume->envio->nota  . ' de ' . $conteudo->volume->envio->data: @old('volume_id') }}"
                        {{isset($conteudo->show) ? 'disabled' : ''}}
                    ></select-filter-component>
                </div>
                <div class="col-12 col-sm-6 col-md-4 p-2">
                    <input-group-component
                        label="Quantidade:" 
                        type="number"
                        name="quantidade" 
                        id="quantidade" 
                        required="required"
                        value="{{isset($conteudo) ? $conteudo->quantidade : (old('quantidade')?old('quantidade'):'')}}"
                        {{isset($conteudo->show) ? 'disabled' : ''}} 
                        class="@error('quantidade') is-invalid @enderror {{old('quantidade') ? 'is-valid' : ''}}"
                        @error('quantidade') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>

                @if(!isset($conteudo->show))
                    <div class="col-12 p-2 d-flex flex-wrap gap-2 align-items-center justify-content-end">
                        <span class="text-muted small me-2">Não encontrou o que precisava?</span>
                        <button type="button" class="btn btn-sm {{ $errors->hasAny(['nome', 'codigo', 'item', 'proporcao_cm', 'proporcao_unidade']) ? 'btn-danger' : 'btn-outline-secondary' }}" data-bs-toggle="modal" data-bs-target="#modalNovaPublicacao">
                            <i class="bi bi-plus-circle me-1"></i> Nova Publicação
                        </button>
                        <button type="button" class="btn btn-sm {{ $errors->hasAny(['nota', 'congregacao_id', 'data', 'retirada']) ? 'btn-danger' : 'btn-outline-secondary' }}" data-bs-toggle="modal" data-bs-target="#modalNovoEnvio">
                            <i class="bi bi-plus-circle me-1"></i> Novo Envio
                        </button>
                        <button type="button" class="btn btn-sm {{ $errors->hasAny(['volume', 'envio_id']) ? 'btn-danger' : 'btn-outline-secondary' }}" data-bs-toggle="modal" data-bs-target="#modalNovoVolume">
                            <i class="bi bi-plus-circle me-1"></i> Novo Volume
                        </button>
                    </div>
                @endif
            </div>
        @endif
    </x-crud>

    @if(!$conteudos && !isset($conteudo->show))
        <!-- Modal Novo Envio -->
        <div class="modal fade" id="modalNovoEnvio" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('envio.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="back">
                        <div class="modal-header">
                            <h5 class="modal-title">Cadastrar Novo Envio</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body row">
                            <div class="col-12 mb-3">
                                <select-filter-component
                                    id="modal_congregacao_id" 
                                    label="Congregação:" 
                                    name="congregacao_id"
                                    option="Selecione..." options="{{json_encode($congregacoes)}}"
                                    required="required" 
                                    class="@error('congregacao_id') is-invalid @enderror"
                                    @error('congregacao_id') message="{{$message}}" classmessage="invalid-feedback" @enderror
                                ></select-filter-component>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nota:</label>
                                <input type="text" name="nota" class="form-control @error('nota') is-invalid @enderror" value="{{ old('nota') }}" required>
                                @error('nota') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Data:</label>
                                <input type="date" name="data" class="form-control" value="{{ old('data') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Retirada:</label>
                                <input type="date" name="retirada" class="form-control" value="{{ old('retirada') }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar Envio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Novo Volume -->
        <div class="modal fade" id="modalNovoVolume" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('volume.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="back">
                        <div class="modal-header">
                            <h5 class="modal-title">Cadastrar Novo Volume</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <select-filter-component
                                    id="modal_envio_id" 
                                    label="Envio:" 
                                    name="envio_id"
                                    option="Selecione o Envio..." options="{{json_encode($envios)}}"
                                    required="required" 
                                    class="@error('envio_id') is-invalid @enderror"
                                    @error('envio_id') message="{{$message}}" classmessage="invalid-feedback" @enderror
                                ></select-filter-component>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Volume:</label>
                                <input type="text" name="volume" class="form-control @error('volume') is-invalid @enderror" value="{{ old('volume') }}" required>
                                @error('volume') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar Volume</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Nova Publicação -->
        <div class="modal fade" id="modalNovaPublicacao" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('publicacao.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="redirect_to" value="back">
                        <div class="modal-header">
                            <h5 class="modal-title">Cadastrar Nova Publicação</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Nome:</label>
                                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}" required>
                                @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Código:</label>
                                <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror" value="{{ old('codigo') }}">
                                @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Item:</label>
                                <input type="text" name="item" class="form-control @error('item') is-invalid @enderror" value="{{ old('item') }}">
                                @error('item') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Proporção (cm):</label>
                                <input type="number" name="proporcao_cm" step="0.1" class="form-control @error('proporcao_cm') is-invalid @enderror" value="{{ old('proporcao_cm', 0) }}">
                                @error('proporcao_cm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Proporção (un):</label>
                                <input type="number" name="proporcao_unidade" class="form-control @error('proporcao_unidade') is-invalid @enderror" value="{{ old('proporcao_unidade', 0) }}">
                                @error('proporcao_unidade') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Observação:</label>
                                <input type="text" name="observacao" class="form-control" value="{{ old('observacao') }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar Publicação</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
