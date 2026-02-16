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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pessoas as $key => $p)
                        <tr>
                            <th class="text-center py-0" scope="row">{{$p->id}}</th>
                            <td class=" py-0" scope="row">{{$p->nome}}</td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('pessoa.show',['pessoa' => $p])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('pessoa.edit',['pessoa' => $p])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
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