@props(['paginator'])

@if($paginator->hasPages())
    <div class="mt-4">
        <nav aria-label="{{ __('Pagination Navigation') }}">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                {{-- Информация о страницах --}}
                <div class="mb-3 mb-md-0">
                    <p class="small text-muted mb-0">
                        {{ __('Showing') }}
                        <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                        {{ __('to') }}
                        <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                        {{ __('of') }}
                        <span class="fw-semibold">{{ $paginator->total() }}</span>
                        {{ __('results') }}
                    </p>
                </div>

                {{-- Навигация по страницам --}}
                <div>
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Предыдущая страница --}}
                        @if($paginator->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="bi bi-chevron-left"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Номера страниц --}}
                        @foreach(range(1, $paginator->lastPage()) as $page)
                            @if($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Следующая страница --}}
                        @if($paginator->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="bi bi-chevron-right"></i>
                                </span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </div>
@endif
