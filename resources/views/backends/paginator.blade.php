<div class="my-3">
@php
    $show = ($paginator->currentPage() - 1) * $paginator->perPage() + 1 ;
    $current = $paginator->currentPage() * $paginator->perPage();
    $to = $paginator->currentPage() * $paginator->perPage() ;
@endphp

Showing {{ $show }} to {{ $current < $paginator->total() ? $to : $paginator->total() }} of {{ $paginator->total() }} results
@if ($paginator->lastPage() > 1)
        <ul class="pagination float-right">
            <li class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }} page-item">
                <a class=" page-link " href="{{ $paginator->url(1) }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }} page-item">
                    <a class=" page-link " href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }} page-item">
                <a href="{{ $paginator->url($paginator->currentPage()+1) }}" class="page-link" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>
@endif
</div>