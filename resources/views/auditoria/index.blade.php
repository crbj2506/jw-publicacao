@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card m-3">
        <div class="card-header fw-bold">Auditoria de Sistema</div>
        <div class="card-header p-1">
            <form method="GET" action="{{ route('auditoria.index') }}">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Filtros</span>
                    @if(auth()->user()->permissoes->contains('permissao', 'Administrador') || auth()->user()->permissoes->contains('permissao', 'Ancião'))
                        <div class="col-md-3 p-0">
                            <multi-select-users-component
                                :options="{{json_encode($users)}}"
                                :old_ids="{{ json_encode(old('user_ids', request('user_ids'))) }}"
                                name="user_ids[]"
                                placeholder="Selecionar Usuários..."
                                search-placeholder="Buscar usuário..."
                            ></multi-select-users-component>
                        </div>
                    @endif
                    @if(auth()->user()->permissoes->contains('permissao', 'Administrador'))
                        <div class="col-md-3 p-0">
                            <multi-select-congregations-component
                                :options="{{json_encode($congregacoes)}}"
                                :old_ids="{{ json_encode(old('congregacao_ids', request('congregacao_ids'))) }}"
                                name="congregacao_ids[]"
                                placeholder="Selecionar Congregações..."
                                search-placeholder="Buscar congregação..."
                            ></multi-select-congregations-component>
                        </div>
                    @endif
                    <div class="col-md-2 p-0">
                        <multi-select-eventos-component
                            :options="{{json_encode($eventos)}}"
                            :old_ids="{{ json_encode(old('eventos', request('eventos'))) }}"
                            name="eventos[]"
                            placeholder="Selecionar Eventos..."
                        ></multi-select-eventos-component>
                    </div>
                    <input type="text" name="recurso" class="form-control" placeholder="Recurso (ex: Publicacao)" value="{{ request('recurso') }}">
                    <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                    <a href="{{ route('auditoria.index') }}" class="btn btn-outline-success">Limpar</a>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">Data</th>
                        <th class="text-center">Usuário</th>
                        <th class="text-center">Congregação</th>
                        <th class="text-center">Evento</th>
                        <th class="text-center">Recurso</th>
                        <th class="text-center">ID</th>
                        <th class="text-center">Dados</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($auditorias as $auditoria)
                        <tr>
                            <td class="text-center small">{{ $auditoria->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="text-center">{{ $auditoria->user->name ?? 'Sistema' }}</td>
                            <td class="text-center">{{ $auditoria->user && $auditoria->user->congregacao ? $auditoria->user->congregacao->nome : '-' }}</td>
                            <td class="text-center">
                                <span class="badge {{ in_array($auditoria->evento, ['login', 'logout']) ? 'bg-info' : ($auditoria->evento == 'criado' ? 'bg-success' : ($auditoria->evento == 'atualizado' ? 'bg-warning' : 'bg-danger')) }}">
                                    {{ strtoupper($auditoria->evento) }}
                                </span>
                            </td>
                            <td class="text-center small">{{ str_replace('App\\Models\\', '', $auditoria->auditable_type) }}</td>
                            <td class="text-center">{{ $auditoria->auditable_id }}</td>
                            <td class="text-center">
                                @if($auditoria->valores_antigos || $auditoria->valores_novos)
                                    <button type="button" class="btn btn-sm btn-outline-secondary py-0" data-bs-toggle="modal" data-bs-target="#modalAuditoria{{ $auditoria->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    
                                    <div class="modal fade" id="modalAuditoria{{ $auditoria->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detalhes da Alteração #{{ $auditoria->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="fw-bold">Antes:</label>
                                                            <pre class="bg-light p-2 small">{{ json_encode($auditoria->valores_antigos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="fw-bold">Depois:</label>
                                                            <pre class="bg-light p-2 small">{{ json_encode($auditoria->valores_novos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-paginacao :p="$auditorias"></x-paginacao>
    </div>
</div>
@endsection
