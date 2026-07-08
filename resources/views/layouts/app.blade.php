<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Digit BTP</title>
    @if($branding?->faviconUrl())
        <link rel="icon" href="{{ $branding->faviconUrl() }}">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        :root {
            --org-primary: {{ $theme['primary'] ?? '#003d82' }};
            --org-secondary: {{ $theme['secondary'] ?? '#1e3a8a' }};
            --org-accent: {{ $theme['accent'] ?? '#4CAF50' }};
            --ui-radius: 0.75rem;
            --app-topbar-height: 4rem;
        }
        .stat-icon.icon-green { background: #6b7280; color: #fff; }
        .stat-icon.icon-red { background: #ff7200; color: #fff; }
        .stat-icon.icon-black { background: #111827; color: #fff; }
        .stat-icon.icon-gray { background: #6b7280; color: #fff; }
    </style>
    @stack('styles')
</head>
<body x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        @unless($hideSidebar ?? false)
        {{-- Sidebar --}}
        <aside class="app-sidebar fixed inset-y-0 left-0 z-40 w-72 transform bg-black text-white transition-transform lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex h-16 items-center border-b border-white/10 px-5">
                <div class="text-xl font-bold tracking-tight leading-none">
                    <span class="text-white">DIGIT</span><span class="text-[#ff7200]"> BTP</span>
                </div>
            </div>
            <nav class="space-y-1 p-4 overflow-y-auto max-h-[calc(100vh-4rem)]">
                @foreach($navItems as $item)
                    @if(Route::has($item['route']))
                        <a href="{{ route($item['route']) }}"
                           class="sidebar-link {{ request()->routeIs($item['route'].'*') || request()->routeIs($item['route']) ? 'sidebar-link-active' : '' }}">
                            <x-icon :name="$item['icon'] ?? 'document'" class="sidebar-link-icon h-5 w-5 shrink-0" />
                            <span class="truncate">{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>
        </aside>

        {{-- Overlay mobile --}}
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-30 bg-black/40 lg:hidden" @click="sidebarOpen = false"></div>
        @endunless

        <div class="app-layout-main flex min-w-0 flex-1 flex-col {{ ($hideSidebar ?? false) ? 'app-layout-main--full' : 'lg:ml-72' }}">
            <header class="app-topbar {{ ($hideSidebar ?? false) ? 'app-topbar--no-sidebar' : '' }}{{ ($topNavLinks ?? false) ? ' app-topbar--client' : '' }}">
                @unless($hideSidebar ?? false)
                <button type="button" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden" @click="sidebarOpen = !sidebarOpen">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                @else
                    @if($topNavLinks ?? false)
                        @include('client.partials.topbar-tabbar')
                    @else
                        <a href="{{ route('operateur.dashboard') }}" class="app-navbar-brand">
                            <span>DIGIT</span><span> BTP</span>
                        </a>
                    @endif
                @endunless

                @if(!($hideSidebar ?? false) || !($topNavLinks ?? false))
                <div class="app-topbar-actions">
                    <div class="app-navbar-profile" x-data="{ open: false }">
                        <button
                            type="button"
                            @click="open = !open"
                            class="app-navbar-profile-toggle"
                            aria-label="Menu profil"
                            :aria-expanded="open"
                        >
                            <x-user-avatar :user="auth()->user()" />
                        </button>
                        <div
                            x-show="open"
                            x-cloak
                            @click.outside="open = false"
                            class="app-navbar-profile-menu"
                        >
                            <a href="{{ ($navRole ?? '') === 'client' ? route('client.profile') : route('profile.show') }}">Mon profil</a>
                            <div class="app-navbar-profile-menu-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">Déconnexion</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </header>

            <main class="app-main flex-1 {{ ($navRole ?? '') === 'client' ? 'app-main--client bg-white' : (($hideSidebar ?? false) ? 'app-main--operateur bg-white' : 'bg-white p-4 lg:p-6') }}">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
