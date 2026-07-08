@props(['actions' => true, 'resetUrl' => null])

<form {{ $attributes->merge(['method' => 'GET', 'class' => 'data-table-container list-filters-card mb-4']) }}>
    <div class="list-body list-filters-card__body">
        <div class="filter-bar-fields">
            {{ $slot }}
        </div>
        @if($actions)
            <div class="filter-bar-actions">
                <button type="submit" class="btn-primary">Filtrer</button>
                <a href="{{ $resetUrl ?? url()->current() }}" class="btn-secondary">Réinitialiser</a>
            </div>
        @endif
    </div>
</form>
