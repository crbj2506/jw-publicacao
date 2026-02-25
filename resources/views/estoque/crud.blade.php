@extends('layouts.app')

@section('content')
    @php($estoques = isset($estoques) ? $estoques : null)
    @php($estoque = isset($estoque) ? $estoque : null)
    <x-crud
        :l="$estoques"
        :o="$estoque"
        r="estoque"
        tc="Cadastra Estoque"
        te="Altera Estoque"
        ti="Lista do Estoque"
        ts="Mostra Estoque"
    >
        @if($estoques)
            <x-slot:filtro>  
        <div class="card-header p-1">    
            <form  id="formFiltro" method="POST" action="{{ route('estoque.filtrado.post')}}" enctype="multipart/form-data">
                @csrf
                <div class="input-group input-group-sm">
                    <span class="input-group-text" id="selectLabelCongregacao">Filtros</span>
                    
                    <input id="local" name="local" type="text" class="form-control" placeholder="digite parte do nome do Local" value="{{ $estoques->localFiltro ? $estoques->localFiltro : ''}}">
                    <input id="codigo" name="codigo" type="text" class="form-control" placeholder="digite o Código" value="{{ $estoques->codigoFiltro ? $estoques->codigoFiltro : ''}}">
                    <input id="publicacao" name="publicacao" type="text" class="form-control" placeholder="digite parte do nome da Publicação" value="{{ $estoques->publicacaoFiltro ? $estoques->publicacaoFiltro : ''}}">
                    <button type="submit" class="btn btn-sm btn-outline-primary" form="formFiltro"> Filtrar </button>
                    <a href="{{ route('estoque.index')}}" class="btn btn-sm btn-outline-success">Limpar</a>
                </div>
            </form> 
        </div>               
            </x-slot>
            <x-slot:lista>
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Local</th>
                        <th class="text-center" scope="col">Código</th>
                        <th class="text-center" scope="col">Publicação</th>
                        <th class="text-center" scope="col">Observação</th>
                        <th class="text-center" scope="col">Proporção</th>
                        <th class="text-center" scope="col">Quantidade</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                        <th class="text-center" scope="col">Excluir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estoques as $key => $e) 
                        <tr>
                            <th class="py-0 text-center" scope="row">{{$e->id}}</th>
                            <td class="py-0" scope="row">{{$e->local->sigla}} - {{$e->local->nome}}</td>
                            <td class="py-0 text-end" scope="row">{{$e->publicacao->codigo}}</td>
                            <td class="py-0" scope="row">{{$e->publicacao->nome}}</td>
                            <td class="py-0 text-center" scope="row">{{$e->publicacao->observacao}}</td>
                            <td class="py-0 text-center" scope="row">{{$e->publicacao->proporcao()}}</td>
                            <td class="py-0 text-center" scope="row">{{$e->quantidade}}</td>
                            <td class="py-0 text-center" scope="row"><a href="{{ route('estoque.show',['estoque' => $e->id])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="py-0 text-center" scope="row"><a href="{{ route('estoque.edit',['estoque' => $e->id])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                            <td class="py-0 text-center" scope="row">
                                @if($e->quantidade == 0)
                                    <form  id="formExcluir_{{$e->id}}" method="POST" action="{{ route('estoque.destroy',['estoque' => $e->id])}}" enctype="multipart/form-data">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0" form="formExcluir_{{$e->id}}"> Excluir </button>
                                    </form>
                                @endif
                            </td>
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
                        class="@error('local_id') is-invalid @enderror {{old('local_id') ? 'is-valid' : ''}}"
                        @error('local_id') classinputgroup="has-validation" @enderror
                        classmessage="valid-feedback @error('local_id') invalid-feedback @enderror"
                        id="local_id"
                        label="Local:"
                        @error('local_id') message="{{$message}}" @enderror
                        name="local_id"
                        option="Selecione o Local..."
                        options="{{json_encode($locais)}}"
                        old_id="{{ isset($estoque) ? $estoque->local_id : @old('local_id') }}"
                        required="required"
                        value="{{ isset($estoque) ? $estoque->local->nome : @old('local_id') }}"
                        {{isset($estoque->show) ? 'disabled' : ''}}
                    ></select-filter-component>
                </div>
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
                        old_id="{{ isset($estoque) ? $estoque->publicacao_id : @old('publicacao_id') }}"
                        required="required"
                        value="{{ isset($estoque) ? $estoque->publicacao->nome : @old('publicacao_id') }}"
                        {{isset($estoque->show) ? 'disabled' : ''}}
                    ></select-filter-component>
                </div>
                <div class="col-12 col-sm-6 col-md-4 p-2">
                    <input-group-component
                        label="Quantidade:" 
                        type="number"
                        name="quantidade" 
                        id="quantidade" 
                        required="required"
                        value="{{isset($estoque) ? $estoque->quantidade : (old('quantidade')?old('quantidade'):'')}}"
                        {{isset($estoque->show) ? 'disabled' : ''}} 
                        class="@error('quantidade') is-invalid @enderror {{old('quantidade') ? 'is-valid' : ''}}"
                        @error('quantidade') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>

                @if(isset($estoque))
                    @if($estoque->publicacao->proporcao() > 0)
                        <div class="col-12 col-sm-6 col-md-4 p-2">
                            <input-group-component
                                label="Proporção:" 
                                type="text"
                                name="proporcao" 
                                id="proporcao_display" 
                                disabled="disabled"
                                value="{{$estoque->publicacao->proporcao()}}"
                            >
                            </input-group-component>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 p-2">
                            <input-group-quantidade-estoque-component
                                proporcao="{{$estoque->publicacao->proporcao()}}"
                                quantidade="{{$estoque->quantidade}}"
                                {{isset($estoque->edit) ? 'disabled' : ''}}
                            >
                                @if(isset($estoque->edit))
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalAtualizarProporcao" title="Atualizar Proporção">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                @endif
                            </input-group-quantidade-estoque-component>
                        </div>
                    @else
                        <div class="col-12 col-sm-12 col-md-8 p-2">
                            <div class="input-group">
                                <span class="input-group-text">Proporção:</span>
                                <input type="text" class="form-control text-muted small" disabled value="Esta publicação não possui proporção cadastrada para auxílio na contagem.">
                                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalProporcao">
                                    <i class="bi bi-calculator me-1"></i> Definir Proporção
                                </button>
                            </div>
                        </div>
                    @endif
                @endif
                @if(!isset($estoque->show))
                    <div class="col-12 p-2 d-flex flex-wrap gap-2 align-items-center justify-content-end">
                        <span class="text-muted small me-2">Não encontrou o que precisava?</span>
                        <button type="button" class="btn btn-sm {{ $errors->hasAny(['congregacao_id', 'nome', 'sigla']) ? 'btn-danger' : 'btn-outline-secondary' }}" data-bs-toggle="modal" data-bs-target="#modalNovoLocal">
                            <i class="bi bi-plus-circle me-1"></i> Novo Local
                        </button>
                        <button type="button" class="btn btn-sm {{ $errors->hasAny(['nome', 'codigo', 'proporcao_cm', 'proporcao_unidade']) ? 'btn-danger' : 'btn-outline-secondary' }}" data-bs-toggle="modal" data-bs-target="#modalNovaPublicacao">
                            <i class="bi bi-plus-circle me-1"></i> Nova Publicação
                        </button>
                    </div>
                @endif
            </div>
        @endif
    </x-crud>

    @if(!isset($estoque) || !isset($estoque->show))
        <script>
            // Função para limpar todos os campos de um modal
            function limparModalForm(modalId) {
                const form = document.querySelector(modalId + ' form');
                if (form) {
                    // Limpa inputs de texto
                    form.querySelectorAll('input[type="text"], input[type="date"], input[type="number"]').forEach(input => {
                        input.value = '';
                    });
                    
                    // Limpa selects (para componentes Vue e selects normais)
                    form.querySelectorAll('select, [role="combobox"]').forEach(select => {
                        if (select.tagName === 'SELECT') {
                            select.value = '';
                        } else {
                            // Para componentes Vue, reseta o valor
                            select.value = '';
                        }
                    });
                    
                    // Limpa textareas
                    form.querySelectorAll('textarea').forEach(textarea => {
                        textarea.value = '';
                    });
                    
                    // Remove feedback/error classes
                    form.querySelectorAll('.is-invalid, .is-valid').forEach(el => {
                        el.classList.remove('is-invalid', 'is-valid');
                    });
                    
                    // Reseta a cor do botão que abre o modal
                    const botaoAbreModal = document.querySelector(`button[data-bs-target="${modalId}"]`);
                    if (botaoAbreModal) {
                        botaoAbreModal.classList.remove('btn-danger');
                        botaoAbreModal.classList.add('btn-outline-secondary');
                    }
                }
            }
        </script>

        <!-- Modal Novo Local -->
        <div class="modal fade" id="modalNovoLocal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('local.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="back">
                        <div class="modal-header">
                            <h5 class="modal-title">Cadastrar Novo Local</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <select-filter-component
                                    id="modal_congregacao_id" 
                                    label="Congregação:" 
                                    name="congregacao_id"
                                    option="Selecione..." 
                                    options="{{json_encode($congregacoes)}}"
                                    old_id="{{ @old('congregacao_id') }}"
                                    value="{{ @old('congregacao_id') ? collect($congregacoes)->firstWhere('value', @old('congregacao_id'))->text ?? '' : '' }}"
                                    required="required" 
                                    class="@error('congregacao_id') is-invalid @enderror"
                                    @error('congregacao_id') message="{{$message}}" classmessage="invalid-feedback" @enderror
                                ></select-filter-component>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nome:</label>
                                <input type="text" name="nome_local" class="form-control @error('nome_local') is-invalid @enderror" value="{{ old('nome_local') }}" required>
                                @error('nome_local') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sigla:</label>
                                <input type="text" name="sigla" class="form-control @error('sigla') is-invalid @enderror" value="{{ old('sigla') }}" required>
                                @error('sigla') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" onclick="limparModalForm('#modalNovoLocal')">Limpar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar Local</button>
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
                            <button type="button" class="btn btn-outline-secondary" onclick="limparModalForm('#modalNovaPublicacao')">Limpar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar Publicação</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if ($errors->any())
            @php($modalId = null)
            @if($errors->hasAny(['congregacao_id', 'nome_local', 'sigla']))
                @php($modalId = 'modalNovoLocal')
            @elseif($errors->hasAny(['nome', 'codigo', 'proporcao_cm', 'proporcao_unidade']))
                @php($modalId = 'modalNovaPublicacao')
            @endif

            @if($modalId)
                @push('scripts')
                    <script>
                        // Aguarda o Bootstrap ficar disponível (carregado pelo Vite)
                        function tentarAbrirModal() {
                            const modalElement = document.getElementById('{{ $modalId }}');
                            
                            if (modalElement && typeof bootstrap !== 'undefined') {
                                try {
                                    const myModal = new bootstrap.Modal(modalElement);
                                    myModal.show();
                                    return true;
                                } catch(e) {
                                    console.error('Erro ao abrir modal:', e);
                                    return false;
                                }
                            }
                            return false;
                        }
                        
                        // Tenta a cada 50ms por até 10 segundos
                        let tentativas = 0;
                        const maxTentativas = 200;
                        
                        const intervalo = setInterval(() => {
                            tentativas++;
                            
                            if (tentarAbrirModal() || tentativas >= maxTentativas) {
                                clearInterval(intervalo);
                            }
                        }, 50);
                    </script>
                @endpush
            @endif
        @endif
    @endif

    @if(isset($estoque))
        <!-- Modal para atualizar proporção da publicação -->
        <div class="modal fade" id="modalProporcao" tabindex="-1" aria-labelledby="modalProporcaoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formModalProporcao" action="{{ route('publicacao.update', ['publicacao' => $estoque->publicacao->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="redirect_to" value="back">
                        {{-- Campos ocultos necessários para satisfazer a validação da Publicação --}}
                        <input type="hidden" name="nome" value="{{ $estoque->publicacao->nome }}">
                        @if($estoque->publicacao->codigo)
                            <input type="hidden" name="codigo" value="{{ $estoque->publicacao->codigo }}">
                        @endif
                        @if($estoque->publicacao->item)
                            <input type="hidden" name="item" value="{{ $estoque->publicacao->item }}">
                        @endif
                        
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalProporcaoLabel">Definir Proporção: {{ $estoque->publicacao->nome }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if ($errors->any())
                                <div class="alert alert-danger small">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Centímetros (cm)</label>
                                <input type="number" name="proporcao_cm" step="0.1" min="0.1" class="form-control" value="{{ old('proporcao_cm', $estoque->publicacao->proporcao_cm) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Unidades</label>
                                <input type="number" name="proporcao_unidade" min="1" class="form-control" value="{{ old('proporcao_unidade', $estoque->publicacao->proporcao_unidade) }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Cancelar</button>
                            <button type="submit" class="btn btn-primary" form="formModalProporcao"><i class="bi bi-check-circle me-1"></i> Salvar e Ativar Calculador</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(isset($estoque->edit) && $estoque->publicacao->proporcao() > 0)
            <!-- Novo Modal para ATUALIZAR proporção existente -->
            <div class="modal fade" id="modalAtualizarProporcao" tabindex="-1" aria-labelledby="modalAtualizarProporcaoLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="formModalAtualizarProporcao" action="{{ route('publicacao.update', ['publicacao' => $estoque->publicacao->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="redirect_to" value="back">
                            <input type="hidden" name="nome" value="{{ $estoque->publicacao->nome }}">
                            @if($estoque->publicacao->codigo)
                                <input type="hidden" name="codigo" value="{{ $estoque->publicacao->codigo }}">
                            @endif
                            
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalAtualizarProporcaoLabel">Atualizar Proporção: {{ $estoque->publicacao->nome }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info small">
                                    Ajuste os valores abaixo para recalcular a proporção desta publicação.
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Centímetros (cm)</label>
                                    <input type="number" name="proporcao_cm" step="0.1" min="0.1" class="form-control" value="{{ old('proporcao_cm', $estoque->publicacao->proporcao_cm) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Unidades</label>
                                    <input type="number" name="proporcao_unidade" min="1" class="form-control" value="{{ old('proporcao_unidade', $estoque->publicacao->proporcao_unidade) }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Cancelar</button>
                                <button type="submit" class="btn btn-primary" form="formModalAtualizarProporcao"><i class="bi bi-arrow-repeat me-1"></i> Atualizar Proporção</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            @php($modalId = $estoque->publicacao->proporcao() <= 0 ? 'modalProporcao' : 'modalAtualizarProporcao')
            <script>
                window.onload = function() {
                    var myModal = new bootstrap.Modal(document.getElementById('{{ $modalId }}'));
                    myModal.show();
                }
            </script>
        @endif
    @endif
@endsection