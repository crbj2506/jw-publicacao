@extends('layouts.app')

@section('content')
    @php($permissoes = isset($permissoes) ? $permissoes : null)
    @php($permissao = isset($permissao) ? $permissao : null)
    <x-crud
        :l="$permissoes"
        :o="$permissao"
        r="permissao"
        tc="Cadastra Permissão"
        te="Altera Permissão"
        ti="Lista de Permissões"
        ts="Mostra Permissão"
    >
        @if($permissoes)
            <x-slot:lista>
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Permissão</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th> 
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissoes as $key => $p)
                        <tr>
                            <th class="text-center py-0" scope="row">{{$p->id}}</th>
                            <td class=" py-0" scope="row">{{$p->permissao}}</td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('permissao.show',['permissao' => $p])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('permissao.edit',['permissao' => $p])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-slot>
        @else
            <x-slot:lista>
            </x-slot>
            <div class="container-fluid d-flex flex-wrap">
                <div class="col-12 p-2">
                    <input-group-component
                        label="Permissão:" 
                        type="text"
                        name="permissao" 
                        id="permissao" 
                        required="required"
                        value="{{isset($permissao) ? $permissao->permissao : (old('permissao')?old('permissao'):'')}}"
                        {{!isset($permissao->show) ? '' : 'disabled' }} 
                        class="@error('permissao') is-invalid @enderror {{old('permissao') ? 'is-valid' : ''}}"
                        @error('permissao') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
            </div>
        @endif
    </x-crud>
@endsection