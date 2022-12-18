@if ($paginator->hasPages())
    <nav class="shadow-sm">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link bg-dark border border-dark" aria-hidden="true"><i class="fa fa-arrow-left" aria-hidden="true"></i></span>
                </li>
            @else
                <li class="page-item">
                    <a class="text-white page-link bg-dark border border-dark" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item disabled" aria-current="page">
                                <span class="page-link font-weight-bold bg-dark border border-dark">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link text-white font-weight-bold bg-dark border border-dark" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="text-white page-link bg-dark border border-dark" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link bg-dark border border-dark" aria-hidden="true"><i class="fa fa-arrow-right" aria-hidden="true"></i></span>
                </li>
            @endif
        </ul>
    </nav>
@endif
