@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center">
    {{--Rota INDEX Lista --}}
    @if(isset($pedidos))
        <div class="card m-3">
            <div class="card-header fw-bold container-fluid">
                <div class="row align-items-center">
                    <div class="col">
                        {{ __('Lista de Pedidos') }}
                    </div>
                    <div class="col-4 container-fluid d-flex-inline text-end p-0">
                            <a href="{{ route('pedido.create')}}" class="btn btn-sm btn-outline-success py-0 ">Novo</a>
                    </div>  
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col">#</th>
                            <th class="text-center" scope="col">Pessoa</th>
                            <th class="text-center" scope="col">Publicacao</th>
                            <th class="text-center" scope="col">Quantidade</th>
                            <th class="text-center" scope="col">Solicitado</th>
                            <th class="text-center" scope="col">Entregue</th>
                            <th class="text-center" scope="col">Ver</th>
                            <th class="text-center" scope="col">Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedidos as $key => $p)
                            <tr>
                                <th class="text-center py-0" scope="row">{{$p->id}}</th>
                                <td class=" py-0" scope="row">{{$p->pessoa->nome}}</td>
                                <td class=" py-0" scope="row">{{$p->publicacao->nome}}</td>
                                <td class="text-center py-0" scope="row">{{$p->quantidade}}</td>
                                <td class="text-center py-0" scope="row">{{$p->solicitado}}</td>
                                <td class="text-center py-0" scope="row">{{$p->entregue}}</td>
                                <td class="text-center py-0" scope="row"><a href="{{ route('pedido.show',['pedido' => $p])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                                <td class="text-center py-0" scope="row"><a href="{{ route('pedido.edit',['pedido' => $p])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <x-paginacao :p="$pedidos"></x-paginacao>
        </div>
    @else
        <div class="card m-3">
            <div class="card-header fw-bold container-fluid">
                <div class="row align-items-center">
                    <div class="col">
                        @if(isset($pedido->edit)) Altera Pedido @elseif(!isset($pedido->show)) Novo Pedido @elseif(isset($pedido->show)) Mostra Pedido @endif
                    </div>
                    <div class="col-5 container-fluid d-flex-inline text-end p-0">
                            <a href="{{ route('pedido.index')}}" class="btn btn-sm btn-outline-primary me-2 py-0">Listar</a>
                            @if(isset($pedido->edit)) 
                                <a href="{{ route('pedido.create')}}" class="btn btn-sm btn-outline-success py-0 ">Novo</a>
                            @elseif(!isset($pedido->show))
                            @elseif(isset($pedido->show))
                                <a href="{{ route('pedido.create')}}" class="btn btn-sm btn-outline-success py-0 ">Novo</a>
                            @endif
                    </div>  
                </div>
            </div>
            <div class="card-body p-0">
                <form method="POST" id="formPedido" enctype="multipart/form-data"
                    @if ($errors->any()) class=" needs-validation was-validation" @else class="needs-validation" @endif
                    @if(isset($pedido->edit)) {{-- Se a rota for EDIT --}}
                        action="{{ route('pedido.update',['pedido' => $pedido]) }}">
                        @method('PUT')
                        @csrf
                    @elseif(!isset($pedido->show)) {{-- Se a rota não for EDIT e nem SHOW, ela é CREATE --}}
                        action="{{ route('pedido.store') }}">
                        @csrf
                    @else {{-- Se a rota for SHOW --}}
                        >
                    @endif
                    <div class="container-fluid d-flex flex-wrap">
                        <div class="col-12 col-xl-6 col-xxl-4 p-2">
                            <select-filter-component
                                class="@error('publicacao_id') is-invalid @enderror {{old('publicacao_id') ? 'is-valid' : ''}}"
                                @error('publicacao_id') classinputgroup="has-validation" @enderror
                                classmessage="valid-feedback @error('publicacao_id') invalid-feedback @enderror"
                                id="publicacao_id"
                                label="Publicação:"
                                @error('publicacao_id') message="{{$message}}" @enderror
                                name="publicacao_id"
                                option="Selecione a Publicação..."
                                options="{{json_encode($publicacoes)}}"
                                old_id="{{ isset($pedido) ? $pedido->publicacao_id : @old('publicacao_id') }}"
                                required="required"
                                value="{{ isset($pedido) ? $pedido->publicacao->nome : @old('publicacao_id') }}"
                                {{isset($pedido->show) ? 'disabled' : ''}}
                            ></select-filter-component>
                        </div>
                        <div class="col-12 col-xl-6 col-xxl-4 p-2">
                            <select-filter-component
                                class="@error('pessoa_id') is-invalid @enderror {{old('pessoa_id') ? 'is-valid' : ''}}"
                                @error('pessoa_id') classinputgroup="has-validation" @enderror
                                classmessage="valid-feedback @error('pessoa_id') invalid-feedback @enderror"
                                id="pessoa_id"
                                label="Pessoa:"
                                @error('pessoa_id') message="{{$message}}" @enderror
                                name="pessoa_id"
                                option="Selecione a Pessoa..."
                                options="{{json_encode($pessoas)}}"
                                old_id="{{ isset($pedido) ? $pedido->pessoa_id : @old('pessoa_id') }}"
                                required="required"
                                value="{{ isset($pedido) ? $pedido->pessoa->nome : @old('pessoa_id') }}"
                                {{isset($pedido->show) ? 'disabled' : ''}}
                            ></select-filter-component>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 p-2">
                            <input-group-component
                                label="Quantidade:" 
                                type="number"
                                name="quantidade" 
                                id="quantidade" 
                                required="required"
                                value="{{isset($pedido) ? $pedido->quantidade : (old('quantidade')?old('quantidade'):'')}}"
                                {{isset($pedido->show) ? 'disabled' : ''}} 
                                class="@error('quantidade') is-invalid @enderror {{old('quantidade') ? 'is-valid' : ''}}"
                                @error('quantidade') message="{{$message}}" @enderror>
                            </input-group-component>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 p-2">
                            <input-group-component
                                label="Solicitaddo:" 
                                type="date"
                                name="solicitado" 
                                id="solicitado" 
                                required="required"
                                value="{{isset($pedido) ? $pedido->solicitado : (old('solicitado')?old('solicitado'):'')}}"
                                {{isset($pedido->show) ? 'disabled' : ''}} 
                                class="@error('solicitado') is-invalid @enderror {{old('solicitado') ? 'is-valid' : ''}}"
                                @error('solicitado') message="{{$message}}" @enderror>
                            </input-group-component>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 p-2">
                            <input-group-component
                                label="Entregue:" 
                                type="date"
                                name="entregue" 
                                id="entregue"
                                value="{{isset($pedido) ? $pedido->entregue : (old('entregue')?old('entregue'):'')}}"
                                {{isset($pedido->show) ? 'disabled' : ''}} 
                                class="@error('entregue') is-invalid @enderror {{old('entregue') ? 'is-valid' : ''}}"
                                @error('entregue') message="{{$message}}" @enderror>
                            </input-group-component>
                        </div>
                    </div>
                @if(!isset($pedido->show))
                </form>
                @endif                   
            </div>
            <div class="card-footer">
                @if(isset($pedido->show))
                    <a href="{{ route('pedido.edit',['pedido' => $pedido]) }}" class="btn btn-sm btn-outline-warning">{{ __('Editar') }}</a>
                @else
                    <button 
                        type="submit" 
                        form="formPedido" 
                        class="btn btn-sm btn-outline-success {{isset($pedido->show) ? 'd-none' : ''}}">
                        {{ isset($pedido->edit) ? 'Salvar' : 'Cadastrar' }}
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection