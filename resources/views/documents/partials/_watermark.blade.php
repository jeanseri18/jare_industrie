@php
    $brand = $brand ?? null;
    $watermarkSrc = $brand?->watermarkDataUri() ?? $brand?->watermarkAbsolutePath();
@endphp
@if($watermarkSrc)
    <div class="watermark">
        <img src="{{ $watermarkSrc }}" alt="">
    </div>
@endif
