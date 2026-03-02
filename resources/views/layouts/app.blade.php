<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap Icons -->

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand d-none d-sm-block" href="{{ url('/') }}">
                    <img class="" src="{{ URL::to('/') }}/img/logo_controle_publicacoes.png">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <div class="container-fluid  d-flex flex-wrap-reverse justify-content-end">
                        <div class="">
                            <ul class="navbar-nav me-auto">
                                @guest
                                    @if (Route::has('login'))
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                        </li>
                                    @endif

                                    @if (Route::has('register'))
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                        </li>
                                    @endif
                                @endguest
                            </ul>
                        </div>
                        
                        {{-- Menu de Servo: Servo, Ancião e Admin (permissões cumulativas) --}}
                        <div class="">
                            <ul class="navbar-nav me-auto">
                                @auth
                                    @if(Auth::user()->ehAdmin() || Auth::user()->ehAnciao() || Auth::user()->ehServidor())
                                        <li class="nav-item dropdown">
                                            <a id="navbarDropdownServo" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            {{ __('Menu de Servo') }}
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownServo">
                                                <a class="dropdown-item" href="{{ route('pedido.index') }}">
                                                    {{ __('Pedidos dos Irmãos') }}
                                                </a>
                                                <a class="dropdown-item" href="{{ route('pessoa.index') }}">
                                                    {{ __('Irmãos') }}
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('envio.index') }}">
                                                    📊 {{ __('Envios') }}
                                                </a>
                                                <a class="dropdown-item" href="{{ route('estoque.index') }}">
                                                    {{ __('Estoque') }}
                                                </a>
                                                <a class="dropdown-item" href="{{ route('inventario.index') }}">
                                                    {{ __('Inventário') }}
                                                </a>
                                                <a class="dropdown-item" href="{{ route('local.index') }}">
                                                    {{ __('Locais') }}
                                                </a>
                                                <a class="dropdown-item" href="{{ route('publicacao.index') }}">
                                                    {{ __('Publicações') }}
                                                </a>
                                            </div>
                                        </li>
                                    @elseif(Auth::user()->ehPublicador())
                                        {{-- Publicador vê apenas Pedidos --}}
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('pedido.index') }}">{{ __('Pedidos') }}</a>
                                        </li>
                                    @endif
                                @endauth
                            </ul>
                        </div>
                        
                        {{-- Menu de Ancião: Ancião e Admin --}}
                        <div class="">
                            <ul class="navbar-nav me-auto">
                                @auth
                                    @if(Auth::user()->ehAdmin() || Auth::user()->ehAnciao())
                                        <li class="nav-item dropdown">
                                            <a id="navbarDropdownAnciao" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            {{ __('Menu de Ancião') }}
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownAnciao">
                                                <a class="dropdown-item" href="{{ route('user.index') }}">
                                                    {{ __('Usuários do Sistema') }}
                                                </a>
                                            </div>
                                        </li>
                                    @endif
                                @endauth
                            </ul>
                        </div>
                        
                        {{-- Menu de Administrador: apenas Admin --}}
                        <div class="">
                            <ul class="navbar-nav me-auto">
                                @auth
                                    @if(Auth::user()->ehAdmin())
                                        <li class="nav-item dropdown">
                                            <a id="navbarDropdownAdmin" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            {{ __('Menu de Administrador') }}
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownAdmin">
                                                <a class="dropdown-item" href="{{ route('congregacao.index') }}">
                                                    {{ __('Área Administrativa: Congregações') }}
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('permissao.index') }}">
                                                    {{ __('Permissões Possíveis') }}
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('log.index') }}">
                                                    {{ __('Logs de Acesso') }}
                                                </a>
                                            </div>
                                        </li>
                                    @endif
                                @endauth
                            </ul>
                        </div>
                        
                        {{-- Menu do Usuário: Perfil e Logout --}}
                        <div class="">
                            <ul class="navbar-nav me-auto">
                                @auth
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            <div class="d-flex flex-column">
                                                <span>{{ Auth::user()->name }}</span>
                                                @if(Auth::user()->ehAdmin() && isset($congregacaoAtiva))
                                                    <small class="text-muted">
                                                        {{ $congregacaoAtiva->nome }}
                                                        @if(isset($congregacaoAtivaId, $congregacaoPadraoId) && (int)$congregacaoAtivaId !== (int)$congregacaoPadraoId)
                                                            <span class="badge bg-warning text-dark ms-1">Ativa</span>
                                                        @endif
                                                    </small>
                                                @endif
                                            </div>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                            @if(Auth::user()->ehAdmin() && isset($congregacoesAdmin) && $congregacoesAdmin->count())
                                                <div class="px-3 py-2">
                                                    <label class="form-label small mb-1">Congregação ativa</label>
                                                    <form method="POST" action="{{ route('congregacao.ativa.set') }}">
                                                        @csrf
                                                        <select class="form-select form-select-sm mb-2" name="congregacao_ativa_id">
                                                            @foreach ($congregacoesAdmin as $congregacao)
                                                                <option value="{{ $congregacao->id }}" {{ isset($congregacaoAtivaId) && (int)$congregacaoAtivaId === (int)$congregacao->id ? 'selected' : '' }}>
                                                                    {{ $congregacao->nome }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-outline-primary w-100">Aplicar</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('congregacao.ativa.reset') }}" class="mt-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary w-100">Usar congregação padrão</button>
                                                    </form>
                                                </div>
                                                <div class="dropdown-divider"></div>
                                            @endif
                                            <a class="dropdown-item" href="{{ route('auditoria.index') }}">
                                                {{ __('Minha Auditoria') }}
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
        
        <footer class="footer">
            @php
                $loggedUser = Auth::user();
                $congregacaoNome = $loggedUser ? (optional($loggedUser->congregacao)->nome ?? 'Sem congregação') : 'Visitante';
                $perfilNome = $loggedUser && method_exists($loggedUser, 'permissaoMaiorNivel') ? $loggedUser->permissaoMaiorNivel() : 'Sem permissão';
                $lastCommitDate = trim((string) @shell_exec('git log -1 --format="%cd" --date=format:"%d/%m/%Y %H:%M" 2>NUL'));
                $lastCommitDate = $lastCommitDate !== '' ? $lastCommitDate : '-';
            @endphp
            <div class="fixed-bottom bg-white border-top">
                <div class="container-fluid py-1 px-3 d-flex justify-content-between align-items-center small text-muted">
                    <span>{{ $congregacaoNome }} | {{ $perfilNome }}</span>
                    <span>Desenvolvido por Custódio Junior.</span>
                    <span>Último commit: {{ $lastCommitDate }}</span>
                </div>
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
