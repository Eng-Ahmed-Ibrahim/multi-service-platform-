<style>.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    list-style: none;
    padding: 10px;
}

.pagination-link {
    display: inline-block;
    padding: 10px 15px;
    margin: 0 5px;
    text-decoration: none;
    color: #007bff;
    border: 1px solid #ddd;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.pagination-link:hover {
    background-color: #f8f9fa;
}

.pagination-link.current {
    background-color: #007bff;
    color: white;
    cursor: default;
}

.pagination-link.disabled {
    color: #ddd;
    cursor: not-allowed;
    border-color: #ddd;
}

.pagination-link i {
    font-size: 12px;
}

.pagination-link.disabled i {
    color: #ddd;
}
</style>

@if ($paginator->lastPage() > 1)
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <div class="pagination-link disabled"><i class="fa-solid fa-angle-left"></i></div>
        @else
            <a href="{{ $paginator->previousPageUrl() }}&category_id={{ request('category_id') }}" class="pagination-link">
                <i class="fa-solid fa-angle-left"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            @if ($i == $paginator->currentPage())
                <div class="pagination-link current">{{ $i }}</div>
            @else
                <a href="{{ $paginator->url($i) }}&category_id={{ request('category_id') }}" class="pagination-link">{{ $i }}</a>
            @endif
        @endfor

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}&category_id={{ request('category_id') }}" class="pagination-link">
                <i class="fa-solid fa-angle-right"></i>
            </a>
        @else
            <div class="pagination-link disabled"><i class="fa-solid fa-angle-right"></i></div>
        @endif
    </div>
@endif
