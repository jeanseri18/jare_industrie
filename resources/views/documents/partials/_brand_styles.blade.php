@php
    $brand = $brand ?? null;
    $primary = $brand?->primary_color ?? '#ff7200';
    $secondary = $brand?->secondary_color ?? '#1e3a8a';
    $accent = $brand?->accent_color ?? '#4CAF50';
@endphp
<style>
    .company-name,
    .bar-left,
    .section-title,
    .dossier-label,
    .housing-title,
    .fees-label,
    .fees-amount,
    .fees-note,
    .doc-title,
    .title-main,
    .title-sub,
    .header-title,
    .title {
        color: {{ $primary }} !important;
    }

    .bar-left,
    .fees-amount,
    .red-line,
    .doc-title-wrapper,
    .title-bar {
        background: {{ $primary }} !important;
    }

    .bar-right,
    .dossier-box {
        border-color: {{ $primary }} !important;
    }

    .company-sub,
    .left-label,
    .housing-card-title,
    .value-label,
    .signatures td,
    .company-subtitle,
    .info-left,
    .lbl {
        color: {{ $secondary }} !important;
    }

    .site-bar,
    .footer {
        background: {{ $secondary }} !important;
    }

    .line,
    .dots {
        border-bottom-color: {{ $secondary }} !important;
    }

    .footer {
        border-top-color: {{ $primary }} !important;
    }

    .status-paid,
    .accent-text {
        color: {{ $accent }} !important;
    }
</style>
