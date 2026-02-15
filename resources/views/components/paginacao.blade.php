
<div class="card-footer">
    <div class="d-flex align-items-center">
        <ul class="pagination pagination-sm mb-0 {{ $p->lastPage() == 1 ? 'd-none' : '' }}">
            <li class="page-item {{ $p->currentPage() == 1 ? 'disabled' : ''}}">
                <a class="page-link" href="{{ $p->url(1) }}">Página 1</a>
            </li>
            <li class="page-item {{ $p->currentPage() == 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $p->previousPageUrl() }}" tabindex="-1" aria-disabled="true">Anterior</a>
            </li>

            @for ($i = 1;  $i <= $p->lastPage() ; $i++)
                <li class="page-item {{ $p->currentPage() == $i ? 'active' : '' }}
                                    {{ ($i < $p->currentPage() - $p->d1) || ($i > $p->currentPage() + $p->d2) ? 'd-none' : '' }}">
                    <a class="page-link" href="{{ $p->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item">
                <a class="page-link {{ $p->currentPage() == $p->lastPage() ? 'disabled' : '' }}" href="{{ $p->nextPageUrl() }}">Próxima</a>
            </li>
            <li class="page-item">
                <a class="page-link {{ $p->currentPage() == $p->lastPage() ? 'disabled' : '' }}" href="{{ $p->url($p->lastPage()) }}">Página {{$p->lastPage()}}</a>
            </li>
        </ul>

        <ul class="pagination pagination-sm mb-0 ms-auto">
            @php($sizes = [10,20,50,100])
            @foreach($sizes as $s)
                @if($p->perPage() == $s)
                    <li class="page-item active">
                        <span class="page-link">{{ $s }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['perpage' => $s, 'page' => 1]) }}">{{ $s }}</a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>