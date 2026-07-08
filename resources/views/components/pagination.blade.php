@props(['paginator'])

@if($paginator instanceof \Illuminate\Contracts\Pagination\Paginator)
<div {{ $attributes->merge(['class' => 'list-pagination mt-4']) }}>
    @if(method_exists($paginator, 'total') && $paginator->total() > 0)
        <p class="list-pagination__summary">
            @if($paginator->hasPages())
                Affichage {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} sur {{ $paginator->total() }} résultats
            @else
                {{ $paginator->total() }} résultat{{ $paginator->total() > 1 ? 's' : '' }}
            @endif
        </p>
    @endif
    @if($paginator->hasPages())
        {{ $paginator->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
    @endif
</div>
@endif
