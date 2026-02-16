@extends('layouts.app')

@section('content')
    @php($volumes = isset($volumes) ? $volumes : null)
    @php($volume = isset($volume) ? $volume : null)
    <x-crud
        :l="$volumes"
        :o="$volume"
        r="volume"
        tc="Cadastra Volume"
        te="Altera Volume"
        ti="Lista de Volumes"
        ts="Mostra Volume"
    >
        @if($volumes)
            <x-slot:filtro>
                <div class="card-header p-1">
                    <form id="formFiltro" method="POST" action="{{ route('volume.filtrada.post') }}">
                        @csrf
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Filtros</span>
                            <input name="volume" type="text" class="form-control" placeholder="Volume" value="{{ $volumes->volumeFiltro ?? '' }}">
                            <input name="envio" type="text" class="form-control" placeholder="Nota do Envio" value="{{ $volumes->envioFiltro ?? '' }}">
                            <button type="submit" class="btn btn-sm btn-outline-primary">Filtrar</button>
                            <a href="{{ route('volume.index') }}" class="btn btn-sm btn-outline-success">Limpar</a>
                        </div>
                    </form>
                </div>
            </x-slot>
            <x-slot:lista>
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Volume</th>
                        <th class="text-center" scope="col">Envio</th>
                        <th class="text-center" scope="col">Data</th>
                        <th class="text-center" scope="col">Congregação</th>
                        <th class="text-center" scope="col">Ver</th>
                        <th class="text-center" scope="col">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($volumes as $key => $v)
                        <tr>
                            <th class="text-center py-0" scope="row">{{$v->id}}</th>
                            <td class="py-0" scope="row">{{$v->volume}}</td>
                            <td class="text-center py-0" scope="row">{{$v->envio->nota}}</td>
                            <td class="text-center py-0" scope="row">{{$v->envio->data}}</td>
                            <td class="text-center py-0" scope="row">{{$v->envio->congregacao->nome}}</td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('volume.show',['volume' => $v->id])}}" class="btn btn-sm btn-outline-primary py-0">Ver</a></td>
                            <td class="text-center py-0" scope="row"><a href="{{ route('volume.edit',['volume' => $v->id])}}" class="btn btn-sm btn-outline-warning py-0">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </x-slot>
        @else
            <x-slot:filtro>
            </x-slot>
            <x-slot:lista>
            </x-slot>
            <div class="container-fluid d-flex flex-wrap">
                <div class="col-12 col-sm-11 col-md-10 col-lg-9 col-xl-8 col-xxl-7 p-2">
                    <select-filter-component
                        class="@error('envio_id') is-invalid @enderror {{old('envio_id') ? 'is-valid' : ''}}"
                        @error('envio_id') classinputgroup="has-validation" @enderror
                        classmessage="valid-feedback @error('envio_id') invalid-feedback @enderror"
                        id="envio_id"
                        label="Envio:"
                        @error('envio_id') message="{{$message}}" @enderror
                        name="envio_id"
                        option="Selecione o Envio..."
                        options="{{json_encode($envios)}}"
                        old_id="{{ isset($volume) ? $volume->envio_id : @old('envio_id') }}"
                        required="required"
                        value="{{ isset($volume) ? $volume->envio->nota : @old('envio_id') }}"
                        {{isset($volume->show) ? 'disabled' : ''}}
                    ></select-filter-component>
                </div>
                <div class="col-12 col-sm-8 col-md-5 p-2">
                    <input-group-component
                        label="Volume:" 
                        type="text"
                        name="volume" 
                        id="volume" 
                        required="required"
                        value="{{isset($volume) ? $volume->volume : (old('volume')?old('volume'):'')}}"
                        {{isset($volume->show) ? 'disabled' : ''}} 
                        class="@error('volume') is-invalid @enderror {{old('volume') ? 'is-valid' : ''}}"
                        @error('volume') message="{{$message}}" @enderror>
                    </input-group-component>
                </div>
            </div>
        @endif
    </x-crud>
@endsection