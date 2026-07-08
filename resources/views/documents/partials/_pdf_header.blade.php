@php
    $brand = $brand ?? null;
    $logoSrc = $brand?->logoDataUri() ?? $brand?->logoAbsolutePath();
    $legalName = $brand?->displayName() ?? config('app.name');
    $tagline = $brand?->tagline ?? 'PROMOTEUR IMMOBILIER AGRÉÉ';
@endphp
<table class="top-table">
    <tr>
        <td class="logo">
            @if($logoSrc)
                <img src="{{ $logoSrc }}" alt="" style="max-width: 120px;">
            @endif
        </td>
        <td class="title">
            <div class="company-name">{{ $legalName }}</div>
            <div class="company-sub">{{ $tagline }}</div>
        </td>
        <td class="logo-right"></td>
    </tr>
</table>
