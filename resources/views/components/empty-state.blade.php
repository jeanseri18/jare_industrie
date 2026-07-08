@props(['title' => 'Aucune donnée', 'message' => 'Il n\'y a rien à afficher pour le moment.'])

<div {{ $attributes->merge(['class' => 'py-8 text-center']) }}>
    <h3 class="text-base font-semibold text-slate-600">{{ $title }}</h3>
    <p class="mt-1 text-sm text-slate-500">{{ $message }}</p>
    @if(isset($action))
        <div class="mt-4">{{ $action }}</div>
    @endif
</div>
