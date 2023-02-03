@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center">
    {{--Rota INDEX Lista --}}
    @if(isset($pessoas))
        <div class="card m-3">
            <div class="card-header fw-bold container-fluid">
                <div class="row align-items-center">
                    <div class="col">
                        {{ __('Lista de Pessoas') }}
                    </div>
                    <div class="col-4 container-fluid d-flex-inline text-end p-0">
                            <a href="{{ route('pessoa.create')}}" class="btn btn-sm btn-outline-success py-0 ">Nova</a>
                    </div>  
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
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
                </table>
            </div>
            <x-paginacao :p="$pessoas"></x-paginacao>
        </div>
    @else
        <div class="card m-3">
            <div class="card-header fw-bold container-fluid">
                <div class="row align-items-center">
                    <div class="col">
                        @if(isset($pessoa->edit)) Altera Pessoa @elseif(!isset($pessoa->show)) Nova Pessoa @elseif(isset($pessoa->show)) Mostra Pessoa @endif
                    </div>
                    <div class="col-5 container-fluid d-flex-inline text-end p-0">
                            <a href="{{ route('pessoa.index')}}" class="btn btn-sm btn-outline-primary me-2 py-0">Listar</a>
                            <a href="{{ route('pessoa.create')}}" class="btn btn-sm btn-outline-success py-0 ">Nova</a>
                    </div>  
                </div>
            </div>
            <div class="card-body p-0">
                <form method="POST" id="formPessoa" enctype="multipart/form-data"
                    @if ($errors->any()) class=" needs-validation was-validation" @else class="needs-validation" @endif
                    @if(isset($pessoa->edit)) {{-- Se a rota for EDIT --}}
                        action="{{ route('pessoa.update',['pessoa' => $pessoa]) }}">
                        @method('PUT')
                        @csrf
                    @elseif(!isset($pessoa->show)) {{-- Se a rota não for EDIT e nem SHOW, ela é CREATE --}}
                        action="{{ route('pessoa.store') }}">
                        @csrf
                    @else {{-- Se a rota for SHOW --}}
                        >
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
                @if(!isset($pessoa->show))
                </form>
                @endif                   
            </div>
            <div class="card-footer">
                @if(isset($pessoa->show))
                    <a href="{{ route('pessoa.edit',['pessoa' => $pessoa]) }}" class="btn btn-sm btn-outline-warning">{{ __('Editar') }}</a>
                @else
                    <button 
                        type="submit" 
                        form="formPessoa" 
                        class="btn btn-sm btn-outline-success {{isset($pessoa->show) ? 'd-none' : ''}}">
                        {{ isset($pessoa->edit) ? 'Salvar' : 'Cadastrar' }}
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection