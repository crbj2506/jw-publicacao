@extends('layouts.app')

@section('content')
    <div class="container-fluid d-flex justify-content-center">
        <div class="card m-3 w-100">
            <div class="card-header fw-bold container-fluid">
                <div class="row align-items-center">
                    <div class="col">Lista do Estoque - Modo rápido</div>
                    <div class="col-8 container-fluid d-flex-inline text-end p-0 gap-2 d-flex justify-content-end">
                        <a href="{{ route('estoque.create') }}" class="btn btn-sm btn-outline-secondary" title="Cadastrar novo estoque">
                            <i class="bi bi-plus-circle me-1"></i> Novo Estoque
                        </a>
                        <a href="{{ route('estoque.old') }}" class="btn btn-sm btn-outline-secondary py-0">Modo antigo (OLD)</a>
                    </div>
                </div>
            </div>

            <div class="card-header p-1">
                <form method="GET" action="{{ route('estoque.rapido') }}" class="input-group input-group-sm">
                    <span class="input-group-text">Filtros</span>
                    <select name="local_id" class="form-select">
                        <option value="">Todos os locais</option>
                        @foreach ($locais as $l)
                            <option value="{{ $l->id }}" {{ (string) $localId === (string) $l->id ? 'selected' : '' }}>
                                {{ $l->sigla }} - {{ $l->nome }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-primary">Filtrar</button>
                    <a href="{{ route('estoque.rapido') }}" class="btn btn-sm btn-outline-success">Limpar</a>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Local</th>
                                <th class="text-end">Codigo</th>
                                <th>Publicacao</th>
                                <th class="text-center">Proporcao (cm/un)</th>
                                <th class="text-center">Cm atual</th>
                                <th class="text-center">Cm medido</th>
                                <th class="text-center">Qtd atual</th>
                                <th class="text-center">Qtd final</th>
                                <th class="text-center">Salvar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($estoques as $e)
                                @php($proporcao = $e->publicacao->proporcao())
                                @php($cmAtual = $proporcao > 0 ? $e->quantidade * $proporcao : null)
                                <tr>
                                        <td>
                                            <form id="form-{{ $e->id }}" method="POST" action="{{ route('estoque.update', ['estoque' => $e->id]) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="local_id" value="{{ $e->local_id }}">
                                                <input type="hidden" name="publicacao_id" value="{{ $e->publicacao_id }}">
                                                <input type="hidden" name="redirect_to" value="rapido">
                                                <input type="hidden" name="local_id_filter" value="{{ $localId }}">
                                                <input type="hidden" name="page_filter" value="{{ $estoques->currentPage() }}">
                                                <input type="hidden" name="perpage_filter" value="{{ $estoques->perPage() }}">
                                            </form>
                                            {{ $e->local->sigla }}
                                        </td>
                                        <td class="text-end">{{ $e->publicacao->codigo }}</td>
                                        <td>{{ $e->publicacao->nome }}</td>
                                        <td class="text-center">
                                            @if ($proporcao > 0)
                                                {{ number_format($proporcao, 2, ',', '.') }}
                                            @else
                                                <span class="text-muted">Sem proporcao</span>
                                            @endif
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary py-0 ms-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalAtualizarProporcaoRapido"
                                                data-publicacao-id="{{ $e->publicacao->id }}"
                                                data-publicacao-nome="{{ $e->publicacao->nome }}"
                                                data-publicacao-codigo="{{ $e->publicacao->codigo }}"
                                                data-publicacao-item="{{ $e->publicacao->item ?? '' }}"
                                                data-proporcao-cm="{{ $e->publicacao->proporcao_cm ?? '' }}"
                                                data-proporcao-unidade="{{ $e->publicacao->proporcao_unidade ?? '' }}"
                                                title="Atualizar Proporção"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            @if (!is_null($cmAtual))
                                                {{ number_format($cmAtual, 2, ',', '.') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <input
                                                type="number"
                                                step="0.1"
                                                class="form-control form-control-sm cm-input"
                                                data-proporcao="{{ $proporcao }}"
                                                {{ $proporcao > 0 ? '' : 'disabled' }}
                                            >
                                        </td>
                                        <td class="text-center">{{ $e->quantidade }}</td>
                                        <td class="text-center">
                                            <input
                                                type="number"
                                                name="quantidade"
                                                min="0"
                                                step="1"
                                                class="form-control form-control-sm qtd-input"
                                                value="{{ $e->quantidade }}"
                                                data-quantidade-atual="{{ $e->quantidade }}"
                                                form="form-{{ $e->id }}"
                                                required
                                            >
                                        </td>
                                        <td class="text-center">
                                            <button type="submit" class="btn btn-sm btn-outline-success" form="form-{{ $e->id }}">Salvar</button>
                                            <i class="bi bi-check-circle-fill text-success ms-1 saved-indicator d-none" data-estoque-id="{{ $e->id }}" title="Salvo"></i>
                                        </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-3">Nenhum item encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <x-paginacao :p="$estoques"></x-paginacao>
        </div>
    </div>


    <div class="modal fade" id="modalAtualizarProporcaoRapido" tabindex="-1" aria-labelledby="modalAtualizarProporcaoRapidoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formModalAtualizarProporcaoRapido" method="POST" action="{{ route('publicacao.update', ['publicacao' => 0]) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirect_to" value="back">
                    <input type="hidden" name="nome" id="modalPublicacaoNomeInput" value="">
                    <input type="hidden" name="codigo" id="modalPublicacaoCodigoInput" value="" disabled>
                    <input type="hidden" name="item" id="modalPublicacaoItemInput" value="" disabled>

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAtualizarProporcaoRapidoLabel">Atualizar Proporção</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info small">
                            Ajuste os valores abaixo para recalcular a proporção desta publicação.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Centímetros (cm)</label>
                            <input type="number" name="proporcao_cm" min="0" step="0.1" autocomplete="off" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unidades</label>
                            <input type="number" name="proporcao_unidade" min="1" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Cancelar</button>
                        <button type="submit" class="btn btn-primary" form="formModalAtualizarProporcaoRapido"><i class="bi bi-arrow-repeat me-1"></i> Atualizar Proporção</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const localKey = '{{ (string) ($localId ?? "all") }}';
            const storageKey = `estoqueRapidoSalvos:${localKey}`;
            const EXPIRY_TIME_MS = 20 * 60 * 1000; // 20 minutos
            const url = new URL(window.location.href);
            const savedFromUrl = url.searchParams.get('saved_id');

            const getSavedMap = () => {
                try {
                    const raw = sessionStorage.getItem(storageKey);
                    const parsed = raw ? JSON.parse(raw) : {};
                    const now = Date.now();
                    const filtered = {};
                    
                    for (const [id, timestamp] of Object.entries(parsed)) {
                        if (typeof timestamp === 'number' && (now - timestamp) < EXPIRY_TIME_MS) {
                            filtered[id] = timestamp;
                        }
                    }
                    
                    return filtered;
                } catch (e) {
                    return {};
                }
            };

            const persistSavedMap = (savedMap) => {
                sessionStorage.setItem(storageKey, JSON.stringify(savedMap));
            };

            const paintSavedIndicators = (savedMap) => {
                document.querySelectorAll('.saved-indicator').forEach((icon) => {
                    const estoqueId = String(icon.dataset.estoqueId || '');
                    icon.classList.toggle('d-none', !(estoqueId in savedMap));
                });
            };

            const savedMap = getSavedMap();
            if (savedFromUrl) {
                savedMap[String(savedFromUrl)] = Date.now();
                persistSavedMap(savedMap);
                url.searchParams.delete('saved_id');
                window.history.replaceState({}, '', url.toString());
            }
            paintSavedIndicators(savedMap);

            document.addEventListener('input', (event) => {
                const input = event.target;
                if (!input.classList.contains('cm-input')) return;

                const proporcao = parseFloat(input.dataset.proporcao || '0');
                const cm = parseFloat(input.value || '0');
                const row = input.closest('tr');
                if (!row) return;

                const qtdInput = row.querySelector('.qtd-input');
                if (!qtdInput) return;
                const quantidadeAtual = qtdInput.dataset.quantidadeAtual;

                if (input.value === '') {
                    qtdInput.value = quantidadeAtual ?? '';
                    return;
                }

                if (!proporcao || !cm) {
                    return;
                }

                const qtd = Math.round(cm / proporcao);
                if (!Number.isFinite(qtd)) {
                    return;
                }

                qtdInput.value = qtd;
            });

            const modalEl = document.getElementById('modalAtualizarProporcaoRapido');
            if (!modalEl) return;

            modalEl.addEventListener('show.bs.modal', (event) => {
                const trigger = event.relatedTarget;
                if (!trigger) return;

                const publicacaoId = trigger.getAttribute('data-publicacao-id') || '';
                const publicacaoNome = trigger.getAttribute('data-publicacao-nome') || '';
                const publicacaoCodigo = trigger.getAttribute('data-publicacao-codigo') || '';
                const publicacaoItem = trigger.getAttribute('data-publicacao-item') || '';
                const proporcaoCm = trigger.getAttribute('data-proporcao-cm') || '';
                const proporcaoUnidade = trigger.getAttribute('data-proporcao-unidade') || '';

                const form = document.getElementById('formModalAtualizarProporcaoRapido');
                const titulo = document.getElementById('modalAtualizarProporcaoRapidoLabel');
                const nomeInput = document.getElementById('modalPublicacaoNomeInput');
                const codigoInput = document.getElementById('modalPublicacaoCodigoInput');
                const itemInput = document.getElementById('modalPublicacaoItemInput');
                const cmInput = form?.querySelector('input[name="proporcao_cm"]');
                const unidadeInput = form?.querySelector('input[name="proporcao_unidade"]');

                if (!form || !titulo || !nomeInput || !codigoInput || !itemInput || !cmInput || !unidadeInput || !publicacaoId) return;

                form.action = `{{ url('publicacao') }}/${publicacaoId}`;
                titulo.textContent = `Atualizar Proporção: ${publicacaoNome}`;

                nomeInput.value = publicacaoNome;

                codigoInput.value = publicacaoCodigo;
                codigoInput.disabled = !publicacaoCodigo;

                itemInput.value = publicacaoItem;
                itemInput.disabled = !publicacaoItem;

                cmInput.value = proporcaoCm;
                unidadeInput.value = proporcaoUnidade;
            });
        });
    </script>
@endpush
