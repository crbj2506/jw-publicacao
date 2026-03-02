@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @php($users = isset($users) ? $users : null)
    @php($user = isset($user) ? $user : null)
    <x-crud
        :l="$users"
        :o="$user"
        r="user"
        tc="Cadastra Usuário"
        te="Altera Usuário"
        ti="Lista de Usuários"
        ts="Mostra Usuário"
    >
        @if($users)
            <x-slot:filtro>
                <div class="card-header p-1">
                    <form id="formFiltro" method="POST" action="{{ route('user.filtrada.post') }}">
                        @csrf
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Filtros</span>
                            <input name="name" type="text" class="form-control" placeholder="Nome" value="{{ $users->nameFiltro ?? '' }}">
                            <input name="email" type="text" class="form-control" placeholder="E-mail" value="{{ $users->emailFiltro ?? '' }}">
                            <button type="submit" class="btn btn-sm btn-outline-primary">Filtrar</button>
                            <a href="{{ route('user.index') }}" class="btn btn-sm btn-outline-success">Limpar</a>
                        </div>
                    </form>
                </div>
            </x-slot>
            <x-slot:lista>
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Nome</th>
                        <th class="text-center" scope="col">E-mail</th>
                        <th class="text-center" scope="col">Permissão</th>
                        <th class="text-center" scope="col">Verificado em</th>
                        <th class="text-center" scope="col">Criado em</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                        <th class="text-center" scope="col">Excluir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $u)
                        <tr>
                            <th class="text-center py-0" scope="row">{{$u->id}}</th>
                            <td class="text-center py-0" scope="row">{{$u->name}}</td>
                            <td class="text-center py-0" scope="row">{{$u->email}}</td>
                            <td class="text-center py-0" scope="row">{{ $u->permissaoMaiorNivel() }}</td>
                            <td class="text-center py-0" scope="row">{{$u->email_verified_at}}</td>
                            <td class="text-center py-0" scope="row">{{$u->created_at}}</td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('user.show',['user' => $u->id])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('user.edit',['user' => $u->id])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                            <td class="text-center py-0" scope="row">
                                @if(is_null($u->email_verified_at))
                                    <!-- Botão que aciona o modal -->
                                    <button type="button" class="btn btn-sm btn-outline-danger py-0" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{$u->id}}">
                                        Excluir
                                    </button>

                                    <!-- Formulário de exclusão (fora do botão) -->
                                    <form id="delete-form-{{$u->id}}" action="{{ route('user.destroy', ['user' => $u->id]) }}" method="post" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </td>
                        </tr>

                        @if(is_null($u->email_verified_at))
                        <!-- Modal de Confirmação -->
                        <div class="modal fade" id="confirmDeleteModal{{$u->id}}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel{{$u->id}}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel{{$u->id}}">Confirmar Exclusão</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                    </div>
                                    <div class="modal-body">
                                        Tem certeza de que deseja excluir o usuário <strong>{{ $u->name }}</strong>? Esta ação não pode ser desfeita.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <!-- Botão que submete o formulário externo usando o atributo 'form' -->
                                        <button type="submit" class="btn btn-danger" form="delete-form-{{$u->id}}">Confirmar Exclusão</button>
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
                        name="name" 
                        id="name" 
                        required="required"
                        value="{{ old('name', $user->name ?? '') }}"
                        {{!isset($user->show) ? '' : 'disabled' }} 
                        class="@error('name') is-invalid @enderror {{old('name') ? 'is-valid' : ''}}"
                        @error('name') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
                <div class="col-12 p-2">
                    <input-group-component
                        label="E-mail:" 
                        type="text"
                        name="email" 
                        id="email" 
                        required="required"
                        value="{{ old('email', $user->email ?? '') }}"
                        {{ isset($user->show) || isset($user->edit) ? 'disabled' : '' }}
                        class="@error('email') is-invalid @enderror {{old('email') ? 'is-valid' : ''}}"
                        @error('email') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
                
                {{-- Campo Congregação --}}
                <div class="col-12 p-2">
                    @if(isset($user->show))
                        {{-- Modo visualização --}}
                        <div class="form-group">
                            <label class="fw-bold">Congregação:</label>
                            <p class="form-control-plaintext">{{ $user->congregacao ? $user->congregacao->nome : 'Não atribuído' }}</p>
                        </div>
                    @else
                        <input type="hidden" name="congregacao_id" value="{{ isset($user) ? $user->congregacao_id : Auth::user()->congregacao_id }}">
                        <div class="form-group">
                            <label class="fw-bold">Congregação:</label>
                            <p class="form-control-plaintext">{{ isset($user) && $user->congregacao ? $user->congregacao->nome : (Auth::user()->congregacao ? Auth::user()->congregacao->nome : 'N/A') }}</p>
                        </div>
                    @endif
                </div>
                
