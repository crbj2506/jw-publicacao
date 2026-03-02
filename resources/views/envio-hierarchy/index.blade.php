@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- Título e Botão -->
                <div class="card-header fw-bold container-fluid">
                    <div class="row align-items-center">
                        <div class="col">Lista de Envios, Volumes e Conteúdos</div>
                        <div class="col-4 container-fluid d-flex-inline text-end p-0">
                            <button id="btnNovoEnvio" class="btn btn-sm btn-outline-success py-0" type="button">+ Novo Envio</button>
                        </div>
                    </div>
                </div>
                
                <!-- Filtro -->
                <div class="card-header p-1">
                    <form id="formFiltro" method="GET" action="{{ route('envio.index') }}">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Filtros</span>
                            <input 
                                id="search" 
                                name="search" 
                                type="text" 
                                class="form-control" 
                                placeholder="Buscar por Nota, Volume ou Publicação" 
                                value="{{ $envios->searchFiltro ?? '' }}"
                            >
                            <button type="submit" class="btn btn-sm btn-outline-primary" form="formFiltro">Filtrar</button>
                            <a href="{{ route('envio.index') }}" class="btn btn-sm btn-outline-success">Limpar</a>
                        </div>
                    </form>
                </div>

                <!-- Componente Vue -->
                <div class="card-body p-0">
                    <envio-hierarchy-component 
                        :envios-initial="{{ json_encode($envios->items()) }}"
                        :publicacoes="{{ json_encode($publicacoes) }}"
                    />
                </div>

                <!-- Paginação -->
                <x-paginacao :p="$envios" />
            </div>

            <!-- Informações úteis -->
            <div class="alert alert-info mt-4" role="alert">
                <h6 class="alert-heading">💡 Dicas</h6>
                <ul class="mb-0">
                    <li>Use a barra de busca para encontrar rapidamente publicações, volumes ou envios</li>
                    <li>Clique nas linhas do accordion para expandir/recolher detalhes</li>
                    <li>Edite quantidades diretamente nos campos de input ou use os botões ✎ para editar</li>
                    <li>Clique em <strong>+ Novo Envio</strong> para criar um novo envio de publicações</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
