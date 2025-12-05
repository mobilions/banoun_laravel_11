@if ($paginator->hasPages())
    <nav>
        <ul class="pagination pagination-rounded mb-0">
            {{-- Previous Page Link --}}
            <li class="paginate_button page-item previous {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->previousPageUrl() ?: '#' }}" tabindex="-1" aria-disabled="{{ $paginator->onFirstPage() ? 'true' : 'false' }}">
                    {{ __('Previous') }}
                </a>
            </li>

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="paginate_button page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="paginate_button page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="paginate_button page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            <li class="paginate_button page-item next {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $paginator->hasMorePages() ? $paginator->nextPageUrl() : '#' }}" aria-disabled="{{ $paginator->hasMorePages() ? 'false' : 'true' }}">
                    {{ __('Next') }}
                </a>
            </li>
        </ul>
    </nav>
@endif

