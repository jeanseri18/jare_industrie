@props([
    'id',
    'title',
    'icon' => 'fas fa-chart-bar',
    'type' => 'bar',
    'labels' => [],
    'values' => [],
    'datasets' => null,
    'chartLabel' => null,
    'currency' => false,
    'height' => '280px',
])

<div {{ $attributes->merge(['class' => 'data-table-container dashboard-chart-card mb-4']) }}>
    <div class="detail-section__header detail-section__header--static">
        <h3 class="detail-section__title mb-0">
            <i class="{{ $icon }} me-2"></i>{{ $title }}
        </h3>
    </div>
    <div class="list-body">
        @if(empty($labels) && empty($values) && empty($datasets))
            <div class="dashboard-chart-empty">Aucune donnée disponible pour ce graphique.</div>
        @else
            <div class="dashboard-chart-wrap" style="height: {{ $height }}">
                <canvas
                    id="{{ $id }}"
                    data-chart-type="{{ $type }}"
                    data-chart-labels='@json($labels)'
                    data-chart-values='@json($values)'
                    @if($datasets) data-chart-datasets='@json($datasets)' @endif
                    @if($chartLabel) data-chart-label="{{ $chartLabel }}" @endif
                    @if($currency) data-chart-currency="1" @endif
                ></canvas>
            </div>
        @endif
    </div>
</div>
