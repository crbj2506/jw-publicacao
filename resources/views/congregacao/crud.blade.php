@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center">
    {{--Rota INDEX Lista --}}
    @if(isset($congregacoes))
        <div class="card m-3">
            <div class="card-header fw-bold">{{ __('Lista de Congregações') }}</div>
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
                        @foreach ($congregacoes as $key => $c)
                            <tr>
                                <th class="text-center py-0" scope="row">{{$c->id}}</th>
                                <td class="text-center py-0" scope="row">{{$c->nome}}</td>
                                <td class="text-center py-0" scope="row"><a href="{{ route('congregacao.show',['congregacao' => $c])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                                <td class="text-center py-0" scope="row"><a href="{{ route('congregacao.edit',['congregacao' => $c])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <x-paginacao :p="$congregacoes"></x-paginacao>
        </div>
    @else
        <div class="card m-3">
            <div class="card-header fw-bold container">
            
                <div class="row">
                    <div class="col">
                        @if(isset($congregacao->edit)) Altera Congregação @elseif(!isset($congregacao->show)) Nova Congregação @elseif(isset($congregacao->show)) Mostra Congregação @endif
                    </div>
                    <div class="col-3 text-end text-center">
                        <a href="{{ route('congregacao.index')}}" class="btn btn-sm btn-outline-primary py-0">Listar</a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <form method="POST" id="formCongregacao" enctype="multipart/form-data"
                    @if ($errors->any()) class=" needs-validation was-validation" @else class="needs-validation" @endif
                    @if(isset($congregacao->edit)) {{-- Se a rota for EDIT --}}
                        action="{{ route('congregacao.update',['congregacao' => $congregacao]) }}">
                        @method('PUT')
                        @csrf
                    @elseif(!isset($congregacao->show)) {{-- Se a rota não for EDIT e nem SHOW, ela é CREATE --}}
                        action="{{ route('congregacao.store') }}">
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
                                value="{{isset($congregacao) ? $congregacao->nome : (old('nome')?old('nome'):'')}}"
                                {{isset($congregacao->show) ? 'disabled' : ''}} 
                                class="@error('nome') is-invalid @enderror {{old('nome') ? 'is-valid' : ''}}"
                                @error('nome') message="{{$message}}" @enderror>
                            </input-group-component>
                        </div>
                    </div>
                @if(!isset($congregacao->show))
                </form>
                @endif                   
            </div>
            <div class="card-footer">
                @if(isset($congregacao->show))
                    <a href="{{ route('congregacao.edit',['congregacao' => $congregacao]) }}" class="btn btn-sm btn-outline-warning">{{ __('Editar') }}</a>
                @else
                    <button 
                        type="submit" 
                        form="formCongregacao" 
                        class="btn btn-sm btn-outline-success {{isset($congregacao->show) ? 'd-none' : ''}}">
                        {{ isset($congregacao->edit) ? 'Salvar' : 'Cadastrar' }}
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection