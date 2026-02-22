@extends('layouts.app')

@section('content')
    @php($pessoas = isset($pessoas) ? $pessoas : null)
    @php($pessoa = isset($pessoa) ? $pessoa : null)
    <x-crud
        :l="$pessoas"
        :o="$pessoa"
        r="pessoa"
        tc="Cadastra Pessoa"
        te="Altera Pessoa"
        ti="Lista de Pessoas"
        ts="Mostra Pessoa"
    >
        @if($pessoas)
            <x-slot:filtro>
                <div class="card-header p-1">
                    <form id="formFiltro" method="POST" action="{{ route('pessoa.filtrada.post') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Filtros</span>
                            <input id="nome" name="nome" type="text" class="form-control" placeholder="Digite o nome para buscar" value="{{ $pessoas->nomeFiltro ?? '' }}">
                            <button type="submit" class="btn btn-sm btn-outline-primary" form="formFiltro"> Filtrar </button>
                            <a href="{{ route('pessoa.index') }}" class="btn btn-sm btn-outline-success">Limpar</a>
                        </div>
                    </form>
                </div>
            </x-slot>
            <x-slot:lista>
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Nome</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                        <th class="text-center" scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pessoas as $key => $p)
                        <tr @if($p->deleted_at) class="table-danger" @endif>
                            <th class="text-center py-0" scope="row">{{$p->id}}</th>
                            <td class=" py-0" scope="row">{{$p->nome}} @if($p->deleted_at) <span class="badge bg-danger">Deletado</span> @endif</td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('pessoa.show',['pessoa' => $p])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="text-center py-0" scope="row">
                                @if(!$p->deleted_at)
                                    <a href="{{ route('pessoa.edit',['pessoa' => $p])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a>
                                @else
                                    <button class="btn btn-sm btn-outline-warning py-0" disabled>Editar</button>
                                @endif
                            </td>
                            <td class="text-center py-0" scope="row">
                                @if($pessoas->isAdmin)
                                    @if(!$p->deleted_at)
                                        <form id="formExcluir_{{$p->id}}" method="POST" action="{{ route('pessoa.destroy',['pessoa' => $p->id])}}" enctype="multipart/form-data" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-0" form="formExcluir_{{$p->id}}" onclick="return confirm('Tem certeza que deseja excluir esta pessoa? Os dados ficarão salvos no sistema.')"> Excluir </button>
                                        </form>
                                    @else
                                        <form id="formRestaurar_{{$p->id}}" method="POST" action="{{ route('pessoa.restore',['id' => $p->id])}}" enctype="multipart/form-data" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success py-0" form="formRestaurar_{{$p->id}}" onclick="return confirm('Tem certeza que deseja restaurar esta pessoa?')"> Restaurar </button>
                                        </form>
                                    @endif
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
            @if(isset($pessoa))
                <x-slot:actionButton>
                    @if(isset($pessoa->show) && $pessoa->isAdmin)
                        @if(!$pessoa->deleted_at)
                            <form id="formExcluirPessoa" method="POST" action="{{ route('pessoa.destroy',['pessoa' => $pessoa->id])}}" enctype="multipart/form-data" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" form="formExcluirPessoa" onclick="return confirm('Tem certeza que deseja excluir esta pessoa? Os dados ficarão salvos no sistema.')"> Excluir </button>
                            </form>
                        @else
                            <form id="formRestaurarPessoa" method="POST" action="{{ route('pessoa.restore',['id' => $pessoa->id])}}" enctype="multipart/form-data" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success" form="formRestaurarPessoa" onclick="return confirm('Tem certeza que deseja restaurar esta pessoa?')"> Restaurar </button>
                            </form>
                        @endif
                    @endif
                </x-slot:actionButton>
            @endif
            <div class="container-fluid d-flex flex-wrap">
                <div class="col-12 p-2">
                    <input-group-component
                        label="Nome:" 
                        type="text"
                        name="nome" 
                        id="nome" 
                        required="required"
                        value="{{isset($pessoa) ? $pessoa->nome : (old('nome')?old('nome'):'')}}"
                        {{!isset($pessoa->show) ? '' : 'disabled' }} 
                        class="@error('nome') is-invalid @enderror {{old('nome') ? 'is-valid' : ''}}"
                        @error('nome') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
            </div>
        @endif
    </x-crud>
@endsection