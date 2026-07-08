@props([
    'dashboardUrl',
    'steps' => [
        'Catégorie client',
        'Informations personnelles',
        'Identification & programme',
        'Logement & financement',
        'Récapitulatif',
    ],
])

<aside class="wizard-sidebar" id="wizardSidebar">
    <a href="{{ $dashboardUrl }}" class="wizard-back-link">
        <i class="bi bi-arrow-left"></i>
        <span>Retour</span>
    </a>

    <nav class="wizard-steps-nav" aria-label="Étapes du formulaire">
        @foreach($steps as $index => $label)
            <button
                type="button"
                class="wizard-step-item {{ $index === 0 ? 'is-active' : '' }}"
                data-step="{{ $index }}"
                aria-current="{{ $index === 0 ? 'step' : 'false' }}"
            >
                <span class="wizard-step-marker">{{ $index + 1 }}</span>
                <span class="wizard-step-text">{{ $label }}</span>
            </button>
        @endforeach
    </nav>
</aside>