@if(!isset($user->show) && !isset($user->edit))
                <div class="col-12 p-2">
                    <input-group-component
                        label="Senha:" 
                        type="password"
                        name="password" 
                        id="password" 
                        required="required"
                        class="@error('password') is-invalid @enderror"
                        @error('password') message="{{ $message }}" @enderror>
                    </input-group-component>
                </div>
                <div class="col-12 p-2">
                    <input-group-component
                        label="Confirme a senha:" 
                        type="password"
                        name="password_confirmation" 
                        id="password_confirmation" 
                        required="required">
                    </input-group-component>
                </div>
@endif
                <div class="col-12 p-2">
                    <div class="border rounded p-2 m-3">
                        @foreach ($permissoes as $key => $p)
                            @php($checked = '')
                            @foreach($user->permissoes as $indice =>$pu)
                                @if($pu->id == $p->id)
                                    @php($checked = 'checked')
                                @endif
                            @endforeach
                            @php($nivel = $p->permissao === 'Administrador' ? 1 : ($p->permissao === 'Ancião' ? 2 : ($p->permissao === 'Servo' ? 3 : ($p->permissao === 'Publicador' ? 4 : 999))))
                                <div class="form-check-inline form-switch">
                                    <input 
                                        class="form-check-input permissao-checkbox" 
                                        type="checkbox" 
                                        id="permissao_{{ $p->id }}" 
                                        name="permissao_{{ $p->id }}" 
                                        data-permissao="{{ $p->permissao }}"
                                        data-nivel="{{ $nivel }}"
                                        {{ $checked }} 
                                        {{isset($user->show) ? 'disabled' : ''}}>
                                    <label class="form-check-label px-1" for="permissao_{{$p->id}}"> {{ $p->permissao }}</label>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </x-crud>

    {{-- Modal de confirmação para Administrador --}}
    @if(!isset($user->show))
    <div class="modal fade" id="confirmAdminModal" tabindex="-1" aria-labelledby="confirmAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="confirmAdminModalLabel">⚠️ Confirmar Perfil de Administrador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold">Você está prestes a conceder o perfil de <span class="text-danger">Administrador</span> a este usuário.</p>
                    <p>Este perfil possui acesso total ao sistema, incluindo:</p>
                    <ul>
                        <li>Gerenciar todas as congregações</li>
                        <li>Criar e editar qualquer usuário</li>
                        <li>Acesso a logs e auditoria completa</li>
                        <li>Gerenciar configurações do sistema</li>
                    </ul>
                    <p class="text-danger">⚠️ Esta ação requer responsabilidade. Tem certeza?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelAdmin">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmAdmin">Confirmar Administrador</button>
                </div>
            </div>
        </div>
    </div>
    @endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.permissao-checkbox');
    let adminCheckbox = null;
    let pendingAdminCheck = false;

    // Encontrar checkbox do Administrador
    checkboxes.forEach(cb => {
        if (cb.dataset.permissao === 'Administrador') {
            adminCheckbox = cb;
        }
    });

    // Função para ativar perfis inferiores
    function ativarPerfisInferiores(nivelSelecionado, ativar) {
        checkboxes.forEach(cb => {
            const nivelAtual = parseInt(cb.dataset.nivel);
            
            // Se o nível for maior (menor poder), marca/desmarca
            if (nivelAtual > nivelSelecionado) {
                cb.checked = ativar;
            }
        });
    }

    // Listener para cada checkbox
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function(e) {
            const nivel = parseInt(this.dataset.nivel);
            const permissao = this.dataset.permissao;
            const isChecked = this.checked;

            // Se está marcando Administrador, mostrar modal
            if (permissao === 'Administrador' && isChecked) {
                e.preventDefault();
                pendingAdminCheck = true;
                this.checked = false; // Desmarca temporariamente
                
                const modal = new bootstrap.Modal(document.getElementById('confirmAdminModal'));
                modal.show();
                return;
            }

            // Se está marcando, ativar perfis inferiores
            if (isChecked) {
                ativarPerfisInferiores(nivel, true);
            } else {
                // Se está desmarcando, desativar perfis superiores
                checkboxes.forEach(cb => {
                    const nivelCb = parseInt(cb.dataset.nivel);
                    if (nivelCb < nivel) {
                        cb.checked = false;
                    }
                });
            }
        });
    });

    // Botão de confirmação do modal
    const confirmBtn = document.getElementById('confirmAdmin');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (adminCheckbox && pendingAdminCheck) {
                adminCheckbox.checked = true;
                // Ativar todos os perfis inferiores
                ativarPerfisInferiores(1, true);
                pendingAdminCheck = false;
            }
            bootstrap.Modal.getInstance(document.getElementById('confirmAdminModal')).hide();
        });
    }

    // Botão de cancelamento do modal
    const cancelBtn = document.getElementById('cancelAdmin');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            pendingAdminCheck = false;
            if (adminCheckbox) {
                adminCheckbox.checked = false;
            }
            bootstrap.Modal.getInstance(document.getElementById('confirmAdminModal')).hide();
        });
    }

    // Cancelar ao fechar modal pelo X ou clicando fora
    const modal = document.getElementById('confirmAdminModal');
    if (modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            if (pendingAdminCheck && adminCheckbox) {
                adminCheckbox.checked = false;
                pendingAdminCheck = false;
            }
        });
    }
});
</script>
@endpush