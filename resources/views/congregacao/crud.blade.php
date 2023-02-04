@extends('layouts.app')

@section('content')
    @php($congregacoes = isset($congregacoes) ? $congregacoes : null)
    @php($congregacao = isset($congregacao) ? $congregacao : null)
    <x-crud
        :l="$congregacoes"
        :o="$congregacao"
        r="congregacao"
        tc="Cadastra     Congregação"
        te="Altera Congregação"
        ti="Lista de Congregações"
        ts="Mostra Congregação"
    >
        @if($congregacoes)
            <x-slot:filtro>
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
                        @foreach ($congregacoes as $key => $c)
                            <tr>
                                <th class="text-center py-0" scope="row">{{$c->id}}</th>
                                <td class="py-0" scope="row">{{$c->nome}}</td>
                                <td class="text-center py-0" scope="row"><a href="{{ route('congregacao.show', ['congregacao' => $c]) }}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                                <td class="text-center py-0" scope="row"><a href="{{ route('congregacao.edit', ['congregacao' => $c]) }}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
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
                        value="{{isset($congregacao) ? $congregacao->nome : (old('nome')?old('nome'):'')}}"
                        {{isset($congregacao->show) ? 'disabled' : ''}} 
                        class="@error('nome') is-invalid @enderror {{old('nome') ? 'is-valid' : ''}}"
                        @error('nome') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
            </div>
        @endif
    </x-crud>
@endsection