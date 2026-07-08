@props(['label'])

<div class="detail-field">
    <div class="detail-field__label">{{ $label }}</div>
    <div class="detail-field__value">{{ $slot }}</div>
</div>
