{{-- <div>
    @if ($paginator->hasPages())
        <ul class="pagination pagination-flat justify-content">
            <li class="page-item @if ($paginator->onFirstPage()) disabled @endif"><a wire:click="previousPage" href="{{ $paginator->previousPageUrl() }}" class="page-link">« Előző</a></li>
            <li class="page-item @if ($page == $paginator->currentPage()) active @endif"><a wire:click="gotoPage(1)" href="#" class="page-link legitRipple">1</a></li>
            <li class="page-item @if ($page == $paginator->currentPage()) active @endif"><a href="{{ $paginator->currentPage() }}" class="page-link legitRipple">2</a></li>
            <li class="page-item @if ($page == $paginator->currentPage()) active @endif"><a href="#" class="page-link legitRipple">3</a></li>
            <li class="page-item @if ($page == $paginator->currentPage()) active @endif"><span class="page-link bg-transparent legitRipple">...</span></li>
            <li class="page-item @if ($page == $paginator->currentPage()) active @endif"><a href="#" class="page-link legitRipple">9</a></li>
            <li class="page-item @if (!$paginator->hasMorePages()) disabled @endif"><a wire:click="nextPage" href="{{ $paginator->nextPageUrl() }}" class="page-link legitRipple" rel="next">Következő »</a></li>
        </ul>
    @endif

</div> --}}

@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a wire:click.prevent="previousPage" class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a wire:click.prevent="gotoPage({{$page}})" class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a wire:click.prevent="nextPage" class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif