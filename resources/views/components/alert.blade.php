@if(session('success'))
    <div class="app-alert mb-4 border border-gray-200 bg-gray-100 px-4 py-3 text-sm text-gray-800">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="app-alert mb-4 border border-orange-200 bg-orange-50 px-4 py-3 text-sm text-orange-800">{{ session('error') }}</div>
@endif
@if(isset($errors) && $errors->any())
    <div class="app-alert mb-4 border border-orange-200 bg-orange-50 px-4 py-3 text-sm text-orange-800">
        <ul class="list-disc pl-4">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif
