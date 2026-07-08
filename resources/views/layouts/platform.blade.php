<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — Plateforme SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-100">
    <div class="min-h-screen">
        <header class="bg-slate-900 text-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
                <a href="{{ route('platform.organizations.index') }}" class="text-lg font-bold">Instrudie Platform</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-sm text-white/80 hover:text-white">Déconnexion</button></form>
            </div>
        </header>
        <main class="mx-auto max-w-6xl p-6">@yield('content')</main>
    </div>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
