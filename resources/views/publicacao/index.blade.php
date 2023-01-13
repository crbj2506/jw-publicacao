@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Lista de Publicações Cadastradas') }}</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Observação</th>
                                <th scope="col">Item</th>
                                <th scope="col">Código</th>
                                <th scope="col">Imagem</th>
                                <th scope="col">Ver</th>
                                <th scope="col">Editar</th>
                            </tr>
                        </thead>
                    <tbody>
                    @foreach ($publicacoes as $key => $p)
                        <tr>
                            <th scope="row">{{$p['id']}}</th>
                            <td>{{$p['nome']}}</td>
                            <td>{{$p['observacao']}}</td>
                            <td>{{$p['item']}}</td>
                            <td>{{$p['codigo']}}</td>
                            <td> 
                                @if ($p['imagem'])
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modal{{$p['id']}}">Imagem</button>
                                @endif
                            </td>
                            <td><a href="{{ route('publicacao.show',['publicacao' => $p['id']])}}" class="btn btn-sm btn-outline-primary" class="btn btn-sm btn-outline-warning">Ver</a></td>
                            <td><a href="{{ route('publicacao.edit',['publicacao' => $p['id']])}}" class="btn btn-sm btn-outline-warning">Editar</a></td>
                        </tr>


                        @if ($p['imagem'])
                        <!-- Modal -->
                        <div class="modal fade" id="modal{{$p['id']}}" tabindex="-1" aria-labelledby="modalLabelmodal{{$p['id']}}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabelmodal{{$p['id']}}">{{$p['nome']}}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                </div>
                                <div class="modal-body">
                                    <img src="/storage/{{$p['imagem']}}" class="img-thumbnail">
                                </div>
                                <div class="modal-footer">
                                    <button type="button btn-sm" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>
                                </div>
                            </div>
                        </div>
                        @endif

                    @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{--$publicacoes->links() BUGADO!!!!!--}}
  <ul class="pagination justify-content-center">
    <li class="page-item">
      <a class="page-link" href="{{ $publicacoes->url(1) }}"><<</a>
    </li>
    <li class="page-item">
      <a class="page-link" href="{{ $publicacoes->previousPageUrl() }}" tabindex="-1" aria-disabled="true"><</a>
    </li>@for ( $i= 1 ; $i <= $publicacoes->lastPage() ; $i++)
        <li class="page-item {{ $publicacoes->currentPage() == $i ? 'active' : '' }}">
            <a class="page-link" href="{{ $publicacoes->url($i) }}">{{ $i }}</a>
        </li>
    @endfor
    <li class="page-item">
      <a class="page-link" href="{{ $publicacoes->nextPageUrl() }}">></a>
    </li>
    <li class="page-item">
      <a class="page-link" href="{{ $publicacoes->url($publicacoes->lastPage()) }}">>></a>
    </li>
  </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
