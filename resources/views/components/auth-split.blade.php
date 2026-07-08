@props(['maxWidth' => 'max-w-md'])

<div class="flex min-h-screen">
    <div class="flex w-full flex-col justify-center bg-white px-6 py-12 sm:px-12 lg:w-1/2 lg:px-16 xl:px-20">
        <div class="{{ $maxWidth }} mx-auto w-full">
            {{ $slot }}
        </div>
    </div>
    <div class="relative hidden lg:block lg:w-1/2">
        <img
            src="{{ asset('images/african-american-lady-safety-helmet-with-notebook-near-building-construction.jpg') }}"
            alt="Professionnelle du BTP sur un chantier"
            class="absolute inset-0 h-full w-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-br from-black/25 via-[#ff7200]/40 to-black/45"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(0,0,0,0.05)_0%,_rgba(0,0,0,0.15)_100%)]"></div>
    </div>
</div>
