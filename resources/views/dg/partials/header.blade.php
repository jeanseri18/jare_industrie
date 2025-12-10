<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">@yield('page-title', 'Gestion')</h1>
    <a href="@yield('create-route', '#')" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>@yield('create-button-text', 'Créer')
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@yield('content')