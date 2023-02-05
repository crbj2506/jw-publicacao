@extends('layouts.app')

@section('content')
    @php($pedidos = isset($pedidos) ? $pedidos : null)
    @php($pedido = isset($pedido) ? $pedido : null)
    <x-crud
        :l="$pedidos"
        :o="$pedido"
        r="pedido"
        tc="Cadastra Pedido"
        te="Altera Pedido"
        ti="Lista de Pedidos"
        ts="Mostra Pedidos"
    >
        @if($pedidos)
            <x-slot:filtro>
            </x-slot>
            <x-slot:lista>
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Pedido</th>
                        <th class="text-center" scope="col">Publicação</th>
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
            </x-slot>
        @else
            <x-slot:filtro>
            </x-slot>
            <x-slot:lista>
            </x-slot>
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
        @endif
    </x-crud>
@endsection