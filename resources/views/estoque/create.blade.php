@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header fs-5">{{ __('Adicionar Estoque') }}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('estoque.store') }}" enctype="multipart/form-data" class="needs-validation">
                    @csrf

                    <div class="input-group mb-3">
                        <span class="input-group-text" id="selectLabelLocal">Local</span>
                        <select class="form-select @error('local_id') is-invalid @enderror" id="selectLocal" name="local_id" required>
                            <option  value="" selected>Selecione o Local...</option>
                            @foreach ( $locais as $key => $l)
                                <option value="{{$l->id}}" {{@old('local_id') == $l->id ? 'selected': ''}}>{{ $l->sigla }} - {{ $l->nome }}</option>
                            @endforeach
                        </select>
                    @error('local_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    </div>

                    @foreach ($publicacoes as $key => $p)
                        @php($options[$key]['value'] = $p->id)
                        @php($options[$key]['texto'] = $p->nome)
                    @endforeach
                    <select-filter-component
                        class="@error('publicacao_id') is-invalid @enderror {{old('publicacao_id') ? 'is-valid' : ''}}"
                        @error('publicacao_id') classinputgroup="has-validation" @enderror
                        classmessage="valid-feedback @error('publicacao_id') invalid-feedback @enderror"
                        id="publicacao_id"
                        label="Publicação:"
                        @error('publicacao_id') message="{{$message}}" @enderror
                        name="publicacao_id"
                        option="Selecione a Publicação..."
                        options="{{json_encode($options)}}"
                        old_id="{{ isset($estoque) ? $estoque->publicacao_id : @old('publicacao_id') }}"
                        required="required"
                        value="{{ isset($estoque) ? $estoque->publicacao->nome : @old('publicacao_id') }}"
                        {{isset($estoque->show) ? 'disabled' : ''}}
                    ></select-filter-component>
                
                    <div class="input-group mb-3">
                        <span class="input-group-text">{{ __('Quantidade') }}</span>
                        <input id="quantidade" type="number" class="form-control @error('quantidade') is-invalid @enderror text-end" name="quantidade" value="{{ $quantidade ?? old('quantidade') }}" required autocomplete="quantidade" >
                        @error('quantidade')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-outline-primary">
                                {{ __('Cadastrar') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection