@if ($paginator->hasPages())
    <nav class="custom-pagination" role="navigation" aria-label="Pagination Navigation">
        <ul class="pagination-list">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link page-link-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link page-link-arrow" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)

                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item dots" aria-disabled="true">
                        <span class="page-link page-link-dots">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link page-link-arrow" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link page-link-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </li>
            @endif

        </ul>
    </nav>
@endif
