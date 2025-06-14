@if ($paginator->lastPage() > 1)
<div class="pagination">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
    <div class="pagination-link disabled"><i class="fa-solid fa-angle-left"></i></div>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link"><i class="fa-solid fa-angle-left"></i></a>
    @endif

    {{-- Pagination Elements --}}
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        @if ($i == $paginator->currentPage())
        <div class="pagination-link active">{{ $i }}</div>
        @else
        <a href="{{ $paginator->url($i) }}" class="pagination-link">{{ $i }}</a>
        @endif
        @endfor

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link"><i class="fa-solid fa-angle-right"></i></a>
        @else
        <div class="pagination-link disabled"><i class="fa-solid fa-angle-right"></i></div>
        @endif
</div>
@endif