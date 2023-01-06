@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="container">
                        <div class="row">
                            <div class="col-auto me-auto">
                                {{ __('Visualizando Publicação') }}
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('publicacao.index')}}" class="btn btn-sm btn-link">Listar</a>
                            </div>
                        </div>
                    </div>
                        
                </div>

                <div class="card-body">

                        <div class="row mb-3">
                            <label for="nome" class="col-md-4 col-form-label text-md-end">{{ __('Nome') }}</label>

                            <div class="col-md-6">
                                <input id="nome" type="text" class="form-control" name="nome" value="{{ $publicacao->nome }}" disabled>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="codigo" class="col-md-4 col-form-label text-md-end">{{ __('Código') }}</label>

                            <div class="col-md-6">
                                <input id="codigo" type="text" class="form-control" name="codigo" value="{{ $publicacao->codigo }}" disabled>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="item" class="col-md-4 col-form-label text-md-end">{{ __('Item') }}</label>

                            <div class="col-md-6">
                                <input id="item" type="text" class="form-control" name="item" value="{{ $publicacao->item }}" disabled>

                            </div>
                        </div>


                        @if($publicacao->imagem)
                            <div class="row mb-3">
                                <label for="imagem" class="col-md-4 col-form-label text-md-end">{{ __('Imagem') }}</label>
                                <div class="col-md-6">
                                        <img src="/storage/{{$publicacao->imagem}}"  class="img-thumbnail">
                                        <input id="imagem" type="text" class="form-control" name="imagem" value="{{ $publicacao->imagem }}" disabled>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>              
                <div class="card-footer">
                    <a href="{{ route('publicacao.edit',['publicacao' => $publicacao])}}" class="btn btn-sm btn-outline-primary">Editar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
