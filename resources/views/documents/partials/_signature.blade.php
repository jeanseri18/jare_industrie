@php
    $brand = $brand ?? null;
    $directorName = $brand?->director_name ?? 'Directeur Général';
    $legalName = $brand?->displayName() ?? config('app.name');
    $signatureSrc = $brand?->signatureDataUri() ?? $brand?->signatureAbsolutePath();
@endphp
<td class="right">
    SIGNATURE {{ strtoupper($legalName) }}<br>
    @if($signatureSrc)
        <img src="{{ $signatureSrc }}" alt="" style="max-width: 120px; margin-top: 8px;">
    @endif
    <div style="margin-top: 4px; font-weight: 700;">{{ $directorName }}</div>
</td>
