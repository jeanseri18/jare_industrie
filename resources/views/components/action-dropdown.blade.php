@props(['label' => 'Actions', 'align' => 'end'])

<div class="dropdown action-dropdown">
    <button
        class="btn btn-sm btn-secondary dropdown-toggle"
        type="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >
        {{ $label }}
    </button>
    <ul class="dropdown-menu dropdown-menu-{{ $align }} shadow-sm">
        {{ $slot }}
    </ul>
</div>
