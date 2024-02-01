@if ($paginator->hasPages())
    <div class="row tm-row tm-mt-100 tm-mb-75">
        <div class="tm-prev-next-wrapper">
            @if ($paginator->onFirstPage())
                <span class="mb-2 tm-btn tm-btn-primary tm-prev-next disabled tm-mr-20">Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="mb-2 tm-btn tm-btn-primary tm-prev-next">Prev</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="mb-2 tm-btn tm-btn-primary tm-prev-next">Next</a>
            @else
                <span class="mb-2 tm-btn tm-btn-primary tm-prev-next disabled tm-mr-20">Next</span>
            @endif
        </div>

        <div class="tm-paging-wrapper">
            <span class="d-inline-block mr-3">Page</span>
            <nav class="tm-paging-nav d-inline-block">
                <ul>
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="tm-paging-item active">
                                <span href="#" class="mb-2 disabled tm-btn tm-paging-link">{!! $element !!}</span>
                            </li>
                        @endif
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="tm-paging-item active">
                                        <span href="#" class="mb-2 disabled tm-btn tm-paging-link">{!! $page !!}</span>
                                    </li>
                                @else
                                    <li class="tm-paging-item">
                                        <a class="mb-2 tm-btn tm-paging-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </ul>
            </nav>
        </div>
    </div>
@endif
