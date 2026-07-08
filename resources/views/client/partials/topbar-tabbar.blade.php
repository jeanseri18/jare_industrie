<div class="client-topbar-shell">
    <div class="client-topbar-row">
        <a href="{{ route('client.dashboard') }}" class="app-navbar-brand">
            <span>DIGIT</span><span> BTP</span>
        </a>
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
                    <a href="{{ route('client.profile') }}">Mon profil</a>
                    <div class="app-navbar-profile-menu-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Déconnexion</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <nav class="client-tabbar" aria-label="Navigation client">
        @foreach($navItems as $item)
            @if(Route::has($item['route']))
                @php $isActive = request()->routeIs($item['route'].'*') || request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="client-tabbar-item {{ $isActive ? 'is-active' : '' }}"
                   @if($isActive) aria-current="page" @endif>
                    <x-icon :name="$item['icon'] ?? 'document'" class="client-tabbar-icon" />
                    <span class="client-tabbar-label">{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </nav>
</div>
