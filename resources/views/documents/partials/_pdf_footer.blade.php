@php
    $brand = $brand ?? null;
    $address = $brand?->address ?? 'Abidjan Cocody - 2 Plateaux Macaci';
    $city = $brand?->city ?? 'Abidjan';
    $phone = $brand?->phone ?? '';
    $whatsapp = $brand?->whatsapp ?? '';
    $email = $brand?->email ?? '';
    $rccm = $brand?->rccm ?? '';
    $cc = $brand?->cc ?? '';
    $legalName = $brand?->displayName() ?? config('app.name');
    $footerText = $brand?->footer_text;
@endphp
<div class="footer">
    @if($footerText)
        {!! nl2br(e($footerText)) !!}
    @else
        Siège Social : {{ $address }}<br>
        {{ $city }} — Tel : {{ $phone }}@if($whatsapp) — Whatsapp : {{ $whatsapp }}@endif<br>
        @if($rccm)RCCM : {{ $rccm }}@endif @if($cc)— CC N° : {{ $cc }}@endif @if($email)— E-mail : {{ $email }}@endif<br>
        {{ $legalName }}
    @endif
</div>
