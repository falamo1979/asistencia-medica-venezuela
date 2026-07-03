@if ($paginator->hasPages())
    <nav class="pagination">
        @if ($paginator->onFirstPage())
            <span class="page-btn is-disabled">← Anterior</span>
        @else
            <a class="page-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">← Anterior</a>
        @endif

        <span class="page-status">
            Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}
            · {{ $paginator->total() }} resultados
        </span>

        @if ($paginator->hasMorePages())
            <a class="page-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">Siguiente →</a>
        @else
            <span class="page-btn is-disabled">Siguiente →</span>
        @endif
    </nav>
@endif
